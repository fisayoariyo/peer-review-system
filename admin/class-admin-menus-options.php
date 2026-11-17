<?php

/**
 * 
 * Class 'Peer_Review_System_Admin_Menu_Options' defines the product post type custom taxonomy languages
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Peer_Review_System_Admin_Menu_Options
{

	/**
	 * Language Taxonomy
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct()
	{
		add_action('init', array(&$this, 'menus_settings'));
		add_action('admin_footer', array(&$this, 'peer_review_system_menu_meta'), 999);
	}

	/**
	 * @Init taxonomy
	 */
	public function menus_settings()
	{
		/* add extra data */		
		add_action('wp_update_nav_menu_item', array(&$this, 'peer_review_system_menus_meta_update'), 10, 3);
	}

	/**
	 * generate meta fields
	 */
	public function peer_review_system_menu_meta()
	{
		$screen = get_current_screen();		
		$recently_edited = absint( get_user_option( 'nav_menu_recently_edited' ) );		
		$nav_menu_selected_id = isset( $_REQUEST['menu'] ) ? (int) $_REQUEST['menu'] : $recently_edited;

		$checked	= '';
		if(!empty($nav_menu_selected_id)){
			$header_menu	= get_term_meta($nav_menu_selected_id, 'main_header_menu', true);
			if (!empty($header_menu) && $header_menu == 'yes') {
				$checked	= 'checked';
			}
		}
		
		if(!empty($screen->id) && $screen->id == 'nav-menus'){
			wp_enqueue_script('wp-util');
			?>
			<script type="text/html" id="tmpl-prs-menus-settings">			
				<div class="main-menu-settings">
					<h3><?php esc_html_e('Menu Settings', 'peer-review-system'); ?></h3>
					<fieldset class="menu-settings-group main-header-menu-settings">
						<legend class="menu-settings-group-name howto"><?php esc_html_e('Main menu', 'peer-review-system');?></legend>
						<div class="menu-settings-input checkbox-input">
							<input type="hidden" name="main_header_menu" value="no" />
							<input type="checkbox" name="main_header_menu" value="yes" <?php echo esc_attr($checked); ?> />
							<label><?php esc_html_e('Main header menu', 'peer-review-system'); ?></label>
						</div>
					</fieldset>			
				</div>
			</script>
			<?php
		}		
	}

	/**
	 * update meta fields
	 */
	public function peer_review_system_menus_meta_update($menu_id, $menu_item_db_id, $args)
	{	
		if (!empty($_POST['main_header_menu']) && $_POST['main_header_menu']) {
			update_term_meta($menu_id, 'main_header_menu', $_POST['main_header_menu']);
		}
	}
}

new Peer_Review_System_Admin_Menu_Options();
