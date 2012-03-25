<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Pachube_Datastream Model
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Pachube_Datastream Model  
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Pachube_Datastream_Model extends ORM
{
	// Database table name
	protected $table_name = 'pachube_datastream';

	// Belongs to a Feed
	protected $belongs_to = array('pachube_feed');

	// Has many Triggers
	protected $has_many = array('pachube_trigger');
}