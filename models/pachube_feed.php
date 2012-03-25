<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Pachube_Feeds Model
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Pachube_Feeds Model  
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Pachube_Feed_Model extends ORM
{
	// Database table name
	protected $table_name = 'pachube_feed';

	// Has many triggers
	protected $has_many = array('pachube_trigger', 'pachube_datastream');

	/**
	 * Validates and optionally saves a new feed record from an array
	 *
	 * @param array $array Values to check
	 * @param bool $save Saves the record when validation succeeds
	 * @return bool
	 */
	public function validate(array & $array, $save = FALSE)
	{
		// Instantiate validation
		$array = Validation::factory($array)
				->pre_filter('trim', TRUE)
				->add_rules('feed_id','required', 'numeric');
		
		return parent::validate($array, $save);
	}
	
	/**
	 * Checks if the specified feed exists in the database
	 *
	 * @param int $feed_id Database record ID of the feed to check
	 * @return bool
	 */
	public static function is_valid_feed($feed_id)
	{
		return (intval($feed_id) > 0)
			? self::factory('feed', intval($feed_id))->loaded
			: FALSE;
	}	
}