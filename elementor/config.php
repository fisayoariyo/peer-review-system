<?php
/**
 * Elementor Page builder config
 *
 * This file will include all global settings which will be used in all over the plugin,
 * It have gatter and setter methods
 *
 * @link              https://themeforest.net/user/amentotech/portfolio
 * @since             1.0.0
 * @package           Peer_Review_System
 *
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die('No kiddies please!');
}


if( !class_exists( 'Peer_Review_System_Elementor' ) ) {

	final class Peer_Review_System_Elementor{
		private static $_instance = null;
		
		/**
		 *
		 * @since    1.0.0
		 * @access   static
		 * @var      string    Peer_Review_System
		 */
        public function __construct() {
            add_action( 'elementor/elements/categories_registered', array( &$this, 'prs_init_elementor_widgets' ) );
            add_action( 'init', array( &$this, 'elementor_shortcodes' ),  20 );
        }
		/**
		 * class init
         * @since 1.1.0
         * @static
         * @var      string    Peer_Review_System
         */
        public static function instance () {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
		
		/**
		 * Add category
		 * @since    1.0.0
		 * @access   static
		 * @var      string    Peer_Review_System
		 */
        public function prs_init_elementor_widgets( $elements_manager ) {
            $elements_manager->add_category(
                'peer-review-system-elements',
                [
                    'title' => esc_html__( 'Peer Review System Elements', 'peer-review-system' ),
                    'icon'  => 'fa fa-plug',
                ]
            );
        }

        /**
		 * Add widgets
		 * @since    1.0.0
		 * @access   static
		 * @var      string    Peer_Review_System
		 */
        public function elementor_shortcodes() {
			$dir = PEER_REVIEW_SYSTEM_DIRECTORY;
			$scan_shortcodes = glob("$dir/elementor/shortcodes/*");
			foreach ($scan_shortcodes as $filename) {
				$file = pathinfo($filename);
				
				if( !empty( $file['filename'] ) ){
					@include peer_review_system_load_template( '/elementor/shortcodes/'.$file['filename'] );
				} 
			}
        }
		 
	}
}

//Init class
if ( did_action( 'elementor/loaded' ) ) {
    Peer_Review_System_Elementor::instance();
}