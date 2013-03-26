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

class assignments_Core {
	
	/**
	 * Calculate Total Reputation Score for User
	 * @param int User ID
	 * @return int reputation score
	 */

    public static function add_assignment($user_id, $assignment_id) 
    {
        Database::instance()->insert('users_assignments',
                                     array('user_id'=>$user_id,
                                           'assignment'=>$assignment_id));        
    }


	public static function get_assignments($user_id) 
    {
        $query="select * from user_assignments where user_id = ".$user->id;
		return Database::instance()->query($query);
    }
}