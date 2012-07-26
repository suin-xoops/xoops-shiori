<?php
//  ------------------------------------------------------------------------ //
//  命名規則 - 接頭辞 -							     //
//  _MD_ --- main.php		メイン画面用				     //
//  _MB_ --- blocks.php		ブロック用				     //
//  _AM_ --- admin.php		管理画面用				     //
//  _MI_ --- modinfo.php	xoops_version.php用/管理メニュー用	     //
//  ------------------------------------------------------------------------ //
// Module Info
// The name of this module
define("_MI_SHIORI_NAME","SHIORI");

// A brief description of this module
define("_MI_SHIORI_DESC","This module allows users to add any pages into their BOOKMARK");

// Names of admin menu items
define("_MI_SHIORI_MENU_D0", "Setting up basic preferences");
define("_MI_SHIORI_MENU1", "Blocks & Groups Admin");
define("_MI_SHIORI_MENU_D1", "Administration of Blocks and Groups");
define("_MI_SHIORI_MENU2", "Popular Bookmarks");
define("_MI_SHIORI_MENU_D2", "The ranks of pages");

// Names of blocks for this module (Not all module has blocks)
define("_MI_SHIORI_BLOCK1", "Bookmark");
define("_MI_SHIORI_BLOCK_D1", "A block for page registration");

// Templates
define("_MI_TPL_DESC1", "List of bookmarks (index.php)");
define("_MI_TPL_DESC2", "Form for registration (bookmark.php)");

// Configs
define("_MI_SHIORI_CONFIG1", "Max bookmarks for each users");
define("_MI_SHIORI_CONFIG_D1", "");
define("_MI_SHIORI_CONFIG2", "Bookmarks a page");
define("_MI_SHIORI_CONFIG_D2", "");
define("_MI_SHIORI_CONFIG3", "Allow users to register some URLs other than your site");
define("_MI_SHIORI_CONFIG_D3", "");
define("_MI_SHIORI_CONFIG4", "Allow users to register some URLs manually");
define("_MI_SHIORI_CONFIG_D4", "");
define("_MI_SHIORI_CONFIG5", "Minimum number of visitation to be one star");
define("_MI_SHIORI_CONFIG_D5", "");
define("_MI_SHIORI_CONFIG6", "Minimum number of visitation to be two stars");
define("_MI_SHIORI_CONFIG_D6", "");
define("_MI_SHIORI_CONFIG7", "Minimum number of visitation to be three stars");
define("_MI_SHIORI_CONFIG_D7", "");
define("_MI_SHIORI_CONFIG8", "Minimum number of visitation to be four stars");
define("_MI_SHIORI_CONFIG_D8", "");
define("_MI_SHIORI_CONFIG9", "Minimum number of visitation to be five stars");
define("_MI_SHIORI_CONFIG_D9", "");
?>