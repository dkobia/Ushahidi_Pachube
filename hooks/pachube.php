<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Pachube Hook - Load All Events
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   Ushahidi - http://source.ushahididev.com
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
 */

class pachube {
	
	/**
	 * Registers the main event add method
	 * @return void
	 */
	public function __construct()
	{
		// Hook into routing
		Event::add('system.pre_controller', array($this, 'add'));
	}
	
	/**
	 * Adds all the events to the main Ushahidi application
	 * @return void
	 */
	public function add()
	{
		// Add an Admin User Menu Link
		Event::add('ushahidi_action.nav_admin_manage', array($this, '_pachube_nav'));

		// Add CSS
		if (Router::$current_uri == "admin/pachube")
		{
			plugin::add_stylesheet('pachube/views/css/pachube');
		}
	}

	/**
	 * Pachube Nav in Admin Manage Screen
	 * @return void
	 */
	public function _pachube_nav()
	{
		$this_sub_page = Event::$data;
		echo ($this_sub_page == "pachube") ? "Pachube" : "<a href=\"".url::site()."admin/pachube\">Pachube</a>";
	}	
}

new pachube;