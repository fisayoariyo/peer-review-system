<?php
/**
 * 
 * Class 'Peer_Review_System_Edition' defines the cusotm post type
 * 
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Peer_Review_System_Edition{

	/**
	 * Profiles post type
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_action('init', array(&$this, 'init_post_type'));
	}

	/**
	 * @Init post type
	*/
	public function init_post_type() {
		$this->register_posttype();
		$this->register_terms();
	}

	/**
	 *Regirster profiles post type
	*/
	public function register_posttype() {
		$labels = array(
			'name'                  => esc_html__( 'Editions', 'peer-review-system' ),
			'singular_name'         => esc_html__( 'Edition','peer-review-system' ),
			'menu_name'             => esc_html__( 'Editions', 'peer-review-system' ),
			'name_admin_bar'        => esc_html__( 'Edition', 'peer-review-system' ),
			'all_items'             => esc_html__( 'All editions', 'peer-review-system' ),
			'add_new_item'          => esc_html__( 'Add new edition', 'peer-review-system' ),
			'add_new'               => esc_html__( 'Add new edition', 'peer-review-system' ),
			'new_item'              => esc_html__( 'New edition', 'peer-review-system' ),
			'edit_item'             => esc_html__( 'Edit edition', 'peer-review-system' ),
			'update_item'           => esc_html__( 'Update edition', 'peer-review-system' ),
			'view_item'             => esc_html__( 'View edition', 'peer-review-system' ),
			'view_items'            => esc_html__( 'View edition', 'peer-review-system' ),
			'search_items'          => esc_html__( 'Search edition', 'peer-review-system' ),
		);

		$args = array(
			'labels' 				=> $labels,
			'description' 			=> esc_html__('This is where you can add new edition ', 'peer-review-system'),
			'public' 				=> true,
			'supports' 				=> array('title','editor','author', 'comments', 'excerpt','thumbnail'),
			'show_ui' 				=> true,
			'capability_type' 		=> 'post',
			'map_meta_cap' 			=> true,
			'publicly_queryable' 	=> true,
			'exclude_from_search' 	=> false,
			'hierarchical' 			=> false,
			'menu_position' 		=> 10,
			'menu_icon'				=> 'dashicons-portfolio',
			'rewrite' 				=> array('slug' => 'editions', 'with_front' => true),
			'query_var' 			=> false,
			'has_archive' 			=> false,
			'capabilities' 			=> array('create_posts' => false),
			'rest_base'             => 'editions',
			'show_in_menu'			=> 'edit.php?post_type=articles',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			
		);
		
		register_post_type( apply_filters('peer_review_system_edition_post_type_name', 'editions'), $args );
	}

	/**
	 *Regirster relations
	*/
	public function register_terms(){
		$cat_labels = array(
			'name' 					=> _x('Edition category', 'Categories for editions', 'peer-review-system' ),
			'singular_name' 		=> _x('Edition category', 'Categories for editions','peer-review-system'),
			'search_items'			=> _x('Search category', 'Categories for editions', 'peer-review-system'),
			'all_items' 			=> _x('All categories', 'Categories for editions', 'peer-review-system'),
			'parent_item' 			=> _x('Parent category', 'Categories for editions', 'peer-review-system'),
			'parent_item_colon' 	=> _x('Parent category:', 'Categories for editions', 'peer-review-system'),
			'edit_item' 			=> _x('Edit category', 'Categories for editions', 'peer-review-system'),
			'update_item' 			=> _x('Update category', 'Categories for editions', 'peer-review-system'),
			'add_new_item' 			=> _x('Add new category', 'Categories for editions', 'peer-review-system'),
			'new_item_name' 		=> _x('New category Name', 'Categories for editions', 'peer-review-system'),
			'menu_name' 			=> _x( 'Edition categories', 'Categories for editions', 'peer-review-system' ),
		);
		
		$cat_args = array(
			'hierarchical' 			=> true,
			'labels' 				=> $cat_labels,
			'show_in_quick_edit' 	=> true,
			'show_in_nav_menus' 	=> false,
			'show_admin_column' 	=> true,
			'query_var' 			=> true,
			'show_ui'               => true,
			'rewrite' 			=> array('slug' => 'edition-categories'),
		);
		register_taxonomy('edition_categories', array('editions'), $cat_args);
	}
}
new Peer_Review_System_Edition();
