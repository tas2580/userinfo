<?php
/**
 *
 * @package phpBB Extension - tas2580 AJAX Userinfo
 * @copyright (c) 2015 tas2580 (https://tas2580.net)
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace tas2580\userinfo\controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class user
{
	/** @var \phpbb\auth\auth  */
	private $auth;
	/** @var \phpbb\db\driver\driver_interface */
	private $db;
	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\template\template */
	protected $template;
	/** @var string */
	private $usertable;
	/** @var string phpbb_root_path */
	protected $phpbb_root_path;
	/** @var string php_ext */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth				$auth
	 * @param \phpbb\db\driver\driver_interface	$db
	 * @param \phpbb\user					$user
	 * @param string						$usertable
	 * @param string						$phpbb_root_path
	 * @param string						$php_ext
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\db\driver\driver_interface $db, \phpbb\user $user, \phpbb\template\template $template, $usertable, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->db = $db;
		$this->user = $user;
		$this->template = $template;
		$this->usertable = $usertable;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;
	}

	public function info($user_id)
	{
		if (!$this->auth->acl_get('u_viewprofile'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$sql = 'SELECT *
			FROM ' . $this->usertable . '
			WHERE user_id = ' . (int) $user_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
		$user_rank_data = phpbb_get_user_rank($this->data, $this->data['user_posts']);

		$template = $this->template->get_user_style();

		$avatar = phpbb_get_user_avatar($this->data);
		$avatar = empty($avatar) ? '<img src="' . $this->phpbb_root_path . 'styles/' . $template[0] . '/theme/images/no_avatar.gif" width="100" height="100" alt="' . $this->user->lang('USER_AVATAR') . '">' : $avatar;

		$result = array(
			'username'	=> get_username_string('username', $user_id, $this->data['username'], $this->data['user_colour']),
			'regdate'		=> $this->user->format_date($this->data['user_regdate']),
			'posts'		=> $this->data['user_posts'],
			'lastvisit'		=> $this->user->format_date($this->data['user_lastvisit']),
			'avatar'		=> $avatar,
			'rank'		=> $user_rank_data['title'],
		);

		return new JsonResponse(array($result));
	}
}
