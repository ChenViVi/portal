-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-04-29 05:49:38
-- 服务器版本： 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `portal_test`
--

-- --------------------------------------------------------

--
-- 表的结构 `bg`
--

CREATE TABLE `bg` (
  `id` int(11) NOT NULL,
  `url` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `search`
--

CREATE TABLE `search` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `url` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `search`
--

INSERT INTO `search` (`id`, `name`, `url`) VALUES
(1, 'Google', 'https://www.google.com/search?q='),
(2, '百度', 'https://www.baidu.com/s?wd='),
(3, '百度翻译', 'https://fanyi.baidu.com/#en/zh/'),
(4, '知乎', 'https://www.zhihu.com/search?type=content&q='),
(5, '淘宝', 'https://s.taobao.com/search?q='),
(6, '京东', 'https://search.jd.com/Search?enc=utf-8&keyword='),
(7, 'B站', 'https://search.bilibili.com/all?keyword='),
(8, '爱奇艺', 'http://so.iqiyi.com/so/q_'),
(9, '优酷', 'http://www.soku.com/search_video/q_'),
(10, 'Youtube', 'https://www.youtube.com/results?search_query='),
(11, '腾讯视频', 'https://v.qq.com/x/search/?q='),
(12, 'niconico', 'http://www.nicovideo.jp/search/'),
(13, 'Github', 'https://github.com/search?utf8=%E2%9C%93&q='),
(14, 'Maven', 'https://mvnrepository.com/search?q='),
(15, 'stackoverflow', 'https://stackoverflow.com/search?q='),
(16, '掘金', 'https://juejin.im/search?query='),
(17, 'segmentfault', 'https://segmentfault.com/search?q='),
(18, 'CSDN', 'https://so.csdn.net/so/search/s.do?q='),
(19, 'Iconfont', 'http://www.iconfont.cn/search/index?q='),
(20, 'FontAwesome', 'https://fontawesome.com/icons?d=gallery&q='),
(21, 'dribbble', 'ttps://dribbble.com/search?q='),
(22, 'GooglePicture', 'https://www.google.com/search?tbm=isch&q=beauty'),
(23, 'Pinterest', 'https://www.pinterest.com/search/pins/?q=dope'),
(24, 'pixiv', 'https://www.pixiv.net/search.php?word=');

-- --------------------------------------------------------

--
-- 表的结构 `site`
--

CREATE TABLE `site` (
  `id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `url` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `site_type`
--

CREATE TABLE `site_type` (
  `id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `site_type`
--

INSERT INTO `site_type` (`id`, `name`) VALUES
(1, '默认');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bg`
--
ALTER TABLE `bg`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `search`
--
ALTER TABLE `search`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `site`
--
ALTER TABLE `site`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_site_type` (`type_id`);

--
-- Indexes for table `site_type`
--
ALTER TABLE `site_type`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `bg`
--
ALTER TABLE `bg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `search`
--
ALTER TABLE `search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- 使用表AUTO_INCREMENT `site`
--
ALTER TABLE `site`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `site_type`
--
ALTER TABLE `site_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
