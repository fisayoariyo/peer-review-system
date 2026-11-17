<?php

/**
 * Fired during plugin activation
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/includes
 * @author     Amento Tech Pvt ltd <info@amentotech.com>
 */
class Peer_Review_System_Activator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	*/
	public static function activate()
	{
		$article_user_roles = array(
			'article_reviewer'  => 'Article Reviewer',
			'article_author'    => 'Article Author',
			'article_editor'    => 'Article Editor',
			'article_publisher' => 'Publishing Team',
		);

		foreach ($article_user_roles as $key => $role_val) {
			if (!get_role($key)) {
				add_role(
					$key,
					$role_val,
					array(
						'read'                     => true,
						'delete_posts'             => false,
						'delete_published_posts'   => false,
						'edit_posts'               => true,
						'publish_posts'            => true,
						'upload_files'             => true,
						'edit_pages'               => false,
						'edit_published_pages'     => false,
						'publish_pages'            => false,
						'delete_published_pages'   => false,
					)
				);
			}
		}
	}
}
