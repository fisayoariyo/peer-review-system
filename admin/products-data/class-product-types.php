<?php
namespace ProductTypes;
/**
 * 
 * Class 'Peer_Review_System_Admin_CPT_Product_Types' defines the product post type custom types
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/products_data
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
class Peer_Review_System_Admin_Products_Data_Product_Types {

	/**
	 * Add woocommerce filter 'product_type_selector' to define custom product types.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function __construct() {
		add_filter( 'product_type_selector', array($this, 'peer_review_system_add_custom_product_type'),999 );
	}

	/**
	* Add to product type drop down.
	*/
	function peer_review_system_add_custom_product_type( $product_types ){
		$product_types['articles_product']	= apply_filters('peer_review_system_product_type_article_title', esc_html__('Article product', 'peer-review-system'));
 		 
		return $product_types;
	}
}

new Peer_Review_System_Admin_Products_Data_Product_Types();
