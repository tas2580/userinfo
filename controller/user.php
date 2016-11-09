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

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	private $db;

	/** @var \phpbb\event\dispatcher_interface */
	protected $phpbb_dispatcher;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\extension\manager */
	protected $phpbb_extension_manager;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string php_ext */
	protected $php_ext;

	/**
	 * Constructor
	 *
	 * @param \phpbb\auth\auth						$auth
	 * @param \phpbb\config\config					$config				Config object
	 * @param \phpbb\db\driver\driver_interface		$db
	 * @param \\phpbb\event\dispatcher_interface	$phpbb_dispatcher
	 * @param \phpbb\user							$user
	 * @param string								$phpbb_root_path
	 * @param string								$php_ext
	 */
	public function __construct(\phpbb\auth\auth $auth, \phpbb\config\config $config, \phpbb\db\driver\driver_interface $db, \phpbb\event\dispatcher_interface $phpbb_dispatcher, \phpbb\user $user, \phpbb\extension\manager $phpbb_extension_manager, $phpbb_root_path, $php_ext)
	{
		$this->auth = $auth;
		$this->config = $config;
		$this->db = $db;
		$this->phpbb_dispatcher = $phpbb_dispatcher;
		$this->user = $user;
		$this->phpbb_extension_manager = $phpbb_extension_manager;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		$this->user->add_lang('memberlist');
	}

	public function info($user_id)
	{
		if (!$this->auth->acl_gets('u_viewprofile'))
		{
			trigger_error('NOT_AUTHORISED');
		}

		$sql_ary = array(
			'SELECT'	=> 'u.username, u.user_colour, u.user_regdate, u.user_posts, u.user_lastvisit, u.user_rank, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height',
			'FROM'		=> array(
				USERS_TABLE	=> 'u',
			),
			'WHERE'	=>	'u.user_id = ' . (int) $user_id,
		);

		/**
		* Modify SQL query in tas2580 AJAX userinfo extension
		*
		* @event tas2580.userinfo_modify_sql
		* @var    string		sql_ary	The SQL query
		* @var    int		user_id	The ID of the user
		* @since 0.2.3
		*/
		$vars = array('sql_ary', 'user_id');
		extract($this->phpbb_dispatcher->trigger_event('tas2580.userinfo_modify_sql', compact($vars)));

		$result = $this->db->sql_query_limit($this->db->sql_build_query('SELECT', $sql_ary), 1);
		$this->data = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!function_exists('phpbb_get_user_rank'))
		{
			include($this->phpbb_root_path . 'includes/functions_display.' . $this->php_ext);
		}
		$user_rank_data = phpbb_get_user_rank($this->data, $this->data['user_posts']);

		// Get the avatar
		// Wen need to use the full URL here because we don't know the path where userinfo is called
		define('PHPBB_USE_BOARD_URL_PATH', true);
		$avatar = phpbb_get_user_avatar($this->data);
		if (empty($avatar))
		{
			$avatar_url = generate_board_url() . '/' . $this->phpbb_extension_manager->get_extension_path('tas2580/userinfo', false) . 'images/no_avatar.gif';
			$avatar = '<img src="' . $avatar_url . '" width="100" height="100" alt="' . $this->user->lang('USER_AVATAR') . '">';
		}

		$memberdays = max(1, round((time() - $this->data['user_regdate']) / 86400));
		$posts_per_day = $this->data['user_posts'] / $memberdays;
		$percentage = ($this->config['num_posts']) ? min(100, ($this->data['user_posts'] / $this->config['num_posts']) * 100) : 0;

		$result = array(
			'userinfo_header'	=> sprintf($this->user->lang['VIEWING_PROFILE'], $this->data['username']),
			'username'			=> get_username_string('no_profile', $user_id, $this->data['username'], $this->data['user_colour']),
			'regdate'			=> $this->user->format_date($this->data['user_regdate']),
			'posts'				=> $this->data['user_posts'],
			'lastvisit'			=> ($this->data['user_lastvisit'] <> 0) ? $this->user->format_date($this->data['user_lastvisit']) : $this->user->lang('NEVER'),
			'avatar'			=> $avatar,
			'rank'				=> empty($user_rank_data['title']) ? $this->user->lang('NA') : $user_rank_data['title'],
			'postsperday'		=> $this->user->lang('POST_DAY', $posts_per_day),
			'percentage'		=> $this->user->lang('POST_PCT', $percentage),
		);

		/**
		* Modify return data in tas2580 AJAX userinfo extension
		*
		* @event tas2580.userinfo.modify_result
		* @var    array	result	The result array
		* @var    int	user_id	The ID of the user
		* @since 0.2.3
		*/
		$vars = array('result', 'user_id');
		extract($this->phpbb_dispatcher->trigger_event('tas2580.userinfo.modify_result', compact($vars)));

		return new JsonResponse($result);
	}
}
