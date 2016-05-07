<?php
/**
 *
 * @package phpBB Extension - tas2580 AJAX Userinfo
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace tas2580\userinfo\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event listener
 */
class listener implements EventSubscriberInterface
{
	/** @var \phpbb\auth\auth  */
	private $auth;

	/** @var \phpbb\controller\helper */
	protected $helper;

	/** @var \phpbb\template\template */
	protected $template;

	/**
	 * Constructor
	 *
	 * @param \phpbb\template\template		$template		Template object
	 * @param \phpbb\auth\auth				$auth			Auth Object
	 * @param \phpbb\controller\helper		$helper			Controller helper object
	 * @access public
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\controller\helper $helper, \phpbb\template\template $template)
	{
		$this->auth = $auth;
		$this->helper = $helper;
		$this->template = $template;
	}

	/**
	 * Assign functions defined in this class to event listeners in the core
	 *
	 * @return array
	 * @static
	 * @access public
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.page_header'			=> 'page_header',
		);
	}

	/**
	 * Generate link for the AJAX request
	 *
	 * @param	object	$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function page_header($event)
	{
		$this->template->assign_vars(array(
			'AJAX_USERINFO_PATH'	=> $this->helper->route('tas2580_userinfo', array('user_id' => 'USERID')),
			'A_VIEWPROFILE'			=> $this->auth->acl_get('u_viewprofile'),
		));
	}
}
