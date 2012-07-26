<?php

if ( !defined('XOOPS_ROOT_PATH') ) exit;

class Shiori_AssignXoopsModuleHeader extends XCube_ActionFilter
{
	function preBlockFilter()
	{
		$this->mRoot->mDelegateManager->add('Legacy_RenderSystem.SetupXoopsTpl', array(&$this, 'hook'));
	}

	function hook(&$xoopsTpl)
	{
		$dirname = basename(dirname(dirname(__FILE__)));
		require_once XOOPS_ROOT_PATH.'/modules/'.$dirname.'/class/javascript_loader.php';
		$xoopsModuleHeader = $xoopsTpl->get_template_vars('xoops_module_header') . shiori_get_javascript_link();
		$xoopsTpl->assign('xoops_module_header', $xoopsModuleHeader);
	}
}

?>