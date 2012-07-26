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
require( '../../../mainfile.php' );
require_once( XOOPS_ROOT_PATH.'/include/cp_header.php' );
require_once( XOOPS_ROOT_PATH."/class/xoopsmodule.php" );
include_once( XOOPS_ROOT_PATH.'/class/xoopsformloader.php' );

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
$mydir = XOOPS_URL."/modules/" .$mydirname. "/";

if ( file_exists(XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/".$xoopsConfig['language']."/modinfo.php") ) {
	require( XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/".$xoopsConfig['language']."/modinfo.php" );
} else {
	require( XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/japanese/admin.php" );
}

// セキュリティチェック
if( ! isset( $module ) || ! is_object( $module ) ) $module = $xoopsModule ;
else if( ! is_object( $xoopsModule ) ) die( '$xoopsModule is not set' )  ;

//オペレーション初期化
$op = ( isset($_REQUEST['op']) ) ? $_REQUEST['op'] : 'default' ;

$credit = '<br /><br /><div align="center">'._AM_CREDIT.'<br />'._AM_TRANSLATER.'</div>';

switch($op)
{
default:
case 'default':
	include_once 'menu.php';
	xoops_cp_header();
        echo "\n".'<table width="100%" border="0" cellspacing="1" class="outer">';
        echo "\n".'<tr><td class="odd">';
        echo "\n".'<a href="index.php"><h4>' ._MI_SHIORI_NAME. '</h4></a>';
        echo "\n".'<table border="0" cellpadding="4" cellspacing="1" width="100%">';
	while( list($k, $v) = each($adminmenu) )
	{
	        echo "\n".'<tr class="bg1" align="left">';
	        echo "\n".'<td><span class="fg2"><a href="' .$mydir.$adminmenu[$k]['link']. '">' .$adminmenu[$k]['title']. '</a></span></td>';
	        echo "\n".'<td><span class="fg2">' .$adminmenu[$k]['desc']. '</span></td>';
	        echo "\n".'</tr>';
	}
	echo "\n".'<tr class="bg1" align="left">';
	echo "\n".'<td><span class="fg2"><a href="'.XOOPS_URL.'/modules/system/admin.php?fct=preferences&op=showmod&mod='.$module->getvar('mid').'">'._PREFERENCES.'</a></span></td>';
	echo "\n".'<td><span class="fg2">' ._MI_SHIORI_MENU_D0. '</span></td>';
	echo "\n".'</tr>';
        echo "\n".'</table>';
        echo "\n".'</td></tr>';
        echo "\n".'</table>';
	echo "\n".$credit;
	xoops_cp_footer();
	break;
}
?>
