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

class Shiori_Class_Ticket
{
	public static function issue($timeout = 180)
	{
		$expire = time() + intval($timeout);
		$token  = md5(uniqid().mt_rand());

		if ( isset($_SESSION['shiori_tickets']) and is_array($_SESSION['shiori_tickets']) )
		{
			if ( count($_SESSION['shiori_tickets']) >= 5 )
			{
				asort($_SESSION['shiori_tickets']);
				$_SESSION['shiori_tickets'] = array_slice($_SESSION['shiori_tickets'], -4, 4);
			}

			$_SESSION['shiori_tickets'][$token] = $expire;
		}
		else
		{
			$_SESSION['shiori_tickets'] = array($token => $expire);
		}

		return $token;
	}

	public static function check($stub)
	{
		if ( !isset($_SESSION['shiori_tickets'][$stub]) ) return false;
 		if ( time() >= $_SESSION['shiori_tickets'][$stub] ) return false;

		unset($_SESSION['shiori_tickets'][$stub]);

		return true;
	}
}

?>
