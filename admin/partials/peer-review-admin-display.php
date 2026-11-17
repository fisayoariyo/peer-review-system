<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://codecanyon.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/admin/partials
 */


/**
 * Admin order detail item name row class
 *
 * @since    1.0.0
 * @return	void
 */
if (!function_exists('woocommerce_admin_html_order_item_class')) {
    add_action('woocommerce_admin_html_order_item_class', 'woocommerce_admin_html_order_item_class', 99, 3);
    function woocommerce_admin_html_order_item_class($class, $item, $order)
    {
        $order_detail = wc_get_order_item_meta( $item->get_id(), 'cus_woo_product_data', true ); 

        if( !empty( $order_detail )) {
            remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );
            $article_data		= !empty($order_detail['article_data']) ? $order_detail['article_data'] : array();
            if( !empty($article_data['article_title'])){
                $class .=' sj-article-link';                
            }             
        }

        return $class;
    }
}

/**
 * Admin order detail article name
 *
 * @since    1.0.0
 * @return	void
 */
if (!function_exists('woocommerce_before_order_itemmeta')) {
    add_action('woocommerce_before_order_itemmeta', 'woocommerce_before_order_itemmeta', 10, 3);
    function woocommerce_before_order_itemmeta($item_id, $item, $product)
    {
        $order_detail = wc_get_order_item_meta( $item_id, 'cus_woo_product_data', true ); 

        if( !empty( $order_detail )) {
            remove_action( 'woocommerce_order_details_after_order_table', 'woocommerce_order_again_button' );
            $article_data		= !empty($order_detail['article_data']) ? $order_detail['article_data'] : array();
            if( !empty($article_data['article_title'])){
                $article_id  = $article_data['article_id'];
                $article_title  = $article_data['article_title'];
                echo wp_kses_post( sprintf( '<a class="sj-article-name" href="%s">%s</a>', get_the_permalink($article_id), $article_title ) );                
            }             
        }
    }
}

/**
 * Admin user column
 *
 * @since    1.0.0
 * @return	void
 */
if (!function_exists('peer_review_system_manage_user_columns')) {
    add_filter('manage_users_columns', 'peer_review_system_manage_user_columns');
    function peer_review_system_manage_user_columns($column)
    {
        $column['sj_varifiled']    = esc_html__('Profile verification', 'peer-review-system');
        return $column;
    }
}

if (!function_exists('peer_review_system_manage_user_column_row')) {
    add_filter('manage_users_custom_column', 'peer_review_system_manage_user_column_row', 10, 3);
    function peer_review_system_manage_user_column_row($val, $column_name, $user_id)
    {
        global $prs_settings;
        switch ($column_name) {
            case 'sj_varifiled':
                $is_verified     = get_user_meta($user_id, '_is_verified', true);

                //for admin only
                $user_meta    = get_userdata($user_id);

                if (in_array('administrator', (array) $user_meta->roles)) {
                    return;
                }

                $status                    = (isset($is_verified) && $is_verified === 'yes') ? 'reject' : 'approve';
                $status_text            = (isset($is_verified) && $is_verified === 'yes') ? esc_html__('Reject', 'peer-review-system') : esc_html__('Approve', 'peer-review-system');

                $val .= "<a title='" . ucfirst($status) . ' ' . esc_html__('user', 'peer-review-system') . "'
                            class='do_verify_user_confirm dashicons-before " . (!empty($is_verified) && $is_verified === 'yes' ? 'sj-icon-color-green' : 'sj-icon-color-red') . "'
                            data-type='" . esc_attr($status) . "'
                            data-user_id='" . intval($user_id) . "'
                            href='javascript:void(0);'>
                            <span class='dashicons dashicons-admin-users woocommerce-help-tip' data-tip='" . esc_attr($status_text) . "'></span>
                        </a>";
                $val .= '<div id="approve-user-confirm-' . intval($user_id) . '" class="sj-approve-user" style="display:none;">';
                $val .= '<h4>' . wp_sprintf('%s %s', esc_html__('Are you sure you want to %s user?', 'peer-review-system'), $status) . '</h4>';
                $val .= '<div class="sj-action-links">';
                $val .= "<a title='" . esc_html__('Approve user', 'peer-review-system') . "'
                                        class='do_verify_user dashicons-before " . (!empty($is_verified) && $is_verified === 'yes' ? 'sj-icon-color-green' : 'sj-icon-color-red') . "'
                                        data-type='" . esc_attr($status) . "'
                                        data-user_id='" . intval($user_id) . "'
                                        href='javascript:void(0);'>
                                            <span class='dashicons dashicons-admin-users woocommerce-help-tip' data-tip='" . esc_attr($status_text) . "'></span>
                                            
                                    </a>";
                $val .= '</div>';
                $val .= '</div>';
                $val .= '</div>';

                return $val;

                break;
            default:
        }
    }
}

/**
 * Approve and disapprove users
 *
 * @since    1.0.0
 * @return	void
 */
if (!function_exists('peer_review_system_approve_profile')) {
    add_action('wp_ajax_peer_review_system_approve_profile', 'peer_review_system_approve_profile');
    function peer_review_system_approve_profile()
    {

        $json           = array();
        //security check
        if (!wp_verify_nonce($_POST['security'], 'ajax_nonce')) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Oops!', 'peer-review-system');
            $json['message_desc']   = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        // get post data
        $type               = !empty($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $user_id            = !empty($_POST['user_id']) ? intval($_POST['user_id']) : '';

        // validate post data
        if (empty($type) || empty($user_id)) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Oops!', 'peer-review-system');
            $json['message_desc']   = esc_html__('Some data has ben lost, please try again', 'peer-review-system');
            wp_send_json($json);
        }

        // validate user
        $user_meta    = get_userdata($user_id);
        if (empty($user_meta)) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Oops!', 'peer-review-system');
            $json['message_desc']   = esc_html__('User not exists', 'peer-review-system');
            wp_send_json($json);
        }

        $is_user_verify = ($type == 'reject' ? 'no' : 'yes');
        update_user_meta($user_id, '_is_verified', $is_user_verify);
        update_user_meta($user_id, 'confirmation_key', '');
        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);

        $full_name  = !empty($user_meta->display_name) ? $user_meta->display_name : 'Subscriber';
        $email      = !empty($user_meta->user_email) ? $user_meta->user_email : '';

        //Send email to user on account approved/reject
        if (class_exists('Peer_Review_System_Email_Helper')) {
            $blogname               = get_option('blogname');
            $emailData              = array();
            $emailData['name']      = $full_name;
            $emailData['email']     = $email;
            $emailData['sitename']  = $blogname;
            if (class_exists('PeerReviewSystemRegistrationEmail')) {
                $emailHelper = new PeerReviewSystemRegistrationEmail();
                if ($type != 'reject') {
                    $emailHelper->user_account_approved($emailData);
                } else {
                    $emailHelper->user_reject_account($emailData);
                }
            }
        }

        $current_state = ($type == 'reject' ? esc_html__('unverified', 'peer-review-system') : esc_html__('approved', 'peer-review-system'));
        $json['type']           = 'success';
        $json['message']        = esc_html__('Woohoo!', 'peer-review-system');
        $json['message_desc']   = wp_sprintf(esc_html__('Account has been %s and email has been sent to user.', 'peer-review-system'), $current_state);
        wp_send_json($json);
    }
}
