<?php

class User
{
	/**
	 * Check if a user session exists.
	 * 
	 * @return boolean
	 */
	public static function loggedIn()
	{
		if (Session::get('user')) {
			return true;
		}

		return false;
	}

	/**
	 * Check if a user session does not exist.
	 * 
	 * @return boolean
	 */
	public static function guest()
	{
		return ! self::loggedIn();
	}

	public static function install()
	{
		Database::query(
			"CREATE TABLE  `test`.`user` (
			`user_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
			 `email` VARCHAR( 255 ) NOT NULL ,
			 `password` VARCHAR( 255 ) NOT NULL ,
			 `firstname` VARCHAR( 255 ) NOT NULL ,
			 `lastname` VARCHAR( 255 ) NOT NULL ,
			PRIMARY KEY (  `user_id` )
			) ENGINE = INNODB DEFAULT CHARSET = latin1;"
		);

		Database::query(
			"CREATE TABLE IF NOT EXISTS `role` (
			  `role_id` int(11) NOT NULL AUTO_INCREMENT,
			  `name` varchar(255) NOT NULL,
			  `description` varchar(255) NOT NULL,
			  PRIMARY KEY (`role_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6;"
		);

		Database::query(
			"INSERT INTO `role` (`role_id`, `name`, `description`) VALUES
			(1, 'user', 'General user'),
			(5, 'admin', 'Administrator');"
		);
	}
}