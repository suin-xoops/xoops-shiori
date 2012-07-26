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

class Shiori_Object_BookmarkHandler extends Shiori_Abstract_ObjectHandler
{
	protected $object  = 'Shiori_Object_Bookmark';
	protected $table   = 'shiori_bookmark';
	protected $primary = 'id';

	public function loadsStatics($order = 'users', $sort = 'desc', $limit = null, $start = null)
	{
		$orderParams = array('mid', 'url', 'title', 'clicks', 'users');

		if ( !in_array($order, $orderParams) )
		{
			$order = 'users';
		}

		if ( $sort != 'desc' ) $sort = 'asc';

		$sql = "select `mid`, `url`, `name`, sum(`counter`) as `clicks`, count(*) as `users` from `%s` group by `url` order by `%s` %s";
		$sql = sprintf($sql, $this->table, $order, $sort);
		$rsrc = $this->db->query($sql, $limit, $start);

		$bookmarks = array();

		while ( $vars = $this->db->fetchArray($rsrc) )
		{
			$bookmarks[] = array(
				'mid'    => $vars['mid'],
				'url'    => $vars['url'],
				'name'   => $vars['name'],
				'clicks' => $vars['clicks'],
				'users'  => $vars['users'],
			);
		}

		return $bookmarks;
	}

	public function count()
	{
		$sql = "select count(distinct `url`) from `%s`";
		$sql = sprintf($sql, $this->table);
		$rsrc = $this->_query($sql);

		list($total) = $this->db->fetchRow($rsrc);

		return $total;
	}

	public function loadsByUid($uid, $limit = null, $start = null)
	{
		$uid = intval($uid);
		$sql = "select * from `%s` where `uid`='%u' order by `date` desc";
		$sql = sprintf($sql, $this->table, $uid);
		$rsrc = $this->_query($sql, $limit, $start);

		$bookmarks = array();

		while ( $vars = $this->db->fetchArray($rsrc) )
		{
			$bookmark = $this->create();
			$bookmark->unsetNew();
			$bookmark->setVars($vars);
			$bookmarks[] = $bookmark;
		}

		return $bookmarks;
	}

	public function countByUid($uid)
	{
		$uid = intval($uid);
		$sql = sprintf("select count(`id`) from `%s` where `uid` = '%u'", $this->table, $uid);

		$rsrc = $this->db->query($sql);
		list($total) = $this->db->fetchRow($rsrc);

		return $total;
	}

	public function urlExists($uid, $url)
	{
		$uid = intval($uid);
		$url = mysql_real_escape_string($url);
		$sql = sprintf("select count(`id`) from `%s` where `uid` = '%u' and `url` = '%s'", $this->table, $uid, $url);

		$rsrc = $this->db->query($sql);
		list($total) = $this->db->fetchRow($rsrc);

		return ( $total > 0 );
	}

	public function incrementCounter($id)
	{
		$id = intval($id);
		$sql = "update `%s` SET `counter` = `counter` + 1 where `id` = '%u'";
		$sql = sprintf($sql, $this->table, $id);

		return $this->db->queryF($sql);
	}

	public function deleteByUrl($uid, $url)
	{
		$uid = intval($uid);
		$url = mysql_real_escape_string($url);
		$sql = sprintf("delete from `%s` where `uid` = '%u' and `url` = '%s'", $this->table, $uid, $url);
		return $this->_query($sql);
	}
}

?>
