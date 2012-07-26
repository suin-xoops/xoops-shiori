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

abstract class Shiori_Abstract_Controller
{
	protected $template = null;
	protected $data = array();

	protected $config = array();

	public function __construct()
	{
		if ( !$this->_isUser() )
		{
			Shiori::redirect("No permisson", XOOPS_URL);
		}

        $configHandler =& xoops_gethandler('config');
  //      $this->configs =& $configHandler->getConfigsByDirname(SHIORI_DIR);
		global $xoopsModuleConfig;
		$this->config =& $xoopsModuleConfig;
		$this->data['config'] = $this->config;
		$this->data['url'] = SHIORI_URL;
	}

	public function main()
	{
	}

	protected function _view()
	{
		if ( !$this->template )
		{
			$this->template = 'shiori_'.Shiori::$controller.'_'.Shiori::$action.'.tpl';
		}

		global $xoopsOption, $xoopsTpl, $xoopsConfig, $xoopsUser, $xoopsLogger, $xoopsUserIsAdmin;

		require_once XOOPS_ROOT_PATH.'/header.php';

		$xoopsOption['template_main'] =& $this->template;
		$this->_escapeHtml($this->data);
		$xoopsTpl->assign('shiori', $this->data);
		$xoopsTpl->register_modifier('shiori_msg', 'Shiori::msg');

		require_once XOOPS_ROOT_PATH.'/footer.php';
	}

	protected function _escapeHtml(&$vars)
	{
		foreach ( $vars as $key => &$var )
		{
			if ( preg_match('/_raw$/', $key) )
			{
				continue;
			}
			elseif ( is_array($var) )
			{
				$this->_escapeHtml($var);
			}
			elseif ( !is_object($var) )
			{
				$var = Shiori::escapeHtml($var);
			}
		}
	}

	protected function _isUser()
	{
		global $xoopsUser;
		return ( is_object($xoopsUser) );
	}
}

?>
