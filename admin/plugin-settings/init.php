<?php
/**
 * Theme Settings
 *
 * @package     prs
 * @subpackage  prs/admin/Plugin_Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

if ( ! class_exists( 'Redux' ) ) { return;}

$opt_name 	= "prs_settings";
$opt_name   = apply_filters( 'prs_settings_option_name', $opt_name );
$theme 		= wp_get_theme();

$args = array(
    'opt_name'    		=> $opt_name,
    'display_name' 		=> $theme->get('Name') ,
    'display_version' 	=> $theme->get('Version') ,
    'menu_type' 		=> 'menu',
    'allow_sub_menu' 	=> true,
    'menu_title' 		=> esc_html__('PRS settings', 'peer-review-system'),
	'page_title'        => esc_html__('PRS settings', 'peer-review-system') ,
    'google_api_key' 	=> '',
    'google_update_weekly' => false,
    'async_typography' 	   => true,
    'admin_bar' 		=> true,
    'admin_bar_icon' 	=> '',
    'admin_bar_priority'=> 50,
    'global_variable' 	=> $opt_name,
    'dev_mode' 			=> false,
    'update_notice' 	=> false,
    'customizer' 		=> false,
    'page_priority' 	=> null,
    'page_parent' 		=> 'themes.php',
    'page_permissions'  => 'manage_options',
    'menu_icon' 		=> 'dashicons-performance',
    'last_tab' 			=> '',
    'page_icon' 		=> 'icon-themes',
    'page_slug' 		=> 'prs_options',
    'save_defaults' 	=> true,
    'default_show' 		=> false,
    'default_mark' 		=> '',
    'show_import_export' => true
);
 
Redux::setArgs ($opt_name, $args);


/**
 * @init            Return role types
 * @package         Amentotech
 * @since           1.0
 * @desc            If plugin activated return true else false
 */
if (!function_exists('peer_review_system_roles_list')) {
    add_filter('peer_review_system_roles_list', 'peer_review_system_roles_list', 10, 1);
    function peer_review_system_roles_list($roles){
        $roles  = array(
            'subscriber'        => esc_html__('Subscriber', 'peer-review-system'),
            'article_author'    => esc_html__('Author', 'peer-review-system'),
            'article_reviewer'  => esc_html__('Reviewer', 'peer-review-system'),
            'article_editor'    => esc_html__('Editor', 'peer-review-system'),
            'article_publisher' => esc_html__('Publishing team', 'peer-review-system'),
        );

        return $roles;
    }
}

$scan = glob(PEER_REVIEW_SYSTEM_DIRECTORY."/admin/plugin-settings/settings/*");
foreach ( $scan as $path ) {
	include $path;
}