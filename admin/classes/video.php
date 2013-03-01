<?php

class Video
{
	public static function install()
	{
		Database::query(
			'CREATE TABLE IF NOT EXISTS `video_category` (
			  `video_category_id` int(11) NOT NULL auto_increment,
			  `name` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  PRIMARY KEY  (`video_category_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;'
		);

		Database::query(
			'CREATE TABLE IF NOT EXISTS `video` (
			  `video_id` int(10) NOT NULL auto_increment,
			  `path` varchar(255) NOT NULL,
			  `title` varchar(255) NOT NULL,
			  `description` text NOT NULL,
			  `thumb_path` varchar(255) NOT NULL,
			  `video_category_id` int(11) NOT NULL,
			  `active` int(1) NOT NULL,
			  PRIMARY KEY  (`video_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;'
		);
	}
}