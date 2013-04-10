<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Reports Controller.
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

class Help_Controller extends Members_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->template->this_page = 'help';
	}


	/**
	* Lists the reports.
	* @param int $page
	*/
	public function index($page = 1)
	{
		$this->template->content = new View('members/help');
		$this->template->content->title = "Help Page";

	}
}
