<?php defined('SYSPATH') or die('No direct script access.');
/**
* Pachube Manager
* Add new Pachube Feeds
*
* PHP version 5
* LICENSE: This source file is subject to LGPL license 
* that is available through the world-wide-web at the following URI:
* http://www.gnu.org/copyleft/lesser.html
* @author     Ushahidi Team <team@ushahidi.com> 
* @package    Ushahidi - http://source.ushahididev.com
* @subpackage Admin
* @copyright  Ushahidi - http://www.ushahidi.com
* @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL) 
*/

class Pachube_Controller extends Admin_Controller {

	function __construct()
	{
		parent::__construct();
		$this->template->this_page = 'pachube';

		// We'll store the feed here later
		$this->meta = array();
	}

	/**
	 * List all the Pachube Feeds
	 * @return void
	 */
	public function index()
	{
		$this->template->content = new View('pachube/admin/pachube');
		
		// setup and initialize form field names
		$form = array
		(
			'action' => '',
			'feed_id' => '',
			'feed' => ''
		);
		//	copy the form as errors, so the errors will be stored with keys corresponding to the form field names
		$errors = $form;
		$form_error = FALSE;
		$form_saved = FALSE;
		$form_action = "";

		if( $_POST )
		{
			$post = Validation::factory($_POST);

			 //	 Add some filters
			$post->pre_filter('trim', TRUE);

			// Add some rules, the input field, followed by a list of checks, carried out in order
			$post->add_rules('action','required', 'alpha', 'length[1,1]');

			if ($_POST['action'] == 'a')
			{
				$post->add_rules('feed','required', 'numeric');
				$post->add_callbacks('feed', array($this, 'check_feed'));
			}

			if ($post->validate())
			{
				$feed = ORM::factory('pachube_feed', $post->feed_id);

				if ($_POST['action'] == 'a')		// Add Action
				{
					$feed->feed = $post->feed;

					// Pachube Meta Data
					$feed->feed_name = $this->meta['title'];
					$feed->feed_status = $this->meta['status'];
					$feed->feed_lat = $this->meta['location']['lat'];
					$feed->feed_lon = $this->meta['location']['lon'];
					$feed->feed_disposition = $this->meta['location']['disposition'];

					// Extra Stuff
					$feed->feed_description = (isset($this->meta['description'])) ? $this->meta['description'] : '';
					$feed->feed_creator = (isset($this->meta['creator'])) ? $this->meta['creator'] : '';
					$feed->feed_website = (isset($this->meta['website'])) ? $this->meta['website'] : '';
					$feed->feed_date_add = date("Y-m-d H:i:s",time());
					$feed->save();


					// Data Streams
					foreach ($this->meta['datastreams'] as $datastream)
					{
						$stream = ORM::factory('pachube_datastream');
						$stream->pachube_feed_id = $feed->id;
						$stream->datastream_id = $datastream['id'];
						$stream->datastream_tag = (isset($datastream['tags'][0])) ? $datastream['tags'][0] : '';
						$stream->datastream_unit = (isset($datastream['unit']['label'])) ? $datastream['unit']['label'] : '';
						$stream->save();
					}

					$form_saved = TRUE;
					$form_action = strtoupper(Kohana::lang('ui_admin.added_edited'));
				}
				elseif ($_POST['action'] == 'd')
				{
					// Delete Action
					if ($feed->loaded == TRUE)
					{
						ORM::factory('pachube_trigger')->where('pachube_feed_id', $feed->id)->delete_all();
						ORM::factory('pachube_datastream')->where('pachube_feed_id', $feed->id)->delete_all();
						$feed->delete();
						$form_saved = TRUE;
						$form_action = strtoupper(Kohana::lang('ui_admin.deleted'));
					}
				}
				elseif($_POST['action'] == 'v')
				{
					// Active/Inactive Action
					if ($feed->loaded == TRUE)
					{
						$feed->feed_active =  ($feed->feed_active == 1) ? 0 : 1;
						$feed->save();
						$form_saved = TRUE;
						$form_action = strtoupper(Kohana::lang('ui_admin.modified'));
					}
				}
			}
			else
			{
				// Populate the error fields, if any
				$errors = arr::overwrite($errors, $post->errors('pachube'));
				$form_error = TRUE;
			}
		}

		// Pagination
		$pagination = new Pagination(array(
			'query_string' => 'page',
			'items_per_page' => $this->items_per_page,
			'total_items'	 => ORM::factory('pachube_feed')->count_all()
		));

		$feeds = ORM::factory('pachube_feed')
			->orderby('feed_name', 'asc')
			->find_all($this->items_per_page, $pagination->sql_offset);

		$this->template->content->form = $form;
		$this->template->content->form_error = $form_error;
		$this->template->content->form_saved = $form_saved;
		$this->template->content->form_action = $form_action;
		$this->template->content->pagination = $pagination;
		$this->template->content->total_items = $pagination->total_items;
		$this->template->content->feeds = $feeds;
		$this->template->content->errors = $errors;

		$this->template->content->categories = ORM::factory('category')
			->orderby('category_title', 'ASC')
			->find_all();

		// Javascript Header
		$this->template->colorpicker_enabled = TRUE;
		$this->template->js = new View('pachube/admin/pachube_js');
	}


