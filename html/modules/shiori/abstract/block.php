<?php
/**
 * A simple description for this script
 *
 * PHP Version 5.2.4 or Upper version
 *
 * @package    Shiori
 * @author     Hidehito NOZAWA aka Suin <http://suin.asia>
 * @copyright  2009 Hidehito NOZAWA
 * @license    http://www.gnu.org/licenses/gpl-2.0.html GNU GPL v2 or later
 *
 */

abstract class Shiori_Abstract_Block extends Shiori_Abstract_Controller
{
	protected $template  = null;
	protected $data      = array();
	protected $content   = null;
	protected $blockName = null;

	public function __construct($blockName)
	{
		$this->blockName = $blockName;
	}

	public function main()
	{
	}

	protected function _view()
	{
		$template = 'shiori_block_'.strtolower($this->blockName).'.tpl';
		$this->_escapeHtml($this->data);
		$xoopsTpl = new xoopsTpl();
		$xoopsTpl->assign('shiori', $this->data);
		$xoopsTpl->register_modifier('shiori_msg', 'Shiori::msg');
		$this->content = $xoopsTpl->fetch('db:'.$template);
		unset($xoopsTpl);
	}
}

?>
