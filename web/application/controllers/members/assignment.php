<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Alerts Controller.
 * This controller will take care of adding and editing reports in the Member section.
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


class Assignment_Controller extends Members_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->template->this_page = 'assignment';
	}

	/**
	 * Displays the default "profile" page
	 */
	public function index()
	{
		$this->template->content = new View('members/assignment');

		// User
		$this->template->content->user = $this->user;
        
	}

	/**
	 * Displays a profile page for a user
	 */
	public function join()
	{
		// Cacheable Controller
		$this->is_cachable = TRUE;
		$this->template->this_page = 'assignment';

		// Check if we are looking for an assignment. Argument must be set to continue.
		if( ! isset(Router::$arguments[0]))
		{
			url::redirect('members/dashboard');
		}

        $this->template->content = new View('members/assignment_join');
        $this->template->content->user = $this->user;
		$assignment_id = Router::$arguments[0];
        $this->template->content->assignment_id = $assignment_id;
        $categories = Category_Model::categories($assignment_id);        
        $this->template->content->category = reset($categories);

        // do it
        Assignment_Model::add_assignment($this->user->id,$assignment_id);

	}

} // End Profile

