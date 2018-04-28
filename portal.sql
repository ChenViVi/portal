-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2018-04-28 16:36:41
-- 服务器版本: 5.7.21
-- PHP 版本: 5.6.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `portal_single`
--

-- --------------------------------------------------------

--
-- 表的结构 `bg`
--

CREATE TABLE IF NOT EXISTS `bg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- 表的结构 `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- 转存表中的数据 `search`
--

INSERT INTO `search` (`id`, `name`, `url`) VALUES
(6, 'Google', 'https://www.google.com/search?q='),
(7, '百度翻译', 'https://fanyi.baidu.com/#en/zh/'),
(8, '知乎', 'https://www.zhihu.com/search?type=content&q='),
(9, '百度', 'https://www.baidu.com/s?wd='),
(10, '爱奇艺', 'http://so.iqiyi.com/so/q_'),
(11, '中关村', 'http://search.zol.com.cn/s/all.php?keyword='),
(12, 'Github', 'https://github.com/search?utf8=%E2%9C%93&q='),
(13, 'Maven', 'https://mvnrepository.com/search?q='),
(14, 'Iconfont', 'http://www.iconfont.cn/search/index?q='),
(15, 'FontAwesome', 'https://fontawesome.com/icons?d=gallery&q='),
(16, '京东', 'https://search.jd.com/Search?enc=utf-8&keyword='),
(17, '淘宝', 'https://s.taobao.com/search?q='),
(18, 'B站', 'https://search.bilibili.com/all?keyword=');

-- --------------------------------------------------------

--
-- 表的结构 `site`
--

