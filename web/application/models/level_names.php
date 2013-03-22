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

class Level_Names_Model extends ORM
{
	// Database table name
	protected $table_name = 'level_names';

	protected $primary_key = 'level';

	/**
	 * Returns all details for all missions, including user information
	 * @return array
	 */
	public static function level_names()
	{
		$level_names = ORM::factory('level_names')->find_all();
		$arr = array();

        echo Kohana::debug($level_names);

		foreach($level_names as $level_name){
			$arr[$level_name->level] = array('name'=>$level_name->name);
		}

		return $arr;
	}

	public static function get_level_name($level)
	{
		$level_name = ORM::factory('level_names')
			->where("level", $level)
			->find();

        return $level_name->name;
	}

}