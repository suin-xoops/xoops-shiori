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
 
class Shiori_Controller_Ajax extends Shiori_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{
		if ( $_SERVER['REQUEST_METHOD'] != 'POST' )
		{
			die(json_encode('ERROR'));
		}

		if ( Shiori::$Action == 'ticket' )
		{
			$this->_ticket();
		}
		elseif ( Shiori::$Action == 'delete' )
		{
			$this->_delete();
		}
		else
		{
			$this->_default();
		}
	}

	protected function _default()
	{
		$url = Shiori::post('url');

		if ( !$url )
		{
			die(json_encode('ERROR'));
		}

		$uid = Shiori::uid();
		$bookmarkHandler = new Shiori_Object_BookmarkHandler();
		$urlExists = $bookmarkHandler->urlExists($uid, $url);

		if ( $urlExists )
		{
			die(json_encode('1'));
		}
		else
		{
			die(json_encode('0'));
		}
	}

	protected function _ticket()
	{
		die(json_encode(Shiori_Class_Ticket::issue()));
	}

	protected function _delete()
	{
		$ticket = Shiori::post('ticket');

		if ( !Shiori_Class_Ticket::check($ticket) )
		{
			die(json_encode('ERROR'));
		}

		$url = Shiori::post('url');

		if ( !$url )
		{
			die(json_encode('ERROR'));
		}

		$uid = Shiori::uid();
		$bookmarkHandler = new Shiori_Object_BookmarkHandler();
		$result = $bookmarkHandler->deleteByUrl($uid, $url);

		if ( $result )
		{
			die(json_encode('1'));
		}
		else
		{
			die(json_encode('0'));
		}
	}
}

?>
