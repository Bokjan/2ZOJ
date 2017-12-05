# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.36-log)
# Database: oj
# Generation Time: 2017-12-05 11:12:39 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table oj_comment
# ------------------------------------------------------------

CREATE TABLE `oj_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_consub
# ------------------------------------------------------------

CREATE TABLE `oj_consub` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `probname` varchar(255) DEFAULT NULL,
  `lang` varchar(255) DEFAULT NULL,
  `result` varchar(255) DEFAULT NULL,
  `detail` varchar(255) DEFAULT NULL,
  `score` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_contest
# ------------------------------------------------------------

CREATE TABLE `oj_contest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `start` bigint(20) NOT NULL,
  `end` bigint(20) NOT NULL,
  `problemcount` int(11) NOT NULL,
  `problems` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_marked
# ------------------------------------------------------------

CREATE TABLE `oj_marked` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  PRIMARY KEY (`id`,`uid`),
  KEY `uid` (`uid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_problem
# ------------------------------------------------------------

CREATE TABLE `oj_problem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `input` text,
  `output` text,
  `sample_input` text,
  `sample_output` text,
  `hint` text,
  `source` text,
  `tags` varchar(255) DEFAULT '',
  `difficulty` int(11) NOT NULL,
  `jointime` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `dataset` int(11) NOT NULL,
  `mlim` int(11) NOT NULL DEFAULT '128000',
  `tlim` int(11) DEFAULT '1000',
  `spj` tinyint(1) NOT NULL DEFAULT '0',
  `accept` int(11) NOT NULL DEFAULT '0',
  `submit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_session
# ------------------------------------------------------------

CREATE TABLE `oj_session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `hash` char(32) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_submit
# ------------------------------------------------------------

CREATE TABLE `oj_submit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `lang` varchar(6) NOT NULL,
  `result` varchar(6) NOT NULL DEFAULT 'u',
  `timeused` int(11) DEFAULT '0',
  `memused` int(11) NOT NULL DEFAULT '0',
  `resdata` text,
  `score` int(11) NOT NULL DEFAULT '0',
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  `compmsg` text,
  `open` tinyint(1) DEFAULT '0',
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`) USING BTREE,
  KEY `pid` (`pid`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_tag
# ------------------------------------------------------------

CREATE TABLE `oj_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `pid` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_task
# ------------------------------------------------------------

CREATE TABLE `oj_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `start` bigint(20) NOT NULL,
  `end` bigint(20) NOT NULL,
  `problemcount` int(11) NOT NULL,
  `problems` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



# Dump of table oj_user
# ------------------------------------------------------------

CREATE TABLE `oj_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `nick` text NOT NULL,
  `group` varchar(255) NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(32) NOT NULL,
  `mail` text NOT NULL,
  `lang` varchar(6) NOT NULL DEFAULT 'cpp',
  `share` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(4) NOT NULL COMMENT '-1无权限，0可交代码，1可看代码，127为管理员',
  `accept` int(11) NOT NULL DEFAULT '0',
  `submit` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  `jointime` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `id` (`id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
