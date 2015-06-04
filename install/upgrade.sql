CREATE TABLE IF NOT EXISTS  `comment_setting` (
 `admin_id` VARCHAR( 255 ) NOT NULL ,
 `app_id` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET = latin1;

INSERT INTO  `comment_setting` (`admin_id` ,`app_id`)
VALUES ('',  '');

ALTER TABLE  `media` ADD  `description` VARCHAR( 170 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER  `title` ;
ALTER TABLE  `media` CHANGE  `permalink`  `permalink` VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `source`  `source` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `file`  `file` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `thumb`  `thumb` VARCHAR( 40 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;
ALTER TABLE `media` CHANGE `date` `date` DATETIME NOT NULL ;
ALTER TABLE  `media` CHANGE  `title`  `title` VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;

ALTER TABLE  `media_setting` ADD  `allow_pictures` TINYINT( 1 ) NOT NULL DEFAULT  '1',
ADD  `allow_videos` TINYINT( 1 ) NOT NULL DEFAULT  '1',
ADD  `allow_gifs` TINYINT( 1 ) NOT NULL DEFAULT  '1',
ADD  `show_nav` TINYINT( 1 ) NOT NULL DEFAULT  '1';

CREATE TABLE IF NOT EXISTS `media_tags` (
  `id` bigint(20) NOT NULL,
  `tag_id` bigint(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS  `tags` (
 `tag_id` INT( 6 ) NOT NULL AUTO_INCREMENT ,
 `tag` VARCHAR( 35 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
PRIMARY KEY (  `tag_id` )
) ENGINE = MYISAM DEFAULT CHARSET = latin1 AUTO_INCREMENT =0;

ALTER TABLE  `pages` CHANGE  `permalink`  `permalink` VARCHAR( 80 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `title`  `title` VARCHAR( 70 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `content`  `content` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;


CREATE TABLE IF NOT EXISTS `rss_settings` (
  `enable` tinyint(1) NOT NULL,
  `limit_rss` int(11) NOT NULL,
  `desc_length` int(11) NOT NULL,
  `cat_rss_enable` tinyint(1) NOT NULL,
  `rss_tags` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `rss_settings` (`enable`, `limit_rss`, `desc_length`, `cat_rss_enable`, `rss_tags`) VALUES
(1, 10, 170, 1, 1);

CREATE TABLE IF NOT EXISTS `sitemaps` (
  `cats_status` tinyint(1) NOT NULL,
  `pages_status` tinyint(1) NOT NULL,
  `cont_form_status` tinyint(1) NOT NULL,
  `posts_status` tinyint(1) NOT NULL,
  `tags_status` tinyint(1) NOT NULL,
  `output_path` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `sitemaps` (`cats_status`, `pages_status`, `cont_form_status`, `posts_status`, `tags_status`, `output_path`, `last_modified`) VALUES
(1, 1, 1, 1, 1, 'sitemap.xml', '0000-00-00 00:00:00');

ALTER TABLE `settings` CHANGE `rootpath` `rootpath` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `logo_text` `logo_text` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `title` `title` VARCHAR(70) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `description` `description` VARCHAR(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `meta_tags` `meta_tags` VARCHAR(160) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `logo` `logo` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `favicon` `favicon` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `username` `username` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `password` `password` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `email` `email` VARCHAR(254) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL, CHANGE `contact_email` `contact_email` VARCHAR(254) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

ALTER TABLE  `thumbnails_setting` CHANGE  `gif_watermark`  `gif_watermark` VARCHAR( 2083 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `vid_watermark`  `vid_watermark` VARCHAR( 2083 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;

ALTER TABLE  `watermark_setting` CHANGE  `text`  `text` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;