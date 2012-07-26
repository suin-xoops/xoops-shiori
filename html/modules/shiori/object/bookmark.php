<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.4 or Upper version
 *
 * @package    CMS Mew
 * @author     Hidehito NOZAWA aka Suin <http://cmsmew.com>
 * @copyright  2009 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 *
 */

class Shiori_Object_Bookmark extends Shiori_Abstract_Object
{
	public function __construct()
	{
		$this->val('id', self::INTEGER, null, 10);
		$this->val('uid', self::INTEGER, null, 10);
		$this->val('mid', self::INTEGER, null, 10);
		$this->val('sort', self::INTEGER, 0, 10);
		$this->val('date', self::INTEGER, null, 10);
		$this->val('counter', self::INTEGER, 0, 10);
		$this->val('url', self::STRING, null, 255);
		$this->val('name', self::STRING, null, 255);
		$this->val('icon', self::STRING, null, 100);
	}
}

?>
