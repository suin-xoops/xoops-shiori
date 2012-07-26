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

// セキュリティチェック
if( ! isset( $module ) || ! is_object( $module ) ) $module = $xoopsModule ;
else if( ! is_object( $xoopsModule ) ) die( '$xoopsModule is not set' )  ;

//オペレーション初期化
$op = ( isset($_REQUEST['op']) ) ? $_REQUEST['op'] : 'default' ;

switch($op)
{
default:
case 'default':
	xoops_cp_header();

	$mid = $xoopsModule->getVar('mid');
	$module = $xoopsModule;

	echo '<h4 style="text-align:left">'.$module->getVar('name').' - '._AM_ONSETUP.'</h4>';
	echo '<p>';
	echo _AM_INSTALL.'&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
	echo '<span style="color:red;">'._AM_MODULE_SETTING.'</span>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
	$config =& $config_handler->getConfigs(new Criteria('conf_modid', $mid));
	$count = count($config);
	if ($count > 0) {
		echo _AM_MODCONFIG.'&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
	}
	if( file_exists( XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/admin/myblocksadmin.php' ) ){
		echo _AM_GROUP_BLOCK.'&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
	}
	echo _AM_FINISH.'<p>';

	$form = new XoopsThemeForm(_AM_MODULE_SETTING, "form", "setup.php");
	$form->addElement(new XoopsFormLabel(_AM_MOD_ICON,'<img src="'.XOOPS_URL.'/modules/'.$module->getVar('dirname').'/'.$module->getInfo('image').'" alt="'.$module->getVar('name', 'E').'" border="0" />'));
	$form->addElement(new XoopsFormText(_AM_MOD_NAME,'newname', 20, 150, $module->getVar('name', 'E')));
	$form->addElement(new XoopsFormLabel(_AM_MOD_VERSION,round($module->getVar('version') / 100, 2)));
	$form->addElement(new XoopsFormLabel(_AM_MOD_DATE,formatTimestamp($module->getVar('last_update'),'m')));
	if ($module->getVar('hasmain') == 1) {
		$form->addElement(new XoopsFormText(_AM_MOD_SORT,'weight', 5, 5, $module->getVar('weight')));
	}
	$form->addElement(new XoopsFormHidden('op', 'modsave'));
	$form->addElement(new XoopsFormButton('', 'submit', _AM_NEXT, 'submit'));
	$form->display();

	xoops_cp_footer();
	break;
case 'modsave':
	$mid = $xoopsModule->getVar('mid');
	$module = $xoopsModule;
	$name = ( isset($_POST['newname']) ) ? $_POST['newname'] : $module->getVar('name') ;
	$weight = ( isset($_POST['weight']) ) ? $_POST['weight'] : $module->getVar('weight') ;

	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->get($mid);
	$module->setVar('weight', $weight);
	$module->setVar('name', $name);
	$myts =& MyTextSanitizer::getInstance();
	if (!$module_handler->insert($module)) {
		redirect_header("setup.php?op=preferance",5,$module->getHtmlErrors());
	}
	redirect_header("setup.php?op=preferance",2,_AM_DBUPDATED);

	break;

case 'preferance':
		$config_handler =& xoops_gethandler('config');
		$mod = $xoopsModule->getVar('mid');
		if (empty($mod)) {
			header('Location: setup.php');
			exit();
		}
		$config =& $config_handler->getConfigs(new Criteria('conf_modid', $mod));
		$count = count($config);
		if ($count < 1) {
			header('Location: setup.php?op=finish');
			exit();
		}
		include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
		$form = new XoopsThemeForm(_AM_MODCONFIG, 'pref_form', 'setup.php');
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->get($mod);
		if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php')) {
			include_once XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php';
		}

		// if has comments feature, need comment lang file
		if ($module->getVar('hascomments') == 1) {
			include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/comment.php';
		}
		// RMV-NOTIFY
		// if has notification feature, need notification lang file
		if ($module->getVar('hasnotification') == 1) {
			include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/notification.php';
		}

		$modname = $module->getVar('name');
		for ($i = 0; $i < $count; $i++) {
			$title = (!defined($config[$i]->getVar('conf_desc')) || constant($config[$i]->getVar('conf_desc')) == '') ? constant($config[$i]->getVar('conf_title')) : constant($config[$i]->getVar('conf_title')).'<br /><br /><span style="font-weight:normal;">'.constant($config[$i]->getVar('conf_desc')).'</span>';
			switch ($config[$i]->getVar('conf_formtype')) {
			case 'textarea':
				$myts =& MyTextSanitizer::getInstance();
				if ($config[$i]->getVar('conf_valuetype') == 'array') {
					// this is exceptional.. only when value type is arrayneed a smarter way for this
					$ele = ($config[$i]->getVar('conf_value') != '') ? new XoopsFormTextArea($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars(implode('|', $config[$i]->getConfValueForOutput())), 5, 50) : new XoopsFormTextArea($title, $config[$i]->getVar('conf_name'), '', 5, 50);
				} else {
					$ele = new XoopsFormTextArea($title, $config[$i]->getVar('conf_name'), $myts->htmlspecialchars($config[$i]->getConfValueForOutput()), 5, 50);
				}
				break;
			case 'select':
				$ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput());
				$options =& $config_handler->getConfigOptions(new Criteria('conf_id', $config[$i]->getVar('conf_id')));
				$opcount = count($options);
				for ($j = 0; $j < $opcount; $j++) {
					$optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
					$optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
					$ele->addOption($optval, $optkey);
				}
				break;
			case 'select_multi':
				$ele = new XoopsFormSelect($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), 5, true);
				$options =& $config_handler->getConfigOptions(new Criteria('conf_id', $config[$i]->getVar('conf_id')));
				$opcount = count($options);
				for ($j = 0; $j < $opcount; $j++) {
					$optval = defined($options[$j]->getVar('confop_value')) ? constant($options[$j]->getVar('confop_value')) : $options[$j]->getVar('confop_value');
					$optkey = defined($options[$j]->getVar('confop_name')) ? constant($options[$j]->getVar('confop_name')) : $options[$j]->getVar('confop_name');
					$ele->addOption($optval, $optkey);
				}
				break;
			case 'yesno':
				$ele = new XoopsFormRadioYN($title, $config[$i]->getVar('conf_name'), $config[$i]->getConfValueForOutput(), _YES, _NO);
				break;
			case 'group':
				include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
				$ele = new XoopsFormSelectGroup($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
				break;
			case 'group_multi':
				include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
				$ele = new XoopsFormSelectGroup($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
				break;
			// RMV-NOTIFY: added 'user' and 'user_multi'
			case 'user':
				include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
				$ele = new XoopsFormSelectUser($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 1, false);
				break;
			case 'user_multi':
				include_once XOOPS_ROOT_PATH.'/class/xoopslists.php';
				$ele = new XoopsFormSelectUser($title, $config[$i]->getVar('conf_name'), false, $config[$i]->getConfValueForOutput(), 5, true);
				break;
			case 'password':
				$myts =& MyTextSanitizer::getInstance();
				$ele = new XoopsFormPassword($title, $config[$i]->getVar('conf_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
				break;
			case 'textbox':
			default:
				$myts =& MyTextSanitizer::getInstance();
				$ele = new XoopsFormText($title, $config[$i]->getVar('conf_name'), 50, 255, $myts->htmlspecialchars($config[$i]->getConfValueForOutput()));
				break;
			}
			$hidden = new XoopsFormHidden('conf_ids[]', $config[$i]->getVar('conf_id'));
			$form->addElement($ele);
			$form->addElement($hidden);
			unset($ele);
			unset($hidden);
		}
		$form->addElement(new XoopsFormHidden('op', 'prefsave'));
		$form->addElement(new XoopsFormButton('', 'button', _AM_NEXT, 'submit'));
		xoops_cp_header();
		echo '<h4 style="text-align:left">'.$module->getVar('name').' - '._AM_ONSETUP.'</h4>';
		echo '<p>';
		echo _AM_INSTALL.'&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
		echo _AM_MODULE_SETTING.'&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
		$config =& $config_handler->getConfigs(new Criteria('conf_modid', $mid));
		$count = count($config);
		if ($count > 0) {
			echo '<span style="color:red;">'._AM_MODCONFIG.'</span>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
		}
		if( file_exists( XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/admin/myblocksadmin.php' ) ){
			echo _AM_GROUP_BLOCK.'&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
		}
		echo _AM_FINISH.'<p>';
		$form->display();
		xoops_cp_footer();
		exit();

	break;

case 'prefsave':
		$mod = $xoopsModule->getVar('mid');
		$module_handler =& xoops_gethandler('module');
		$module =& $module_handler->get($mod);
		$conf_ids = isset($_POST['conf_ids']) ? $_POST['conf_ids'] : array() ;
		$count = count($_POST['conf_ids']);
		$tpl_updated = false;
		$theme_updated = false;
		$startmod_updated = false;
		$lang_updated = false;
		if ($count > 0) {
			for ($i = 0; $i < $count; $i++) {
				$config =& $config_handler->getConfig($conf_ids[$i]);
				$new_value =& $_POST[$config->getVar('conf_name')];
				if (is_array($new_value) || $new_value != $config->getVar('conf_value')) {
					$config->setConfValueForInput($new_value);
					$config_handler->insertConfig($config);
				}
				unset($new_value);
			}
		}
		$modname = $module->getVar('name');
		if( file_exists( XOOPS_ROOT_PATH.'/modules/'.$module->getVar('dirname').'/admin/myblocksadmin.php' ) ){
			redirect_header(XOOPS_URL.'/modules/'.$module->getVar('dirname').'/admin/myblocksadmin.php' , 2, _AM_DBUPDATED);
		}elseif( $module->getInfo('adminindex') ) {
			redirect_header(XOOPS_URL.'/modules/'.$module->getVar('dirname').'/'.$module->getInfo('adminindex') , 2, _AM_DBUPDATED);
		} else {
			redirect_header('setup.php?op=finish',2,_AM_DBUPDATED);
		}
	break;
}
?>
