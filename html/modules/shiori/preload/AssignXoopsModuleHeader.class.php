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
		$jQueryLink = '<script type="text/javascript" src="'.XOOPS_URL.'/modules/'.$dirname.'/javascript/bookmark.js"></script>'. "\n";
		$xoopsTpl->assign('xoops_module_header', $xoopsTpl->get_template_vars('xoops_module_header').$jQueryLink);
	}
}

?>