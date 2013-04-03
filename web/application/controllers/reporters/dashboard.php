<?php defined('SYSPATH') or die('No direct script access.');
/**
 * This controller is used for the main Admin panel
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author	   Ushahidi Team <team@ushahidi.com>
 * @package	   Ushahidi - http://source.ushahididev.com
 * @subpackage Members
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class Dashboard_Controller extends Reporters_Controller {

	function __construct()
	{
		parent::__construct();
	}


	function index()
	{
		$this->template->content = new View('reporters/dashboard');
		$this->template->content->title = Kohana::lang('ui_admin.dashboard');
		$this->template->this_page = 'dashboard';


		// Map and Slider Blocks
		$div_map = new View('main/map');
		$div_timeline = new View('main/timeline');

		// Filter::map_main - Modify Main Map Block
		Event::run('ushahidi_filter.map_main', $div_map);

		// Filter::map_timeline - Modify Main Map Block
		Event::run('ushahidi_filter.map_timeline', $div_timeline);

		$this->template->content->div_map = $div_map;
		$this->template->content->div_timeline = $div_timeline;



		// User
		$this->template->content->user = $this->user;

		// User Reputation Score
		$this->template->content->reputation = reputation::calculate($this->user->id);

		// Get Badges
		$this->template->content->badges = Badge_Model::users_badges($this->user->id);

        missions::calculate($this->user);

		// Get Missions
		$this->template->content->completed_missions = Mission_Model::users_completed_missions($this->user);
		$this->template->content->pending_missions = Mission_Model::users_pending_missions($this->user);

		$this->template->content->level_name = Level_Names_Model::get_level_name($this->user->level);
		$this->template->content->next_level_name = Level_Names_Model::get_level_name($this->user->level+1);

        $this->template->content->total_photos = Missions_Core::count_photos(ORM::factory('incident')->where("user_id", $this->user->id)->find_all());

		// Retrieve Dashboard Counts...
		// Total Reports
		$this->template->content->reports_total = ORM::factory('incident')
			->where("user_id", $this->user->id)
			->count_all();

		// Total Unapproved Reports
		$this->template->content->reports_unapproved = ORM::factory('incident')
			->where('incident_active', '0')
			->where("user_id", $this->user->id)
			->count_all();

		// Total Checkins
		$this->template->content->checkins = ORM::factory('checkin')
			->where("user_id", $this->user->id)
			->count_all();

		// Total Alerts
		$this->template->content->alerts = ORM::factory('alert')
			->where("user_id", $this->user->id)
			->count_all();

		// Total Votes
		$this->template->content->votes = ORM::factory('rating')
			->where("user_id", $this->user->id)
			->count_all();

		// Total Votes Positive
		$this->template->content->votes_up = ORM::factory('rating')
			->where("user_id", $this->user->id)
			->where("rating", "1")
			->count_all();

		// Total Votes Negative
		$this->template->content->votes_down = ORM::factory('rating')
			->where("user_id", $this->user->id)
			->where("rating", "-1")
			->count_all();

		// Get reports for display
		$this->template->content->incidents = ORM::factory('incident')
				->where("user_id", $this->user->id)
				->limit(5)
				->orderby('incident_dateadd', 'desc')
				->find_all();

		// To support the "welcome" or "not enough info on user" form
		if($this->user->public_profile == 1)
		{
			$this->template->content->profile_public = TRUE;
			$this->template->content->profile_private = FALSE;
		}else{
			$this->template->content->profile_public = FALSE;
			$this->template->content->profile_private = TRUE;
		}

		$this->template->content->hidden_welcome_fields = array
		(
			'email' => $this->user->email,
			'notify' => $this->user->notify,
			'color' => $this->user->color,
			'password' => '', // Don't set a new password from here
			'needinfo' => 0 // After we save this form once, we don't need to show it again
		);

		// Javascript Header
		$this->template->map_enabled = TRUE;
		$this->template->colorpicker_enabled = TRUE;
		$this->template->treeview_enabled = TRUE;
		$this->template->json2_enabled = TRUE;

		$this->template->protochart_enabled = TRUE;


		$db = new Database();
        $geometries = array();

					// Get Geometries via SQL query as ORM can't handle Spatial Data
					$sql = "SELECT AsText(geometry) as geometry, geometry_label, 
						geometry_comment, geometry_color, geometry_strokewidth 
						FROM ".Kohana::config('database.default.table_prefix')."geometry";
					$query = $db->query($sql);
					foreach ( $query as $item )
					{
						$geometry = array(
							"geometry" => $item->geometry,
							"label" => $item->geometry_label,
							"comment" => $item->geometry_comment,
							"color" => $item->geometry_color,
							"strokewidth" => $item->geometry_strokewidth
						);
						$geometries[] = json_encode($geometry);
					}



//		$this->template->js = new View('admin/stats/stats_js');
		$this->template->js = new View('reports/reporter_dash_js');

		$this->template->js->geometries = $geometries;

		$this->template->js->default_map = Kohana::config('settings.default_map');
		$this->template->js->default_zoom = Kohana::config('settings.default_zoom');
        $this->template->js->latitude = Kohana::config('settings.default_lat');
        $this->template->js->longitude = Kohana::config('settings.default_lon');
        $this->template->js->incident_zoom = Kohana::config('settings.default_zoom');
        $this->template->js->edit_mode = FALSE;

        $this->template->js->marker_radius = 4;
        $this->template->js->marker_opacity = 4;
        $this->template->js->marker_stroke_width = 2;
        $this->template->js->marker_stroke_opacity = 4;


		$this->template->js->active_startDate = 0;
		$this->template->js->active_endDate = 0;
		$this->template->js->blocks_per_row = Kohana::config('settings.blocks_per_row');


		$this->template->content->failure = '';

		// Get Default Color
		$this->template->content->default_map_all = Kohana::config('settings.default_map_all');
		// Get default icon
		$this->template->content->default_map_all_icon = '';
		if (Kohana::config('settings.default_map_all_icon_id'))
		{
			$icon_object = ORM::factory('media')->find(Kohana::config('settings.default_map_all_icon_id'));
			$this->template->content->default_map_all_icon = Kohana::config('upload.relative_directory')."/".$icon_object->media_medium;
		}





		// Get locale
		$l = Kohana::config('locale.language.0');

        // Get all active top level categories
		$parent_categories = array();
		$all_parents = ORM::factory('category')
		    ->where('category_visible', '1')
		    ->where('parent_id', '0')
		    ->find_all();

		foreach ($all_parents as $category)
		{
			// Get The Children
			$children = array();
			foreach ($category->children as $child)
			{
				$child_visible = $child->category_visible;
				if ($child_visible)
				{
					// Check for localization of child category
					$display_title = Category_Lang_Model::category_title($child->id,$l);

					$ca_img = ($child->category_image != NULL)
					    ? url::convert_uploaded_to_abs($child->category_image)
					    : NULL;
					
					$children[$child->id] = array(
						$display_title,
						$child->category_color,
						$ca_img
					);
				}
			}

			// Check for localization of parent category
			$display_title = Category_Lang_Model::category_title($category->id,$l);

			// Put it all together
			$ca_img = ($category->category_image != NULL)
			    ? url::convert_uploaded_to_abs($category->category_image)
			    : NULL;

			$parent_categories[$category->id] = array(
				$display_title,
				$category->category_color,
				$ca_img,
				$children
			);
		}
		$this->template->content->categories = $parent_categories;









		// Build dashboard chart

		// Set the date range (how many days in the past from today?)
		// Default to one year if invalid or not set
		$range = (!empty($_GET['range']))
			? $_GET['range']
			: 365;

		// Phase 3 - Invoke Kohana's XSS cleaning mechanism just incase an outlier wasn't caught
		$range = $this->input->xss_clean($range);
		$incident_data = Incident_Model::get_number_reports_by_date($range, $this->user->id);
		$data = array('Reports'=>$incident_data);
		$options = array('xaxis'=>array('mode'=>'"time"'));
		$this->template->content->report_chart = protochart::chart('report_chart',$data,$options,array('Reports'=>'CC0000'),410,310);













/*
		
		// Inline Javascript
		$this->template->content->date_picker_js = $this->_date_picker_js();
		$this->template->content->color_picker_js = $this->_color_picker_js();
		
		// Pack Javascript
		$myPacker = new javascriptpacker($this->template->js , 'Normal', FALSE, FALSE);
		$this->template->js = $myPacker->pack();

*/



	}
}
?>
