<?php defined('SYSPATH') or die('No direct script access.');

/**
* Model for Mission
 *
 * PHP version 5
 * LICENSE: This source file is subject to LGPL license
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/lesser.html
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi - http://source.ushahididev.com
 * @subpackage Models
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license    http://www.gnu.org/copyleft/lesser.html GNU Lesser General Public License (LGPL)
 */

class Mission_Model extends ORM
{
	// Database table name
	protected $table_name = 'mission';

	protected $primary_key = 'id';

	protected $has_and_belongs_to_many = array('users');

	/**
	 * Returns all details for all missions, including user information
	 * @return array
	 */
	public static function missions()
	{
		$missions = ORM::factory('mission')->find_all();
		$arr = array();
		foreach($missions as $mission){
			$arr[$mission->id] = array('id'=>$mission->id,
                                       'name'=>$mission->name,
                                       'description'=>$mission->description,
                                       'users'=>array());
			foreach($mission->users as $user)
			{
				$arr[$mission->id]['users'][$user->id] = $user->username;
			}

			asort($arr[$mission->id]['users']);
		}

		return $arr;
	}

	/**
	 * Returns a simple array of badge names with badge id as the array key
	 * @return array
	 */
	public static function mission_names()
	{
		$missions = ORM::factory('mission')->select_list('id','name');
		return $missions;
	}

	/**
	 * Returns an array of completed missions for a specific user
	 * @return array
	 */
	public static function users_completed_missions($user)
	{
		// Get completed missions
		$completed_missions = ORM::factory('mission_user')
            ->join('mission','id','mission_id')
            ->where(array('user_id'=>$user->id))
            ->where(array('level'=>$user->level))
            ->find_all();

		$missions = array();
		foreach($completed_missions as $completed_mission)
		{
			$missions[] = $completed_mission->mission_id;
		}
		
		$arr = array();
		if(count($missions) > 0)
		{
			// Get missions with those ids
			$missions = ORM::factory('mission')->in('id', $missions)->find_all();
			foreach($missions as $mission)
			{
				$arr[$mission->id] = array('id'=>$mission->id,
									  	   'name'=>$mission->name,
                                           'description'=>$mission->description);
			}
		}

		return $arr;
	}

	public static function users_pending_missions($user)
	{
		$pending_missions = missions_Core::get_pending_missions($user);

        $missions = array();
		foreach($pending_missions as $pending_mission)
		{
			$missions[] = $pending_mission->id;
		}
		
		$arr = array();
		if(count($missions) > 0)
		{
			// Get missions with those ids
			$missions = ORM::factory('mission')->in('id', $missions)->find_all();
			foreach($missions as $mission)
			{
				$arr[$mission->id] = array('id'=>$mission->id,
									  	   'name'=>$mission->name,
                                           'description'=>$mission->description);
			}
		}
		return $arr;
	}


}
