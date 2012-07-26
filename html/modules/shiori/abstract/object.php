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

class Shiori_Abstract_Object
{
	const BOOL     = 1;
	const INTEGER  = 2;
	const FLOAT    = 3;
	const STRING   = 4;
	const TEXT     = 5;
	const DATETIME = 6;

	protected $vars = array();
	protected $new  = null;

	public function val($name, $type, $default = null, $size = null)
	{
		$this->vars[$name]['value']   = $default;
		$this->vars[$name]['type']    = $type;
		$this->vars[$name]['default'] = $default;

		if ( $type == self::INTEGER )
		{
			$this->vars[$name]['size'] = ($size) ? $size : 8;
		}
		elseif ( $type == self::STRING )
		{
			$this->vars[$name]['size'] = ($size) ? $size : 255;
		}
	}

	public function setNew()
	{
		$this->new = true;
	}

	public function unsetNew()
	{
		$this->new = false;
	}

	public function isNew()
	{
		return $this->new;
	}

	public function setVar($name, $value)
	{
		$type = $this->vars[$name]['type'];

		if ( self::BOOL == $type )
		{
			$value = ( $value ) ? true : false ;
		}
		elseif ( self::INTEGER == $type )
		{
			$value = intval($value);
		}
		elseif ( self::FLOAT == $type )
		{
			$value = floatval($value);
		}
		elseif ( self::STRING == $type )
		{
			$value = strval($value);
		}
		elseif ( self::TEXT == $type )
		{
			$value = strval($value);
		}
		elseif ( self::DATETIME == $type )
		{
			if ( ($timestamp = strtotime($value)) !== false )
			{
				$value = $timestamp;
			}
		}

		$this->vars[$name]['value'] = $value;
	}

	public function getVar($name)
	{
		return $this->vars[$name]['value'];
	}

	public function setVars($vars)
	{
		foreach ( $this->vars as $key => $v )
		{
			if ( isset($vars[$key]) )
			{
				$this->setVar($key, $vars[$key]);
			}
		}
	}

	public function getVars()
	{
		$vars = array();

		foreach ( $this->vars as $name => $var )
		{
			$vars[$name] = $var['value'];
		}

		return $vars;
	}

	public function getVarSqlEscaped($name)
	{
		$type  = $this->vars[$name]['type'];
		$value = $this->vars[$name]['value'];

		if ( self::BOOL == $type )
		{
			return ( $value ) ? true : false ;
		}
		elseif ( self::INTEGER == $type )
		{
			return intval($value);
		}
		elseif ( self::FLOAT == $type )
		{
			return floatval($value);
		}
		elseif ( self::STRING == $type )
		{
			return mysql_real_escape_string($value); // todo : size check
		}
		elseif ( self::TEXT == $type )
		{
			return mysql_real_escape_string($value);
		}
		elseif ( self::DATETIME == $type )
		{
			return date('Y-m-d H:i:s', $value);
		}
	}

	public function getVarsSqlEscaped()
	{
		$vars = array();

		foreach ( $this->vars as $name => $var )
		{
			$vars[$name] = $this->getVarSqlEscaped($name);
		}

		return $vars;
	}
}

?>
