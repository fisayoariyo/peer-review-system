<?php

/**
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/public/partials *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 */
if (!class_exists('Peer_Review_System_Profile_Menu')) {

	class Peer_Review_System_Profile_Menu
	{

		protected static $instance = null;

		public function __construct()
		{
		}

		/**
		 * Returns the *Singleton* instance of this class.
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
		public static function getInstance()
		{
			if (is_null(self::$instance)) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Returns user profile avatar menu
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
		public static function peer_review_system_get_dashboard_profile_menu()
		{
			global $prs_settings, $current_user;
			$user_identity  	= $current_user->ID;
			$user_type      	= apply_filters('PeerReviewSystemGetUserType', $user_identity);
			$dashboard_page		= peer_review_system_get_page_uri('dashboard');
			$inbox_page			= peer_review_system_get_page_uri('inbox');
			$approved_articles_page	= $rejected_articles_page = $underreview_articles_page = $assinged_articles_page = '';

			if (!empty($dashboard_page)) {
				$approved_articles_page	= add_query_arg(array('useridentity' => $user_identity, 'tab' => 'approved-articles'), $dashboard_page);
				$rejected_articles_page	= add_query_arg(array('useridentity' => $user_identity, 'tab' => 'rejected-articles'), $dashboard_page);
				$assinged_articles_page	= add_query_arg(array('useridentity' => $user_identity, 'tab' => 'assigned-articles'), $dashboard_page);
				$minor_articles_page	= add_query_arg(array('useridentity' => $user_identity, 'tab' => 'minor-revisions'), $dashboard_page);
				$major_articles_page	= add_query_arg(array('useridentity' => $user_identity, 'tab' => 'major-revisions'), $dashboard_page);
				$underreview_articles_page	= add_query_arg(array('useridentity' => $user_identity, 'tab' => 'review-articles'), $dashboard_page);
				$invoices_page	= add_query_arg(array('useridentity' => $user_identity, 'tab' => 'invoices'), $dashboard_page);
			}

			$peer_review_system_menu_list 	= array(
				'dashboard'	=> array(
					'title' 	=> esc_html__('Dashboard', 'peer-review-system'),
					'class'		=> 'prs-dashboard',
					'icon'		=> 'icon icon-home',
					'ref'		=> 'dashboard',
					'mode'		=> 'insights',
					'sortorder'	=> 1,
					'type'		=> 'none',
					'url'		=> esc_url($dashboard_page),
				),
				'logout'		=> array(
					'title' 	=> esc_html__('Logout', 'peer-review-system'),
					'class'		=> 'prs-logout',
					'icon'		=> 'icon icon-power',
					'ref'		=> 'logout',
					'mode'		=> '',
					'sortorder'	=> 90,
					'type'		=> 'none',
					'url'		=> esc_url(wp_logout_url(home_url('/'))),
				),
			);

			if ($user_type == 'author' || $user_type == 'editor') {
				$peer_review_system_menu_list['approved-articles']	= array(
					'title' 	=> esc_html__('Approved articles', 'peer-review-system'),
					'class'		=> 'prs-approved-articles',
					'icon'		=> 'icon icon-database',
					'ref'		=> 'dashboard',
					'mode'		=> 'insights',
					'sortorder'	=> 10,
					'type'		=> 'none',
					'url'		=> esc_url($approved_articles_page),
				);
				$peer_review_system_menu_list['minor-revisions']	= array(
					'title' 	=> esc_html__('Minor revisions', 'peer-review-system'),
					'class'		=> 'prs-approved-articles',
					'icon'		=> 'icon-refresh-cw',
					'ref'		=> 'dashboard',
					'mode'		=> 'insights',
					'sortorder'	=> 10,
					'type'		=> 'none',
					'url'		=> esc_url($minor_articles_page),
				);
				$peer_review_system_menu_list['major-revisions']	= array(
					'title' 	=> esc_html__('Major revisions', 'peer-review-system'),
					'class'		=> 'prs-approved-articles',
					'icon'		=> 'icon-rotate-ccw',
					'ref'		=> 'dashboard',
					'mode'		=> 'insights',
					'sortorder'	=> 10,
					'type'		=> 'none',
					'url'		=> esc_url($major_articles_page),
				);
				$peer_review_system_menu_list['rejected-articles']	= array(
					'title' 	=> esc_html__('Rejected articles', 'peer-review-system'),
					'class'		=> 'prs-rejected-articles',
					'icon'		=> 'icon icon-x-square',
					'ref'		=> 'dashboard',
					'mode'		=> 'insights',
					'sortorder'	=> 70,
					'type'		=> 'none',
					'url'		=> esc_url($rejected_articles_page),
				);
			}

			if ($user_type == 'author' || $user_type == 'editor') {
				$peer_review_system_menu_list['under-review-articles']	= array(
					'title' 	=> esc_html__('Under review articles', 'peer-review-system'),
					'class'		=> 'prs-under-review-articles',
					'icon'		=> 'icon icon-activity',
					'ref'		=> 'dashboard',
					'mode'		=> 'insights',
					'sortorder'	=> 50,
					'type'		=> 'none',
					'url'		=> esc_url($underreview_articles_page),
				);
			}

			$peer_review_system_menu_list['invoices']	= array(
				'title' 	=> esc_html__('Invoices', 'peer-review-system'),
				'class'		=> 'prs-downloads',
				'icon'		=> 'icon icon-arrow-down-circle',
				'ref'		=> 'downloads',
				'mode'		=> 'listings',
				'sortorder'	=> 90,
				'type'		=> 'none',
				'url'		=> esc_url($invoices_page),
			);
			 
			if ($user_type == 'reviewer') {
				$peer_review_system_menu_list['assigned-articles']	= array(
					'title' 	=> esc_html__('Assigned articles', 'peer-review-system'),
					'class'		=> 'prs-assigned-articles',
					'icon'		=> 'icon icon-activity',
					'ref'		=> 'dashboard',
					'mode'		=> 'insights',
					'sortorder'	=> 60,
					'type'		=> 'none',
					'url'		=> esc_url($assinged_articles_page),
				);
			}

			if (apply_filters('peer_review_system_chat_solution_guppy', false) === true) {
				$peer_review_system_menu_list['inbox']	= array(
					'title' 	=> esc_html__('Inbox', 'peer-review-system'),
					'class'		=> 'prs-inbox',
					'icon'		=> 'icon icon-message-square',
					'type'		=> 'none',
					'ref'		=> 'inbox',
					'mode'		=> '',
					'sortorder'	=> 80,
					'url'		=> esc_url($inbox_page),
				);
			}

			$peer_review_system_menu_list 	= apply_filters('peer_review_system_filter_dashboard_profile_menu', $peer_review_system_menu_list);
			return $peer_review_system_menu_list;
		}

		/**
		 * Generate Menu Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
		public static function peer_review_system_profile_menu_link($ref = '', $user_identity = '', $return = false, $tab = '', $id = '')
		{

			$profile_page	= peer_review_system_get_page_uri('dashboard');

			if (empty($profile_page)) {
				$pages = get_pages(array(
					'meta_key'		=> '_wp_page_template',
					'meta_value'	=> 'templates/dashboard.php'
				));

				foreach ($pages as $page) {
					$profile_page 	= !empty($page->ID) ? get_permalink((int) $page->ID) : '';
					break;
				}
			}

			if (empty($profile_page)) {
				$permalink = home_url('/');
			} else {
				$query_arg['ref'] = urlencode($ref);
				//mode
				if (!empty($tab)) {
					$query_arg['tab'] = urlencode($tab);
				}
				//id for edit record
				if (!empty($id)) {
					$query_arg['id'] = urlencode($id);
				}
				$query_arg['identity']	= urlencode($user_identity);
				$permalink	= add_query_arg(
					$query_arg,
					esc_url($profile_page)
				);
			}

			if ($return) {
				return esc_url_raw($permalink);
			} else {
				echo esc_url_raw($permalink);
			}
		}

		/**
		 * Generate Menu Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
		public static function peer_review_system_profile_admin_menu_link($ref = '', $user_identity = '', $return = false, $tab = '', $id = '')
		{

			$profile_page = peer_review_system_get_page_uri('dashboard');

			if (empty($profile_page)) {
				$permalink = home_url('/');
			} else {
				//$query_arg['ref'] = urlencode($ref);
				//mode
				if (!empty($tab)) {
					$query_arg['tab'] = urlencode($tab);
				}

				//id for edit record
				if (!empty($id)) {
					$query_arg['article_id'] = urlencode($id);
				}

				$query_arg['useridentity'] = urlencode($user_identity);

				$permalink = add_query_arg(
					$query_arg,
					esc_url($profile_page)
				);
			}

			if ($return) {
				return esc_url_raw($permalink);
			} else {
				echo esc_url_raw($permalink);
			}
		}

		/**
		 * Generate Profile Avatar Image Link
		 *
		 * @throws error
		 * @author Amentotech <theamentotech@gmail.com>
		 * @return
		 */
		public static function peer_review_system_get_avatar()
		{
			global $current_user, $wp_roles, $userdata, $post;
			$user_identity  = $current_user->ID;
			$user_type      = apply_filters('PeerReviewSystemGetUserType', $user_identity);
			$profile_id		= peer_review_system_get_linked_profile_id($user_identity);
			$avatar			= apply_filters(
				'peer_review_system_avatar_fallback',
				peer_review_system_get_user_avatar(array('width' => 100, 'height' => 100), $user_identity),
				array('width' => 100, 'height' => 100)
			);

			if (empty($avatar)) {
				$user_dp = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/default-avatar.jpg';
				$avatar = $user_dp;
				if ($user_type == 'author' || $user_type == 'reviewer' || $user_type == 'editor') {
					if (!empty($prs_settings['default_user_profile']['id'])) {
						$placeholder_id     = !empty($prs_settings['default_user_profile']['id']) ? $prs_settings['default_user_profile']['id'] : '';
						$img_atts           = wp_get_attachment_image_src($placeholder_id, 'sj_user_profile');
						$avatar             = !empty($img_atts['0']) ? $img_atts['0'] : '';
					} else {
						$avatar = !empty($prs_settings['default_user_profile']['url']) ? $prs_settings['default_user_profile']['url'] : $user_dp;
					}
				}
			}

			if (!empty($user_type) && $user_type === 'administrator') {
				$avatar	= get_avatar_url($user_identity, array('size' => 100));
			}

			if (!empty($avatar)) {?>
				<img src="<?php echo esc_url($avatar); ?>" alt="<?php esc_attr_e('User profile', 'peer-review-system'); ?>">
			<?php
			} 
		}
	}
	new Peer_Review_System_Profile_Menu();
}
