-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2026-04-09 17:42:48
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `dc_admin`
--

-- --------------------------------------------------------

--
-- 表的结构 `dc_administrators`
--

CREATE TABLE `dc_administrators` (
  `admin_id` int(11) NOT NULL COMMENT '管理员ID',
  `admin_name` varchar(255) DEFAULT NULL COMMENT '用户帐号',
  `admin_password` varchar(255) DEFAULT NULL COMMENT '密码',
  `admin_password_rnd` varchar(255) DEFAULT NULL COMMENT '登陆随机密码',
  `role_id` int(11) DEFAULT '0' COMMENT '权限组ID',
  `is_enabled` tinyint(1) DEFAULT '1' COMMENT '是否启用，1为启用',
  `real_name` varchar(255) DEFAULT NULL COMMENT '真实姓名',
  `tel` varchar(255) DEFAULT NULL COMMENT '电话，手机',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `wechat` varchar(255) DEFAULT NULL COMMENT '微信号',
  `qq` varchar(255) DEFAULT NULL COMMENT 'QQ号',
  `last_login_ip` varchar(50) DEFAULT NULL COMMENT '最后登陆IP',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登陆时间',
  `last_logout_time` datetime DEFAULT NULL COMMENT '最后退出时间',
  `login_times` int(11) DEFAULT NULL COMMENT '登陆次数',
  `admin_remarks` text COMMENT '备注'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `dc_administrators`
--

INSERT INTO `dc_administrators` (`admin_id`, `admin_name`, `admin_password`, `admin_password_rnd`, `role_id`, `is_enabled`, `real_name`, `tel`, `email`, `wechat`, `qq`, `last_login_ip`, `last_login_time`, `last_logout_time`, `login_times`, `admin_remarks`) VALUES
(1, 'dengcao', '3d53d5757ce611e57abf98d9bfe57098', NULL, 1, 1, '邓草', '1320000000', 'dzh188@qq.com', 'wx', 'qq', '127.0.0.1', '2026-04-09 17:15:07', '2026-04-09 17:14:54', 85, '');

-- --------------------------------------------------------

--
-- 表的结构 `dc_article`
--

CREATE TABLE `dc_article` (
  `aid` int(11) UNSIGNED NOT NULL,
  `catid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT '' COMMENT '标题',
  `content` longtext COLLATE utf8mb4_unicode_ci COMMENT '内容',
  `style` char(24) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '风格',
  `thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '缩略图',
  `keywords` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '' COMMENT '关键词',
  `description` mediumtext COLLATE utf8mb4_unicode_ci COMMENT '描述',
  `tags` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tag标签，多个中间用,分隔',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '打开链接',
  `listorder` int(11) UNSIGNED DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '状态：0无效，1正在审核，2退稿，9通过',
  `islink` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '是否外部链接，1=外部链接',
  `inputtime` datetime DEFAULT NULL COMMENT '发布时间',
  `iscomment` tinyint(1) DEFAULT '1' COMMENT '是否允许评论',
  `author` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '作者',
  `copyfrom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '文章来源',
  `hits` int(11) DEFAULT '0' COMMENT '点击数',
  `isreco` tinyint(1) DEFAULT '0' COMMENT '是否推荐，1=推荐',
  `ishot` tinyint(1) DEFAULT '0' COMMENT '是否热点，1=热点',
  `istop` tinyint(1) DEFAULT '0' COMMENT '是否置顶，1=置顶'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `dc_article`
--

INSERT INTO `dc_article` (`aid`, `catid`, `title`, `content`, `style`, `thumb`, `keywords`, `description`, `tags`, `url`, `listorder`, `status`, `islink`, `inputtime`, `iscomment`, `author`, `copyfrom`, `hits`, `isreco`, `ishot`, `istop`) VALUES
(1, 1, '新冠肺炎权威小贴士', '<p>内容可随意通过数据库添加（此处省略）</p>', '', '', '', NULL, NULL, NULL, 0, 9, 0, '2020-05-23 20:57:40', 1, NULL, NULL, 89, 0, 0, 0),
(2, 1, '必读！关于新冠肺炎的20个知识点', '<p>内容可随意通过数据库添加（此处省略）</p>', '', '', '', NULL, NULL, NULL, 0, 9, 1, '2020-05-23 20:57:33', 1, NULL, NULL, 2, 0, 0, 0),
(3, 1, '你需要知道的新冠肺炎最新知识', '<p>内容可随意通过数据库添加（此处省略）</p>', '', '', '', NULL, NULL, NULL, 0, 9, 0, '2020-05-23 20:57:25', 0, NULL, NULL, 4, 0, 0, 0),
(4, 1, '新冠肺炎患者需要注意哪些心理疏导?', '<p>内容可随意通过数据库添加（此处省略）</p>', '', '', '', NULL, NULL, NULL, 0, 9, 0, '2020-05-23 20:55:29', 1, NULL, NULL, 7, 0, 0, 0),
(5, 1, '新冠肺炎的日常防护知识', '<p><strong>日常防护知识：</strong></p><p>1.最重要的一条：不要到处跑。</p><p>专家始终强调，预防新冠肺炎最有效的方式之一是：减少出行，这不仅关乎自己和家人，也关乎整个社会。一定要外出时不要乘坐比较拥挤的公共交通车，建议步行或开车，逗留时间尽量缩短。宅在家时，应格外注意3个细节：通风：每日打开门窗通风2-3次，每次30分钟左右；天气好时，可以晒晒被子、衣服。洗手：回家后、做菜前、吃饭前、如厕后，应在流动水下用肥皂或洗手液揉搓30秒左右。饮食安全：处理食物时生熟分开，肉类充分做熟再吃；家庭实行分餐制或使用公筷。不要吃野味。</p><p>2.不要参加集会。</p><p>少出门、少聚会，是减少交叉感染的重要方法，尤其应避免去人员密集的公共场所，如商场、公共浴池、棋牌室、医院等。</p><p>3.出门戴口罩，不一定戴N95。</p><p>钟南山院士表示，戴口罩不一定要戴N95，医用外科口罩即可阻止大部分病毒进入呼吸道。普通口罩也能起到一定的隔离作用。如果口罩不够用，普通大众的口罩不必用一次换一次，可根据清洁程度延长使用时间。戴口罩时要把口鼻都完全覆盖住，并与面部贴合严实，尽量减少漏气情况。摘口罩时，不要抓着污染面，用手抓住系带脱离，扔到垃圾桶，不要到处乱扔。</p><p>4.学会正确洗手。</p><p>新型冠状病毒可通过接触传播，如果没有注意使双手沾上病毒，揉眼睛时就可能造成感染，所以一定要勤洗手。暂没有洗手条件时可用消毒湿巾擦拭双手。</p>', '', '', '', NULL, NULL, NULL, 0, 9, 0, '2020-05-23 20:55:41', 1, NULL, NULL, 55, 0, 0, 0),
(6, 1, '我省昨日新冠肺炎疫情公告', '<p>据省卫健委官网公布，5月23日0-24时，我省新增新型冠状病毒肺炎确诊病例<span style=\\\"color: rgb(255, 0, 0);\\\">xx</span>例；新增疑似病例xx例；新增治愈出院病例xx例；新增死亡病例x例。</p>', '风格', '/uploads/image/20200531/1590918832985443.jpg', '关键词', '文章描述', '', '', 11, 9, 0, '2020-06-01 20:57:50', 1, '作者', '文章来源', 16994, 1, 1, 1),
(8, 1, '开源系统dc-admin正式上线，欢迎使用', '<p style=\\\"\\\\&quot;\\\\\\\\&quot;text-indent:\\\\&quot;\\\">开源系统dc-admin正式上线，欢迎使用！</p><p><span style=\\\"\\\\&quot;\\\\\\\\&quot;\\\\\\\\\\\\\\\\&quot;color:\\\\\\\\&quot;\\\\&quot;\\\">dc-admin（原caozha-admin）是一个通用的PHP网站后台管理框架，基于开源的ThinkPHP开发，特点：易上手，零门槛，界面清爽极简，极便于二次开发。</span></p>', '', '', '', '', 'tag', '', 0, 9, 0, '2020-06-02 18:11:40', 1, '', '', 5, 1, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dc_category`
--

CREATE TABLE `dc_category` (
  `catid` smallint(11) UNSIGNED NOT NULL COMMENT '栏目ID',
  `type` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '栏目类型ID',
  `modelid` smallint(5) UNSIGNED DEFAULT '0' COMMENT '模型ID，0=系统，1=文章，2=下载，3=图片，可自定义',
  `parentid` smallint(11) UNSIGNED DEFAULT '0' COMMENT '上级父栏目',
  `arrparentid` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '所有父栏目',
  `child` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '是否存在子栏目，1，存在',
  `arrchildid` mediumtext COLLATE utf8_unicode_ci COMMENT '所有子栏目',
  `catname` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '栏目名称',
  `style` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '风格',
  `image` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '栏目图片',
  `description` mediumtext COLLATE utf8_unicode_ci COMMENT '栏目描述',
  `url` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '栏目链接',
  `items` mediumint(8) UNSIGNED DEFAULT '0' COMMENT '栏目内容数',
  `hits` int(11) UNSIGNED DEFAULT '0' COMMENT '点击数',
  `setting` mediumtext COLLATE utf8_unicode_ci COMMENT '栏目相关配置信息',
  `listorder` smallint(11) UNSIGNED DEFAULT '0' COMMENT '排序',
  `ismenu` tinyint(1) UNSIGNED DEFAULT '1' COMMENT '是否显示，1 显示',
  `letter` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '栏目拼音'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `dc_category`
--

INSERT INTO `dc_category` (`catid`, `type`, `modelid`, `parentid`, `arrparentid`, `child`, `arrchildid`, `catname`, `style`, `image`, `description`, `url`, `items`, `hits`, `setting`, `listorder`, `ismenu`, `letter`) VALUES
(1, 0, 1, 0, '0', 0, '1', '系统公告', NULL, '', '', '', 0, 0, NULL, 1, 1, NULL),
(2, 0, 1, 0, '0', 0, '2', '国内新闻', NULL, '', '', '', 0, 0, NULL, 2, 1, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `dc_comment`
--

CREATE TABLE `dc_comment` (
  `id` int(11) NOT NULL,
  `cmtname` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '评论者昵称',
  `userid` int(11) DEFAULT '0' COMMENT '用户ID,0为游客',
  `userpic` int(4) DEFAULT NULL COMMENT '游客用户的头像',
  `cmtip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '评论IP',
  `addtime` datetime DEFAULT NULL COMMENT '评论时间',
  `cmtid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '评论ID，标识符，用于关联',
  `catid` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '评论分类ID，标识符，用于关联',
  `parentid` int(11) DEFAULT '0' COMMENT '父ID',
  `arrparentid` text COLLATE utf8mb4_unicode_ci COMMENT '所有父ID，多个中间按顺序用逗号,分隔',
  `cmtcontent` text COLLATE utf8mb4_unicode_ci COMMENT '评论内容',
  `like_num` int(11) DEFAULT '0' COMMENT '点赞次数',
  `bad_num` int(11) DEFAULT '0' COMMENT '被踩次数',
  `ischeck` tinyint(1) DEFAULT '0' COMMENT '是否审核通过，1=通过',
  `ishot` tinyint(1) DEFAULT '0' COMMENT '是否热门评论，1=热门'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `dc_comment`
--

INSERT INTO `dc_comment` (`id`, `cmtname`, `userid`, `userpic`, `cmtip`, `addtime`, `cmtid`, `catid`, `parentid`, `arrparentid`, `cmtcontent`, `like_num`, `bad_num`, `ischeck`, `ishot`) VALUES
(1, '草札', 2, NULL, '127.0.0.1', '2020-06-05 19:01:20', 'act_1', '0', 0, NULL, '这篇文章不错啊，很精彩！', 36, 7, 1, 1),
(2, '张峰', 0, 15, '127.0.0.1', '2020-06-05 19:01:20', 'act_1', '0', 0, NULL, '使用有什么诀窍吗？', 1, 0, 1, 1),
(3, '小林', 0, 16, '127.0.0.1', '2020-06-05 19:17:26', 'act_1', '0', 1, '1', '对啊，我也觉得还是不错的！[/呲牙]', 0, 1, 1, 0),
(4, '李露', 0, 22, '127.0.0.1', '2020-06-05 19:17:26', 'act_1', '0', 3, '1,3', '你懂个什么啊！[/憨笑]', 1, 0, 1, 0),
(5, '大黄', 0, 33, '127.0.0.1', '2020-06-09 02:29:54', 'act_1', '0', 0, '', '[/哈欠]有点困了', 1, 0, 1, 0),
(6, '测试', 0, 31, '127.0.0.1', '2020-06-09 02:31:13', 'act_1', '0', 5, '5', '[/斜眼笑]那可以去睡了', 0, 1, 1, 0),
(7, '阿达', 0, 17, '127.0.0.1', '2020-06-09 02:49:56', 'act_1', '0', 0, '', '[/酷]大家好', 2, 1, 1, 0),
(8, '', 0, 25, '127.0.0.1', '2020-06-09 11:33:57', 'act_1', '0', 6, '5,6', '睡得比狗晚，起得比鸡早[/偷笑]', 0, 1, 1, 0),
(9, '', 0, 10, '127.0.0.1', '2020-06-09 16:53:33', 'act_1', '0', 0, '', '[/斜眼笑]', 0, 1, 1, 0),
(10, '', 0, 3, '127.0.0.1', '2020-06-09 17:10:58', 'act_1', '0', 0, '', '你好啊[/酷]', 1, 0, 1, 0),
(11, '', 0, 41, '127.0.0.1', '2020-06-09 17:14:22', 'act_1', '0', 0, '', '[img]https://ss3.bdstatic.com/70cFv8Sh_Q1YnxGkpoWK1HF6hhy/it/u=1332800745,3820435792&fm=11&gp=0.jpg[/img]测试提交图片', 0, 1, 1, 0),
(12, '', 0, 28, '127.0.0.1', '2020-06-09 18:25:13', 'act_1', '0', 0, '', '[/呲牙]测试提交脚本。百度网链接\ndocument.write(\\\'\\\');\n', 1, 0, 1, 0),
(13, '大宝', 0, 39, '127.0.0.1', '2020-06-10 17:11:09', 'act_1', '0', 0, '', '[/哈欠]下午好！', 1, 0, 1, 0),
(14, '', 0, 14, '127.0.0.1', '2020-06-10 23:42:20', 'act_1', '0', 0, '', '测试换行。\r\n大家晚上好啊！\r\n[/调皮]', 1, 0, 1, 0),
(16, '菲尔丁', 0, 37, '127.0.0.1', '2020-06-11 12:01:07', 'act_1', '0', 0, '', '如果你把金钱当成上帝，它便会像魔鬼一样折磨你。', 1, 0, 1, 0),
(17, '', 0, 1, '127.0.0.1', '2020-06-11 14:33:22', 'act_1', '0', 0, '', '每个人心里都有一团火，路过的人只能看到烟。但是我觉得总会有一个人看到这团火，然后走过来，陪我一起。', 1, 0, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `dc_member`
--

CREATE TABLE `dc_member` (
  `userid` mediumint(11) UNSIGNED NOT NULL,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '密码',
  `encrypt` varchar(6) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '随机码',
  `nickname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '昵称',
  `avatar` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '头像',
  `regtime` datetime DEFAULT NULL COMMENT '注册时间',
  `lasttime` datetime DEFAULT NULL COMMENT '最后登录时间',
  `regip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '注册ip',
  `lastip` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '上次登录ip',
  `loginnum` smallint(8) UNSIGNED DEFAULT '0' COMMENT '登陆次数',
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '电子邮箱',
  `groupid` int(8) UNSIGNED DEFAULT '0' COMMENT '用户组id',
  `address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '用户地址',
  `amount` decimal(8,2) UNSIGNED DEFAULT '0.00' COMMENT '金钱总额',
  `point` smallint(8) UNSIGNED DEFAULT '0' COMMENT '积分',
  `islock` tinyint(1) UNSIGNED DEFAULT '0' COMMENT '是否锁定，1=锁定',
  `qq` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT 'QQ',
  `mobile` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '手机号码',
  `weixin` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '微信号',
  `remarks` text COLLATE utf8_unicode_ci COMMENT '备注',
  `isrn` tinyint(1) DEFAULT '0' COMMENT '是否已实名，0=未实名，1=实名，2=正在审核',
  `realname` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '真实姓名',
  `id_card` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '身份证号'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='会员表';

--
-- 转存表中的数据 `dc_member`
--

INSERT INTO `dc_member` (`userid`, `username`, `password`, `encrypt`, `nickname`, `avatar`, `regtime`, `lasttime`, `regip`, `lastip`, `loginnum`, `email`, `groupid`, `address`, `amount`, `point`, `islock`, `qq`, `mobile`, `weixin`, `remarks`, `isrn`, `realname`, `id_card`) VALUES
(1, 'admin', '991cb36dc61b73dd37a385dd4927ce47', '', '管理员', '../../uploads/image/20200531/1590918832985443.jpg', '2020-06-03 18:24:14', '2020-06-03 18:24:14', NULL, NULL, 0, 'dzh188@qq.com', 1, '广西', '8671.00', 0, 0, '8866629', '13286805000', 'wx', '', 0, '', ''),
(2, 'caozha', '5fd9cd58f4e516bae46557b355c5208a', NULL, '草札', '../../uploads/image/20200531/1590918832985443.jpg', '2020-06-03 20:40:50', '2020-06-03 20:40:50', NULL, NULL, 0, 'dzh188@qq.com', 2, '广西贵港市', '0.00', 0, 1, '8866629', '13286805000', 'weixin', '备注', 0, '', '');

-- --------------------------------------------------------

--
-- 表的结构 `dc_member_group`
--

CREATE TABLE `dc_member_group` (
  `groupid` int(11) NOT NULL COMMENT '用户组ID',
  `roles` text COLLATE utf8mb4_unicode_ci COMMENT '权限标识符，多个中间用,分隔',
  `group_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '用户组名称',
  `group_remarks` text COLLATE utf8mb4_unicode_ci COMMENT '备注',
  `is_enabled` tinyint(1) DEFAULT '1' COMMENT '是否启用，1为启用',
  `listorder` int(11) DEFAULT '0' COMMENT '排序'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `dc_member_group`
--

INSERT INTO `dc_member_group` (`groupid`, `roles`, `group_name`, `group_remarks`, `is_enabled`, `listorder`) VALUES
(1, 'mine,cmt', '普通会员', '', 1, 100),
(2, 'mine,cmt', '高级会员', '', 1, 90);

-- --------------------------------------------------------

--
-- 表的结构 `dc_roles`
--

CREATE TABLE `dc_roles` (
  `role_id` int(11) NOT NULL COMMENT '权限组ID',
  `roles` text COLLATE utf8mb4_unicode_ci COMMENT '权限标识符，多个中间用,分隔',
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '权限组名称',
  `role_remarks` text COLLATE utf8mb4_unicode_ci COMMENT '备注',
  `is_enabled` tinyint(1) DEFAULT '1' COMMENT '是否启用，1为启用'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `dc_roles`
--

INSERT INTO `dc_roles` (`role_id`, `roles`, `role_name`, `role_remarks`, `is_enabled`) VALUES
(1, 'config,roles,admin,log_view,log_del,mine,category,article,member,mbr_group,comment', '超级管理员', '可使用后台所有功能', 1),
(2, 'article', '内容管理员', '测试停用', 0),
(3, 'article', '编辑', '只管理文章', 1);

-- --------------------------------------------------------

--
-- 表的结构 `dc_syslog`
--

CREATE TABLE `dc_syslog` (
  `log_id` int(11) NOT NULL,
  `log_content` text COMMENT '系统日志内容',
  `log_user` varchar(255) DEFAULT NULL COMMENT '操作管理员',
  `log_ip` varchar(50) DEFAULT NULL COMMENT 'IP',
  `log_datetime` datetime DEFAULT NULL COMMENT '操作时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统日志';

-- --------------------------------------------------------

--
-- 表的结构 `dc_web_config`
--

CREATE TABLE `dc_web_config` (
  `id` int(11) NOT NULL,
  `web_config` text COLLATE utf8mb4_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `dc_web_config`
--

INSERT INTO `dc_web_config` (`id`, `web_config`) VALUES
(1, '{\"site_name\":\"dc-admin\",\"site_url\":\"http:\\/\\/5300.cn\",\"admin_limit\":\"15\",\"roles_limit\":\"15\",\"syslog_limit\":\"15\",\"article_limit\":\"15\",\"member_limit\":\"10\",\"member_group_limit\":\"10\",\"comment_limit\":\"10\",\"index_title\":\"dc-admin\\u540e\\u53f0\\u7ba1\\u7406\\u7cfb\\u7edf\",\"index_keywords\":\"\\u9093\\u8349\\uff0c5300.cn,dc-admin\",\"index_description\":\"dc-admin\\u662f\\u4e00\\u4e2a\\u901a\\u7528\\u7684\\u7f51\\u7ad9\\u540e\\u53f0\\u5f00\\u53d1\\u6846\\u67b6\\uff0c\\u57fa\\u4e8e\\u5f00\\u6e90\\u7684ThinkPHP\\u3001layuimini\\u3001layui\\u3001font-awesome\\u3001phpoffice\\u7b49\\u5f00\\u53d1\\uff0c\\u7279\\u70b9\\uff1a\\u6613\\u4e0a\\u624b\\uff0c\\u96f6\\u95e8\\u69db\\uff0c\\u754c\\u9762\\u6e05\\u723d\\u6781\\u7b80 \\u3002\",\"site_footer\":\"Copyright \\u00a9 \\u9093\\u8349 5300.cn All rights reserved.\"}');

--
-- 转储表的索引
--

--
-- 表的索引 `dc_administrators`
--
ALTER TABLE `dc_administrators`
  ADD PRIMARY KEY (`admin_id`);

--
-- 表的索引 `dc_article`
--
ALTER TABLE `dc_article`
  ADD PRIMARY KEY (`aid`) USING BTREE,
  ADD KEY `status` (`status`,`listorder`,`aid`),
  ADD KEY `listorder` (`catid`,`status`,`listorder`,`aid`),
  ADD KEY `catid` (`catid`,`status`,`aid`);

--
-- 表的索引 `dc_category`
--
ALTER TABLE `dc_category`
  ADD PRIMARY KEY (`catid`),
  ADD KEY `module` (`parentid`,`listorder`,`catid`),
  ADD KEY `siteid` (`type`);

--
-- 表的索引 `dc_comment`
--
ALTER TABLE `dc_comment`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `dc_member`
--
ALTER TABLE `dc_member`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `email` (`email`(20));

--
-- 表的索引 `dc_member_group`
--
ALTER TABLE `dc_member_group`
  ADD PRIMARY KEY (`groupid`);

--
-- 表的索引 `dc_roles`
--
ALTER TABLE `dc_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- 表的索引 `dc_syslog`
--
ALTER TABLE `dc_syslog`
  ADD PRIMARY KEY (`log_id`);

--
-- 表的索引 `dc_web_config`
--
ALTER TABLE `dc_web_config`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `dc_administrators`
--
ALTER TABLE `dc_administrators`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员ID', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `dc_article`
--
ALTER TABLE `dc_article`
  MODIFY `aid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 使用表AUTO_INCREMENT `dc_category`
--
ALTER TABLE `dc_category`
  MODIFY `catid` smallint(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '栏目ID', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `dc_comment`
--
ALTER TABLE `dc_comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- 使用表AUTO_INCREMENT `dc_member`
--
ALTER TABLE `dc_member`
  MODIFY `userid` mediumint(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `dc_member_group`
--
ALTER TABLE `dc_member_group`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户组ID', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `dc_roles`
--
ALTER TABLE `dc_roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限组ID', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `dc_syslog`
--
ALTER TABLE `dc_syslog`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `dc_web_config`
--
ALTER TABLE `dc_web_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
