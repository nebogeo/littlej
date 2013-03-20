<?php

/**
 * Model for Missions of each User
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
 * $Id: $
 */
class Mission_User_Model extends ORM {

	protected $belongs_to = array('mission','users');

	protected $table_name = 'mission_users';
	
	protected $sorting = array('mission_id' => 'asc');
}
