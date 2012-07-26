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
 
class Shiori_Controller_Default extends Shiori_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{
		if ( Shiori::$Action == 'form' )
		{
			$this->_form();
		}
		elseif ( Shiori::$Action == 'save' )
		{
			$this->_save();
		}
		elseif ( Shiori::$Action == 'delete' )
		{
			$this->_delete();
		}
		else
		{
			$this->_default();
		}

		$this->_view();
	}

	protected function _default()
	{
		$uid   = Shiori::uid();
		$limit = $this->config['per_page'];
		$start = Shiori::get('start', 0);

		$bookmarkHandler = new Shiori_Object_BookmarkHandler;
		$bookmarkObjects = $bookmarkHandler->loadsByUid($uid, $limit, $start);

		$navi = "" ;
		$total = $bookmarkHandler->countByUid($uid);

		if( $total > $limit)
		{
			include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
			$nav = new XoopsPageNav($total, $limit, $start, 'start');
			$this->data['navi_raw'] = $nav->renderNav();
		}

		foreach ( $bookmarkObjects as $bookmarkObject )
		{
			$bookmark = new stdClass;
			$bookmark->id          = $bookmarkObject->getVar('id');
			$bookmark->url         = $bookmarkObject->getVar('url');
			$bookmark->title       = $bookmarkObject->getVar('name');
			$bookmark->mid         = $bookmarkObject->getVar('mid');
			$bookmark->icon        = $bookmarkObject->getVar('icon');
			$bookmark->counter     = $bookmarkObject->getVar('counter');
			$bookmark->module_name = $this->_getModuleName($bookmark->mid);

			$this->data['bookmarks'][] = (array) $bookmark;
		}

		$this->data['ticket'] = Shiori_Class_Ticket::issue();
	}

	protected function _form()
	{
		$url   = Shiori::post('url');
		$title = Shiori::post('title');

		$this->_encodeTitleForAjax($title);

		$this->_validateUrl($url);
		$this->_checkDatabase($url);

		$modname = Shiori::msg("No module");
		$mid = 0;

		$siteUrl = str_replace('/','\/',preg_quote(XOOPS_URL));

		if ( preg_match('/^'.$siteUrl.'\/modules\/([a-zA-Z0-9_\-]+)\//i', $url, $matches) )
		{
			$dirname = $matches[1];
			$module_handler =& xoops_gethandler('module');
			$module =& $module_handler->getByDirname($dirname);
			$modname = $module->getVar('name');
			$mid = $module->getVar('mid');
		}
		elseif ( preg_match('/^'.$siteUrl.'\/userinfo\.php/', $url) )
		{
			$modname = Shiori::msg("User Information");
			$mid = -1;
		}
		elseif ( preg_match('/^'.$siteUrl.'\/search\.php/', $url) )
		{
			$modname = Shiori::msg("Search");
			$mid = -2;
		}
		elseif ( preg_match('/^'.$siteUrl.'\/readpmsg\.php/', $url) or preg_match('/^'.$siteUrl.'\/viewpmsg\.php/', $url) )
		{
			$modname = Shiori::msg("Private Message");
			$mid = -3;
		}
		elseif ( preg_match('/^'.$siteUrl.'\/(index\.php)/', $url) )
		{
			$modname = Shiori::msg("Home");
			$mid = -4;
		}
		elseif ( !preg_match('/^'.$siteUrl.'/', $url) )
		{
			$modname = Shiori::msg("Other Site");
			$mid = -5;
		}

		if ( !$title )
		{
			if ( $this->_isThisSite($url) )
			{
				$title = $modname;
			}
			else
			{
				$title = $url;
			}
		}

		$this->data['url']   = $url;
		$this->data['title'] = $title;
		$this->data['icons'] = $this->_getIcons();
		$this->data['module']['id']   = $mid;
		$this->data['module']['name'] = $modname;

		$this->data['ticket'] = Shiori_Class_Ticket::issue();
	}

	protected function _save()
	{
		$this->_validateTicket();

		$url   = Shiori::post('url');
		$title = Shiori::post('title');
		$mid   = Shiori::post('mid');
		$icon  = Shiori::post('icon');

		$this->_encodeTitleForAjax($title);

		$this->_validateUrl($url);
		$this->_checkDatabase($url);

		$icons = $this->_getIcons();
		$icon  = ( in_array($icon, $icons) ) ? $icon : reset($icons) ;

		$bookmarkHandler = new Shiori_Object_BookmarkHandler();

		$bookmarkObject = $bookmarkHandler->create();
		$bookmarkObject->setVar('uid', Shiori::uid());
		$bookmarkObject->setVar('mid', $mid);
		$bookmarkObject->setVar('icon', $icon);
		$bookmarkObject->setVar('url', $url);
		$bookmarkObject->setVar('date', time());
		$bookmarkObject->setVar('name', $title);

		if ( !$bookmarkHandler->save($bookmarkObject) )
		{
			Shiori::error("Database Error");
		}
		elseif ( $this->_isThisSite($url) )
		{
			Shiori::redirect("Successly bookmarked.", $url);
		}
		else
		{
			Shiori::redirect("Successly bookmarked.", SHIORI_URL);
		}
	}

	protected function _delete()
	{
		$this->_validateTicket();

		$deletingBookmarks = Shiori::post('del_bok');

		if ( !$deletingBookmarks or !is_array($deletingBookmarks) )
		{
			Shiori::error("No delete.");
		}

		$bookmarkHandler = new Shiori_Object_BookmarkHandler();
		$deleteMissing = 0;

		foreach ( $deletingBookmarks as $bookmark )
		{
			if ( !$bookmarkHandler->delete($bookmark) )
			{
				$deleteMissing++;
			}
		}

		if ( $deleteMissing > 0 )
		{
			Shiori::error(Shiori::msg("{1} bookmarks was not deleted.", $deleteMissing));
		}
		else
		{
			Shiori::redirect("Successly deleted.", SHIORI_URL);
		}
	}

	protected function _validateTicket()
	{
		$ticket = Shiori::post('ticket');

		if ( !Shiori_Class_Ticket::check($ticket) )
		{
			Shiori::error("Ticket error.");
		}
	}

	protected function _validateUrl($url)
	{
		if ( !$url )
		{
			Shiori::error("URL is empty.");
		}

		if ( !preg_match("/^http[s]*:\/\//i", $url) )
		{
			Shiori::error("URL is invalid.");
		}

		if( !$this->_isThisSite($url) and $this->config['bookmark_other_sites'] == 0 )
		{
			Shiori::error("Other site pages cannot be bookmarked.");
		}
	}

	protected function _isThisSite($url)
	{
		$siteUrl = str_replace('/','\/',preg_quote(XOOPS_URL));

		return ( preg_match('/^'.$siteUrl.'/i', $url) );
	}

	protected function _checkDatabase($url)
	{
		$db =& Shiori::database();
		$uid = Shiori::uid();
		$bookmarkHandler = new Shiori_Object_BookmarkHandler;
		$total = $bookmarkHandler->countByUid($uid);

		if ( $total >= $this->config['capacity'] )
		{
			Shiori::error("No space.");
		}

		$urlExists = $bookmarkHandler->urlExists($uid, $url);

		if ( $urlExists )
		{
			Shiori::error("This URL has already been bookmarked.");
		}
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

	protected function _getIcons()
	{
		require_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
		$lists = new XoopsLists;
		return $lists->getSubjectsList();
	}

	protected function _encodeTitleForAjax(&$title)
	{
		if ( _CHARSET != 'UTF-8' )
		{
			$title = mb_convert_encoding($title, _CHARSET, 'UTF-8');
		}
	}
}

?>
