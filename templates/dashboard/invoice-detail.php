<?php
/**
 * Student invoice detail
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Profile_Settings/student
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
if (!class_exists('WooCommerce')) {
    do_action('peer_review_system_woocommerce_install_notice');
    return;
}

if (!empty($args) && is_array($args)) {
	extract($args);
}
global $current_user, $prs_settings;
$order_id       = !empty($id) ? intval($id) : '';
$pdfDownload    = !empty($_GET['pdfDownload']) ? intval($_GET['pdfDownload']) : '';
/* if order Id empty */
if (empty($order_id)) {
    return;
}

$user_id    = $current_user->ID;
$user_id    = !empty($user_id) ? intval($user_id) : 0 ;
$article_id = '';

$order          = wc_get_order($order_id);
$data_created   = $order->get_date_created();
$data_created   = date_i18n(get_option( 'date_format' ), strtotime($data_created));
$get_total      = $order->get_total();
$store_address  = get_option( 'woocommerce_store_address' );
$admin_id       = peer_review_system_get_administrator_user_id();
$user_data      = get_userdata($admin_id);

$article_name   = '';
$order_detail 	= get_post_meta( $order->get_id(), 'cus_woo_product_data', true );
$article_data	= !empty($order_detail['article_data']) ? $order_detail['article_data'] : array();

if( !empty($article_data['article_title'])){
    $article_name   = $article_data['article_title'];
}

$article    = '';
$article_author_id  = '';
if( !empty($article_data['article_id'])){
    $article_id         = $article_data['article_id'];
    $article            = get_post($article_id);
    $article_author_id  = get_post_field('post_author', $article_id);
}

$admin_email        = !empty($user_data->user_email) ? $user_data->user_email : '' ;
$admin_name         = !empty($user_data->display_name) ? $user_data->display_name : '' ;
$billing_address    = $order->get_billing_address_1();
$billing_email      = $order->get_billing_email();
$bill_to_name       = $order->get_billing_first_name();
$payment_type       = get_post_meta( $order_id, 'payment_type',true );
$payment_type       = !empty($payment_type) ? $payment_type : '';
$total_tax 	        = $order->get_total_tax();
$total_tax          = !empty($total_tax) ? $total_tax : 0;
$invoice_terms      = !empty($prs_settings['invoice_terms']) ? sanitize_text_field($prs_settings['invoice_terms']) : '';
$admin_commision    = '';
$refunded           = $order->get_total_refunded();

$author_address     = $store_address; 
$order_total        = $order->get_total();
$order_total        = !empty($order_total) ? $order_total : 0;
 
