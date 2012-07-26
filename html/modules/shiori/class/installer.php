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

$mydirname = basename(dirname(dirname(__FILE__)));

eval('
function xoops_module_install_'.$mydirname.'($module)
{
	return shiori_installer($module, "'.$mydirname.'", "install");
}
function xoops_module_update_'.$mydirname.'($module)
{
	return shiori_installer($module, "'.$mydirname.'", "update");
}
');

function shiori_installer($module, $mydirname, $event)
{
	if ( $event == 'update' )
	{
		global $msgs;
		$ret =& $msgs;
	}
	else
	{
		global $ret;
	}

	if ( !is_array($ret) ) $ret = array();
	$mid = $module->getVar('mid');

	$tplfileHandler =& xoops_gethandler('tplfile');
	$tplPath = dirname(dirname(__FILE__)).'/templates';

	if ( $handler = @opendir($tplPath.'/') )
	{
		while ( ( $file = readdir($handler) ) !== false )
		{
			if ( substr($file, 0, 1) == '.' ) continue;

			$filePath = $tplPath.'/'.$file;

			if ( is_file($filePath) and substr($file, -4) == '.tpl' )
			{
				$mtime = intval(@filemtime($filePath));
				$tplfile =& $tplfileHandler->create();
				$tplfile->setVar('tpl_source', file_get_contents($filePath), true);
				$tplfile->setVar('tpl_refid', $mid);
				$tplfile->setVar('tpl_tplset', 'default');
				$tplfile->setVar('tpl_file', $file);
				$tplfile->setVar('tpl_desc', '', true);
				$tplfile->setVar('tpl_module', $mydirname);
				$tplfile->setVar('tpl_lastmodified', $mtime);
				$tplfile->setVar('tpl_lastimported', 0);
				$tplfile->setVar('tpl_type', 'module');

				if ( !$tplfileHandler->insert($tplfile) )
				{
					$ret[] = '<span style="color:#ff0000;">ERROR: Could not insert template <b>'.htmlspecialchars($file).'</b> to the database.</span><br />';
				}
				else
				{
					$tplid = $tplfile->getVar('tpl_id');
					$ret[] = 'Template <b>'.htmlspecialchars($file).'</b> added to the database. (ID: <b>'.$tplid.'</b>)<br />';
					// generate compiled file
					require_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
					require_once XOOPS_ROOT_PATH.'/class/template.php';

					if ( !shiori_template_touch($tplid) )
					{
						$ret[] = '<span style="color:#ff0000;">ERROR: Failed compiling template <b>'.htmlspecialchars($mydirname.'_'.$file).'</b>.</span><br />';
					}
					else
					{
						$ret[] = 'Template <b>'.htmlspecialchars($mydirname.'_'.$file).'</b> compiled.</span><br />';
					}
				}
			}
		}
		closedir($handler);
	}

	require_once XOOPS_ROOT_PATH.'/class/xoopsblock.php' ;
	require_once XOOPS_ROOT_PATH.'/class/template.php' ;
	xoops_template_clear_module_cache($mid);

	// delete shiori language cache.
	if ( defined('XOOPS_TRUST_PATH') and file_exists(XOOPS_TRUST_PATH . '/cache') )
	{
		$cacheDir = XOOPS_TRUST_PATH . '/cache';
	}
	else
	{
		$cacheDir = XOOPS_ROOT_PATH . '/cache';
	}

	$dir = opendir($cacheDir);
	while ( $file = readdir($dir) )
	{
		if ( is_file($cacheDir.'/'.$file) )
		{
			if ( preg_match('/^shiori_/', $file) )
			{
				if ( @unlink($cacheDir.'/'.$file) )
				{
					$ret[] = 'Language cache was deleted: <strong>'.htmlspecialchars($file).'</strong>';
				}
				else
				{
					$ret[] = '<span style="color:#ff0000;">ERROR: Language cache could not be deleted: <strong>'.htmlspecialchars($file).'</strong></span>';
				}
			}
		}
	}
	closedir($dir);

	return true;
}

function shiori_template_touch($tpl_id, $clear_old = true)
{
	$tpl = new XoopsTpl();
	$tpl->register_modifier('shiori_msg', 'Shiori::msg');
	$tpl->force_compile = true;
	$tplfile_handler =& xoops_gethandler('tplfile');
	$tplfile =& $tplfile_handler->get($tpl_id);
	if ( is_object($tplfile) ) {
		$file = $tplfile->getVar('tpl_file');
		if ($clear_old) {
			$tpl->clear_cache('db:'.$file);
			$tpl->clear_compiled_tpl('db:'.$file);
		}
		$tpl->fetch('db:'.$file);
		return true;
	}
	return false;
}

// dummy class
class Shiori
{
	public static function msg()
	{
	}
}

?>
