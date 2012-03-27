<?php defined('SYSPATH') or die('No direct script access.');
/**
* Pachube Callback URL
*
* PHP version 5
* LICENSE: This source file is subject to LGPL license 
* that is available through the world-wide-web at the following URI:
* http://www.gnu.org/copyleft/lesser.html
* @author     Ushahidi Team <team@ushahidi.com> 
* @package    Ushahidi - http://source.ushahididev.com
* @subpackage Controller
* @copyright  Ushahidi - http://www.ushahidi.com
* @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
*/

class Pachube_Controller extends Controller {

	/**
	 * Receive the Pachube Trigger
	 * Process the POST from Pachube
	 * @param int $trigger_id
	 * @return void
	 */
	public function trigger($trigger_id = 0)
	{
		if( $_POST )
		{
			$json = ( isset($_POST["body"]) ) ? stripslashes($_POST["body"]) : false;

			if ($json AND json_decode($json) != null)
			{
				$json = json_decode($json);

				// Get Category
				$trigger = ORM::factory('pachube_trigger');
				if ( $trigger->loaded AND isset($json->environment->location) )
				{
					$title = $json->environment->title;
					$description = '';
					$description .= 'New Condition: '.$json->triggering_datastream->value->value.' Units: ';
					$description .= (isset($trigger->pachube_datastream->datastream_unit)) ? $trigger->pachube_datastream->datastream_unit : 'UNKNOWN';
					$description .= '\n\n';
					$description .= $trigger->pachube_feed->feed_description.'\n';
					$description .= 'https://pachube.com/feeds/'.$trigger->pachube_feed->feed;

					// Save Location
					$location = ORM::factory('location');
					$location->location_name = (isset($json->environment->location->name)) ? $json->environment->location->name : "Unknown";
					$location->latitude = (isset($json->environment->location->lat)) ? $json->environment->location->lat : '0.0';
					$location->longitude = (isset($json->environment->location->lon)) ? $json->environment->location->lon : '0.0';
					$location->location_date = date("Y-m-d H:i:s",time());
					$location->save();

					// Save Report
					$incident = ORM::factory('incident');
					$incident->incident_title = $title;
					$incident->incident_description = xxx;
					$incident->incident_dateadd = date("Y-m-d H:i:s",strtotime($json->timestamp));
					$incident->save();

					// Save Category
					$incident_category = new Incident_Category_Model();
					$incident_category->incident_id = $incident->id;
					$incident_category->category_id = $trigger->category_id;
					$incident_category->save();
				}
			}
		}
	}

}