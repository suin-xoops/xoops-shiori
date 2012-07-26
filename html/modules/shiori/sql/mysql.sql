#
# `xoops_shiori_bookmark`
#

CREATE TABLE `shiori_bookmark` (
  `id` int(10) NOT NULL auto_increment,
  `uid` mediumint(8) NOT NULL default '0',
  `mid` smallint(5) NOT NULL default '0',
  `date` int(10) NOT NULL default '0',
  `url` varchar(250) NOT NULL default '',
  `sort` int(3) NOT NULL default '0',
  `name` varchar(200) NOT NULL default '',
  `icon` varchar(100) NOT NULL default '',
  `counter` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `mid` (`mid`),
  KEY `url` (`url`),
  KEY `uid` (`uid`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

