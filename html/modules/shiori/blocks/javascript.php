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

class Shiori_Blocks_Javascript extends Shiori_Abstract_Block
{
	public function main()
	{
		global $xoopsTpl;
		$dirname = basename(dirname(dirname(__FILE__)));

		require_once XOOPS_ROOT_PATH.'/modules/'.$dirname.'/class/javascript_loader.php';

		$xoopsModuleHeader = $xoopsTpl->get_template_vars('xoops_module_header') . shiori_get_javascript_link();
		$xoopsTpl->assign('xoops_module_header', $xoopsModuleHeader);

		return false;
	}
}

?>
