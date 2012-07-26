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
 
class Shiori_Controller_Load extends Shiori_Abstract_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function main()
	{
		$this->_default();
	}

	protected function _default()
	{
		$id = Shiori::get('id');

		if ( !$id )
		{
			Shiori::error("Invalid access.");
		}

		$bookmarkHandler = new Shiori_Object_BookmarkHandler();
		$bookmarkObject = $bookmarkHandler->load($id);
		$uid = Shiori::uid();

		if ( $bookmarkObject->getVar('uid') != $uid )
		{
			Shiori::error("Invalid access.");
		}

		$bookmarkHandler->incrementCounter($id);

		$url = $bookmarkObject->getVar('url');

		header("Location: $url");
		exit();
	}
}

?>
