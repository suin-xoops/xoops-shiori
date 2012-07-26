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
//                This module; Shiori Copyright (c) 2005 suin                //
//                          <http://www.suin.jp>                             //
//  ------------------------------------------------------------------------ //

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

// referer check
$ref = xoops_getenv('HTTP_REFERER');
if( $ref == '' || strpos( $ref , XOOPS_URL.'/modules/system/admin.php' ) === 0 ) {
	
	/* module specific part */
	
	global $xoopsDB;
	$sql0 = "ALTER TABLE ".$xoopsDB->prefix('shiori_bookmark')." DROP KEY `uid`";
	if( !$xoopsDB->query( $sql0 ) ){
		$sql1 = "ALTER TABLE ".$xoopsDB->prefix('shiori_bookmark')." ADD PRIMARY KEY ( `id` ) ";
		$sql3 = "ALTER TABLE ".$xoopsDB->prefix('shiori_bookmark')." ADD KEY ( `mid` ) ";
		$sql4 = "ALTER TABLE ".$xoopsDB->prefix('shiori_bookmark')." ADD KEY ( `url` ) ";
		$sql5 = "ALTER TABLE ".$xoopsDB->prefix('shiori_bookmark')." ADD `counter` INT( 10 ) DEFAULT '0' NOT NULL";
		$xoopsDB->query( $sql1 ) ;
		$xoopsDB->query( $sql3 ) ;
		$xoopsDB->query( $sql4 ) ;
		$xoopsDB->query( $sql5 ) ;
	}
	$sql2 = "ALTER TABLE ".$xoopsDB->prefix('shiori_bookmark')." ADD KEY ( `uid` ) ";
	$xoopsDB->query( $sql2 ) ;

	// Keep the values of block's options when module is updated (by nobunobu)
	//include dirname( __FILE__ ) . "/updateblock.inc.php" ;
}
?>