?>
<div class="sj-instructor-invoice-detail"> 
    <div class="sj-dbwrapper">
        <?php if(empty($pdfDownload) || (!empty($pdfDownload) && $pdfDownload !== 1)){
            $invoice_url  = add_query_arg(array('mode' => 'detail', 'id'=>intval($order->get_id()), 'pdfDownload' => 1), $current_page_link);
            $invoice_pdfdownload_url  = !empty($invoice_url) ? $invoice_url : '#';
            ?>
            <div class="sj-dbtitle">
                <h3><?php esc_html_e('Invoice preview','peer-review-system') ?></h3>
                <div class="sj-invoivebtns">
                    <a href="<?php echo esc_url($invoice_pdfdownload_url);?>" data-id="<?php echo intval($article_id)?>" data-userid="<?php echo intval($user_id)?>" data-author-id = "<?php echo intval($article_author_id)?>" class="sj-primbtn sj-greenbtn sj-approve-btn sj-article-download-btn"> <i class="icon icon-download"></i><span><?php esc_html_e('Download article','peer-review-system') ?></span></a>
                </div>
            </div>
        <?php }?>
        <div class="sj-invoicedetail" id="divToPrint">
            <div class="sj-boxlg sj-boxdark">
                <div class="sj-incoiveinfo">
                    <?php if(!empty($order_id)){?>
                        <h4>
                            <?php
                            esc_html_e('Invoice # ','peer-review-system');
                            echo intval($order_id);
                            ?>
                        </h4>
                    <?php } ?>
                    <?php if(!empty($admin_name) || !empty($admin_email) || !empty($author_address)){?>
                        <ul class="sj-userinvoice">
                            <?php if(!empty($admin_name)){?>
                                <li><?php echo esc_html($admin_name)?><i class="icon icon-user"></i></li>
                            <?php } ?>
                            <?php if(!empty($admin_email)){?>
                                <li><?php echo esc_html($admin_email)?> <i class="icon icon-mail"></i></li>
                            <?php } ?>
                            <?php if(!empty($author_address)){?>
                                <li><?php echo do_shortcode(nl2br($author_address)) ?> <i class="icon icon-map-pin"></i></li>
                            <?php } 
                            ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
            <div class="sj-boxlg sj-billadd">
                 <ul class="sj-billinfo">
                    <?php if(!empty( $order->get_formatted_billing_full_name())) {?>
                        <li>
                            <h6><?php esc_html_e('Billed to','peer-review-system') ?></h6>
                            <h5><?php echo esc_html($order->get_formatted_billing_full_name());?></h5>
                        </li>
                    <?php } ?>
                    <?php if(!empty($data_created)){?>
                        <li>
                            <h6><?php esc_html_e('Date','peer-review-system')?></h6>
                            <?php if(!empty($data_created)){?>
                                <h5><?php echo esc_html($data_created) ?></h5>
                            <?php } ?>
                        </li>
                    <?php } ?>
                    <?php if(!empty($billing_address)){?>
                        <li>
                            <h6><?php esc_html_e('Address','peer-review-system') ?></h6>
                            <h5><?php echo do_shortcode($billing_address) ?></h5>
                        </li>
                    <?php } ?>
                </ul>
                <?php if(!empty($order_total)){?>
                    <div class="sj-invoiceamount">
                        <h6><?php esc_html_e('Invoice total amount','peer-review-system') ?></h6>
                        <h3><?php echo peer_review_system_price_format($order_total);?></h3>
                    </div>
                <?php } ?>
            </div>
            <div class="sj-boxlg">
                <div class="sj-incoiveinfo">
                    <h4><?php esc_html_e('Invoice summary','peer-review-system') ?></h4>
                </div>
                <table class="table sj-tableinvoice">
                    <thead>
                        <tr>
                            <th scope="col"><?php esc_html_e('Description','peer-review-system')?></th>
                            <th scope="col"><?php esc_html_e('Item price','peer-review-system')?></th>
                            <th scope="col"><?php esc_html_e('Quantity','peer-review-system')?></th>
                            <th scope="col"><?php esc_html_e('Amount','peer-review-system') ?></th>
                        </tr>
                    </thead>
                    <tbody>   
                        <?php
                        if(!empty($order->get_items())){
                            foreach ( $order->get_items() as $item_id => $item ) {
                                $product        = $item->get_product();
                                $product_name   = $item->get_name();
                                $product_price  = $item->get_subtotal();
                                $item_quantity  = $item->get_quantity();
                                $subtotal       = $item->get_subtotal();
                                $total          = $item->get_total();
                                ?>
                                <tr>
                                    <td data-label='Description'><?php echo esc_html($article_name)?></td>
                                    <td data-label='Item price'><?php echo peer_review_system_price_format($product_price)?></td>
                                    <td data-label='Quantity'><?php echo intval($item_quantity)?></td>
                                    <td data-label='Amount'><?php echo peer_review_system_price_format($subtotal)?></td>
                                </tr>  
                            <?php }?>
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="sj-subtotal"><?php esc_html_e('Subtotal:','peer-review-system')?></td>
                                <td class="sj-subtotal"><?php echo peer_review_system_price_format($get_total)?></td>
                            </tr>
                        <?php }?>
                        <?php if(!empty($refunded)){?>
                            <tr>
                                <td></td>
                                <td></td>    
                                <td class="sj-subtotal"><?php esc_html_e('Refunded:', 'peer-review-system')?></td>
                                <td class="sj-subtotal"><?php echo peer_review_system_price_format($refunded);?></td>
                            </tr>
                        <?php }?>
                        <?php if(!empty($order_total)){?>
                            <tr>
                                <td></td>
                                <td></td>    
                                <td class="sj-subtotal"><?php esc_html_e('Total:', 'peer-review-system')?></td>
                                <td class="sj-subtotal"><?php echo peer_review_system_price_format($order_total);?></td>
                            </tr>
                        <?php }?>
                    </tbody>
                </table>
            </div>
            <?php if (!empty($invoice_terms)) { ?>
                <div class="sj-descpbox">
                    <div class="sj-tabledescription">
                        <h5><?php esc_html_e('Terms & conditions','peer-review-system')?></h5>
                        <p><?php echo esc_html($invoice_terms)?></p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>