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

class Shiori_Blocks_Bookmark extends Shiori_Abstract_Block
{
	public function main()
	{
		if ( !$this->_isUser() )
		{
			return false;
		}

		if ( isset($_SERVER['HTTPS']) and $_SERVER['HTTPS'] == 'on' )
		{
			$protocol = 'https://';
		}
		else
		{
			$protocol = 'http://';
		}
		
		$url  = $protocol.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		$uid = Shiori::uid();
		$bookmarkHandler = new Shiori_Object_BookmarkHandler();
		$isBookmarked = $bookmarkHandler->urlExists($uid, $url);

		$this->data['url'] = $url;
		$this->data['is_bookmarked'] = $isBookmarked;

		$this->_view();
		return $this->content;
	}
}

?>
