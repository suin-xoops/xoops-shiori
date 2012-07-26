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
require_once( XOOPS_ROOT_PATH.'/class/xoopsformloader.php' );

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
$mydir = XOOPS_URL."/modules/" .$mydirname. "/";

if ( file_exists(XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/".$xoopsConfig['language']."/modinfo.php") ) {
	require( XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/".$xoopsConfig['language']."/modinfo.php" );
} else {
	require( XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/japanese/admin.php" );
}

if ( file_exists(XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/".$xoopsConfig['language']."/main.php") ) {
	require( XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/".$xoopsConfig['language']."/main.php" );
} else {
	require( XOOPS_ROOT_PATH."/modules/" .$mydirname. "/language/japanese/main.php" );
}

//栞クラス
require_once( XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/class/shiori.php' );

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
case 'lank':

	$days = ( isset($_GET['days']) ) ? floatval( $_GET['days'] ) : 7 ;
	$secago = time() - $days * 24 * 60 * 60 ;

	//URL
	$criteria = array('date>'.$secago);
	$urls_arr =& Shiori::getURLs($criteria);
	$counturls_arr = array_count_values($urls_arr);
	arsort($counturls_arr);

	//ファイル
	$countfiles_arr = array();
	foreach( $urls_arr as $url){
		$countfiles_arr[] = preg_replace('/\.php?.*/', '.php', $url);
	}
	$countfiles_arr = array_count_values($countfiles_arr);
	arsort($countfiles_arr);

	//モジュール
	$mods_arr =& Shiori::getModules($criteria);
	$mods_arr = array_count_values($mods_arr);
	arsort($mods_arr);

	xoops_cp_header();
        echo "\n".'<form action="'.$mydir.'admin/index.php" method="GET">';
        echo "\n".'<h4 style="text-align:left">';
        printf(_AM_DAYS_LANKING, '<input name="days" value="'.$days.'" size="3" />');
        echo "\n".'<input type="hidden" name="op" value="lank" />';
        echo "\n".'<input type="submit" value="'._SEND.'" />';
        echo "\n".'</h4>';
        echo "\n".'</form>';
        echo "\n".'<table border="0" class="outer" cellspacing="1" cellpadding="4" summary="the lanking of pages" style="width:100%">';
        echo "\n".'<tr>';
        echo "\n".'<th align="center" width="30">'._AM_LANK.'</th>';
        echo "\n".'<th align="center">'._AM_PAGES.'</th>';
        echo "\n".'<th align="center" width="50">'._AM_SUM.'</th>';
        echo "\n".'</tr>';
	$i = 0;
	$pre_sum = 0;
	foreach($counturls_arr as $url => $sum){
		$lank = "&nbsp;";
		if( $sum != $pre_sum){ $i++; $lank = $i; }
	        echo "\n".'<tr class="even">';
	        echo "\n".'<td width="30" align="center">'.$lank.'</td>';
	        echo "\n".'<td><a href="'. htmlspecialchars($url).'">'.htmlspecialchars( $url ).'</a></td>';
	        echo "\n".'<td width="50" align="center">'.$sum.'</td>';
	        echo "\n".'</tr>';
		$pre_sum = $sum;
	}
        echo "\n".'</table>';
        echo "\n".'<br />';
        echo "\n".'<table border="0" class="outer" cellspacing="1" cellpadding="4" summary="the lanking of pages" style="width:100%">';
        echo "\n".'<tr>';
        echo "\n".'<th align="center" width="30">'._AM_LANK.'</th>';
        echo "\n".'<th align="center">'._AM_FILES.'</th>';
        echo "\n".'<th align="center" width="50">'._AM_SUM.'</th>';
        echo "\n".'</tr>';
	$i = 0;
	$pre_sum = 0;
	foreach($countfiles_arr as $url => $sum){
		$lank = "&nbsp;";
		if( $sum != $pre_sum){ $i++; $lank = $i; }
	        echo "\n".'<tr class="even">';
	        echo "\n".'<td width="30" align="center">'.$lank.'</td>';
	        echo "\n".'<td><a href="'.htmlspecialchars($url).'">'.htmlspecialchars( $url ).'</a></td>';
	        echo "\n".'<td width="50" align="center">'.$sum.'</td>';
	        echo "\n".'</tr>';
		$pre_sum = $sum;
	}
        echo "\n".'</table>';
        echo "\n".'<br />';
        echo "\n".'<table border="0" class="outer" cellspacing="1" cellpadding="4" summary="the lanking of pages" style="width:100%">';
        echo "\n".'<tr>';
        echo "\n".'<th align="center" width="30">'._AM_LANK.'</th>';
        echo "\n".'<th align="center">'._AM_MODULES.'</th>';
        echo "\n".'<th align="center" width="50">'._AM_SUM.'</th>';
        echo "\n".'</tr>';
	$i = 0;
	$pre_sum = 0;
	foreach($mods_arr as $mid => $sum){
		$lank = "&nbsp;";
		if( $sum != $pre_sum){ $i++; $lank = $i; }
		
		//モジュール名
		$modname = _MD_BOOK_NOTMOD;
		if( $mid > 0 ){
			$module_handler =& xoops_gethandler('module');
			$module =& $module_handler->get($mid);
			$modname = $module->getVar('name');
		}else{
			switch($mid){
			  case '-1':
				$modname = _MD_BOOK_USERINFO;
				break;
			  case '-2':
				$modname = _MD_BOOK_SEARCH;
				break;
			  case '-3':
				$modname = _MD_BOOK_PM;
				break;
			  case '-4':
				$modname = _MD_BOOK_INDEX;
				break;
			  case '-5':
				$modname = _MD_BOOK_OUTER;
				break;
			}
		}
	        echo "\n".'<tr class="even">';
	        echo "\n".'<td width="30" align="center">'.$lank.'</td>';
	        echo "\n".'<td>'.htmlspecialchars( $modname ).'</td>';
	        echo "\n".'<td width="50" align="center">'.$sum.'</td>';
	        echo "\n".'</tr>';
		$pre_sum = $sum;
	}
        echo "\n".'</table>';
	xoops_cp_footer();
	break;
}
?>