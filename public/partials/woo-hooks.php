<?php

/**
 * Order options
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WooCommerce')) {
	if (!function_exists('peer_review_system_order_option')) {
		add_action( 'init', 'peer_review_system_order_option' );
		function peer_review_system_order_option(){
			add_filter( 'woocommerce_cod_process_payment_order_status','peer_review_system_update_order_status', 10, 2 );
			add_filter( 'woocommerce_cheque_process_payment_order_status','peer_review_system_update_order_status', 10, 2 );
			add_filter( 'woocommerce_bacs_process_payment_order_status','peer_review_system_update_order_status', 10, 2 );
			if( is_admin() ){
				add_action( 'woocommerce_order_status_completed','peer_review_system_payment_complete',10,1 );
			}
		}
	}
}


/**
 * Display order detail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_display_order_data_success')) {
	add_action( 'woocommerce_thankyou', 'peer_review_system_display_order_data_success', 20 );
	function peer_review_system_display_order_data_success( $order_id ) {
        global $woocommerce;
		$order = new WC_Order( $order_id );
		$items = $order->get_items();
		$order_detail 	= get_post_meta( $order_id, 'cus_woo_product_data', true );
		$article_id 	= get_post_meta( $order_id, 'article_id', true );
        if( !empty( $order_detail ) && !empty( $article_id )) {
            $article_data   = !empty($order_detail['article_data']) ? $order_detail['article_data'] : array();
            ?>
            <div class="sj-haslayout">
                <div class="cart-data-wrap">
                    <h3><?php esc_html_e('Summary','peer-review-system');?></h3>
                    <div class="selection-wrap">
                    <?php peer_review_system_get_template_part('dashboard/article', 'summary',array('article_data'=>$article_data));?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

/**
 * Complete order
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('woocommerce_order_item_name')) {
    add_filter('woocommerce_order_item_name', 'woocommerce_order_item_name', 10, 3 );
    function woocommerce_order_item_name($product_permalink, $item, $is_visible) {
        $order_detail   = wc_get_order_item_meta( $item->get_id(), 'cus_woo_product_data', true ); 

        if( !empty( $order_detail )) {
            remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );
            $article_data   = !empty($order_detail['article_data']) ? $order_detail['article_data'] : array();
            if( !empty($article_data['article_title'])){
                $article_id     = $article_data['article_id'];
                $article_title  = $article_data['article_title'];
                return wp_kses_post( sprintf( '<a href="%s">%s</a>', get_the_permalink($article_id), $article_title ) );                
            }            
            return $product_permalink;            
        }
    }
}


/**
 * Complete order
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_payment_complete')) {
    add_action('woocommerce_payment_complete', 'peer_review_system_payment_complete',10,1 );
    function peer_review_system_payment_complete($order_id) {
		global $current_user;

		if (class_exists('WooCommerce')) {
			$order 			= wc_get_order($order_id);
			$user 			= $order->get_user_id( );
			$items 			= $order->get_items();
			$current_date 	= current_time('mysql');
			//Update order status
			$order->update_status( 'completed' );
			$order->save();

			if(!empty($items)){
				foreach ($items as $key => $item) {
					if ($user) {
						$order_detail   = wc_get_order_item_meta( $key, 'cus_woo_product_data', true );
						if( !empty($order_detail)){
							peer_review_system_update_services_data( $order_id,$order_detail,$current_user->ID);
						}
						update_post_meta($order_id,'prs_order_date',date('Y-m-d H:i:s', strtotime($current_date)));
						update_post_meta($order_id,'prs_order_date_gmt',strtotime($current_date));
					}
				}
			}
		}
    }
}

/**
 * Update User Hiring payment
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('peer_review_system_update_services_data')) {
    function peer_review_system_update_services_data( $order_id,$order_detail,$user_id) {
        $article_id = !empty($order_detail['article_id']) ? $order_detail['article_id'] : 0;
		update_post_meta( $order_id, 'article_id',$article_id );
	}
}

/**
 * Price override
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_apply_custom_price_to_cart_item')) {
	add_action( 'woocommerce_before_calculate_totals', 'peer_review_system_apply_custom_price_to_cart_item', 99 );
	function peer_review_system_apply_custom_price_to_cart_item( $cart_object ) {
		if( !WC()->session->__isset( "reload_checkout" )) {
			foreach ( $cart_object->cart_contents as $key => $value ) {
				$product 		= $value['data'];
				$product_id		= !empty($value['product_id']) ? $value['product_id'] : 0;

                if( isset( $value['article_data']['article_price'] ) ){
                    $bk_price = floatval( $value['article_data']['article_price'] );
                    $value['data']->set_price($bk_price);
                }
			}
		}
	}
}

/**
 * Add meta on order item
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_woo_convert_item_session_to_order_meta')) {
	add_action( 'woocommerce_new_order_item', 'peer_review_system_woo_convert_item_session_to_order_meta',  1, 3 );
	function peer_review_system_woo_convert_item_session_to_order_meta( $item_id, $item, $order_id ) {
		$order              = wc_get_order($order_id);
		$order_total	    = $order->get_total();
		$order_total  	    = !empty($order_total) ? $order_total : 0.0;
		$order_total_tax	= $order->get_total_tax();
		$order_total_tax    = !empty($order_total_tax) ? $order_total_tax : 0.0;

        if ( !empty( $item->legacy_values['cart_data'] ) ) {
            wc_add_order_item_meta( $item_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
            update_post_meta( $order_id, 'cus_woo_product_data', $item->legacy_values['cart_data'] );
        }

        if ( !empty( $item->legacy_values['article_id'] ) ) {
            update_post_meta( $order_id, 'article_id', $item->legacy_values['article_id'] );
        }
    }
}

/**
 * Display order detail
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_display_order_data')) {
	add_action( 'peer_review_system_display_order_data', 'peer_review_system_display_order_data', 20 );
	add_action( 'woocommerce_view_order', 'peer_review_system_display_order_data', 20 );
	function peer_review_system_display_order_data( $order_id ) {
        global $woocommerce;
		if (class_exists('WooCommerce')) {
			$order = new WC_Order( $order_id );
			$items = $order->get_items();
			$order_detail 	= get_post_meta( $order_id, 'cus_woo_product_data', true );
			$article_id 	= get_post_meta( $order_id, 'article_id', true );

			if( !empty( $order_detail ) && !empty( $article_id )) {
				$article_data   = !empty($order_detail['article_data']) ? $order_detail['article_data'] : array();
				?>
				<div class="sj-haslayout">
					<div class="cart-data-wrap">
						<h3><?php esc_html_e('Summary','peer-review-system');?></h3>
						<div class="selection-wrap">
						<?php peer_review_system_get_template_part('dashboard/article', 'summary',array('article_data'=>$article_data));?>
						</div>
					</div>
				</div>
			    <?php
			}
		}
    }
}

/**
 * Add data in checkout
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_checkout_after_customer_details')) {
    remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 ); 
	add_filter( 'woocommerce_checkout_after_customer_details', 'peer_review_system_checkout_after_customer_details', 10, 1 );
	function peer_review_system_checkout_after_customer_details() {
        global $product,$woocommerce;
		$cart_data = WC()->session->get( 'cart', null );
        if( !empty( $cart_data ) ) {
			foreach( $cart_data as $key => $cart_items ){
                if( !empty( $cart_items['article_data'] )) {
                    $article_data   = !empty($cart_items['article_data']) ? $cart_items['article_data'] : array(); ?>
                    <div class="sj-haslayout">
                        <div class="cart-data-wrap">
                            <h3><?php esc_html_e('Summary','peer-review-system');?></h3>
                            <div class="selection-wrap">
                            <?php peer_review_system_get_template_part('dashboard/article', 'summary',array('article_data'=>$article_data));?>
                            </div>
                        </div>
                    </div>
                <?php
                }
            }
        }
    }
}

/**
 * Service checkout
 *
 * @since    1.0.0
 */
