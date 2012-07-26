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

abstract class Shiori_Abstract_AdminController extends Shiori_Abstract_Controller
{
	protected function _view()
	{
		if ( !$this->template )
		{
			$this->template = 'shiori_admin_'.Shiori::$controller.'_'.Shiori::$action.'.tpl';
		}

		require_once XOOPS_ROOT_PATH.'/class/template.php';
		require_once XOOPS_ROOT_PATH.'/include/cp_functions.php';

		$xoopsTpl = new XoopsTpl();

		xoops_cp_header();

		$this->_escapeHtml($this->data);
		$xoopsTpl->assign('shiori', $this->data);
		$xoopsTpl->register_modifier('shiori_msg', 'Shiori::msg');
		$xoopsTpl->display('db:'.$this->template);

		xoops_cp_footer();
	}
}

?>
