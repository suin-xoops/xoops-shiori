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

class Shiori_Abstract_ObjectHandler
{
	protected $object  = '';
	protected $table   = '';
	protected $primary = 'id';

	protected $db      = null;

	protected $errors = array();

	public function __construct()
	{
		$this->db =& Shiori::database();
		$this->table = $this->db->prefix($this->table);
	}

	public function create()
	{
		$obj = $this->object;
		$obj = new $obj();
		$obj->setNew();
		return $obj;
	}

	public function load($id)
	{
		$id = intval($id);
		$sql = "select * from `%s` where `%s`='%u'";
		$sql = sprintf($sql, $this->table, $this->primary, $id);
		$rsrc = $this->_query($sql, 1);
		$vars = $this->db->fetchArray($rsrc);

		$obj = $this->create();
		$obj->unsetNew();
		$obj->setVars($vars);

		return $obj;
	}

	public function save(&$obj)
	{
		if ( $obj->isNew() )
		{
			$this->_insert($obj);
		}
		else
		{
			$this->_update($obj);
		}

		return ( count($this->errors) === 0 );
	}

	public function delete($id)
	{
		$id = (int) $id;
		$sql = "DELETE FROM `%s` WHERE `%s` = '%u'";
		$sql = sprintf($sql, $this->table, $this->primary, $id);
		return $this->_query($sql);
	}

	public function getErrors()
	{
		return $this->errors;
	}

	protected function _insert(&$obj)
	{
		$vars = $obj->getVarsSqlEscaped();
		$data = $this->_buildData($vars);

		$sql = "INSERT INTO `%s` SET %s";
		$sql = sprintf($sql, $this->table, $data);

		if ( !$this->_query($sql) ) return;

		$obj->unsetNew();
	}

	protected function _update(&$obj)
	{
		$id   = $obj->getVar($this->primary);
		$vars = $obj->getVarsSqlEscaped();
		$data = $this->_buildData($vars);

		$sql = "UPDATE `%s` SET %s WHERE `%s` = '%u'";
		$sql = sprintf($sql, $this->table, $data, $this->primary, $id);

		$this->_query($sql);
	}

	protected function _query($sql, $limit = null, $start = null)
	{
		$result = $this->db->query($sql, $limit, $start);
		if ( !$result ) $this->errors[] = $this->db->getError();
		return $result;
	}

	protected function _buildData($vars)
	{
		$ret = array();

		foreach ( $vars as $name => $value )
		{
			$ret[] = sprintf("`%s` = '%s'", $name, $value);
		}

		$ret = implode(', ', $ret);

		return $ret;
	}
}

?>
