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

function b_shiori_block()
{
	$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
	global $xoopsModule, $xoopsTpl, $xoopsUser;
	
	if ( !$xoopsUser ) {
		return false;
	}
	
	if( is_object($xoopsModule) ){
		$mid = $xoopsModule->getVar('mid');
	}else{
		$mid = 0;
	}
	
	$block = array();
	$block['lang_bmthispage'] = _MB_BM_THISPAGE;
	$block['submit'] = _MB_ADD_BOOKMARK;
	$block['action_url'] = XOOPS_URL."/modules/".$mydirname."/bookmark.php";
	$block['url'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$block['mid'] = $mid;
	$block['title'] = $xoopsTpl->get_template_vars("xoops_pagetitle");
	$block['mydirname'] = $mydirname;
	return $block;
}


?>