	public function trigger()
	{
		$this->template = "";
		$this->auto_render = FALSE;

		if( $_POST )
		{
			$post = Validation::factory($_POST);

			 //	 Add some filters
			$post->pre_filter('trim', TRUE);

			// Add some rules, the input field, followed by a list of checks, carried out in order
			$post->add_rules('feed_id','required', 'numeric');
			$post->add_rules('stream_id','required', 'numeric');
			$post->add_rules('category_id','required', 'numeric');
			$post->add_rules('trigger','required');
			$post->add_rules('trigger_type','required');

			$post->add_callbacks('feed', array($this, 'set_trigger'));
			
			if ($post->validate())
			{
				
				echo json_encode(array("status"=>"success", "message"=>""));
			}
			else
			{
				$errors = arr::overwrite($errors, $post->errors('pachube'));
				echo json_encode(array("status"=>"error", "message"=>$errors));
			}
		}
	}


	public function check_feed(Validation $post)
	{
		// If add->rules validation found any errors, get me out of here!
		if (array_key_exists('feed', $post->errors()))
			return;

		// First Lets make sure this feed doesn't already exist
		$feeds = ORM::factory('pachube_feed')
			->where('feed', $post->feed)
			->find();

		if ($feeds->loaded)
		{
			$post->add_error('feed','exists');
			return;
		}

		// Pachube Parser
		$settings = ORM::factory('pachube_settings', 1);
		$pachube = new PachubeAPI($settings->pachube_key);

		$json = $pachube->getFeed('json', $post->feed);
		$json = json_decode($json, TRUE);

		// Valid JSON?
		if($json === null)
		{
			$post->add_error('feed','invalid_feed');
			return;
		}
		else
		{
			// Feed Not Found?
			if ( isset($json['errors']) OR ! isset($json['id']) )
			{
				$post->add_error('feed','invalid_feed');
				return;
			}

			// Feed has no location info?
			if ( ! isset($json['location']['lat']) OR ! isset($json['location']['lon']) )
			{
				$post->add_error('feed','no_location');
				return;
			}
			$this->meta = $json;
		}
	}

	public function create_trigger(Validation $post)
	{
		// If add->rules validation found any errors, get me out of here!
		if (array_key_exists('feed', $post->errors()))
			return;

		// Pachube Parser
		$settings = ORM::factory('pachube_settings', 1);
		$pachube = new PachubeAPI($settings->pachube_key);

		$json = $pachube->createTrigger('json', $post->feed);
		$json = json_decode($json, TRUE);

		// Valid JSON?
		if($json === null)
		{
			$post->add_error('feed','create_error');
			return;
		}
		else
		{
			// Feed Response Error?
			if ( isset($json['errors']) OR ! isset($json['id']) )
			{
				$post->add_error('feed','create_error');
				return;
			}

			
		}		
	}
}