if(!function_exists('peer_review_system_article_checkout')){
    function peer_review_system_article_checkout(){
        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $json        = array();
        $userId      = !empty($_POST['userId']) ? intval($_POST['userId']) : 0;
        $article_id  = !empty($_POST['article_id']) ? intval($_POST['article_id']) : 0; 
        $author_id   = !empty($_POST['author_id']) ? intval($_POST['author_id']) : 0; 

        $validation = array(
            'userId'        => esc_html__('Something went wrong','peer-review-system'),
            'article_id'    => esc_html__('Something went wrong','peer-review-system'),
            'author_id'     => esc_html__('Something went wrong','peer-review-system'),
        );

        foreach($validation as $key => $val ){
            if( empty($_POST[$key]) ){
                $json['type']           = 'error';
                $json['title'] 			= esc_html__('Oopps', 'peer-review-system');
                $json['message_desc']   = $val;
                wp_send_json($json);
            }
        }

        $article_status = get_post_status ( $article_id );
        $article_status = !empty($article_status) ? esc_html($article_status) : '';
        if(!empty($article_status) && $article_status == 'publish'){

            $article            = get_post($article_id);
            $article_price      = get_post_meta($article_id, 'article_price', true);
            $article_data       = array(
                'article_id'        => $article_id,
                'article_title'     => $article->post_title,
                'article_author'    => $article->post_author,
                'article_price'     => $article_price,
            );
            if ( class_exists('WooCommerce') ) {
                global $woocommerce;
                check_prerequisites($userId);
                $woocommerce->cart->empty_cart();
                $product_id    = peer_review_system_instructor_service_create($article_price);
                $cart_meta['product_id']	= $product_id;
                $cart_meta['total_amount']	= $article_price;
                $cart_meta['price']         = $article_price;
                $cart_meta['article_data']	= $article_data;
                $cart_meta['article_id']    = $article_id;
                $cart_meta['userId']		= $userId;
                $cart_data = array(
                    'cart_data'             => $cart_meta,
                    'price'                 => $article_price,
                    'article_id'            => $article_id,
                    'product_id'            => $product_id,
                    'article_data'          => $article_data,
                    'userId'                => $userId,
                );
    
                $woocommerce->cart->empty_cart();
                $cart_item_data = $cart_data;
                WC()->cart->add_to_cart($product_id, 1, null, null, $cart_item_data);
                session_start();
                $_SESSION["redirect_type"]  = 'service_checkout';
                $_SESSION["redirect_url"]   = !empty($_POST['url']) ? esc_url_raw($_POST['url']): '';
                $json['title'] 				= esc_html__('All set to go', 'peer-review-system');
                $json['type'] 		        = 'success';
                $json['message']   			= esc_html__("You're now redirecting to the checkout page", 'peer-review-system');
                $json['checkout_url']		= wc_get_checkout_url();
                $json['cart_item_data']		= $cart_item_data;
                wp_send_json($json);
    
            } else {
                $json['type']       = 'error';
                $json['title']		= esc_html__('Failed!', 'peer-review-system');
                $json['message']    = esc_html__('Please install WooCommerce plugin to process this order', 'peer-review-system');
                wp_send_json($json);
            }

        } else {
            $json['type']       = 'error';
            $json['title']		= esc_html__('Failed!', 'peer-review-system');
            $json['message']    = esc_html__('Article is not published yet.', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_peer_review_system_article_checkout', 'peer_review_system_article_checkout');
}
