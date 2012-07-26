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

$mydirname = basename( dirname( __FILE__ ) ) ;

// Main
$modversion['name'] 	   = _SHIORI_NAME;
$modversion['version']	   = 1.02;
$modversion['description'] = _SHIORI_DESC;
$modversion['credits']	   = "Hidehito NOZAWA aka Suin";
$modversion['author']	   = "Suin <http://suin.asia>";
$modversion['help']	       = "ReadMe-Japanese.html";
$modversion['license']	   = "GNU GPL v2 or later";
$modversion['image']	   = "images/shiori_logo.png";
$modversion['dirname']	   = $mydirname;

$modversion['hasMain'] = 1;

$modversion['hasAdmin']   = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu']  = "admin/menu.php";

$modversion['hasSearch'] = 0;

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0] = "shiori_bookmark";

$modversion['blocks'] = array(
	array(
		'file'        => 'block.php',
		'show_func'   => 'shiori_block_show',
		'name'        => _SHIORI_BLOCK1,
		'description' => _SHIORI_BLOCK1_DESC,
		'options'     => 'Bookmark',
	),
	array(
		'file'        => 'block.php',
		'show_func'   => 'shiori_block_show',
		'name'        => _SHIORI_BLOCK2,
		'description' => _SHIORI_BLOCK2_DESC,
		'options'     => 'Javascript',
	)
);

$modversion['config'] = array(
	array(
		'name' 	 => 'capacity',
		'title'	 => '_SHIORI_CONFIG1',
		'description' => '',
		'formtype'    => 'text',
		'valuetype'   => 'int',
		'default'     => 30,
	),
	array(
		'name' 	 => 'per_page',
		'title'	 => '_SHIORI_CONFIG2',
		'description' => '',
		'formtype'    => 'text',
		'valuetype'   => 'int',
		'default'     => 30,
	),
	array(
		'name' 	 => 'free_input_url',
		'title'	 => '_SHIORI_CONFIG3',
		'description' => '',
		'formtype'    => 'yesno',
		'valuetype'   => 'int',
		'default'     => 0,
	),
	array(
		'name' 	 => 'bookmark_other_sites',
		'title'	 => '_SHIORI_CONFIG4',
		'description' => '',
		'formtype'    => 'yesno',
		'valuetype'   => 'int',
		'default'     => 0,
	),
);

$modversion['hasNotification'] = 0;

$modversion['hasComments'] = 0;

$modversion['onInstall'] = 'class/installer.php';
$modversion['onUpdate']  = 'class/installer.php';

?>