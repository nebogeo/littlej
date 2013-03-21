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

	public static function calculate($user)	
	{
        // get pending missions for the user's current level
        $query="select * from mission 
            left join mission_users 
            on mission_id = id 
            where (mission_id is null or user_id != ".$user->id.") and level = ".$user->level;
		$pending_missions = Database::instance()->query($query);

        // get the reports whatever
        $reports = ORM::factory("incident")
            ->where("user_id", $user->id)
            ->find_all();
        

//		$photos = ORM::factory('media')
//				      ->where('incident_id', $this->id)
//				      ->where('media_type', 1)
//				      ->find_all();


        // check each one
		foreach($pending_missions as $pending_mission)
		{
            switch ($pending_mission->code)
            {
            case "first_report": if (count($reports)>0) mission_complete($user->id,$pending_mission->id); break;
            case "three_reports": if (count($reports)>2) mission_complete($user->id,$pending_mission->id); break;
            }
        }

            
            
      
            // write new ones that have been completed
        

            // update user level when complete
	
        


        /*       foreach($pending_missions as $pending_mission)
		{
			
            

		}*/
        

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