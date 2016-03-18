-- phpMyAdmin SQL Dump
-- version 4.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2014-08-15 18:41:47
-- 服务器版本： 5.5.37-MariaDB-log
-- PHP Version: 5.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `oj`
--

-- --------------------------------------------------------

--
-- 表的结构 `oj_comment`
--

CREATE TABLE IF NOT EXISTS `oj_comment` (
`id` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `oj_problem`
--

CREATE TABLE IF NOT EXISTS `oj_problem` (
`id` int(11) NOT NULL,
  `title` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `input` text NOT NULL,
  `output` text NOT NULL,
  `sample_input` text NOT NULL,
  `sample_output` text NOT NULL,
  `hint` text NOT NULL,
  `source` text NOT NULL,
  `difficulty` int(11) NOT NULL,
  `jointime` int(11) NOT NULL,
  `operator_id` int(11) NOT NULL,
  `dataset` int(11) NOT NULL,
  `mlim` int(11) NOT NULL DEFAULT '128000',
  `tlim` int(11) DEFAULT '1000',
  `accept` int(11) NOT NULL DEFAULT '0',
  `submit` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1002 ;

--
-- 转存表中的数据 `oj_problem`
--

INSERT INTO `oj_problem` (`id`, `title`, `description`, `input`, `output`, `sample_input`, `sample_output`, `hint`, `source`, `difficulty`, `jointime`, `operator_id`, `dataset`, `mlim`, `tlim`, `accept`, `submit`) VALUES
(1000, 'A+B Problem', 'Read two intergers from standard input, and output the sum of them.', 'Two intergers.', 'A interger, the sum.', '1 2', '3', 'Hint: Use the standard input / output.', 'N/A', 1, 1407156044, 1, 10, 8000, 50, 2, 2),
(1001, '明明的随机数', '明明想在学校中请一些同学一起做一项问卷调查，为了实验的客观性，他先用计算机生成了N个1到1000之间的随机整数（N≤100），对于其中重复的数字，只保留一个，把其余相同的数去掉，不同的数对应着不同的学生的学号。然后再把这些数从小到大排序，按照排好的顺序去找同学做调查。  \n请你协助明明完成“去重”与“排序”的工作。', '输入有2行。   \n第1行为1个正整数，表示所生成的随机数的个数N。   \n第2行有N个用空格隔开的正整数，为所产生的随机数。', '输出也是2行。  \n第1行为1个正整数M，表示不相同的随机数的个数。  \n第2行为M个用空格隔开的正整数，为从小到大排好序的不相同的随机数。', '10   \n20 40 32 67 40 20 89 300 400 15', '8   \n15 20 32 40 67 89 300 400', '最快方法为桶排序。', 'NOIP2006 普及组第1题', 2, 1407404772, 1, 10, 128000, 1000, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `oj_session`
--

CREATE TABLE IF NOT EXISTS `oj_session` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `hash` char(32) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `oj_session`
--

-- --------------------------------------------------------

--
-- 表的结构 `oj_submit`
--

CREATE TABLE IF NOT EXISTS `oj_submit` (
`id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `lang` varchar(6) NOT NULL,
  `result` varchar(6) NOT NULL DEFAULT 'u',
  `timeused` int(11) DEFAULT '0',
  `memused` int(11) NOT NULL DEFAULT '0',
  `resdata` text NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `accepted` tinyint(1) NOT NULL DEFAULT '0',
  `compmsg` text NOT NULL,
  `open` tinyint(1) DEFAULT '0',
  `time` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `oj_user`
--

CREATE TABLE IF NOT EXISTS `oj_user` (
`id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `nick` text NOT NULL,
  `password` char(32) NOT NULL,
  `salt` char(32) NOT NULL,
  `mail` text NOT NULL,
  `lang` varchar(6) NOT NULL,
  `share` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `accept` int(11) NOT NULL DEFAULT '0',
  `submit` int(11) NOT NULL DEFAULT '0',
  `jointime` int(11) NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `oj_user`
--

INSERT INTO `oj_user` (`id`, `name`, `nick`, `password`, `salt`, `mail`, `lang`, `share`, `admin`, `accept`, `submit`, `jointime`) VALUES
(1, '陈博引', 'Bokjan', '79b18a5f1788ada0c15a62cafdacf1e3', '1f0049562c802f0ee783b54631aabfee', 'i@bokjan.com', 'cpp', 0, 1, 2, 3, 1407161826);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `oj_comment`
--
ALTER TABLE `oj_comment`
 ADD PRIMARY KEY (`id`), ADD KEY `pid` (`pid`);

--
-- Indexes for table `oj_problem`
--
ALTER TABLE `oj_problem`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oj_session`
--
ALTER TABLE `oj_session`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oj_submit`
--
ALTER TABLE `oj_submit`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oj_user`
--
ALTER TABLE `oj_user`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `oj_comment`
--
ALTER TABLE `oj_comment`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `oj_problem`
--
ALTER TABLE `oj_problem`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1002;
--
-- AUTO_INCREMENT for table `oj_session`
--
ALTER TABLE `oj_session`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `oj_submit`
--
ALTER TABLE `oj_submit`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `oj_user`
--
ALTER TABLE `oj_user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
