<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/includes
 * @author     Amento Tech Pvt ltd <info@amentotech.com>
 */
class Peer_Review_System_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'peer-review-system',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
