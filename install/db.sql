)-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 16, 2013 at 05:37 AM
-- Server version: 5.1.70-cll
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `leaderboard1` text NOT NULL,
  `leaderboard2` text NOT NULL,
  `rectangle` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`leaderboard1`, `leaderboard2`, `rectangle`) VALUES
('', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE IF NOT EXISTS `analytics` (
  `tracking_code` varchar(1000) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `analytics`
--

INSERT INTO `analytics` (`tracking_code`) VALUES
('');

-- --------------------------------------------------------

--
-- Table structure for table `comment_setting`
--

CREATE TABLE IF NOT EXISTS `comment_setting` (
  `admin_id` varchar(255) NOT NULL,
  `app_id` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comment_setting`
--

INSERT INTO `comment_setting` (`admin_id`, `app_id`) VALUES
('', '');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `permalink` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(170) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `thumb` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `votes` int(11) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  `orderid` bigint(20) NOT NULL DEFAULT '0',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

--
-- Table structure for table `media_setting`
--

CREATE TABLE IF NOT EXISTS `media_setting` (
  `title_length` smallint(6) NOT NULL,
  `posts_per_page` smallint(6) NOT NULL,
  `related_posts` smallint(6) NOT NULL,
  `allow_voting` tinyint(1) NOT NULL,
  `auto_approve` tinyint(1) NOT NULL,
  `allow_pictures` tinyint(1) NOT NULL DEFAULT '1',
  `allow_videos` tinyint(1) NOT NULL DEFAULT '1',
  `allow_gifs` tinyint(1) NOT NULL DEFAULT '1',
  `show_nav` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `media_setting`
--

INSERT INTO `media_setting` (`title_length`, `posts_per_page`, `related_posts`, `allow_voting`, `auto_approve`, `allow_pictures`, `allow_videos`, `allow_gifs`, `show_nav`) VALUES
(45, 9, 6, 1, 0, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `media_tags`
--

CREATE TABLE IF NOT EXISTS `media_tags` (
  `id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `permalink` varchar(80) NOT NULL,
  `title` varchar(70) NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `display_order` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `permalink`, `title`, `content`, `status`, `display_order`) VALUES
(1, 'about-us', 'About Us', 'This Script Has Been Shared On NullPHP.com, Hi we are Nexthon you can check us at\r\n<a href="www.nexthon.com"> Nexthon.com</a>\r\n\r\n<br />\r\n<br />\r\nYou can edit this page from admin panel :)\r\n<br />\r\nYou can also add new pages from admin panel', 1, 1),
(2, 'terms-of-use', 'Terms of Use', 'Hi there this is TOS page\r\n\r\n<br>\r\n<br>\r\nYou can edit this page from admin panel :)\r\n<br>\r\nYou can also add new pages from admin panel', 1, 2),
(3, 'faq', 'FAQ', 'Hi there this is FAQ page\r\n\r\n<br />\r\n<br />\r\nYou can edit this page from admin panel :)\r\n<br />\r\nYou can also add new pages from admin panel', 1, 3),
(4, 'privacy-policy', 'Privacy Policy', 'Hi there this is Privacy Policy page\r\n\r\n<br />\r\n<br />\r\nYou can edit this page from admin panel :)\r\n<br />\r\nYou can also add new pages from admin panel', 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `rss_settings`
--

CREATE TABLE IF NOT EXISTS `rss_settings` (
  `enable` tinyint(1) NOT NULL,
  `limit_rss` int(11) NOT NULL,
  `desc_length` int(11) NOT NULL,
  `cat_rss_enable` tinyint(1) NOT NULL,
  `rss_tags` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rss_settings`
--

INSERT INTO `rss_settings` (`enable`, `limit_rss`, `desc_length`, `cat_rss_enable`, `rss_tags`) VALUES
(1, 10, 170, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `rootpath` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logo_format` tinyint(1) NOT NULL DEFAULT '1',
  `logo_text` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `meta_tags` varchar(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `favicon` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(254) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `captcha` tinyint(1) NOT NULL DEFAULT '1',
  `enable_submission` tinyint(1) NOT NULL DEFAULT '1',
  `contact_email` varchar(254) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`rootpath`, `logo_format`, `logo_text`, `title`, `description`, `meta_tags`, `logo`, `favicon`, `username`, `password`, `email`, `captcha`, `enable_submission`, `contact_email`) VALUES
('http://www.nullphp.com', 1, 'Logo', 'Shared On NullPHP.com', 'sample description here', 'sample keyword 1, sample keyword 2, sample keyword 3, sample keyword 4, sample keyword 5', 'logo.png', 'favicon.ico', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'contact@nullphp.com', 1, 1, 'contact@nullphp.com');

-- --------------------------------------------------------

--
-- Table structure for table `sitemaps`
--

CREATE TABLE IF NOT EXISTS `sitemaps` (
  `cats_status` tinyint(1) NOT NULL,
  `pages_status` tinyint(1) NOT NULL,
  `cont_form_status` tinyint(1) NOT NULL,
  `posts_status` tinyint(1) NOT NULL,
  `tags_status` tinyint(1) NOT NULL,
  `output_path` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sitemaps`
--

INSERT INTO `sitemaps` (`cats_status`, `pages_status`, `cont_form_status`, `posts_status`, `tags_status`, `output_path`, `last_modified`) VALUES
(1, 1, 1, 1, 1, 'sitemap.xml', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `social_profiles`
--

CREATE TABLE IF NOT EXISTS `social_profiles` (
  `facebook` varchar(50) NOT NULL,
  `twitter` varchar(50) NOT NULL,
  `google` varchar(50) NOT NULL,
  `social_sharing` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `social_profiles`
--

INSERT INTO `social_profiles` (`facebook`, `twitter`, `google`, `social_sharing`) VALUES
('nexthon', 'nexthon', '104682548360379303861', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(6) NOT NULL AUTO_INCREMENT,
  `tag` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;


--
-- Table structure for table `thumbnails_setting`
--

CREATE TABLE IF NOT EXISTS `thumbnails_setting` (
  `width` smallint(6) NOT NULL DEFAULT '231',
  `height` smallint(6) NOT NULL DEFAULT '159',
  `quality` tinyint(1) NOT NULL DEFAULT '3',
  `watermark` tinyint(1) NOT NULL DEFAULT '1',
  `gif_watermark` varchar(2083) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `vid_watermark` varchar(2083) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `thumbnails_setting`
--

INSERT INTO `thumbnails_setting` (`width`, `height`, `quality`, `watermark`, `gif_watermark`, `vid_watermark`) VALUES
(231, 159, 1, 1, 'images/gif.png', 'images/video.png');

-- --------------------------------------------------------

--
-- Table structure for table `watermark_setting`
--

CREATE TABLE IF NOT EXISTS `watermark_setting` (
  `enabled` tinyint(1) NOT NULL,
  `opacity` smallint(6) NOT NULL,
  `text` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `font_size` smallint(6) NOT NULL,
  `color` varchar(7) NOT NULL,
  `position` tinyint(4) NOT NULL DEFAULT '6'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `watermark_setting`
--

INSERT INTO `watermark_setting` (`enabled`, `opacity`, `text`, `font_size`, `color`, `position`) VALUES
(1, 95, 'Viral Media Portal', 18, '#ffffff', 6);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;