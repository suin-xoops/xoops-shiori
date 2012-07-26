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
define("_MI_SHIORI_NAME","栞");

// A brief description of this module
define("_MI_SHIORI_DESC","このモジュールはサイト内のページをブックマークするためのモジュールです。");

// Names of admin menu items
define("_MI_SHIORI_MENU_D0", "基本的な設定を行います。");
define("_MI_SHIORI_MENU1", "グループ/ブロック管理");
define("_MI_SHIORI_MENU_D1", "アクセス権限やブロックの管理を行います。");


// Names of blocks for this module (Not all module has blocks)
define("_MI_SHIORI_BLOCK1", "ブックマーク");
define("_MI_SHIORI_BLOCK_D1", "ブックマークに追加するためのブロック");

// Templates
define("_MI_TPL_DESC1", "ブックマーク一覧(index.php)");
define("_MI_TPL_DESC2", "フォーム(bookmark.php)");

// Configs
define("_MI_SHIORI_CONFIG1", "ブックマークの最大保存件数");
define("_MI_SHIORI_CONFIG_D1", "１人当たりのブックマークの保存容量です。");
define("_MI_SHIORI_CONFIG2", "１ページ当たりに表示するブックマークの件数");
define("_MI_SHIORI_CONFIG_D2", "");
?>
