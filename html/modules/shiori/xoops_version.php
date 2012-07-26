<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//               This module; Shiori Copyright (c) 2005 suin                 //
//                          <http://www.suin.jp>                             //
//  ------------------------------------------------------------------------ //

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;
$mydirname = basename( dirname( __FILE__ ) ) ;

// Main
$modversion['name'] 	   = _MI_SHIORI_NAME;
$modversion['version']	   = 0.2;
$modversion['description'] = _MI_SHIORI_DESC;
$modversion['credits']	   = "suin";
$modversion['author']	   = "suin <a href=\"http://www.suin.jp/\" target=\"_blank\">www.suin.jp</a>";
$modversion['help']	   = "ReadMe-Japanese.html";
$modversion['license']	   = "GPL see LICENSE";
$modversion['official']	   = 0;
$modversion['image']	   = "images/shiori_logo.png";
$modversion['dirname']	   = $mydirname;

// Menu
$modversion['hasMain']	   = 1;

// Admin
$modversion['hasAdmin']	   = 1;
$modversion['adminindex']  = "admin/index.php";
$modversion['adminmenu']   = "admin/menu.php";

// Search
$modversion['hasSearch']   = 0;

// Sql
$modversion['sqlfile']['mysql']        = "sql/mysql.sql";
//$modversion['sqlfile']['postgresql']   = "sql/pgsql.sql";

// Tables
$modversion['tables'][0] = "shiori_bookmark";

// Templates
$t=1;
$modversion['templates'][$t]['file']        = 'shiori_index.html';
$modversion['templates'][$t]['description'] = _MI_TPL_DESC1;
$t++;
$modversion['templates'][$t]['file']        = 'shiori_form.html';
$modversion['templates'][$t]['description'] = _MI_TPL_DESC2;
$t++;

// Smarty
$modversion['use_smarty'] = 1;

// Blocks
$b=1;
$modversion['blocks'][$b]['file']	 = "shiori_block.php";
$modversion['blocks'][$b]['name']	 = _MI_SHIORI_BLOCK1;
$modversion['blocks'][$b]['description'] = _MI_SHIORI_BLOCK_D1;
$modversion['blocks'][$b]['show_func']	 = "b_shiori_block";
$modversion['blocks'][$b]['template']	 = "shiori_block.html";
$b++;

// Configs
// * formtype  -> textbox / textarea / select / select_multi / yesno / group / group_multi
// * valuetype -> int / text / float / array / other
$c=1;
$modversion['config'][$c]['name'] 	 = "shiori_capacity";
$modversion['config'][$c]['title']	 = "_MI_SHIORI_CONFIG1";
$modversion['config'][$c]['description'] = "_MI_SHIORI_CONFIG_D1";
$modversion['config'][$c]['formtype']    = "text";
$modversion['config'][$c]['valuetype']   = "int";
$modversion['config'][$c]['default']     = 30;
$c++;/*
$modversion['config'][$c]['name'] 	 = "shiori_item_num_pg";
$modversion['config'][$c]['title']	 = "_MI_SHIORI_CONFIG2";
$modversion['config'][$c]['description'] = "_MI_SHIORI_CONFIG_D2";
$modversion['config'][$c]['formtype']    = "text";
$modversion['config'][$c]['valuetype']   = "int";
$modversion['config'][$c]['default']     = 15;
$c++;*/
$modversion['config'][$c]['name'] 	 = "shiori_use_freeurl";
$modversion['config'][$c]['title']	 = "_MI_SHIORI_CONFIG4";
$modversion['config'][$c]['description'] = "_MI_SHIORI_CONFIG_D4";
$modversion['config'][$c]['formtype']    = "yesno";
$modversion['config'][$c]['valuetype']   = "int";
$modversion['config'][$c]['default']     = 0;
$c++;
$modversion['config'][$c]['name'] 	 = "shiori_prmt_outofsite";
$modversion['config'][$c]['title']	 = "_MI_SHIORI_CONFIG3";
$modversion['config'][$c]['description'] = "_MI_SHIORI_CONFIG_D3";
$modversion['config'][$c]['formtype']    = "yesno";
$modversion['config'][$c]['valuetype']   = "int";
$modversion['config'][$c]['default']     = 0;
$c++;

// Notification
$modversion['hasNotification'] = 0;

// Comments
$modversion['hasComments'] = 0;

// On Install
//$modversion['onInstall']   = "install.php";
if(    !empty( $_POST['fct'] ) 
    && !empty( $_POST['op'] )
    && $_POST['fct'] == 'modulesadmin'
    && $_POST['op'] == 'install_ok'
    && $_POST['module'] == $mydirname ) {
	global $ret;
	$module_handler =& xoops_gethandler('module');
	if( !empty( $ret ) ){
		require  XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/include/oninstall.php" ;
	}
}

// onUpdate
//if( ! empty( $_POST['fct'] ) && ! empty( $_POST['op'] ) && $_POST['fct'] == 'modulesadmin' && $_POST['op'] == 'update_ok' && $_POST['dirname'] == $modversion['dirname'] ) {
//	include( XOOPS_ROOT_PATH. "/modules/" .$mydirname . "/include/onupdate.inc.php" );
//}


?>
