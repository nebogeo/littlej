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

class Assignment_Model extends ORM {
	
	// Database table name
	protected $table_name = 'user_assignments';

	protected $primary_key = 'index_id';

	/**
	 * Calculate Total Reputation Score for User
	 * @param int User ID
	 * @return int reputation score
	 */

    public static function add_assignment($user_id, $assignment_id) 
    {
        $query="select * from user_assignments 
                join category on id = assignment
                where user_id = ".$user_id." and id = ".$assignment_id;

		if (count(Database::instance()->query($query))==0)
        {
            Database::instance()->insert('user_assignments',
                                         array('user_id'=>$user_id,
                                               'assignment'=>$assignment_id));      
        }  
    }

	public static function get_assignments($user_id) 
    {
        $query="select * from user_assignments 
                join category on id = assignment
                where user_id = ".$user_id;
		return Database::instance()->query($query);
    }

	public static function get_unjoined_open_assignments($user_id) 
    {
        $query="select * from category as c
                left join (select * from user_assignments 
                where user_id = ".$user_id.") as joined
                on id = joined.assignment
                where parent_id = 13 and user_id is null";

		return Database::instance()->query($query);
    }
}