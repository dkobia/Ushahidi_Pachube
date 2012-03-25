<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Pachube_Triggers Model
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com> 
 * @package    Ushahidi - http://source.ushahididev.com
 * @module     Pachube_Triggers Model  
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class Pachube_Trigger_Model extends ORM
{
	// Database table name
	protected $table_name = 'pachube_trigger';

	// Belongs to a Pachube Feed
	protected $belongs_to = array('pachube_feed', 'category', 'pachube_datastream');
}