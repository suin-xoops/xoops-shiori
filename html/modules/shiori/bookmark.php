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
$xoopsOption['pagetype'] = 'user';

require( '../../mainfile.php' );

//ユーザーで無ければ
if ( !$xoopsUser ) {
	redirect_header(XOOPS_URL, 3, _NOPERM);
	exit();
}

//サニタイザ読み込み
$myts =& MyTextSanitizer::getInstance();

//モジュール名取得
$mydirname = basename( dirname( __FILE__ ) ) ;
$myurl = XOOPS_URL . '/modules/' . $mydirname ;

//オペレーション初期化
$op = isset( $_POST['op'] ) ? $_POST['op'] : 'form';

//Gチケットシステム呼び出し
include_once( XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/include/gtickets.php' );

if( $op == 'form' ){
	
	//URLが空
	if( empty( $_REQUEST['url'] ) ){
		redirect_header(XOOPS_URL, 3, _MD_EMPTY_URL);
		exit();
	}
	
	//当サイトのURLではない
	if( !preg_match( '|'.XOOPS_URL.'|', $_REQUEST['url'] ) && $xoopsModuleConfig['shiori_prmt_outofsite'] == 0 ){
		redirect_header(XOOPS_URL, 3, _MD_NOT_THISITE);
		exit();
	}
	
	//初期化
	$url   = $myts->stripSlashesGPC( $myts->htmlSpecialChars( $_REQUEST['url'] ) );
//	$mid   = isset( $_POST['mid'] )   ? intval( $_POST['mid'] ) : 0 ;
	
	//容量確認
	$db =& Database::getInstance();
	$uid = $xoopsUser->getVar('uid');
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("shiori_bookmark")." WHERE uid=".$uid."";
	list($sum) = $db->fetchRow($db->query($sql));
	if( $sum >= $xoopsModuleConfig['shiori_capacity'] ){
		redirect_header($myurl, 5, _MD_NO_SPACE);
		exit();
	}
	
	//
	$nottrans = ( isset($_GET['url']) ) ? 1 : 0 ;
	
	//重複確認
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("shiori_bookmark")." WHERE uid=".$uid." AND url=".$db->quoteString( $_REQUEST['url'] );
	list($sum) = $db->fetchRow($db->query($sql));
	if( $sum > 0 ){
		redirect_header($myurl, 5, _MD_ALREDY_BOOKMARKED);
		exit();
	}
	
	//モジュール名
	$modname = _MD_BOOK_NOTMOD;
	$mid = 0;
	if( preg_match('/\/modules\/[^\/]+\//', $url ) ){
		$dirname = preg_replace('/.*\/modules\/([^\/]+)\/.*/', '$1', $url );
	        $sql = 'SELECT COUNT(*) FROM '.$db->prefix('modules').' WHERE dirname='.$db->quoteString($dirname);
		list( $count ) = $db->fetchRow($db->query($sql));
		if( $count > 0 ){
			$module_handler =& xoops_gethandler('module');
			$module =& $module_handler->getByDirname($dirname);
			$modname = $module->getVar('name');
			$mid = $module->getVar('mid');
		}
	}elseif( preg_match('|'.XOOPS_URL.'/userinfo\.php.*|', $url ) ){
		$modname = _MD_BOOK_USERINFO;
		$mid = -1;
	}elseif( preg_match('|'.XOOPS_URL.'/search\.php.*|', $url ) ){
		$modname = _MD_BOOK_SEARCH;
		$mid = -2;
	}elseif( preg_match('|'.XOOPS_URL.'/readpmsg\.php.*|', $url ) || preg_match('|'.XOOPS_URL.'/viewpmsg\.php.*|', $url ) ){
		$modname = _MD_BOOK_PM;
		$mid = -3;
	}elseif( preg_match('|'.XOOPS_URL.'/(index\.php)|', $url ) ){
		$modname = _MD_BOOK_INDEX;
		$mid = -4;
	}elseif( !preg_match('|'.XOOPS_URL.'/.*|', $url ) ){
		$modname = _MD_BOOK_OUTER;
		$mid = -5;
	}
	
	$xoopsOption['template_main'] = 'shiori_form.html';
	
	require_once( XOOPS_ROOT_PATH.'/header.php' );
	require_once( XOOPS_ROOT_PATH.'/class/xoopslists.php' );
	
	//アイコン
	$lists = new XoopsLists;
	$filelist = $lists->getSubjectsList();
	$count = 1;
	$bookicon = "";
	while ( list($key, $file) = each($filelist) ) {
		$checked = "";
		if ( isset($icon) && $file==$icon ) {
			$checked = " checked='checked'";
		}
		$bookicon .= "<input type='radio' value='$file' name='icon' id='$file'$checked />&nbsp;";
		$bookicon .= "<label for='$file'><img src='".XOOPS_URL."/images/subject/$file' alt='' /></label>&nbsp;";
		if ( $count == 8 ) {
			$bookicon .= "<br />";
			$count = 0;
		}
		$count++;
	}
	
	//割り当て
	$xoopsTpl->assign('action_url', $myurl.'/bookmark.php');
	$xoopsTpl->assign('lang_form', _MD_BOOK_FORM);
	$xoopsTpl->assign('lang_name', _MD_BOOK_NAME);
	$xoopsTpl->assign('bookname', $modname);
	$xoopsTpl->assign('lang_modname', _MD_BOOK_MODNAME);
	$xoopsTpl->assign('modulename', $modname);
	$xoopsTpl->assign('lang_url', _MD_BOOK_URL);
	$xoopsTpl->assign('bookurl', $url);
	$xoopsTpl->assign('booksubmit', _MD_BOOK_SUBMIT);
	$xoopsTpl->assign('bookmid', $mid);
	$xoopsTpl->assign('lang_icon', _MD_BOOK_ICON);
	$xoopsTpl->assign('bookicon', $bookicon);
	$xoopsTpl->assign('copyright', '<a href="http://www.suin.jp/" target="_blank">shiori</a>');
	//チケット発行
	$xoopsTpl->assign('hiddenelements', $xoopsGTicket->getTicketHtml( __LINE__ ).'<input type="hidden" name="nottrans" value="'.$nottrans.'" />');
	
	require_once( XOOPS_ROOT_PATH.'/footer.php' );
	
	exit();
}
if( $op == 'save' ){
	
	if ( ! $xoopsGTicket->check() ) {
		redirect_header( XOOPS_URL, 3, $xoopsGTicket->getErrors() );
		exit();
	}
	
	//URLが空白
	if( empty( $_POST['url'] ) ){
		redirect_header(XOOPS_URL, 3, _MD_EMPTY_URL);
		exit();
	}
	
	//当サイトのURLではない
	if( !preg_match( '|'.XOOPS_URL.'|', $_POST['url'] ) && $xoopsModuleConfig['shiori_prmt_outofsite'] == 0 ){
		redirect_header(XOOPS_URL, 3, _MD_NOT_THISITE);
		exit();
	}
	
	//栞クラス呼び出し
	require_once( XOOPS_ROOT_PATH. '/modules/' .$mydirname. '/class/shiori.php' );
	
	//初期化
	$url = $myts->addSlashes( $_POST['url'] );
	$title = isset( $_POST['title'] ) ? $myts->addSlashes( $_POST['title'] ) : "" ;
	$icon = isset( $_POST['icon'] ) ? $myts->addSlashes( $_POST['icon'] ) : "" ;
	$mid = isset( $_POST['mid'] ) ? intval( $_POST['mid'] ) : 0 ;
	$nottrans = isset( $_POST['nottrans'] ) ? intval( $_POST['nottrans'] ) : 0 ;
	
	//容量確認
	$db =& Database::getInstance();
	$uid = $xoopsUser->getVar('uid');
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("shiori_bookmark")." WHERE uid=".$uid."";
	list($sum) = $db->fetchRow($db->query($sql));
	if( $sum >= $xoopsModuleConfig['shiori_capacity'] ){
		redirect_header($myurl, 5, _MD_NO_SPACE);
		exit();
	}
	
	//重複確認
	$sql = "SELECT COUNT(*) FROM ".$db->prefix("shiori_bookmark")." WHERE uid=".$uid." AND url=".$db->quoteString( $_REQUEST['url'] );
	list($sum) = $db->fetchRow($db->query($sql));
	if( $sum > 0 ){
		redirect_header($myurl, 5, _MD_ALREDY_BOOKMARKED);
		exit();
	}
	
	$shiori = new Shiori();
	$shiori->setVar('uid', $xoopsUser->getVar('uid'));
	$shiori->setVar('mid', $mid);
	$shiori->setVar('icon', $icon);
	$shiori->setVar('url', $url);
	$shiori->setVar('date', time());
	$shiori->setVar('name', $title);
	
	//保存失敗
	if( !$shiori->store() ){
		redirect_header($url, 5, $shiori->getHtmlErrors());
		exit();
	}
	
	if( $nottrans == 1 ){
		//保存成功
		redirect_header($myurl, 1, _MD_BOOKMARKED);
		exit();
	}
	
	//保存成功
	redirect_header($url, 1, _MD_BOOKMARKED);
	
	exit();
	
}
?>