<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/public
 * @author     Amento Tech Pvt ltd <info@amentotech.com>
 */
class Peer_Review_System_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_filter('PeerReviewSystemGetUserType', array(&$this, 'PeerReviewSystemGetUserType'));
		add_action('wp_head', array($this, 'PeerReviewSystemCustomCSS'));
		add_action('wp_footer', array($this, 'PeerReviewSystemCustomJS'));
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		wp_register_style('bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
		wp_register_style('feather-icons', plugin_dir_url(__FILE__) . 'css/feather.css', array(), $this->version, 'all');
		wp_register_style('fontawesome', plugin_dir_url(__FILE__) . 'css/fontawesome/fontawesome.css', array(), $this->version, 'all');
		wp_register_style('jquery-confirm', plugin_dir_url(__FILE__) . 'css/jquery-confirm.min.css', array(), $this->version, 'all');
		wp_register_style('select2', plugin_dir_url(__FILE__) . 'css/select2.min.css', array(), $this->version, 'all');
		wp_register_style('mCustomScrollbar', plugin_dir_url(__FILE__) . 'css/jquery.mCustomScrollbar.min.css', array(), $this->version, 'all');
		wp_register_style('croppie', 'https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css', array(), $this->version, 'all');
		wp_register_style('lightpick', plugin_dir_url(__FILE__) . 'css/lightpick.css', array(), $this->version, 'all');
		wp_register_style('peer-review-system-styles', plugin_dir_url(__FILE__) . 'css/main.css', array(), $this->version, 'all');

		wp_enqueue_style('bootstrap');
		wp_enqueue_style('feather-icons');
		wp_enqueue_style('fontawesome');
		wp_enqueue_style('jquery-confirm');
		wp_enqueue_style('select2');
		wp_enqueue_style('mCustomScrollbar');

		if (is_page_template('templates/dashboard.php')) {
			wp_enqueue_style('lightpick');
			wp_enqueue_style('croppie');
		}
		wp_enqueue_style('peer-review-system-styles');

		
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		global $prs_settings;
		wp_register_script('bootstrap', plugin_dir_url(__FILE__) . 'js/vendor/bootstrap.min.js', array('jquery'), $this->version, true);
		wp_register_script('jquery-confirm', plugin_dir_url(__FILE__) . 'js/vendor/jquery-confirm.min.js', array(), $this->version, true);
		wp_register_script('select2', plugin_dir_url(__FILE__) . 'js/vendor/select2.min.js', array(), $this->version, true);
		wp_register_script('mCustomScrollbar', plugin_dir_url(__FILE__) . 'js/vendor/jquery.mCustomScrollbar.concat.min.js', array('jquery'), $this->version, true);
		wp_register_script('popper', plugin_dir_url(__FILE__) . 'js/vendor/popper-core.js', array('jquery'), $this->version, true);
		wp_register_script('tippy', plugin_dir_url(__FILE__) . 'js/vendor/tippy-bundle.umd.min.js', array('jquery'), $this->version, true);
		wp_register_script('croppie', 'https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js', array('jquery', 'wp-util'), $this->version, true);
		wp_register_script('lightpick', plugin_dir_url(__FILE__) . 'js/vendor/lightpick.js', array('jquery'), $this->version, true);
		wp_register_script('peer-review-profile-settings', plugin_dir_url(__FILE__) . 'js/prs-settings.js', array('jquery', 'wp-util'), $this->version, true);
		wp_register_script('peer-review-system-public', plugin_dir_url(__FILE__) . 'js/peer-review-public.js', array('jquery'), $this->version, true);
		wp_enqueue_script('bootstrap');
		wp_enqueue_script('jquery-confirm');
		wp_enqueue_script('select2');
		wp_enqueue_script('mCustomScrollbar');
		wp_enqueue_script('popper');
		wp_enqueue_script('tippy');		
		
		if (is_page_template('templates/dashboard.php')) {
			wp_enqueue_script('lightpick');
			wp_enqueue_script('plupload');
			wp_enqueue_script('croppie');
			
		}

		$data = array(
			'ajax_nonce'    				=> wp_create_nonce('ajax_nonce'),
			'ajaxurl'       				=> admin_url('admin-ajax.php'),
			'username'      				=> esc_html__('Username required.', 'peer-review-system'),
			'valid_email'   				=> esc_html__('Valid email required', 'peer-review-system'),
			'first_name'    				=> esc_html__('First Name is required', 'peer-review-system'),
			'last_name'     				=> esc_html__('Last Name is required', 'peer-review-system'),
			'user_password' 				=> esc_html__('Password is required', 'peer-review-system'),
			'validation_title' 				=> esc_html__('Error', 'peer-review-system'),
			'validation_msg' 				=> esc_html__('Please select atleast one category.', 'peer-review-system'),
			'user_password_confirm_match'	=> esc_html__('Password and confirm password should be same', 'peer-review-system'),
			'user_agree_terms'              => esc_html__('You must agree our terms & conditions before signup.', 'peer-review-system'),
			'publish_edition_title'         => esc_html__('Publish article', 'peer-review-system'),
			'publish_edition_description'   => esc_html__('Are you sure to publish this edition.', 'peer-review-system'),
			'article_delete_title'   		=> esc_html__('Remove article', 'peer-review-system'),
			'article_delete_desc'   		=> esc_html__('Are you sure to delete this article.', 'peer-review-system'),
			'remove_edition_title'          => esc_html__('Remove edition', 'peer-review-system'),
			'remove_edition_description'    => esc_html__('Are you sure to remove this edition.', 'peer-review-system'),
			'remove_edition_description'    => esc_html__('Are you sure to remove this edition.', 'peer-review-system'),
			'error_title'    				=> esc_html__('Oops!', 'peer-review-system'),
			'message_error'    				=> esc_html__('Something went wrong, please try again.', 'peer-review-system'),
			'default_file_extensions'       => !empty( $prs_settings['default_file_extensions'] ) ? $prs_settings['default_file_extensions'] : '',
			'upload_size'                   => peer_review_system_max_upload_size_plupload(),
			'upload_max_file_size'          => peer_review_system_max_upload_size_label(),
			'file_size_error'               => sprintf(
				esc_html__('File size must not exceed %s.', 'peer-review-system'),
				peer_review_system_max_upload_size_label()
			),

			
		);
		
		wp_localize_script('peer-review-system-public', 'scripts_vars', $data);
		wp_enqueue_script('peer-review-system-public');
		if (is_page_template('templates/dashboard.php')) {
			wp_enqueue_script('peer-review-profile-settings');
		}
	}

	/**
	 * get prs user type
	 *
	 * @since    1.0.0
	 */
	public function PeerReviewSystemGetUserType($userId = 0)
	{
		if (!empty($userId)) {
			$user_meta = get_userdata($userId);
			$roles		= !empty($user_meta->roles) ? $user_meta->roles : '';

			if (!empty($roles) && in_array('article_reviewer',$roles)) {
				return 'reviewer';
			} else if (!empty($roles) && in_array('article_author',$roles)) {
				return 'author';
			} else if (!empty($roles) && in_array('article_editor',$roles)) {
				return 'editor';
			} else if (!empty($roles) && in_array('article_publisher',$roles)) {
				return 'publisher';
			} else if (!empty($roles) && in_array('subscriber',$roles)) {
				return 'subscriber';
			} else if (empty($user_type)) {
				
				if (!empty($data->roles[0]) && $data->roles[0] == 'administrator') {
					return 'administrator';
				}
			}
		}
		return 'administrator';
	}

	/**
	 * Custom CSS
	 *
	 * @since    1.0.0
	 */
	public function PeerReviewSystemCustomCSS() {
		global $prs_settings;
		$custom_css	= !empty($prs_settings['custom_css']) ? $prs_settings['custom_css'] : '';
		if(!empty($custom_css)){?>
			<style>
				<?php echo do_shortcode($custom_css);?>
			</style>
		<?php }
	}
	
	/**
	 * Custom CSS
	 *
	 * @since    1.0.0
	 */
	public function PeerReviewSystemCustomJS() {
		global $prs_settings;
		$custom_js	= !empty($prs_settings['custom_js']) ? $prs_settings['custom_js'] : '';
		if(!empty($custom_js)){?>
			<script>
				<?php echo do_shortcode($custom_js);?>
			</script>
		<?php }
	}
}
