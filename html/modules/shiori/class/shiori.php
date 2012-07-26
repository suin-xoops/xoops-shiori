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
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";

class Shiori extends XoopsObject
{
	var $db;

	//constructor
	function Shiori($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("id", 	XOBJ_DTYPE_INT, 	null, 	false);
		$this->initVar("uid", 	XOBJ_DTYPE_INT, 	null, 	false);
		$this->initVar("mid", 	XOBJ_DTYPE_INT, 	null, 	false);
		$this->initVar("sort", 	XOBJ_DTYPE_INT, 	0, 	false);
		$this->initVar("date", 	XOBJ_DTYPE_INT, 	null, 	false);
		$this->initVar("url", 	XOBJ_DTYPE_TXTBOX, 	null, 	false, 	250);
		$this->initVar("name", 	XOBJ_DTYPE_TXTBOX, 	null, 	false, 	200);
		$this->initVar("icon", 	XOBJ_DTYPE_TXTBOX, 	null, 	false, 	100);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
				$this->load(intval($id));
			}
		}
	}

	function store()
	{
		if ( !$this->cleanVars() ) {
			return false;
		}
		foreach ( $this->cleanVars as $k=>$v ) {
			$$k = $v;
		}
		if ( empty($id) ) {
			$id = $this->db->genId($this->db->prefix("shiori_bookmark")."_id_seq");
			$sql  = "INSERT INTO ".$this->db->prefix("shiori_bookmark")." (id, uid, mid, sort, date, url, name, icon) ";
			$sql .= "VALUES ($id, $uid, $mid, $sort, $date, ".$this->db->quoteString($url).", ".$this->db->quoteString($name).", ".$this->db->quoteString($icon).")";
		} else {
			$sql  = "UPDATE ".$this->db->prefix("shiori_bookmark")." SET ";
			$sql .= "uid=$uid, mid=$mid, sort=$sort, date=$date, url=".$this->db->quoteString($url).", name=".$this->db->quoteString($name).", icon=".$this->db->quoteString($icon)." WHERE id=$id";
		}
		//echo $sql;
		if ( !$result = $this->db->query($sql) ) {
			$this->setErrors("Could not store data in the database.");
			return false;
		}
		if ( empty($id) ) {
			return $this->db->getInsertId();
		}
		return $id;
	}

	function load($id)
	{
		$sql = "SELECT * FROM ".$this->db->prefix("shiori_bookmark")." WHERE id=".$id."";
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
	}

	function delete()
	{
		$sql = sprintf("DELETE FROM %s WHERE id = %u", $this->db->prefix("shiori_bookmark"), $this->getVar("id"));
        	if ( !$this->db->query($sql) ) {
			return false;
		}
		return true;
	}

	function &getAll($criteria=array(), $asobject=true, $orderby="date DESC", $limit=0, $start=0)
	{
		$db =& Database::getInstance();
		$ret = array();
		$where_query = "";
		if ( is_array($criteria) && count($criteria) > 0 ) {
			$where_query = " WHERE";
			foreach ( $criteria as $c ) {
				$where_query .= " $c AND";
			}
			$where_query = substr($where_query, 0, -4);
		}
		if ( !$asobject ) {
			$sql = "SELECT id FROM ".$db->prefix("shiori_bookmark")."$where_query ORDER BY $orderby";
			$result = $db->query($sql,intval($limit),intval($start));
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("shiori_bookmark")."".$where_query." ORDER BY $orderby";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Shiori($myrow);
			}
		}
		//echo $sql;
		return $ret;
	}

	function &getURLs($criteria=array(), $orderby="date DESC", $limit=0, $start=0)
	{
		$db =& Database::getInstance();
		$ret = array();
		$where_query = "";
		if ( is_array($criteria) && count($criteria) > 0 ) {
			$where_query = " WHERE";
			foreach ( $criteria as $c ) {
				$where_query .= " $c AND";
			}
			$where_query = substr($where_query, 0, -4);
		}
		$sql = "SELECT url FROM ".$db->prefix("shiori_bookmark")."$where_query ORDER BY $orderby";
		$result = $db->query($sql,intval($limit),intval($start));
		while ( $myrow = $db->fetchArray($result) ) {
			$ret[] = $myrow['url'];
		}
		//echo $sql;
		return $ret;
	}

	function &getModules($criteria=array(), $orderby="date DESC", $limit=0, $start=0)
	{
		$db =& Database::getInstance();
		$ret = array();
		$where_query = "";
		if ( is_array($criteria) && count($criteria) > 0 ) {
			$where_query = " WHERE";
			foreach ( $criteria as $c ) {
				$where_query .= " $c AND";
			}
			$where_query = substr($where_query, 0, -4);
		}
		$sql = "SELECT mid FROM ".$db->prefix("shiori_bookmark")."$where_query ORDER BY $orderby";
		$result = $db->query($sql,intval($limit),intval($start));
		while ( $myrow = $db->fetchArray($result) ) {
			$ret[] = $myrow['mid'];
		}
		//echo $sql;
		return $ret;
	}
}
?>