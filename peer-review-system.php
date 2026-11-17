<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codecanyon.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Peer_Review_System
 *
 * @wordpress-plugin
 * Plugin Name:       Journal management and Peer review system
 * Plugin URI:        https://codecanyon.net/user/amentotech/portfolio
 * Description:       Journal management and Peer review system is a research related plugin where author can post their articles and editors can approve or reject the articles. It has strong module like articles, editions and reviewers
 * Version:           1.3
 * Author:            Amento Tech Pvt ltd
 * Author URI:        https://codecanyon.net/user/amentotech/portfolio
 * Text Domain:       peer-review-system
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( !function_exists( 'peer_review_system_load_last' ) ) {
	function peer_review_system_load_last() {
		$peer_review_system_file_path 		= preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
		$peer_review_system_plugin 			= plugin_basename(trim($peer_review_system_file_path));
		$peer_review_system_active_plugins 	= get_option('active_plugins');
		$peer_review_system_plugin_key 		= array_search($peer_review_system_plugin, $peer_review_system_active_plugins);
		array_splice($peer_review_system_active_plugins, $peer_review_system_plugin_key, 1);
		array_push($peer_review_system_active_plugins, $peer_review_system_plugin);
		update_option('active_plugins', $peer_review_system_active_plugins);
	}	
	add_action("activated_plugin", "peer_review_system_load_last");
}

/**
 * Currently plugin version.
 */
if ( ! defined( 'PEER_REVIEW_SYSTEM_VERSION' ) ) {
	define( 'PEER_REVIEW_SYSTEM_VERSION', '1.3' );
}
if ( ! defined( 'PEER_REVIEW_SYSTEM_DIRECTORY' ) ) {
	define( 'PEER_REVIEW_SYSTEM_DIRECTORY', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'PEER_REVIEW_SYSTEM_DIRECTORY_URI' ) ) {
	define( 'PEER_REVIEW_SYSTEM_DIRECTORY_URI', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'PEER_REVIEW_SYSTEM_ACTIVE_THEME_DIRECTORY' ) ) {
	define( 'PEER_REVIEW_SYSTEM_ACTIVE_THEME_DIRECTORY', get_stylesheet_directory() );
}
if ( ! defined( 'PEER_REVIEW_SYSTEM_BASENAME' ) ) {
	define( 'PEER_REVIEW_SYSTEM_BASENAME', plugin_basename( __FILE__ ) );
}

/**
 * Get template from plugin or theme.
 *
 * @param string $file  Template file name.
 * @param array  $param Params to add to template.
 *
 * @return string
 */
if ( ! function_exists( 'peer_review_system_load_template' ) ) {
	function peer_review_system_load_template( $file, $param = array() ) {
		extract( $param );

		if ( is_dir( PEER_REVIEW_SYSTEM_ACTIVE_THEME_DIRECTORY . '/extend/' ) ) {
			if ( file_exists( PEER_REVIEW_SYSTEM_ACTIVE_THEME_DIRECTORY . '/extend/' . $file . '.php' ) ) {
				$template_load = PEER_REVIEW_SYSTEM_ACTIVE_THEME_DIRECTORY . '/extend/' . $file . '.php';
			} else {
				$template_load = PEER_REVIEW_SYSTEM_DIRECTORY . '/' . $file . '.php';
			}
		} else {
			$template_load = PEER_REVIEW_SYSTEM_DIRECTORY . '/' . $file . '.php';
		}
		return $template_load;
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-peer-review-system-activator.php
 */
function peer_review_system_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-peer-review-system-activator.php';
	Peer_Review_System_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-peer-review-system-deactivator.php
 */
function peer_review_system_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-peer-review-system-deactivator.php';
	Peer_Review_System_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'peer_review_system_activate' );
register_deactivation_hook( __FILE__, 'peer_review_system_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'helpers/email-register-files.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-peer-review-system.php';
require plugin_dir_path( __FILE__ ) . '/admin/plugin-settings/init.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/functions.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/hooks.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/publishing.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/ajax-hooks.php';
require plugin_dir_path( __FILE__ ) . 'public/partials/woo-hooks.php';
require plugin_dir_path( __FILE__ ) . 'elementor/config.php';
require peer_review_system_load_template( 'public/partials/template-loader');
require plugin_dir_path( __FILE__ ) . 'public/partials/class-dashboard-menu.php';
require peer_review_system_load_template( 'templates/registration');
require peer_review_system_load_template( 'templates/login');
require peer_review_system_load_template( 'templates/forgot');
include peer_review_system_load_template( 'import-users/class-import-user' );
require plugin_dir_path( __FILE__ ) . 'widgets/class-buy-sell-footer.php';
require plugin_dir_path( __FILE__ ) . 'widgets/class-growing-expanding.php';

/**
 * Add http from URL
 */
if (!function_exists('peer_review_system_add_http_protcol')) {

	function peer_review_system_add_http_protcol($url) {
	  $protolcol = is_ssl() ? "https" : "http";
  
	  if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
		  $url = $protolcol . ':' . $url;
	  }
	  return $url;
	}
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function peer_review_system_run() {
	$plugin = new Peer_Review_System();
	$plugin->run();
}
peer_review_system_run();

/**
 * Load plugin textdomain.
 *
 * @since 1.0.0
 */
add_action( 'init', 'peer_review_system_load_textdomain' );
function peer_review_system_load_textdomain() {
	load_plugin_textdomain( 'peer-review-system', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}