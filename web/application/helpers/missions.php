<?php defined('SYSPATH') OR die('No direct access allowed.');
/**
 * Reputation Score helper class
 * 
 *
 * @package	   Reputation
 * @author	   Ushahidi Team
 * @copyright  (c) 2008 Ushahidi Team
 * @license	   http://www.ushahidi.com/license.html
 */

class missions_Core {

	
	/**
	 * Calculate Total Reputation Score for User
	 * @param int User ID
	 * @return int reputation score
	 */

    public static function mission_complete($user_id, $mission_id) 
    {
        Database::instance()->insert('mission_users',
                                     array('user_id'=>$user_id,
                                           'mission_id'=>$mission_id));        
    }

    public static function update_user_level($user_id,$level)
    {
        Database::instance()->update('users',array('level'=>$level),array('id'=>$user_id));
    }

    public static function check_photos($reports)
    {
        foreach($reports as $report) 
        {
            $photos = ORM::factory('media')
                ->where('incident_id', $report->id)
                ->where('media_type', 1)
                ->find_all();
            
            echo Kohana::debug(count($photos));
            if (count($photos)>0) return true;
        }

        return false;
    }

    public static function check_missions($user, $pending_missions, $reports) 
    {
        // check each one
		foreach($pending_missions as $pending_mission)
		{
            // write new ones that have been completed
            switch ($pending_mission->code)
            {
                // level 1
            case "login": missions_Core::mission_complete($user->id,$pending_mission->id); break;
            case "first_report": if (count($reports)>0)
                    missions_Core::mission_complete($user->id,$pending_mission->id); break;
            case "report_photo": if (missions_Core::check_photos($reports)) 
                    missions_Core::mission_complete($user->id,$pending_mission->id); break;

                // level 2
            case "three_reports": if (count($reports)>2) 
                    missions_Core::mission_complete($user->id,$pending_mission->id); break;
            case "ten_reports": if (count($reports)>9) 
                    missions_Core::mission_complete($user->id,$pending_mission->id); break;
            }
        }
    }

	public static function get_pending_missions($user) 
    {
        // get pending missions for the user's current level
        $query="select * from mission 
            left join mission_users 
            on mission_id = id 
            where (mission_id is null or user_id != ".$user->id.") and level = ".$user->level;
		return Database::instance()->query($query);
    }



	public static function calculate($user)	
	{
        $pending_missions = missions_Core::get_pending_missions($user);
        
        // get the reports whatever
        $reports = ORM::factory("incident")
            ->where("user_id", $user->id)
            ->find_all();
        
        missions_Core::check_missions($user, $pending_missions, $reports);

        // update user level when complete
        if (count($pending_missions)==0 && $user->level<2) 
        {
            $user->level=$user->level+1;
            missions_Core::update_user_level($user->id,$user->level);
            missions_Core::check_missions($user, $pending_missions, $reports);
        }
            
        

/*
			$total = 0;
			$upvoted_reports = 10;
			$approved_reports = 15;
			$verified_reports = 20;
			$upvoted_comments = 5;
			$downvoted_reports = 2;
			$downvoted_comments = 1;

			// Get Reports Approved Verified
			$reports = ORM::factory("incident")
						->where("user_id", $user_id)
						->find_all();
						
			foreach ($reports as $report)
			{
				if ($report->incident_active)
				{
					$total += $approved_reports;
				}

				if ($report->incident_verified)
				{
					$total += $verified_reports;
				}
			}

			// Get Totals on [My] Reports that have been voted on
			$ratings = ORM::factory("rating")
						->join("incident", "incident.id", "rating.incident_id")
						->join("users", "users.id", "incident.user_id")
						->where("users.id", $user_id)
						->find_all();
			foreach ($ratings as $rating)
			{
				if ($rating->rating > 0)
				{ // Upvote
					$total += ( $rating->rating * $upvoted_reports );
				}
				elseif ($rating->rating < 0)
				{ // Downvote
					$total += ( $rating->rating * $downvoted_reports );
				}
			}

			// Get Totals on [My] Comments that have been voted on
			$ratings = ORM::factory("rating")
				->join("comment", "comment.id", "rating.comment_id")
				->join("users", "users.id", "comment.user_id")
				->where("users.id", $user_id)
				->find_all();
				
			foreach ($ratings as $rating)
			{
				if ($rating->rating > 0)
				{ // Upvote
					$total += ( $rating->rating * $upvoted_comments );
				}
				elseif ($rating->rating < 0)
				{ // Downvote
					$total += ( $rating->rating * $downvoted_comments );
				}
			}
*/


	}
}