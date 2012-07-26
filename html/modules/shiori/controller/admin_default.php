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
 
class Shiori_Controller_AdminDefault extends Shiori_Abstract_AdminController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{
		$this->_default();
		$this->_view();
	}

	protected function _default()
	{
		$start = Shiori::get('start', 0);
		$limit = Shiori::get('limit', 50);
		$order = Shiori::get('order', -3);
		$order = ( abs($order) <= 4 ) ? $order : '-3';

		$this->data['limit'] = $limit;
		$this->data['order'] = $order;

		$sort  = ( $order < 0 ) ? 'desc' : 'asc' ;
		$orderParams = array('name', 'mid', 'users', 'clicks');
		$order = $orderParams[abs($order) - 1];

		$bookmarkHandler = new Shiori_Object_BookmarkHandler;
		$bookmarks = $bookmarkHandler->loadsStatics($order, $sort, $limit, $start);
		$total = $bookmarkHandler->count();

		if ( $limit != 0 and $total > $limit )
		{
			include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
			$nav = new XoopsPageNav($total, $limit, $start, 'start');
			$this->data['navi_raw'] = $nav->renderNav();
		}

		foreach ( $bookmarks as &$bookmark )
		{
			$bookmark['module_name'] = $this->_getModuleName($bookmark['mid']);
		}

		$this->data['bookmarks'] = $bookmarks;
	}

	protected function _getModuleName($mid)
	{
		$moduleName = Shiori::msg("No module");

		if ( $mid > 0 )
		{
			$module_handler =& xoops_gethandler('module');
			$module =& $module_handler->get($mid);
			$moduleName = $module->getVar('name');
		}
		elseif ( $mid == -1 )
		{
			$moduleName = Shiori::msg("User Information");
		}
		elseif ( $mid == -2 )
		{
			$moduleName = Shiori::msg("Search");
		}
		elseif ( $mid == -3 )
		{
			$moduleName = Shiori::msg("Private Message");
		}
		elseif ( $mid == -4 )
		{
			$moduleName = Shiori::msg("Home");
		}
		elseif ( $mid == -5 )
		{
			$moduleName = Shiori::msg("Other Site");
		}

		return $moduleName;
	}
}

?>
