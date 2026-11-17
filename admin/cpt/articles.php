<?php
/**
 * 
 * Class 'Peer_Review_System_Articles' defines the cusotm post type
 * 
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/cpt
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */
class Peer_Review_System_Articles{

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
			'name'                  => esc_html__( 'Articles', 'peer-review-system' ),
			'singular_name'         => esc_html__( 'Article','peer-review-system' ),
			'menu_name'             => esc_html__( 'Articles', 'peer-review-system' ),
			'name_admin_bar'        => esc_html__( 'Article', 'peer-review-system' ),
			'all_items'             => esc_html__( 'All articles', 'peer-review-system' ),
			'add_new_item'          => esc_html__( 'Add new article', 'peer-review-system' ),
			'add_new'               => esc_html__( 'Add new article', 'peer-review-system' ),
			'new_item'              => esc_html__( 'New article', 'peer-review-system' ),
			'edit_item'             => esc_html__( 'Edit article', 'peer-review-system' ),
			'update_item'           => esc_html__( 'Update article', 'peer-review-system' ),
			'view_item'             => esc_html__( 'View article', 'peer-review-system' ),
			'view_items'            => esc_html__( 'View article', 'peer-review-system' ),
			'search_items'          => esc_html__( 'Search article', 'peer-review-system' ),
		);

		$args = array(
			'labels' 				=> $labels,
			'description' 			=> esc_html__('This is where you can add new articles ', 'peer-review-system'),
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
			'rewrite' 				=> array('slug' => 'articles', 'with_front' => true),
			'query_var' 			=> false,
			'has_archive' 			=> false,
			'capabilities' 			=> array('create_posts' => false),
			'rest_base'             => 'articles',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			
		);
		
		register_post_type( apply_filters('peer_review_system_articles_post_type_name', 'articles'), $args );
	}

	/**
	 *Regirster relations
	*/
	public function register_terms(){
		$cat_labels = array(
			'name' 					=> _x('Article category', 'Categories for articles', 'peer-review-system' ),
			'singular_name' 		=> _x('Article category', 'Categories for articles','peer-review-system'),
			'search_items'			=> _x('Search category', 'Categories for articles', 'peer-review-system'),
			'all_items' 			=> _x('All categories', 'Categories for articles', 'peer-review-system'),
			'parent_item' 			=> _x('Parent category', 'Categories for articles', 'peer-review-system'),
			'parent_item_colon' 	=> _x('Parent category:', 'Categories for articles', 'peer-review-system'),
			'edit_item' 			=> _x('Edit category', 'Categories for articles', 'peer-review-system'),
			'update_item' 			=> _x('Update category', 'Categories for articles', 'peer-review-system'),
			'add_new_item' 			=> _x('Add new category', 'Categories for articles', 'peer-review-system'),
			'new_item_name' 		=> _x('New category Name', 'Categories for articles', 'peer-review-system'),
			'menu_name' 			=> _x( 'Article categories', 'Categories for articles', 'peer-review-system' ),
		);
		
		$cat_args = array(
			'hierarchical' 			=> true,
			'labels' 				=> $cat_labels,
			'show_in_quick_edit' 	=> true,
			'show_in_nav_menus' 	=> true,
			'show_admin_column' 	=> true,
			'query_var' 			=> true,
			'show_ui'               => true,
			'rewrite' 			=> array('slug' => 'article_categories'),
		);
		register_taxonomy('article_categories', array('articles'), $cat_args);
	}
}
new Peer_Review_System_Articles();
