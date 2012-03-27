<?php
/**
 * Performs install/uninstall methods for the Pachube Plugin
 *
 * @package    Ushahidi
 * @author     Ushahidi Team
 * @copyright  (c) 2008 Ushahidi Team
 * @license    http://www.ushahidi.com/license.html
 */
class Pachube_Install {
	
	/**
	 * Load the shared database library
	 */
	public function __construct()
	{
		$this->db =  new Database();
	}

	/**
	 * Creates the required columns for the Pachube Plugin
	 * @return void
	 */
	public function run_install()
	{
		// ****************************************
		// DATABASE STUFF
		// ****************************************
		
		// Settings Table
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."pachube_settings` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `pachube_key` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			);
		");
		

		// Feeds Table
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."pachube_feed` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `feed` int(11) NOT NULL DEFAULT '0',
			  `feed_status` varchar(100) DEFAULT NULL,
			  `feed_name` varchar(255) DEFAULT NULL,
			  `feed_creator` varchar(255) DEFAULT NULL,
			  `feed_description` text,			  
			  `feed_lat` double NOT NULL DEFAULT '0',
			  `feed_lon` double NOT NULL DEFAULT '0',
			  `feed_disposition` varchar(50) DEFAULT NULL,
			  `feed_website` varchar(255) DEFAULT NULL,
			  `feed_created` datetime DEFAULT NULL,
			  `feed_active` tinyint(1) NOT NULL DEFAULT '1',
			  `feed_date_add` datetime DEFAULT NULL,
			  PRIMARY KEY (`id`),
			  UNIQUE KEY `feed_id` (`feed_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;			
		");

		// Triggers Table
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".Kohana::config('database.default.table_prefix')."pachube_trigger` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `pachube_feed_id` int(11) unsigned NOT NULL DEFAULT '0',
			  `pachube_datastream_id` int(11) unsigned NOT NULL DEFAULT '0',
			  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
			  `trigger` varchar(255) DEFAULT NULL,
			  `trigger_type` varchar(50) DEFAULT NULL,
			  `trigger_type_long` varchar(50) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;
		");

		// Datastreams Table
		$this->db->query("
			CREATE TABLE `pachube_datastream` (
			  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `pachube_feed_id` int(11) unsigned NOT NULL DEFAULT '0',
			  `datastream_id` varchar(100) NOT NULL DEFAULT '0',
			  `datastream_tag` varchar(255) DEFAULT NULL,
			  `datastream_unit` varchar(255) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;			
		");
		// ****************************************
	}

	/**
	 * Deletes the columns for the Pachube Plugin
	 * @return void
	 */
	public function uninstall()
	{
		$this->db->query("
			DROP TABLE ".Kohana::config('database.default.table_prefix')."pachube_settings;
			");
	}
}