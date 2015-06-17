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
	/* @var \phpbb\user */

	protected $user;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\auth\auth  */
	private $auth;

	/* @var \phpbb\controller\helper */
	protected $helper;

	/**
	 * Constructor
	 *
	 * @param \phpbb\user					$user			User Object
	 * @param \phpbb\template\template		$template			Template object
	 * @param \phpbb\auth\auth				$auth			Auth Object
	 * @param \phpbb\controller\helper			$helper			Controller helper object
	 * @access public
	 */
	public function __construct(\phpbb\user $user, \phpbb\template\template $template, \phpbb\auth\auth $auth, \phpbb\controller\helper $helper)
	{
		$this->user = $user;
		$this->template = $template;
		$this->auth = $auth;
		$this->helper = $helper;
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
			'core.modify_username_string'	=> 'modify_username_string',
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
		));
	}

	/**
	 * Add JavaScript to profile links
	 *
	 * @param	object	$event	The event object
	 * @return	null
	 * @access	public
	 */
	public function modify_username_string($event)
	{
		if (!$this->auth->acl_get('u_viewprofile'))
		{
			return true;
		}
		// if user is not logged in output no links to profiles
		if ($event['mode'] == 'full')
		{
			if ($event['username_colour'])
			{
				$event['username_string'] = '<a href="./memberlist.php?mode=viewprofile&amp;u=' . $event['user_id'] . '" onmouseover="show_popup(' . $event['user_id'] . ')" onmouseout="close_popup()" style="color: ' . $event['username_colour'] . ' ;" class="username-coloured">' . $event['username'] . '</a>';
			}
			else
			{
				$event['username_string'] = '<a href="./memberlist.php?mode=viewprofile&amp;u=' . $event['user_id'] . '" onmouseover="show_popup(' . $event['user_id'] . ')" onmouseout="close_popup()">' . $event['username'] . '</a>';
			}
		}
	}
}
