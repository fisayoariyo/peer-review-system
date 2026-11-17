<?php
/**
 * Invoices listing
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Profile_Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $current_user;
if (!class_exists('WooCommerce')) {
    do_action('peer_review_system_woocommerce_install_notice');
    return;
}

if (!empty($args) && is_array($args)) {
	extract($args);
}

$user_id    = $current_user->ID;
$user_id    = !empty($user_id) ? intval($user_id) : 0 ;

$sort_by        = !empty($_GET['sort_by']) ? esc_html($_GET['sort_by']) : '';
$posts_per_page = get_option('posts_per_page') ? get_option('posts_per_page') : 10;
$pg_page        = get_query_var('page') ? get_query_var('page') : 1;
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1;
$paged          = max($pg_page, $pg_paged);
$order_arg      = array(
    'page'      => $paged,
    'orderby'   => 'date',
    'customer'  => $user_id,  
    'paginate'  => true,
    'limit'     => $posts_per_page,
);

if( !empty($sort_by) ){
    if($sort_by == 'orderby_asc'){
        $order_arg['order']  = 'ASC';
    } elseif($sort_by == 'payoneer' || $sort_by == 'stripe'){
        $order_arg['payment_method']  = $sort_by;
       
    } elseif($sort_by == 'orderby_desc'){
        $order_arg['order']  = 'DESC';
    }    
}

$customer_orders = wc_get_orders( $order_arg );
?>
<div class="sj-invoice-listings">
    <div class="sj-dbwrapper">
        <div class="sj-dbtitle">
            <h3><?php esc_html_e('Invoices & bills', 'peer-review-system');?></h3>
            <form id="invoice-search-form" action="<?php echo esc_url( $current_page_link ); ?>">
                <input type="hidden" name="identity" value="<?php echo esc_attr($user_identity); ?>">  
                <div class="sj-selectwrapper">
                    <span><?php esc_html_e('Sort by', 'peer-review-system');?>:</span>
                    <div class="sj-select">
                        <select name="sort_by" id="sj-sort-articles" class="form-control invoice-sort-by">                           
                            <option value=""><?php esc_html_e('All invoices', 'peer-review-system');?></option>
                            <option value="orderby_desc" <?php if(!empty($sort_by) && $sort_by == 'orderby_desc'){echo esc_attr('selected');}?>><?php esc_html_e('New to old', 'peer-review-system');?></option>
                            <option value="orderby_asc" <?php if(!empty($sort_by) && $sort_by == 'orderby_asc'){echo esc_attr('selected');}?>><?php esc_html_e('Old to new', 'peer-review-system');?></option>
                        </select>
                    </div>
                </div>
            </form>
        </div>        
    </div>
     <?php       
        if (!empty($customer_orders->orders)) {?>
            <div class="sj-invoicestable">
                <table class="table dhb-table">
                    <thead>
                        <tr>
                            <th scope="col"><?php esc_html_e('Client name', 'peer-review-system');?></th>
                            <th scope="col"><?php esc_html_e('Invoice date', 'peer-review-system');?></th>
                            <th scope="col"><?php esc_html_e('Amount', 'peer-review-system');?></th>
                            <th scope="col"><?php esc_html_e('Action', 'peer-review-system');?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($customer_orders->orders)) {
                            $count_post = count($customer_orders->orders);
                            foreach ($customer_orders->orders as $order) {
                                $data_created = $order->get_date_created();                                
                                $customer_name  = $order->get_formatted_billing_full_name();
                                $order_total  = $order->get_total();
                                $order_total  = !empty($order_total) ? $order_total : 0;
                                $invoice_url  = add_query_arg(array('mode' => 'detail', 'id'=>intval($order->get_id())), $current_page_link);
                                ?>
                                <tr>
                                    <td data-label="<?php esc_attr_e('Client name', 'peer-review-system');?>"><a href="javascript:void(0);"><?php echo esc_html($customer_name); ?></a></td>
                                    <td data-label="<?php esc_attr_e('Invoice #', 'peer-review-system');?>"><?php echo wc_format_datetime( $order->get_date_created(), get_option( 'date_format' ) . ', ' . get_option( 'time_format' ) );?></td>
                                    <td data-label="<?php esc_attr_e('Amount', 'peer-review-system');?>"><?php echo peer_review_system_price_format($order_total);?></td>
                                    <td data-label="<?php esc_attr_e('Action', 'peer-review-system');?>"><a class="sj-linksm" href="<?php echo esc_url($invoice_url); ?>"><?php esc_html_e('View detail', 'peer-review-system');?> <i class="icon icon-chevron-right"></i></a></td>
                                </tr>                       
                            <?php }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        <?php } else {?>
            <div class="sj-bookings sj-booking-epmty-field">
                <h4><?php esc_html_e('Uh ho! No invoice available to show', 'peer-review-system'); ?></h4>
                <p><?php esc_html_e('We\'re sorry but there is no invoices available to show today', 'peer-review-system'); ?></p>
            </div>
            <?php
        }?>
    <?php if (!empty($customer_orders->orders)) {
        peer_review_system_paginate($customer_orders);
    }?>
</div>