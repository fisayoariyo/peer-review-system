<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/admin
 * @author     Amento Tech Pvt ltd <info@amentotech.com>
 */
class Peer_Review_System_Admin
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		foreach (glob(plugin_dir_path(__FILE__) . "cpt/*.php") as $file) {
			include_once $file;
		}
		require plugin_dir_path(__FILE__)  . 'partials/peer-review-admin-display.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin-menus-options.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/products-data/class-product-types.php';

		/* register custom roles */
		add_action('init', array($this, 'peer_review_system_register_custom_role'));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_enqueue_style('jquery-confirm',  PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/css/jquery-confirm.min.css', array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/peer-review-system-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		wp_enqueue_script('jquery-confirm', PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/js/vendor/jquery-confirm.min.js', array(), $this->version, true);
		wp_enqueue_script('peer-review-admin', plugin_dir_url(__FILE__) . 'js/peer-review-system-admin.js', array('jquery'), $this->version, false);
		wp_enqueue_media();
		$data = array(
			'ajax_nonce'					=> wp_create_nonce('ajax_nonce'),
			'ajaxurl'						=> admin_url('admin-ajax.php'),
			'import_message' 				=> esc_html__('Are you sure, you want to import users?', 'peer-review-system'),
			'import'						=> esc_html__('Import users', 'peer-review-system'),
			'reject_account'				=> esc_html__('Reject user account', 'peer-review-system'),
			'reject_account_message'		=> esc_html__('Are you sure, you want to reject this account? After reject, this account will no longer visible in the search listing', 'peer-review-system'),
			'approve_account'				=> esc_html__('Approve user account', 'peer-review-system'),
			'approve_account_message'		=> esc_html__('Are you sure, you want to approve this account? An email will be sent to this user.', 'peer-review-system'),
			'yes'							=> esc_html__('Yes', 'peer-review-system'),
			'close'							=> esc_html__('Close', 'peer-review-system'),
			'no' 							=> esc_html__('No', 'peer-review-system'),
			'accept' 						=> esc_html__('Accept', 'peer-review-system'),
			'reject' 						=> esc_html__('Reject', 'peer-review-system'),
			'select_file'					=> esc_html__('Select File', 'peer-review-system'),
			'add_file'						=> esc_html__('Add File', 'peer-review-system'),
			'reason'						=> esc_html__('Please add reason why you want to reject user uploaded documents?', 'peer-review-system'),
			'circle_loader'					=> plugin_dir_url(__FILE__) . 'public/images/circle-loader.png',
		);
		wp_localize_script('peer-review-admin', 'admin_scripts_vars', $data);
	}
	
	/**
	 * Register custom roles
	 */
	public function peer_review_system_register_custom_role()
	{
		$article_user_roles = array(
			'article_reviewer' 	=> __('Article Reviewer', 'peer-review-system'),
			'article_author' 	=> __('Article Author', 'peer-review-system'),
			'article_editor' 	=> __('Article Editor', 'peer-review-system'),
			'article_publisher' => __('Publishing Team', 'peer-review-system'),
		);
		foreach ($article_user_roles as $key => $role_val) {
			add_role(
				$key,
				$role_val,
				array(
					'read'  					=> true,
					'delete_posts'  			=> false,
					'delete_published_posts' 	=> false,
					'edit_posts'   				=> true,
					'publish_posts' 			=> true,
					'upload_files'  			=> true,
					'edit_pages'  				=> false,
					'edit_published_pages'  	=>  false,
					'publish_pages'  			=> false,
					'delete_published_pages' 	=> false,
				)
			);
		}
	}
}
