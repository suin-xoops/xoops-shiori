<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.4 or Upper version
 *
 * @package    Shiori
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 * @copyright  2009 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 *
 */
if ( class_exists('Shiori') ) return;

class Shiori
{
	/**
	 * Names cosist of [a-z0-9_]
	 * Those are usually used for file names.
	 */
	public static $_controller;
	public static $_action;

	/**
	 * Names cosist of [A-Za-z0-9]
	 * Those are usually used for class or method names.
	 */
	public static $Controller;
	public static $Action;

	/**
	 * Names cosist of [a-z0-9]
	 * Those are usually used for template file names.
	 */
	public static $controller;
	public static $action;

	public static $lang = array();

	/**
	 * Main frame
	 */
	public static function setup()
	{
		if ( defined('SHIORI_LOADED') ) return;

		define('SHIORI_DIR', basename(dirname(__FILE__)));
		define('SHIORI_PATH', XOOPS_ROOT_PATH.'/modules/'.SHIORI_DIR);
		define('SHIORI_URL', XOOPS_URL.'/modules/'.SHIORI_DIR);

		spl_autoload_register(array(__CLASS__, 'autoload'));

		self::_language();

		define('SHIORI_LOADED', true);
	}

	public static function execute($isAdmin = false)
	{
		$controller = self::get('controller', 'default');
		$action     = self::get('action', 'default');

		self::$Controller = self::putintoClassParts($controller);
		self::$Action     = self::putintoClassParts($action);
		self::$Action[0]  = strtolower(self::$Action[0]);

		self::$controller = strtolower(self::$Controller);
		self::$action     = strtolower(self::$Action);

		self::$_controller = self::putintoPathParts(self::$Controller);
		self::$_action     = self::putintoPathParts(self::$Action);

		if ( $isAdmin )
		{
			$class = 'Shiori_Controller_Admin'.self::$Controller;
		}
		else
		{
			$class = 'Shiori_Controller_'.self::$Controller;
		}

		$instance = new $class();
		$instance->main();

		unset($instance);
	}

	public static function block($blockName)
	{
		$class = 'Shiori_Blocks_'.$blockName;
		$instance = new $class($blockName);
		$result = $instance->main();
		unset($instance);
		return $result;
	}

	public static function autoload($class)
	{
		if ( class_exists($class, false) ) return;
		if ( !preg_match('/^Shiori_/', $class) ) return;

		$parts = explode('_', $class);
		$parts = array_map(array(__CLASS__, 'putintoPathParts'), $parts);

		$module = array_shift($parts);

		$class = implode('/', $parts);
		$path  = sprintf('%s/%s.php', SHIORI_PATH, $class);

		if ( !file_exists($path) ) return;

		require $path;
	}

	/**
	 * Usefull functions
	 */
	public static function get($name, $default = null)
	{
		$request = ( isset($_GET[$name]) ) ? $_GET[$name] : $default;
		if ( get_magic_quotes_gpc() and !is_array($request) ) $request = stripslashes($request);
		return $request;
	}

	public static function post($name, $default = null)
	{
		$request = ( isset($_POST[$name]) ) ? $_POST[$name] : $default;
		if ( get_magic_quotes_gpc() and !is_array($request) ) $request = stripslashes($request);
		return $request;
	}

	public static function url($apps = null, $controller = null, $action = null, $params = array())
	{
		if ( $action )     $params = array_unshift($params, array('action' => $action));
		if ( $controller ) $params = array_unshift($params, array('controller' => $controller));
		if ( $apps )       $params = array_unshift($params, array('apps' => $apps));
		$param  = http_build_query($params);
		$url = SHIORI_URL . '/index.php?'.$param;
		return $url;
	}

	public static function msg($message)
	{
		if ( isset(self::$lang[$message]) )
		{
			$message = self::$lang[$message];
		}

		if ( func_num_args() == 1 ) return $message;

		$params = func_get_args();

		foreach ( $params as $i => $param )
		{
			$message = str_replace('{'.$i.'}', $param, $message);
		}

		return $message;
	}

	public static function putintoClassParts($str)
	{
		$str = preg_replace('/[^a-z0-9_]/', '', $str);
		$str = explode('_', $str);
		$str = array_map('trim', $str);
		$str = array_diff($str, array(''));
		$str = array_map('ucfirst', $str);
		$str = implode('', $str);
		return $str;
	}

	public static function putintoPathParts($str)
	{
		$str = preg_replace('/[^a-zA-Z0-9]/', '', $str);
		$str = preg_replace('/([A-Z])/', '_$1', $str);
		$str = strtolower($str);
		$str = substr($str, 1, strlen($str));
		return $str;
	}

	public static function redirect($msg, $url = null)
	{
		redirect_header($url, 3, self::msg($msg));
	}

	public static function error($msg)
	{
		xoops_error(self::msg($msg));
		exit();
	}

	public static function &database()
	{
		static $db;

		if ( $db == null )
		{
			$db =& database::getInstance(); 
		}

		return $db;
	}

	public static function escapeHtml($string)
	{
		return htmlspecialchars($string, ENT_QUOTES);
	}

	public static function uid()
	{
		global $xoopsUser;
		return $xoopsUser->uid();
	}

	protected static function _language()
	{
		$encode   = strtolower(_CHARSET);
		$langcode = strtolower(_LANGCODE);

		$langFile  = SHIORI_PATH.'/language/'.$langcode.'.xml';

		if ( !file_exists($langFile) ) return;

		if ( defined('XOOPS_TRUST_PATH') and file_exists(XOOPS_TRUST_PATH . '/cache') )
		{
			$cacheDir = XOOPS_TRUST_PATH . '/cache';
		}
		else
		{
			$cacheDir = XOOPS_ROOT_PATH . '/cache';
		}

		$cacheFile = $cacheDir.'/shiori_'.$langcode.'_'.$encode.'.php';

		if ( file_exists($cacheFile) )
		{
			self::$lang = require $cacheFile;
			return;
		}

		$langXml = simplexml_load_file($langFile, 'Shiori_Class_Language');
		$messages = $langXml->messages();

		if ( $encode != 'utf-8' )
		{
			mb_convert_variables(_CHARSET, 'UTF-8', $messages);
		}

		self::$lang = $messages;

		$cacheContent = "<?php\nreturn ".var_export($messages, true).";\n?>\n";

		file_put_contents($cacheFile, $cacheContent);
	}
}

?>
