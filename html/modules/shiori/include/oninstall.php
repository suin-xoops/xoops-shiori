<?php

	if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

	$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

	$language = ( file_exists( XOOPS_ROOT_PATH."/modules/".$mydirname."/language/".$xoopsConfig['language']."/admin.php") ) ? $xoopsConfig['language'] : 'english' ;
	require_once( XOOPS_ROOT_PATH."/modules/".$mydirname."/language/".$language."/admin.php" ) ;

	$ret[] = _AM_THANK_U4_CHOSING."<br /><br />";

	ob_start('install_continue');

	function install_continue($str){

		$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

		$in = array(
			"/<a href=\'admin\.php\?fct=modulesadmin\'>"._MD_AM_BTOMADMIN."<\/a>/i"

		);
		$out = array(
			'<a href="'.XOOPS_URL.'/modules/'.$mydirname.'/admin/setup.php" style="font-size:16px"><img src="'.XOOPS_URL.'/modules/system/images/install.gif" alt="'._AM_SETUP.'" align="absMiddle" border="0" />'._AM_SETUP.'</a>'
		);
		$str = preg_replace($in, $out, $str); 
		return $str;
	}

?>