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

function shiori_block_show($options)
{
	require dirname(dirname(__FILE__)).'/shiori.php';
	Shiori::setup();
	$content = Shiori::block($options[0]);

	if ( $content === false ) return false;

	return array('content' => $content);
}

?>
