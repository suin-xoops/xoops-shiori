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

class Shiori_Class_Language extends SimpleXMLElement
{
	public function messages()
	{
		$array = $this->asArray($this);

		if ( is_array($array['dt']) and is_array($array['dd']) )
		{
			$messages = array_combine($array['dt'], $array['dd']);
		}
		else
		{
			$messages[$array['dt']] = $array['dd'];
		}

		return $messages;
	}

	public function asXML()
	{
		$string = parent::asXML();
		$this->_creanupXML($string);
		return $string;
	}

	public function asArray()
	{
		$this->_objectToArray($this);
		return $this;
	}

	protected function _creanupXML(&$string)
	{
		$string = preg_replace("/>_s*</", ">_n<", $string);
		$lines  = explode("_n", $string);
		$string = array_shift($lines) . "_n";
		$depth  = 0;

		foreach ( $lines as $line )
		{
			if ( preg_match('/^<[_w]+>$/U', $line) )
			{
				$string .= str_repeat("_t", $depth);
				$depth++;
			}
			elseif ( preg_match('/^<_/.+>$/', $line) )
			{
				$depth--;
				$string .= str_repeat("_t", $depth);
			}
			else
			{
				$string .= str_repeat("_t", $depth);
			}

			$string .= $line . "_n";
		}

		$string = trim($string);
	}

	protected function _objectToArray(&$object)
	{
		if ( is_object($object) ) $object = (array) $object;
		if ( !is_array($object) ) return;

		foreach ( $object as &$member )
		{
			$this->_objectToArray($member);
		}
	}
}

?>