CREATE TABLE IF NOT EXISTS `site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=92 ;

--
-- 转存表中的数据 `site`
--

INSERT INTO `site` (`id`, `type_id`, `name`, `url`) VALUES
(5, 3, '哔哩哔哩', 'https://www.bilibili.com/'),
(6, 3, 'Github', 'https://github.com/'),
(7, 3, 'YouTube', 'https://www.youtube.com'),
(8, 3, 'Pinterest', 'https://www.pinterest.com/'),
(9, 3, 'Tumblr', 'https://www.tumblr.com/'),
(10, 3, '微博', 'https://weibo.com'),
(11, 3, 'Twitter', 'https://twitter.com'),
(12, 3, 'GitBook', 'https://www.gitbook.com'),
(13, 3, 'Gmail', 'https://mail.google.com/'),
(14, 3, 'QQ邮箱', 'https://mail.qq.com/'),
(15, 3, '163邮箱', 'https://mail.163.com/'),
(16, 3, '千库网', 'http://588ku.com/'),
(17, 3, 'Unsplash', 'https://unsplash.com/'),
(18, 3, 'pixiv', 'https://www.pixiv.net/'),
(19, 4, 'JSON', 'http://www.bejson.com/'),
(20, 4, '编码转换', 'http://tool.chinaz.com/tools/unicode.aspx'),
(21, 4, 'GET和POST测试', 'http://coolaf.com/'),
(22, 4, '七牛云', 'https://portal.qiniu.com/bucket/picpool/index'),
(23, 4, 'Vultr', 'https://my.vultr.com/'),
(24, 4, 'BandwagonHOST', 'https://bwh1.net/vps-hosting.php'),
(25, 4, 'KiwiVM', 'https://kiwivm.64clouds.com/'),
(26, 4, '腾讯云', 'https://console.cloud.tencent.com/cvm/index'),
(27, 4, '阿里云', 'https://www.aliyun.com/'),
(28, 5, 'Android Developer', 'https://developer.android.com/index.html'),
(29, 5, 'AndroidXref', 'http://androidxref.com/'),
(30, 5, 'MaterialPalette', 'https://www.materialpalette.com/'),
(31, 5, 'ButtonMaker', 'http://angrytools.com/android/button/'),
(32, 5, '配色', 'http://tool.c7sky.com/webcolor/'),
(33, 5, '16进制颜色转换', 'http://www.sioe.cn/yingyong/yanse-rgb-16/'),
(34, 5, '颜色介绍', 'http://encycolorpedia.cn/'),
(35, 5, 'Gradle版本', 'https://services.gradle.org/distributions/'),
(36, 6, 'Materialize', 'http://www.materializecss.cn/index.html'),
(37, 6, 'MDUI', 'https://www.mdui.org/docs/'),
(38, 7, '掘金', 'https://juejin.im/welcome'),
(39, 7, 'IT帮', 'http://itbang.me/'),
(40, 7, 'stackoverflow', 'https://stackoverflow.com/'),
(41, 7, 'CSDN', 'https://blog.csdn.net'),
(42, 7, 'V2EX', 'https://www.v2ex.com/'),
(43, 8, 'Steam', 'http://store.steampowered.com/'),
(44, 8, 'HumbleBundle', 'https://www.humblebundle.com/'),
(45, 8, 'SteamDB', 'https://steamdb.info/'),
(46, 8, 'Origin', 'https://www.origin.com/usa/zh-tw/'),
(47, 8, 'Ubisoft', 'http://www.ubisoft.com.cn/'),
(48, 8, '3DM', 'http://www.3dmgame.com/'),
(49, 8, '游民星空', 'http://www.gamersky.com/'),
(50, 8, '大鱼游戏', 'https://www.bigfishgames.com/'),
(51, 8, 'Kongregate', 'https://www.kongregate.com/'),
(52, 8, 'Armor Game', 'https://armorgames.com/'),
(53, 8, 'IGN', 'http://www.ign.com/reviews/games'),
(54, 9, 'C菌の記憶碎片', 'http://blog.roshinichi.com'),
(55, 9, 'C菌の映像馆', 'http://gallery.roshinichi.com'),
(56, 9, 'さくら荘その白しろ猫', 'https://2heng.xin/'),
(57, 9, 'VPS 艹机狂魔', 'https://zhuanlan.zhihu.com/VPS-youhuima'),
(58, 9, 'VPS大全', 'http://www.vpsdaquan.cn/'),
(59, 9, 'Rat''s Blog', 'https://www.moerats.com/'),
(60, 9, '月宅酱', 'https://ikmoe.com/'),
(61, 9, '主机百科', 'https://zhujiwiki.com/'),
(62, 9, 'gaussik', 'https://my.oschina.net/gaussik/blog'),
(63, 10, 'awwwards', 'https://www.awwwards.com/'),
(64, 10, '乌云网', 'http://www.anquan.us/'),
(65, 3, 'coding', 'https://coding.net'),
(66, 3, '妮可妮可妮', 'http://www.nicovideo.jp/'),
(67, 11, '齐木楠雄的灾难', 'https://www.bilibili.com/bangumi/play/ss5069/'),
(68, 11, '陈皮的直播间', 'https://www.douyu.com/2550505'),
(69, 9, '小剧客栈', 'https://www.bh-lay.com/'),
(70, 11, '稻田装尸', 'http://www.dm2046.com'),
(71, 4, 'phpmyadmin-国外', 'http://23.105.219.203/phpmyadmin/'),
(72, 4, 'phpmyadmin-国内', 'http://139.199.32.74/phpmyadmin/'),
(73, 9, '萨摩公园', 'https://i-meto.com/'),
(74, 10, 'GitHub Developer', 'https://developer.github.com/'),
(75, 10, 'steamos', 'http://store.steampowered.com/steamos/'),
(76, 6, 'JSFiddle', 'https://jsfiddle.net/'),
(77, 10, '如果有机会就贡献一下吧', 'https://github.com/swisnl/jQuery-contextMenu/issues/572'),
(78, 10, 'gist', 'https://gist.github.com/'),
(79, 10, 'git-awards', 'http://git-awards.com'),
(80, 10, 'readthedocs', 'https://readthedocs.org/'),
(81, 12, 'jQuery-contextMenu', 'https://swisnl.github.io/jQuery-contextMenu/'),
(82, 10, '墙与书', 'https://wallsandbooks.wordpress.com/'),
(83, 6, 'jquery api', 'https://www.jquery123.com/'),
(84, 9, '鸟片', 'https://user.qzone.qq.com/263579115/main'),
(85, 10, '用手机控制服务器的app', 'https://www.hyperapp.fun/zh/'),
(86, 10, 'couscous', 'http://couscous.io/docs/getting-started.html'),
(87, 3, '极简图床', 'https://jiantuku.com'),
(88, 8, 'yuplay', 'https://yuplay.ru/'),
(89, 10, 'change color', 'https://codepen.io/hiteshsahu/pen/EXoPRq/'),
(90, 12, ' contextMenu-3.x', 'https://github.com/swisnl/jQuery-contextMenu/tree/3.x'),
(91, 6, 'materialize tutorial', 'https://www.tutorialspoint.com/materialize/index.htm');

-- --------------------------------------------------------

--
-- 表的结构 `site_type`
--

CREATE TABLE IF NOT EXISTS `site_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `site_type`
--

INSERT INTO `site_type` (`id`, `name`) VALUES
(3, '日常'),
(4, '网络相关'),
(5, 'Android'),
(6, '建站'),
(7, '社区'),
(8, '游戏'),
(9, '别人的世界'),
(10, '异次元黑洞'),
(11, '看点啥呢'),
(12, '最近要用');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
