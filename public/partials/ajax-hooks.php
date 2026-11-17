<?php

/**
 * File uploader
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_temp_file_uploader')) {
    function peer_review_system_temp_file_uploader()
    {
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }
        $json       = array();
        /*=================== Wp Nonce Verification =================*/
        $do_check   = check_ajax_referer('ajax_nonce', 'ajax_nonce', false);
        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }
        /*=================== End Wp Nonce Verification =================*/
        $response = Peer_Review_System_file_permission::uploadFile($_FILES['file_name']);
        wp_send_json($response);
    }
    add_action('wp_ajax_peer_review_system_temp_file_uploader', 'peer_review_system_temp_file_uploader');
}


/**
 * Profile avatar update
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_update_avatar')) {
    function peer_review_system_update_avatar()
    {
        global $current_user;
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }
        $user_identity  = $current_user->ID;
        $uploadspath    = wp_upload_dir();
        $upload_dir     = $uploadspath['basedir'] . '/peer-review-system-temp/';
        $bse64Url       = !empty($_POST['image_url']) ? $_POST['image_url'] : '';
        $json           = array();
        if (!empty($bse64Url)) {
            // if user upload new image
            $bse64 = explode(',', $bse64Url);
            $bse64 = trim($bse64[1]);

            if (empty($bse64)) {
                $json['type']       = 'error';
                $json['type']       = 'error';
                $json['title']      = esc_html__('OH', 'peer-review-system');
                $json['message']    = esc_html__('Image is not in correct format', 'peer-review-system');
                wp_send_json($json);
            }

            $timestamp    = time(); // create new timestamp
            $file_name    = $user_identity . '-' . $timestamp . '.jpg';
            $image_url    = $upload_dir . $file_name;
            file_put_contents($image_url, file_get_contents($bse64Url));

            if (file_exists($image_url)) {
                $pre_attachment_id  = get_user_meta($current_user->ID, 'profile_attachment_id', true);
                $new_image          = $uploadspath['baseurl'] . '/peer-review-system-temp/' . $file_name;

                if (!empty($pre_attachment_id)) {
                    wp_delete_attachment($pre_attachment_id, true);
                }

                $profile_avatar             = peer_review_system_temp_upload_to_media($new_image, '');
                update_user_meta($current_user->ID, 'profile_attachment_id', $profile_avatar['attachment_id']);
                $avatar_150_x_150           = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 100, 'height' => 100), $user_identity), array('width' => 200, 'height' => 200));

                $json['avatar_150_x_150']   = !empty($avatar_150_x_150)  ? $avatar_150_x_150 : '';
                $json['type']               = 'success';
                $json['title']              = esc_html__('Success', 'peer-review-system');
                $json['message']            = esc_html__('Settings have been updated successfully.', 'peer-review-system');
            } else {
                $json['type']               = 'error';
                $json['title']              = esc_html__('OH', 'peer-review-system');
                $json['message']            = esc_html__('Something went wrong.', 'peer-review-system');
            }
        } else {
            $json['type']       = 'error';
            $json['title']      = esc_html__('OH', 'peer-review-system');
            $json['message']    = esc_html__('Something went wrong.', 'peer-review-system');
        }

        wp_send_json($json);
    }
    add_action('wp_ajax_peer_review_system_update_avatar', 'peer_review_system_update_avatar');
}

/**
 * User Registration
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_user_register')) {
    function peer_review_system_user_register()
    {
        global $prs_settings;
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }
        $json   = array();

        if (get_option('users_can_register') == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('New Registration is disabled by admin!', 'peer-review-system');
            wp_send_json($json, 203);
        }

        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type']           = 'error';
            $json['title']          = esc_html__("Oops!", 'peer-review-system');
            $json['message']        = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json, 203);
        }

        $post_data  = !empty($_POST['data']) ? $_POST['data'] : '';
        parse_str($post_data, $data);
        $registration = !empty($data['registration']) ? $data['registration'] : array();
        $validations = array(
            'fname'         => esc_html__('First name is required', 'peer-review-system'),
            'lname'         => esc_html__('Last name is required.', 'peer-review-system'),
            'email'         => esc_html__('Email field is required.', 'peer-review-system'),
            'password'      => esc_html__('Password field is required.', 'peer-review-system'),
            'terms'         => esc_html__('You must accept terms and conditions', 'peer-review-system'),
            'user_type'     => esc_html__('User type is required', 'peer-review-system'),
        );

        foreach ($validations as $key => $value) {
            if (empty($registration[$key])) {
                $json['title']      = esc_html__("Oops!", 'peer-review-system');
                $json['type']       = 'error';
                $json['message']    = $value;
                wp_send_json($json, 203);
            }

            /* Validate email address */
            if ($key === 'email') {
                if (!is_email($registration[$key])) {
                    $json['type']       = 'error';
                    $json['message']    = esc_html__('Please add a valid email address.', 'peer-review-system');
                    $json['title']      = esc_html__("Oops!", 'peer-review-system');
                    wp_send_json($json, 203);
                }
            }

            if ($key === 'password') {
                $data = userPasswordValidation($registration[$key]);
                if (!empty($data)) {
                    $data['title']  = esc_html__("Oops!", 'peer-review-system');
                    wp_send_json($data, 203);
                }
            }
        }

        $default_register_type  = !empty($prs_settings['defult_register_type']) ? $prs_settings['defult_register_type'] : 'subscriber';
        $first_name             = !empty($registration['fname']) ? sanitize_text_field($registration['fname']) : '';
        $last_name              = !empty($registration['lname']) ? sanitize_text_field($registration['lname']) : '';
        $email                  = !empty($registration['email']) ? sanitize_email($registration['email']) : '';
        $password               = !empty($registration['password']) ? esc_html($registration['password']) : '';
        $user_type              = !empty($registration['user_type']) ? esc_html($registration['user_type']) : $default_register_type;
        $user_agree_terms       = !empty($registration['terms']) ? esc_html($registration['terms']) : '';
        $full_name              = $first_name . ' ' . $last_name;
        $user_nicename          = sanitize_title($full_name);

        $userdata = array(
            'user_login'    => $email,
            'user_pass'     => $password,
            'user_email'    => $email,
            'user_nicename' => $user_nicename,
            'display_name'  => $full_name
        );
        $user_identity = wp_insert_user($userdata);

        if (is_wp_error($user_identity)) {
            $json['title']            = esc_html__("Oops!", 'peer-review-system');
            $json['type']             = "error";
            $json['message']          = esc_html__("User already exists, please try another one.", 'peer-review-system');
            wp_send_json($json, 203);
        } else {
            global $wpdb;
            wp_update_user(array('ID' => esc_sql($user_identity), 'role' => $user_type, 'user_status' => 1));
            $wpdb->update($wpdb->prefix . 'users', array('user_status' => 1), array('ID' => esc_sql($user_identity)));

            update_user_meta($user_identity, 'first_name', $first_name);
            update_user_meta($user_identity, 'last_name', $last_name);
            update_user_meta($user_identity, 'termsconditions', esc_html($user_agree_terms));
            update_user_meta($user_identity, 'show_admin_bar_front', false);
            update_user_meta($user_identity, '_is_verified', 'no');
            update_user_meta($user_identity, 'identity_verified', 0);

            $verify_link = '';
            $verify_new_user = !empty($prs_settings['email_user_registration']) ? $prs_settings['email_user_registration'] : '';
            if (!empty($verify_new_user) && $verify_new_user == 'verify_by_link' && empty($prs_settings['user_account_approve'])) {
                /* verification link */
                $key_hash     = md5(uniqid(openssl_random_pseudo_bytes(32)));
                update_user_meta($user_identity, 'confirmation_key', $key_hash);
                $protocol     = is_ssl() ? 'https' : 'http';
                $verify_link  = esc_url(add_query_arg(array('key' => $key_hash . '&verifyemail=' . $email), home_url('/', $protocol)));
            }

            /* account approved */
            if (!empty($prs_settings['user_account_approve'])) {
                update_user_meta($user_identity, '_is_verified', 'yes');
            }
            $login_url    = !empty($prs_settings['tpl_login']) ? get_permalink($prs_settings['tpl_login']) : wp_login_url();

            /* Send email to users & admin */
            if (class_exists('Peer_Review_System_Email_Helper')) {
                $blogname                       = get_option('blogname');
                $emailData                      = array();
                $emailData['name']              = $full_name;
                $emailData['password']          = $password;
                $emailData['email']             = $email;
                $emailData['verification_link'] = $verify_link;
                $emailData['site']              = $blogname;
                $emailData['login_url']         = $login_url;
                /* Welcome Email */
                if (class_exists('PeerReviewSystemRegistrationEmail')) {
                    $email_helper = new PeerReviewSystemRegistrationEmail();
                    if (!empty($prs_settings['user_account_approve'])) {
                        //email to user on auto approve
                        $email_helper->registration_user_auto_approve_email($emailData);
                    } elseif (!empty($verify_new_user) && $verify_new_user == 'verify_by_link' && empty($prs_settings['user_account_approve'])) {
                        //email to user verify by link
                        $email_helper->user_registration_verify_link_email($emailData);
                    } else {
                        /* to user approved by admin */
                        $email_helper->registration_account_approval_request($emailData);
                        /* to admin if verify by admin */
                        $email_helper->registration_verify_by_admin_email($emailData);
                    }
                    $email_helper->new_user_register_admin_email($emailData);
                }
            }

            /* User Login */
            $user_array = array();
            $user_array['user_login']       = $email;
            $user_array['user_password']    = $password;
            wp_signon($user_array, false);

            $dashboard = (!empty($verify_new_user) && ($verify_new_user === 'verify_by_link') && (empty($prs_settings['user_account_approve']))) ? home_url('/') : peer_review_system_dashboard_page_uri();

            if (!empty($prs_settings['user_account_approve'])) {
                $message = esc_html__("Congratulation Your account has been created successfully!", 'peer-review-system');
            } elseif (empty($prs_settings['user_account_approve']) && $prs_settings['email_user_registration'] == 'verify_by_link') {
                $message = esc_html__("Your account has been created. Please verify your account through the email that has been sent to your email address.", 'peer-review-system');
            } elseif (empty($prs_settings['user_account_approve']) && $prs_settings['email_user_registration'] == 'verify_by_admin') {
                $message = esc_html__("Your account has been created. Please wait while your account is verified by the admin.", 'peer-review-system');
            }

            $json['type']           = 'success';
            $json['title']          = esc_html__('Success', 'peer-review-system');
            $json['message']        = $message;
            $json['redirect']       = esc_url($dashboard);
            wp_send_json($json, 200);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_user_register', 'peer_review_system_user_register');
    add_action('wp_ajax_peer_review_system_user_register', 'peer_review_system_user_register');
}


/**
 * Edition checkout system
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_checkout')) {
    function peer_review_system_checkout()
    {
        
        $post_data  = !empty($_POST['data']) ? $_POST['data'] : '';
      
 
   

            $json['type']   = 'success';
            $json['title']  = esc_html__('Success', 'peer-review-system');
            $json['data']   = $post_data;
             wp_send_json($json, 200);
       
    }
    add_action('wp_ajax_nopriv_peer_review_system_checkout', 'peer_review_system_checkout');
    add_action('wp_ajax_peer_review_system_checkout', 'peer_review_system_checkout');
}

/**
 * User Login
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_user_signin')) {
    function peer_review_system_user_signin()
    {
        global $prs_settings;
        $json       = array();
        $post_data  = !empty($_POST['data']) ? $_POST['data'] : '';
        parse_str($post_data, $data);
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        $login  = !empty($data['login']) ? $data['login'] : array();
        $user_array['user_login']       = sanitize_text_field($login['email']);
        $user_array['user_password']    = sanitize_text_field($login['password']);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        $validations    = array(
            'email'     => esc_html__('Email is required', 'peer-review-system'),
            'password'  => esc_html__('Password is required.', 'peer-review-system'),
        );

        foreach ($validations as $key => $value) {
            if (empty($login[$key])) {
                $json['type']       = 'error';
                $json['title']      = esc_html__("Oops!", 'peer-review-system');
                $json['message']    = $value;
                wp_send_json($json, 203);
            }

            /* Validate email address */
            if ($key === 'email') {
                if (!is_email($login[$key])) {
                    $json['type']       = 'error';
                    $json['title']      = esc_html__("Oops!", 'peer-review-system');
                    $json['message']    = esc_html__('Please add a valid email address.', 'peer-review-system');
                    wp_send_json($json, 203);
                }
            }
        }

        $user = wp_signon($user_array, false);
        if (is_wp_error($user)) {
            $json    = array(
                'type'          => 'error',
                'title'         => esc_html__('Oops!', 'peer-review-system'),
                'loggedin'      => false,
                'message'       => esc_html__('Wrong email/username or password.', 'peer-review-system')
            );
            wp_send_json($json, 203);
        } else {
            $userDetail             = $user->data;
            $profile_id             = peer_review_system_get_linked_profile_id($user->ID);
            $profile_id             = !empty($profile_id) ? $profile_id : 0;
            $identity_verified      = get_user_meta((int)$userDetail->ID, 'identity_verified', true);
            $identity_verified      = !empty($identity_verified) ? $identity_verified : 'no';
            $deactivate_account     = get_post_meta($profile_id, '_deactive_account', true);
            $deactivate_account     = (!empty($deactivate_account) && $deactivate_account == 1) ? 'true' : 'false';
            $is_verified            = get_post_meta($profile_id, '_is_verified', true);
            $is_verified            = !empty($is_verified) ? $is_verified : 'no';
			$redirect               = !empty($login['redirect_to']) ? esc_url_raw($login['redirect_to']) : peer_review_system_dashboard_page_uri();
			$redirect_transient     = get_transient('prs_redirect_page_url');
			if (!empty($redirect_transient)) {
				$redirect = html_entity_decode($redirect_transient);
				delete_transient('prs_redirect_page_url');
			}

            $json['type']       = 'success';
            $json['title']      = esc_html__('Woohoo!', 'peer-review-system');
            $json['message']    = esc_html__('You are successfully logged in', 'peer-review-system');
            $json['loggedin']   = true;
            $json['redirect']   = !empty($redirect) ? esc_url($redirect) : home_url('/');
            wp_send_json($json, 200);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_user_signin', 'peer_review_system_user_signin');
    add_action('wp_ajax_peer_review_system_user_signin', 'peer_review_system_user_signin');
}

/**
 * Reset Forgot Password
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_password_reset')) {
    function peer_review_system_password_reset()
    {
        global $prs_settings;
        $json           = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $post_data      = !empty($_POST['data']) ? wp_unslash($_POST['data']) : '';
        if (is_array($post_data)) {
            $data = $post_data;
        } else {
            parse_str($post_data, $data);
        }
        $email = !empty($data['email']) ? sanitize_email($data['email']) : '';

        if (empty($email)) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Email address is required', 'peer-review-system');
            wp_send_json($json);
        }

        /* Validate email address */
        if (!is_email($email)) {
            $json['type']       = 'error';
            $json['message']    = esc_html__('Please add a valid email address.', 'peer-review-system');
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            wp_send_json($json);
        }

        $user_data = get_user_by('email', $email);
        if (empty($user_data)) {
            $json['type']       = "error";
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__("The email address does not exist", 'peer-review-system');
            wp_send_json($json);
        }

        $user_id    = $user_data->ID;
        $key        = $user_data->user_activation_key;
        if (empty($key)) {
            $key  = wp_generate_password(20, false);
            wp_update_user(array('ID' => $user_id, 'user_activation_key' => $key));
        }

        $forgot_page_url  = !empty($prs_settings['tpl_reset']) ? get_permalink($prs_settings['tpl_reset']) : '';
        if (empty($forgot_page_url) && function_exists('jase_peer_review_page_url')) {
            $forgot_page_url = jase_peer_review_page_url('forgot-password');
        }
        if (empty($forgot_page_url)) {
            $forgot_page_url = home_url('/');
        }
        $reset_link       = esc_url_raw(add_query_arg(array('action' => 'reset_pwd', 'key' => $key, 'login' => $email), $forgot_page_url));

        /* Send email to user */
        if (class_exists('Peer_Review_System_Email_Helper')) {
            $user_login                     = $user_data->user_login;
            $userprofile_email              = !empty($user_data->user_email) ? $user_data->user_email : '';
            $userprofile_name               = !empty($user_data->display_name) ? $user_data->display_name : '';
            $user_profile_id                = peer_review_system_get_linked_profile_id($user_id);
            $username                       = peer_review_system_get_username($user_profile_id);
            $username                       = !empty($username) ? $username : $userprofile_name;
            $blogname                       = get_option('blogname');
            $emailData                      = array();
            $emailData['name']              = $username;
            $emailData['email']             = $userprofile_email;
            $emailData['reset_link']        = $reset_link;
            $emailData['sitename']          = $blogname;

            /* Forgot password email */
            if (class_exists('PeerReviewSystemRegistrationEmail')) {
                try {
                    $email_helper = new PeerReviewSystemRegistrationEmail();
                    $email_helper->user_reset_password($emailData);
                } catch (Throwable $email_error) {
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log('peer_review_system_password_reset mail error: ' . $email_error->getMessage());
                    }
                }
            }
        }

        $json['type']         = "success";
        $json['title']      = esc_html__("Woohoo", 'peer-review-system');
        $json['message']     = esc_html__("Reset password link has been sent, please check your email.", 'peer-review-system');
        wp_send_json($json);
    }
    add_action('wp_ajax_nopriv_peer_review_system_password_reset', 'peer_review_system_password_reset');
    add_action('wp_ajax_peer_review_system_password_reset', 'peer_review_system_password_reset');
}

/**
 * Password recovery
 * userRegistration
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_recover_password')) {
    function peer_review_system_recover_password()
    {
        global $wpdb;
        $json           = array();
        $post_data      = !empty($_POST['data']) ? wp_unslash( $_POST['data'] ) : '';
        if ( is_array( $post_data ) ) {
            $data = $post_data;
        } else {
            parse_str( $post_data, $data );
        }
        $json           = array();
        $password       = !empty( $data['password'] ) ? sanitize_text_field( $data['password'] ) : ( ! empty( $_POST['password'] ) ? sanitize_text_field( wp_unslash( $_POST['password'] ) ) : '' );
        $reset_key      = !empty( $data['key'] ) ? sanitize_text_field( $data['key'] ) : ( ! empty( $_POST['key'] ) ? sanitize_text_field( wp_unslash( $_POST['key'] ) ) : '' );
        $password_valid = userPasswordValidation($password);

        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        if (!empty($password_valid)) {
            $json['title']  = esc_html__("Oops!", 'peer-review-system');
            wp_send_json($password_valid, 203);
        }

        $user_data = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s", $reset_key));

        if (!empty($reset_key) && !empty($password) && !empty($user_data)) {
            wp_set_password($password, $user_data->ID);
            wp_update_user(
                array(
                    'ID'                  => $user_data->ID,
                    'user_activation_key' => '',
                )
            );
            $login_url = peer_review_system_get_page_uri('login');
            if (empty($login_url) && function_exists('jase_peer_review_page_url')) {
                $login_url = jase_peer_review_page_url('login');
            }
            if (empty($login_url)) {
                $login_url = home_url('/');
            }
            $json['title']          = esc_html__("Woohoo", 'peer-review-system');
            $json['redirect']       = $login_url;
            $json['type']           = "success";
            $json['message']        = esc_html__("Congratulation! your password has been changed.", 'peer-review-system');
            wp_send_json($json, 200);
        } else {
            $json['type']       = "error";
            $json['message']    = esc_html__("Oops! Invalid request", 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_recover_password', 'peer_review_system_recover_password');
    add_action('wp_ajax_peer_review_system_recover_password', 'peer_review_system_recover_password');
}

/**
 * Add articles
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_add_article')) {
    function peer_review_system_add_article()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $attachment_    = $authors_arr = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);

        if ($user_type != 'author' || empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json);
        }

        $article_id             = !empty($_POST['article_id']) ?  esc_html($_POST['article_id'])  : 0;
        $categories             = !empty($_POST['selected_cats']) ? $_POST['selected_cats']  : array();
        $introduction           = !empty($_POST['description']) ? wp_kses_post($_POST['description'])  : '';
        $post_data              = !empty($_POST['data']) ? $_POST['data']  : '';
        parse_str($post_data, $data);
        $article_title          = !empty($data['article_title']) ?  sanitize_text_field($data['article_title'])  : '';
        $excerpts               = !empty($data['excerpts']) ?  sanitize_text_field($data['excerpts'])  : '';
        $accept_terms           = !empty($data['sj_accept_terms']) ?  esc_html($data['sj_accept_terms'])  : '';
        $attachments            = !empty($data['attachments']) ?  $data['attachments']  : array();
        $author_name_email      = !empty($data['article_author']) ? $data['article_author'] : array();
        $cover_thumbnail        = !empty($data['cover_attachment_thumbnail']) ? esc_url($data['cover_attachment_thumbnail']) : '';
        $cover_attachment_url   = !empty($data['cover_attachment']) ? esc_url($data['cover_attachment']) : '';
        $cover_attachment_name  = !empty($data['cover_attachment_name']) ? esc_url($data['cover_attachment_name']) : '';
        $revision_reply         = !empty($data['revision-reply']) ?  esc_html($data['revision-reply'])  : '';

        if (!empty($author_name_email)) {
            foreach ($author_name_email as $val_arr) {
                if (!empty($val_arr['name']) && !empty($val_arr['email'])) {
                    $authors_arr[] = array(
                        'name'  => $val_arr['name'],
                        'email' => $val_arr['email']
                    );
                }
            }
        }

        if (empty($article_title) || empty($categories) || empty($introduction) || empty($excerpts)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Some required field are missing.', 'peer-review-system');
            wp_send_json($json);
        }

        if (empty($accept_terms)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('You have agree with terms & condition.', 'peer-review-system');
            wp_send_json($json);
        }

        $article_post = array(
            'post_title'    => wp_strip_all_tags($article_title),
            'post_content'  => $introduction,
            'post_author'   => $user_id,
            'post_type'     => 'articles',
            'post_excerpt'  => $excerpts,
        );

        if (empty($article_id)) {
            /* insert post */
            $article_post['post_status'] = 'pending';
            $article_id    = wp_insert_post($article_post);
            /* display for every editor */
            update_post_meta($article_id, 'article_assign_editor', 'editor');
            /* article is submitted for revision */
            update_post_meta($article_id, 'article_submitted_status', 'under_revision');
            /* article submission count */
            update_post_meta('article_submission_count', $article_id, 1);
            $message = esc_html__('Congratulation! Article has been posted.', 'peer-review-system');
        } else {
            // Update the post into the database
            $edition_status = !empty($article_id)  ? esc_html(get_post_status($article_id)) : '';
            $article_post['ID'] = $article_id;
            $article_post['post_status'] = $edition_status;
            wp_update_post($article_post);
            /* getting last article editor status */
            $editor_status = get_post_meta($article_id, 'prs_article_editor_status', true);
            /* getting submission count */
            $submission_count = get_post_meta($article_id, 'article_submission_count', true);
            /* if article status is !=approved, =rejected, =minor_revision, =major_revision */
            /* article is submitted for revision second time */
            if (($editor_status != 'approved') && ($editor_status == 'minor_revision' || $editor_status == 'major_revision' || $editor_status == 'rejected')) {
                $submission_count = (int)$submission_count + 1;
                update_post_meta($article_id, 'article_submission_count', $submission_count);
                update_post_meta($article_id, 'article_submitted_status', 'under_revision');
                if ($submission_count > 1) {
                    /* again email send to editor and assigned reviewers */
                    $user_detail = get_userdata($user_id);
                    $current_user_name = !empty($user_detail->display_name) ? $user_detail->display_name : '';
                    $user_email = !empty($user_detail->user_email) ? $user_detail->user_email : get_option('admin_email', 'info@example.com');
                    $editors = get_users( array( 'role' => 'article_editor' ) );

                    foreach ($editors as $editor) {
                        if (class_exists('Peer_Review_System_Email_Helper')) {
                            if (class_exists('PeerReviewSystemEditorEmail')) {
                                $emailData['email']             = $editor->user_email;
                                $emailData['user_name']         = $editor->display_name;
                                $emailData['author_name']       = $current_user_name;
                                $emailData['article_name']      = get_the_title($article_id);
                                $email_helper = new PeerReviewSystemEditorEmail();
                                /* email to editors on article resubmitted */
                                $email_helper->email_editor_resubmission_article($emailData);
                            }
                        }
                    }
                }
            }

            $message = esc_html__('Congratulation! article has been updated.', 'peer-review-system');
        }

        if(!empty($revision_reply)){
            $article_author_id  = get_post_field('post_author', $article_id);
            $article_author_id  = !empty($article_author_id) ? $article_author_id : 0;
            $userdata           = !empty($user_id)  ? get_userdata($user_id) : array();
            $user_email         = !empty($userdata) ? $userdata->user_email : '';
            $user_name          = !empty($userdata) ? $userdata->display_name : '';
            $comment_data   = array(
                'comment_post_ID'           => intval($article_id),
                'comment_author'            => wp_strip_all_tags($user_name),
                'comment_author_email'      => sanitize_email($user_email),
                'comment_author_url'        => 'http://',
                'comment_content'           => $revision_reply,
                'comment_type'              => 'prs_article_review',
                'user_id'                   => absint($user_id),
                'comment_date'              => current_time('mysql'),
                'comment_parent'            => 0,
                'comment_approved'          => 1,
                'comment_meta'         => array(
                    'prs_comment_article_author_id'     => $article_author_id,
                    'sender_id'     => $user_id,
                    'receiver_id'   => $user_id, // this will be change bcz here r3eciver id will bre place
                ),
            );
            $comment_id = wp_insert_comment($comment_data);
        }
        if (!empty($attachments)) {
            foreach ($attachments as $attachment) {

                if (strstr($attachment['file'], 'peer-review-system-temp')) {
                    $params   = array(
                        'articleId' => $article_id,
                        'file' => $attachment['fileName'],
                    );
                    $response   = Peer_Review_System_file_permission::moveTempToUploaded($params);

                    if (!empty($response['file'])) {
                        $attachment_[]  = array(
                            'attachment_url'    => $response['file'],
                            'attachment_name'   => $response['fileName'],
                            'attachment_size'   => $attachment['fileSize'],
                        );
                    }
                } else {
                    $attachment_[]  = array(
                        'attachment_url'    => $attachment['file'],
                        'attachment_name'   => $attachment['fileName'],
                        'attachment_size'   => $attachment['fileSize'],
                    );
                }
            }
        }

        if (!empty($cover_attachment_url)) {

            $response   = peer_review_system_temp_upload_to_media($cover_attachment_url, $article_id);
            if (!empty($response['attachment_id'])) {
                update_post_meta($article_id, '_thumbnail_id', (int)$response['attachment_id']);
            }
        }

        if (!is_wp_error($article_id)) {
            wp_set_post_terms($article_id, $categories, 'article_categories');
            update_post_meta($article_id, 'prs_accept_terms', $accept_terms);
            update_post_meta($article_id, 'prs_article_authors', $authors_arr);
            update_post_meta($article_id, 'attachments', $attachment_);

            if($editor_status == 'minor_revision'){
                update_post_meta($article_id, 'revision_reply', $revision_reply);
            }

            /* email to author and editors */
            $user_detail = get_userdata($user_id);
            $current_user_name = !empty($user_detail->display_name) ? $user_detail->display_name : '';
            $user_email = !empty($user_detail->user_email) ? $user_detail->user_email : get_option('admin_email', 'info@example.com');
            $login_url = !empty($prs_settings['tpl_login']) ? $prs_settings['tpl_login'] : home_url('/');
            if (class_exists('Peer_Review_System_Email_Helper')) {
                $blogname = get_option('blogname');
                $emailData = array();
                $emailData['article_name']      = get_the_title($article_id);
                $emailData['site']              = $blogname;
                $emailData['login_url']         = $login_url;

                /* email to author */
                if (class_exists('PeerReviewSystemAuthorEmail')) {
                    $emailData['user_name']         = $current_user_name;
                    $emailData['email']             = $user_email;
                    $email_helper = new PeerReviewSystemAuthorEmail();
                    /* email to author on article submit */
                    $email_helper->email_author_article_submit($emailData);
                }

                /* email to editor on new article post */
                if (class_exists('PeerReviewSystemEditorEmail')) {
                    $email_helper = new PeerReviewSystemEditorEmail();
                    /* getting all editors */
                    $editors = get_users( array( 'role' => 'article_editor' ) );

                    if (!empty($editors) && is_array($editors)) {
                        foreach ($editors as $editor) {
                            $emailData['email']             = $editor->user_email;
                            $emailData['user_name']         = $editor->display_name;
                            $emailData['author_name']       = $current_user_name;
                            /* email to editors on new article received */
                            $email_helper->email_editor_new_article_received($emailData);
                        }
                    }
                }
            }

            $json['type']    = 'success';
            $json['title']   = esc_html__("Success", 'peer-review-system');
            $json['message'] = $message;
            wp_send_json($json);
        } else {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_add_article', 'peer_review_system_add_article');
    add_action('wp_ajax_peer_review_system_add_article', 'peer_review_system_add_article');
}

/**
 * Delete article
 */
if (!function_exists('peer_review_system_delete_article')) {
    function peer_review_system_delete_article()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $article_id     = !empty($_POST['article_id']) ?  esc_html($_POST['article_id'])  : 0;

        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json, 203);
        }

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);

        if ($user_type != 'author' || empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        if (!empty($article_id)) {

            $is_deleted = wp_delete_post($article_id, true);

            if (empty($is_deleted)) {
                $json['type']    = 'success';
                $json['title']   = esc_html__("Oops!", 'peer-review-system');
                $json['message'] = esc_html__("Please try again", 'peer-review-system');
                wp_send_json($json, 203);
            } else {
                $json['type']    = 'success';
                $json['title']   = esc_html__("Success", 'peer-review-system');
                $json['message'] = esc_html__("Congratulation your article has been deleted!", 'peer-review-system');
                wp_send_json($json, 200);
            }
        } else {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Something went wrong!', 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
    add_action('wp_ajax_peer_review_system_delete_article', 'peer_review_system_delete_article');
}

/**
 * Assign articles to editions
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_assign_article')) {
    function peer_review_system_assign_article()
    {
        global $current_user;
        $user_id = $current_user->ID;
        $json    = array();
        $assigned_articles  = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);

        if ($user_type != 'editor' || empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json);
        }

        $edition_id = !empty($_POST['edition_id']) ?  esc_html($_POST['edition_id'])  : 0;
        $author_id  = !empty($_POST['author_id']) ?  esc_html($_POST['author_id'])  : 0;
        $articles   = !empty($_POST['selected_article']) ? $_POST['selected_article']  : array();


        if (empty($articles)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Please select atleast one article.', 'peer-review-system');
            wp_send_json($json);
        }

        foreach ($articles as $article) {
            $title  = get_the_title($article);
            $assigned_articles[$article]    = $title;
        }

        if (!empty($assigned_articles)) {
            $assigned_articles = update_post_meta($edition_id, 'assign_articles', $assigned_articles);

            if (!is_wp_error($assigned_articles)) {
                $json['type']    = 'success';
                $json['title']   = esc_html__('Success', 'peer-review-system');
                $json['message'] = esc_html__('Aricles has been assign to the Edition', 'peer-review-system');
                wp_send_json($json);
            } else {
                $json['type']     = 'error';
                $json['title']    = esc_html__('Oops!', 'peer-review-system');
                $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
                wp_send_json($json);
            }
        } else {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Please select at least one article.', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_assign_article', 'peer_review_system_assign_article');
    add_action('wp_ajax_peer_review_system_assign_article', 'peer_review_system_assign_article');
}

/**
 * Add edition
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_new_edition')) {
    function peer_review_system_new_edition()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $attachment_    = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);

        if ($user_type != 'editor' || empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json);
        }

        $post_data   = !empty($_POST['data']) ? $_POST['data']  : '';
        parse_str($post_data, $data);
        $edition_name  = !empty($data['edition_name']) ?  sanitize_text_field($data['edition_name'])  : '';
        $edition_date  = !empty($data['edition_date']) ?  sanitize_text_field($data['edition_date'])  : '';
        $cover_thumbnail    = !empty($data['cover_attachment_thumbnail']) ? esc_url($data['cover_attachment_thumbnail']) : '';
        $cover_attachment_url  = !empty($data['cover_attachment']) ? esc_url($data['cover_attachment']) : '';
        $cover_attachment_name = !empty($data['cover_attachment_name']) ? esc_url($data['cover_attachment_name']) : '';

        /* validation form fields */
        $validations = array(
            'edition_name'   => esc_html__('Edition name is required.', 'peer-review-system'),
            'edition_date'   => esc_html__('Edition date is required.', 'peer-review-system'),
        );

        foreach ($validations as $key => $value) {
            if (isset($data[$key]) && empty($data[$key])) {
                $json['title']    = esc_html__("Oops!", 'peer-review-system');
                $json['type']     = 'error';
                $json['message']  = $value;
                wp_send_json($json);
            }
        }
        $edition_post = array(
            'post_title'    => wp_strip_all_tags($edition_name),
            'post_status'   => 'pending',
            'post_content'  => '',
            'post_author'   => $user_id,
            'post_type'     => 'editions',
        );

        /* insert post */
        $edition_id = wp_insert_post($edition_post);

        if (!is_wp_error($edition_id)) {
            if (!empty($cover_attachment_url)) {

                $response   = peer_review_system_temp_upload_to_media($cover_attachment_url, $edition_id);
                if (!empty($response['attachment_id'])) {
                    update_post_meta($edition_id, '_thumbnail_id', (int)$response['attachment_id']);
                }
            }
            update_post_meta($edition_id, 'edition_date', $edition_date);

            $json['type']    = 'success';
            $json['title']   = esc_html__("Success", 'peer-review-system');
            $json['message'] = esc_html__('Congratulation! New Edition has been posted.', 'peer-review-system');
            wp_send_json($json);
        } else {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_new_edition', 'peer_review_system_new_edition');
    add_action('wp_ajax_peer_review_system_new_edition', 'peer_review_system_new_edition');
}

/**
 * Edit edition
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_edit_edition')) {
    function peer_review_system_edit_edition()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $attachment_    = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);

        if ($user_type != 'editor' || empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json);
        }
        $post_data   = !empty($_POST['data']) ? $_POST['data']  : '';
        parse_str($post_data, $data);
        $edition_name   = !empty($data['edition_name']) ?  sanitize_text_field($data['edition_name'])  : '';
        $edition_date   = !empty($data['edition_date']) ?  sanitize_text_field($data['edition_date'])  : '';
        $edition_price  = !empty($data['edition_price']) ?  sanitize_text_field($data['edition_price'])  : '';
        $edition_id     = !empty($data['edition_id']) ?  intval($data['edition_id'])  : '';
        $edition_status = !empty($edition_id)  ? esc_html(get_post_status($edition_id)) : '';
        $cover_thumbnail        = !empty($data['cover_attachment_thumbnail']) ? esc_url($data['cover_attachment_thumbnail']) : '';
        $cover_attachment_url   = !empty($data['cover_attachment']) ? esc_url($data['cover_attachment']) : '';
        $cover_attachment_name  = !empty($data['cover_attachment_name']) ? esc_url($data['cover_attachment_name']) : '';

        /* validation form fields */
        $validations = array(
            'edition_name'   => esc_html__('Edition name is required.', 'peer-review-system'),
            'edition_date'   => esc_html__('Edition date is required.', 'peer-review-system'),
            'edition_price'  => esc_html__('Edition price is required.', 'peer-review-system'),
        );

        foreach ($validations as $key => $value) {
            if (isset($data[$key]) && empty($data[$key])) {
                $json['title']    = esc_html__("Oops!", 'peer-review-system');
                $json['type']     = 'error';
                $json['message']  = $value;
                wp_send_json($json);
            }
        }

        if ($edition_id) {
            $edition_post = array(
                'ID'            => $edition_id,
                'post_title'    => wp_strip_all_tags($edition_name),
                'post_status'   => $edition_status,
                'post_content'  => '',
                'post_author'   => $user_id,
                'post_type'     => 'editions',
            );
            /* update post */
            $edition_id    = wp_update_post($edition_post);

            if (!is_wp_error($edition_id)) {
                if (!empty($cover_attachment_url)) {

                    $response   = peer_review_system_temp_upload_to_media($cover_attachment_url, $edition_id);
                    if (!empty($response['attachment_id'])) {
                        update_post_meta($edition_id, '_thumbnail_id', (int)$response['attachment_id']);
                    }
                }
                update_post_meta($edition_id, 'edition_date', $edition_date);
                update_post_meta($edition_id, 'edition_price', $edition_price);
                update_post_meta($edition_id, 'attachments', $attachment_);

                $json['type']    = 'success';
                $json['title']   = esc_html__("Success", 'peer-review-system');
                $json['message'] = esc_html__('Congratulation! Edition info has been updated.!', 'peer-review-system');
                wp_send_json($json);
            } else {
                $json['type']     = 'error';
                $json['title']    = esc_html__("Oops!", 'peer-review-system');
                $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
                wp_send_json($json);
            }
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_edit_edition', 'peer_review_system_edit_edition');
    add_action('wp_ajax_peer_review_system_edit_edition', 'peer_review_system_edit_edition');
}

/**
 * Publish edition
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_edition_action')) {
    function peer_review_system_edition_action()
    {
        global $current_user;
        $attachment_ = $json = array();
        $user_id     = $current_user->ID;
        $do_check    = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);

        if ($user_type != 'editor' || empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json);
        }
        $edition_action = !empty($_POST['edition_action']) ? esc_html($_POST['edition_action'])  : '';
        $edition_id     = !empty($_POST['edition_id']) ?  esc_html($_POST['edition_id'])  : 0;

        /* validation form fields */
        $validations = array(
            'edition_action'        => esc_html__('Something went wtong.', 'peer-review-system'),
            'edition_id'    => esc_html__('Something went wrong.', 'peer-review-system'),
        );
        foreach ($validations as $key => $value) {
            if (isset($_POST[$key]) && empty($_POST[$key])) {
                $json['title']    = esc_html__("Oops!", 'peer-review-system');
                $json['type']     = 'error';
                $json['message']  = $value;
                wp_send_json($json);
            }
        }

        if ($edition_id) {
            if (!empty($edition_action) && $edition_action == 'puslish') {
                $edition_post = array(
                    'ID'            => $edition_id,
                    'post_status'   => 'publish',
                );
                /* update post */
                $edition_id    = wp_update_post($edition_post);
                if (!is_wp_error($edition_id)) {

                    /* email to author on edition publish */
                    if (class_exists('Peer_Review_System_Email_Helper')) {
                        if (class_exists('PeerReviewSystemAuthorEmail')) {
                            $assigned_articles = get_post_meta($edition_id, 'assign_articles', true);
                            foreach ($assigned_articles as $article_id) {
                                $article_names[] = get_the_title($article_id);
                            }

                            $articles_name = implode('|', $article_names);
                            $edition_author_id = get_post_field('post_author', $edition_id);
                            $author_detail = get_userdata($edition_author_id);
                            $author_email = !empty($author_detail->user_email) ? $author_detail->user_email : get_option('admin_email', 'info@example.com');
                            $author_name  = !empty($author_detail->display_name) ? $author_detail->display_name : '';

                            $emailData['email']             = $author_email;
                            $emailData['article_names']     = $articles_name;
                            $emailData['user_name']         = $author_name;
                            $emailData['edition_name']      = get_the_title($edition_id);
                            $emailData['edition_link']      = get_permalink($edition_id);

                            $email_helper = new PeerReviewSystemAuthorEmail();
                            $email_helper->email_author_edition_published($emailData);
                        }
                    }

                    $json['type']    = 'success';
                    $json['title']   = esc_html__("Success", 'peer-review-system');
                    $json['message'] = esc_html__('Congratulation! Edition info has been updated.!', 'peer-review-system');
                    wp_send_json($json);
                } else {
                    $json['type']     = 'error';
                    $json['title']    = esc_html__("Oops!", 'peer-review-system');
                    $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
                    wp_send_json($json);
                }
            } else if (!empty($edition_action) && $edition_action == 'remove') {
                $remove_edition = wp_trash_post($edition_id);
                if (!is_wp_error($remove_edition)) {
                    $json['type']    = 'success';
                    $json['title']   = esc_html__("Success", 'peer-review-system');
                    $json['message'] = esc_html__('Edition has been removed.!', 'peer-review-system');
                    wp_send_json($json);
                } else {
                    $json['type']     = 'error';
                    $json['title']    = esc_html__("Oops!", 'peer-review-system');
                    $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
                    wp_send_json($json);
                }
            }
        } else {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_edition_action', 'peer_review_system_edition_action');
    add_action('wp_ajax_peer_review_system_edition_action', 'peer_review_system_edition_action');
}

/**
 * Personal setting
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_personal_setting')) {
    function peer_review_system_personal_setting()
    {
        global $current_user;
        $json       = array();
        $user_id    = $current_user->ID;

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        if (empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json);
        }

        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        $post_data  = !empty($_POST['data']) ? $_POST['data'] : '';
        parse_str($post_data, $data);

        $first_name  = !empty($data['first_name']) ? sanitize_text_field($data['first_name']) : '';
        $last_name   = !empty($data['last_name']) ? sanitize_text_field($data['last_name']) : '';
        $tagline     = !empty($data['tagline']) ? sanitize_text_field($data['tagline']) : '';
        $country     = !empty($data['country']) ? sanitize_text_field($data['country']) : '';
        $address     = !empty($data['address']) ? sanitize_text_field($data['address']) : '';
        $zipcode     = !empty($data['zipcode']) ? sanitize_text_field($data['zipcode']) : '';
        $description = !empty($data['description']) ? sanitize_textarea_field($data['description']) : '';
        /* validation form fields */
        $validations = array(
            'first_name'    => esc_html__('First name is required.', 'peer-review-system'),
            'last_name'     => esc_html__('Last name is required.', 'peer-review-system'),
            'description'   => esc_html__('Description is required.', 'peer-review-system'),
        );
        foreach ($validations as $key => $value) {
            if (isset($data[$key]) && empty($data[$key])) {
                $json['title']    = esc_html__("Oops!", 'peer-review-system');
                $json['type']     = 'error';
                $json['message']  = $value;
                wp_send_json($json);
            }
        }
        $updated = wp_update_user(array(
            'ID' => esc_sql($user_id),
            'first_name'    =>  $first_name,
            'last_name'     => $last_name,
            'user_nicename' => $first_name . ' ' . $last_name,
            'display_name'  => $first_name . ' ' . $last_name,
            'description'   => $description,

        ));

        if (!is_wp_error($updated)) {
            update_user_meta($user_id, 'tagline', $tagline);
            update_user_meta($user_id, 'country', $country);
            update_user_meta($user_id, 'address', $address);
            update_user_meta($user_id, 'zipcode', $zipcode);

            $json['type']    = 'success';
            $json['title']   = esc_html__("Success", 'peer-review-system');
            $json['message'] = esc_html__("Personal details updated successfully", 'peer-review-system');
            wp_send_json($json);
        } else {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_personal_setting', 'peer_review_system_personal_setting');
    add_action('wp_ajax_peer_review_system_personal_setting', 'peer_review_system_personal_setting');
}

/**
 * Change password
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_change_password')) {
    function peer_review_system_change_password()
    {
        global $current_user;
        $json       = array();
        $user_id    = $current_user->ID;

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        if (empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json);
        }

        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        $post_data  = !empty($_POST['data']) ? $_POST['data'] : '';
        parse_str($post_data, $data);

        $old_password  = !empty($data['old_password']) ? sanitize_text_field($data['old_password']) : '';
        $new_password   = !empty($data['new_password']) ? sanitize_text_field($data['new_password']) : '';
        $retype_password     = !empty($data['retype_password']) ? sanitize_text_field($data['retype_password']) : '';
        
        /* validation form fields */
        $validations = array(
            'old_password'      => esc_html__('Old password is required.', 'peer-review-system'),
            'new_password'      => esc_html__('New password is required.', 'peer-review-system'),
            'retype_password'   => esc_html__('Please confirm your password.', 'peer-review-system'),
        );
        foreach ($validations as $key => $value) {
            if (isset($data[$key]) && empty($data[$key])) {
                $json['title']    = esc_html__("Oops!", 'peer-review-system');
                $json['type']     = 'error';
                $json['message']  = $value;
                wp_send_json($json);
            }
        }
        if ( !empty($new_password) && !empty($retype_password) && $new_password !== $retype_password ) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Password does not match.', 'peer-review-system');
            wp_send_json($json);
        }
        $user 			= wp_get_current_user(); //trace($user);
        $is_password 	= wp_check_password($old_password, $user->user_pass, $user->data->ID);

        if($is_password) {
            $updated = wp_update_user(array('ID' => $user_id, 'user_pass' => sanitize_text_field($new_password)));
            if (!is_wp_error($updated)) {
                $json['type']    = 'success';
                $json['title']   = esc_html__("Success", 'peer-review-system');
                $json['message'] = esc_html__("Password has been updated.", 'peer-review-system');
                wp_send_json($json);
            } else {
                $json['type']     = 'error';
                $json['title']    = esc_html__("Oops!", 'peer-review-system');
                $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
                wp_send_json($json);
            }
        } else {
            $json['type'] = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message'] = esc_html__('Old Password doesn\'t matched with the existing password', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_change_password', 'peer_review_system_change_password');
    add_action('wp_ajax_peer_review_system_change_password', 'peer_review_system_change_password');
}

/**
 * User Social Login/Register
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_social_login')) {
    function peer_review_system_social_login()
    {
        global $prs_settings;
        $json   = array();

        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        if (get_option('users_can_register') == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('New Registration is disabled by admin!', 'peer-review-system');
            wp_send_json($json);
        }

        $post_data  = !empty($_POST['data']) ? $_POST['data'] : '';
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        if (!empty($post_data['email'])) {
            $name           = sanitize_text_field($post_data['name']);
            $last_name      = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $first_name     = trim(preg_replace('#' . preg_quote($last_name, '#') . '#', '', $name));
            $user_types      = !empty($post_data['user_type']) ? esc_html($post_data['user_type']) : '';
            $user_email     = !empty($post_data['email']) && is_email($post_data['email']) ? sanitize_email($post_data['email']) : '';
            $login_type     = !empty($post_data['login_type']) ? sanitize_text_field($post_data['login_type']) : '';
            $ID             = email_exists($user_email);
            $full_name    = $first_name . ' ' . $last_name;

            /* User exists do login */
            if (!empty($ID)) {
                $user_data      = get_user_by('email', $user_email);
                $user_identity  = !empty($user_data) ? $user_data->ID : 0;
                $user_type        = apply_filters('PeerReviewSystemGetUserType', $user_identity);
                if (!empty($user_type) && ($user_type == 'author' || $user_type == 'reviewer' || $user_type == 'editor' || $user_type == 'publisher' || $user_type == 'subscriber')) {
                    $redirect   = peer_review_system_dashboard_page_uri('dashboard');
                } else {
                    $redirect   = home_url('/');
                }

                if (!is_user_logged_in()) {
                    if (!is_wp_error($user_data) && isset($user_data->ID) && !empty($user_data->ID)) {
                        wp_clear_auth_cookie();
                        wp_set_current_user($user_data->ID, $user_data->user_login);
                        wp_set_auth_cookie($user_data->ID, true);
                        update_user_caches($user_data);
                        do_action('wp_login', $user_data->user_login, $user_data);
                    }
                }

                $json['type']       = 'success';
                $json['redirect']   = $redirect;
                $json['title']      = esc_html__('Logged in!', 'peer-review-system');
                $json['message']    = esc_html__('You have successfully logged in', 'peer-review-system');
                wp_send_json($json, 200);
            } else {
                if (empty($user_types)) {
                    $json['type']               = 'success';
                    $json['cooseUserType']      = 'model';
                    $json['userData']           = ($post_data);
                    $json['title']              = esc_html__('Registered!', 'peer-review-system');
                    $json['message']            = esc_html__('Choose user type', 'peer-review-system');
                    wp_send_json($json, 200);
                }

                $user_nicename  = sanitize_title($name);
                $userdata   = array(
                    'user_login'    => $user_email,
                    'user_pass'     => '',
                    'user_email'    => $user_email,
                    'user_nicename' => $user_nicename,
                    'display_name'  => $full_name,
                );

                $user_identity = wp_insert_user($userdata);
                wp_update_user(array('ID' => esc_sql($user_identity), 'role' => $user_types, 'user_status' => 1));
                update_user_meta($user_identity, 'first_name', $first_name);
                update_user_meta($user_identity, 'last_name', $last_name);
                update_user_meta($user_identity, 'login_type', $login_type);
                update_user_meta($user_identity, 'show_admin_bar_front', false);
                update_user_meta($user_identity, '_is_verified', 'yes');
                update_user_meta($user_identity, 'identity_verified', 0);
                $picture        = !empty($post_data['picture']) ? esc_html($post_data['picture']) : '';
                update_user_meta($user_identity, 'user_profile_picture', $picture);
                $login_url = !empty($prs_settings['tpl_login']) ? get_permalink($prs_settings['tpl_login']) : wp_login_url();
                /* Send email to users & admin */
                if (class_exists('Peer_Review_System_Email_Helper')) {
                    $blogname = get_option('blogname');
                    $emailData = array();
                    $emailData['name']      = $name;
                    $emailData['email']     = $user_email;
                    $emailData['site']      = $blogname;
                    $emailData['login_url'] = $login_url;
                    /* Welcome Email */
                    if (class_exists('PeerReviewSystemRegistrationEmail')) {
                        $email_helper = new PeerReviewSystemRegistrationEmail();
                        $email_helper->social_registration_user_email($emailData);
                        $email_helper->new_user_register_admin_email($emailData);
                    }
                }

                $user_data = get_user_by('email', $user_email);

                if (!is_user_logged_in()) {
                    if (!is_wp_error($user_data) && isset($user_data->ID) && !empty($user_data->ID)) {
                        wp_clear_auth_cookie();
                        wp_set_current_user($user_data->ID, $user_data->user_login);
                        wp_set_auth_cookie($user_data->ID, true);
                        update_user_caches($user_data);
                        do_action('wp_login', $user_data->user_login, $user_data);
                    }
                }

                $json_message = esc_html__("Congratulation Your account has been created successfully!", 'peer-review-system');
                $dashboard              = peer_review_system_get_page_uri('dashboard');
                $json['type']           = 'success';
                $json['message']        = $json_message;
                $json['redirect']       = wp_specialchars_decode($dashboard);
                wp_send_json($json, 200);
            }
        }
    }
    add_action('wp_ajax_nopriv_peer_review_system_social_login', 'peer_review_system_social_login');
    add_action('wp_ajax_peer_review_system_social_login', 'peer_review_system_social_login');
}

/**
 * Conversation between reviewer and editor
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_revision_reply')) {
    function peer_review_system_revision_reply()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $userType       = apply_filters('PeerReviewSystemGetUserType', $current_user->ID);
        $post_data      = !empty($_POST['data']) ? $_POST['data']  : array();
        parse_str($post_data, $data);

        /* check is demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $article_status         = !empty($data['prs_revision_status']) ? esc_html($data['prs_revision_status']) : '';
        $article_status_desc    = !empty($data['prs_revision_status_desc']) ? sanitize_textarea_field($data['prs_revision_status_desc']) : '';
        $article_id             = !empty($data['prs_article_id']) ? intval($data['prs_article_id']) : 0;
        $replyType              = !empty($data['prs_single_reply']) ? $data['prs_single_reply']  : 0;
        $receiver_id             = !empty($data['prs_single_receiver']) ? intval($data['prs_single_receiver'])  : 0;

        if ($userType == 'editor' && empty($receiver_id)) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Please assign reviewer first to start converstation', 'peer-review-system');
            wp_send_json($json, 203);
        }

        /* if editor review on article so close more revision by reviewer */
        $is_allow_more_reply = get_post_meta($article_id, 'prs_article_reviewer_no_more_comments', true);
        if ($is_allow_more_reply == '1' && $userType != 'editor') {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('No more revision accepted!', 'peer-review-system');
            wp_send_json($json, 203);
        }

        /* 0 mean status is also required */
        if ($userType == 'reviewer' && empty($article_status)) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Article status is missing!', 'peer-review-system');
            wp_send_json($json, 203);
        }

        /* validation */
        if (empty($article_status_desc) ) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Please add the comments.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        if (empty($article_id)) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Something is missing.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        $article_author_id  = get_post_field('post_author', $article_id);
        $article_author_id  = !empty($article_author_id) ? $article_author_id : 0;
        $userdata           = !empty($user_id)  ? get_userdata($user_id) : array();
        $user_email         = !empty($userdata) ? $userdata->user_email : '';
        $user_name          = !empty($userdata) ? $userdata->display_name : '';
        $comment_data   = array(
            'comment_post_ID'           => intval($article_id),
            'comment_author'            => wp_strip_all_tags($user_name),
            'comment_author_email'      => sanitize_email($user_email),
            'comment_author_url'        => 'http://',
            'comment_content'           => $article_status_desc,
            'comment_type'              => 'prs_article_review',
            'user_id'                   => absint($user_id),
            'comment_date'              => current_time('mysql'),
            'comment_parent'            => 0,
            'comment_approved'          => 1,
            'comment_meta'         => array(
                'prs_comment_article_review_status' => $article_status,
                'prs_comment_article_author_id'     => $article_author_id,
                'sender_id'     => $user_id,
                'receiver_id'   => $receiver_id,
            ),
        );
        /* insert comment data */
        $comment_id = wp_insert_comment($comment_data);

        if (!empty($comment_id)) {
            /* update reviewer status reply to editor */
            update_post_meta($article_id, 'prs_comment_article_review_status', $article_status);

            /* conversation between editor and reviewer */
            if (class_exists('Peer_Review_System_Email_Helper')) {
                /* sender details */
                $sender_id      = $user_id;
                $sender_detail  = get_userdata($sender_id);
                $sender_name    = !empty($sender_detail->display_name) ? $sender_detail->display_name : '';
                $sender_status  = $article_status_desc;
                $sender_email   = !empty($sender_detail->user_email) ? $sender_detail->user_email : get_option('admin_email', 'info@example.com');

                /* reveiver details */
                $receiver_id        = $receiver_id;
                $receiver_detail    = get_userdata($receiver_id);
                $receiver_name      = !empty($receiver_detail->display_name) ? $receiver_detail->display_name : '';;
                $receiver_email     = !empty($receiver_detail->user_email) ? $receiver_detail->user_email : get_option('admin_email', 'info@example.com');
                $emailData = array();
                $emailData['user_name']         = $receiver_name;
                $emailData['email']             = $receiver_email;
                $emailData['article_name']      = get_the_title($article_id);

                if (apply_filters('PeerReviewSystemGetUserType', $sender_id) == 'reviewer') {
                    if (class_exists('PeerReviewSystemEditorEmail')) {
                        $emailData['reviewer_name']     = $sender_name;
                        $emailData['review_status']     = $sender_status;
                        $email_helper = new PeerReviewSystemEditorEmail();
                        $email_helper->email_editor_comment_on_assigned_article($emailData);
                    }
                } else {
                    if (class_exists('PeerReviewSystemReviewerEmail')) {
                        $emailData['editor_name']       = $sender_name;
                        $emailData['editor_status']     = $sender_status;
                        $email_helper = new PeerReviewSystemReviewerEmail();
                        $email_helper->email_reviewer_further_review_request($emailData);
                    }
                }
            }

            $json['type']           = 'success';
            $json['title']          = esc_html__("Success!", 'peer-review-system');
            $json['message']        = esc_html__("Congratulation your review has been submited successfully!", 'peer-review-system');
            wp_send_json($json, 200);
        } else {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Something went wrong.', 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
    add_action('wp_ajax_peer_review_system_revision_reply', 'peer_review_system_revision_reply');
}


/**
 * Conversation between author and editor
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_author_article_conversation')) {
    function peer_review_system_author_article_conversation()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $userType       = apply_filters('PeerReviewSystemGetUserType', $current_user->ID);
        $post_data      = !empty($_POST['data']) ? $_POST['data']  : array();
        parse_str($post_data, $data);

        /* check is demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $article_status         = !empty($data['prs_revision_status']) ? esc_html($data['prs_revision_status']) : '';
        $article_status_desc    = !empty($data['prs_revision_status_desc']) ? sanitize_textarea_field($data['prs_revision_status_desc']) : '';
        $article_id             = !empty($data['prs_article_id']) ? intval($data['prs_article_id']) : 0;
        $receiver_id             = !empty($data['prs_single_receiver']) ? intval($data['prs_single_receiver'])  : 0;

        /* validation */
        if (empty($article_status_desc) || empty($article_id)) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Something is missing.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        /* if editor review on article so close more revision by reviewer */
        $is_allow_more_reply = get_post_meta($article_id, 'prs_article_reviewer_no_more_comments', true);

        if ($is_allow_more_reply == '1' && !in_array($userType, array('editor', 'author'))) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('No more revision accepted!', 'peer-review-system');
            wp_send_json($json, 203);
        }

        $article_author_id  = get_post_field('post_author', $article_id);
        $article_author_id  = !empty($article_author_id) ? $article_author_id : 0;
        $userdata           = !empty($user_id)  ? get_userdata($user_id) : array();
        $user_email         = !empty($userdata) ? $userdata->user_email : '';
        $user_name          = !empty($userdata) ? $userdata->display_name : '';
        $comment_data   = array(
            'comment_post_ID'           => intval($article_id),
            'comment_author'            => wp_strip_all_tags($user_name),
            'comment_author_email'      => sanitize_email($user_email),
            'comment_author_url'        => 'http://',
            'comment_content'           => $article_status_desc,
            'comment_type'              => 'prs_article_review',
            'user_id'                   => absint($user_id),
            'comment_date'              => current_time('mysql'),
            'comment_parent'            => 0,
            'comment_approved'          => 1,
            'comment_meta'         => array(
                'prs_comment_article_review_status' => $article_status,
                'prs_comment_article_author_id'     => $article_author_id,
                'sender_id'     => $user_id,
                'receiver_id'   => $receiver_id,
            ),
        );

        /* insert comment data */
        $comment_id = wp_insert_comment($comment_data);
        /* published by editor or set other status */
        $emailData = array();
        $author_detail = get_userdata($article_author_id);
        $author_email = !empty($author_detail->user_email) ? $author_detail->user_email : get_option('admin_email', 'info@example.com');
        $author_name  = !empty($author_detail->display_name) ? $author_detail->display_name : '';
        if ($userType == 'editor') {

            /* update editor status */
            update_post_meta($article_id, 'prs_article_editor_status', $article_status);
            /* update article submitted status */
            update_post_meta($article_id, 'article_submitted_status', $article_status);

            if ($article_status === 'approved') {
                update_post_meta($article_id, 'prs_article_reviewer_no_more_comments', '1');
                update_post_meta($article_id, 'prs_publishing_status', 'awaiting_production');
                peer_review_system_notify_publishers_awaiting_production($article_id);
                /* email to author about your article is approved */
                if (class_exists('Peer_Review_System_Email_Helper')) {
                    if (class_exists('PeerReviewSystemAuthorEmail')) {
                        $article_link      = Peer_Review_System_Profile_Menu::peer_review_system_profile_admin_menu_link('', $article_author_id, true, 'article-details', $article_id);
                        $emailData['email']             = $author_email;
                        $emailData['article_name']      = get_the_title($article_id);
                        $emailData['user_name']         = $author_name;
                        $emailData['editor_name']       = $user_name;
                        $emailData['article_link']       = $article_link;
                        $email_helper = new PeerReviewSystemAuthorEmail();
                        $email_helper->email_author_article_approved($emailData);
                    }
                }
            } elseif ($article_status == 'rejected') {
                if (class_exists('Peer_Review_System_Email_Helper')) {
                    if (class_exists('PeerReviewSystemAuthorEmail')) {
                        $emailData['email']             = $author_email;
                        $emailData['article_name']      = get_the_title($article_id);
                        $emailData['user_name']         = $author_name;
                        $emailData['editor_name']       = $user_name;
                        $emailData['review_status']     = $article_status;
                        $emailData['review_comment']    = $article_status_desc;
                        $email_helper = new PeerReviewSystemAuthorEmail();
                        $email_helper->email_author_article_rejected($emailData);
                    }
                }
            } elseif ($article_status == 'minor_revision' || $article_status == 'major_revision') {
                if (class_exists('Peer_Review_System_Email_Helper')) {
                    if (class_exists('PeerReviewSystemAuthorEmail')) {
                        $emailData['email']             = $author_email;
                        $emailData['article_name']      = get_the_title($article_id);
                        $emailData['user_name']         = $author_name;
                        $emailData['editor_name']       = $user_name;
                        $emailData['review_status']     = $article_status;
                        $emailData['feedback']          = $article_status_desc;
                        $email_helper = new PeerReviewSystemAuthorEmail();
                        $email_helper->email_author_article_revision_required($emailData);
                    }
                }
            }
        } else {
            /* Email to editor on author comment/reply */
            if (class_exists('Peer_Review_System_Email_Helper')) {
                if (class_exists('PeerReviewSystemEditorEmail')) {
                    $editor_detail  = get_userdata($receiver_id);
                    $editor_email   = !empty($editor_detail->user_email) ? $editor_detail->user_email : get_option('admin_email', 'info@example.com');
                    $editor_name    = !empty($editor_detail->display_name) ?  $editor_detail->display_name : '';
                    $emailData['email']             = $editor_email;
                    $emailData['article_name']      = get_the_title($article_id);
                    $emailData['user_name']         = $editor_name;
                    $emailData['author_name']       = $author_name;
                    $emailData['author_comment']    = $article_status_desc;
                    $email_helper = new PeerReviewSystemEditorEmail();
                    $email_helper->email_editor_author_comment_to_editor($emailData);
                }
            }
        }

        if (!empty($comment_id)) {
            /* email to author about reviewer send review on your article */
            $json['type']           = 'success';
            $json['title']          = esc_html__("Success!", 'peer-review-system');
            $json['message']        = esc_html__("Congratulation your review has been submited successfully!", 'peer-review-system');
            wp_send_json($json, 200);
        } else {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Something went wrong.', 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
    add_action('wp_ajax_peer_review_system_author_article_conversation', 'peer_review_system_author_article_conversation');
}

/**
 
 * File uploader
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_temp_multiple_files_uploader')) {
    function peer_review_system_temp_multiple_files_uploader()
    {
        if (function_exists('peer_is_demo_site')) {
            peer_review_system_is_demo_site();
        }
        /*=================== Wp Nonce Verification =================*/
        $json       = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'ajax_nonce', false);
        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        $files      = !empty($_FILES['file']) ? $_FILES['file'] : array();
        $fileSizes  = !empty($_POST['fileSizes']) ? $_POST['fileSizes'] : array();
        $_POST = array(
            'files'     => $files,
            'sizes'     => $fileSizes,
            'dir'       => 'temp',
        );
        /*=================== End Wp Nonce Verification =================*/

        $response = Peer_Review_System_file_permission::uploadAttachment($_POST);

        if (!empty($response['type']) && $response['type'] == 'success') {
            $json['attachments']    = $response['attachments']['0'];
            $json['type']           = 'success';
            wp_send_json($json);
        } else {
            wp_send_json($response);
        }
    }
    add_action('wp_ajax_peer_review_system_temp_multiple_files_uploader', 'peer_review_system_temp_multiple_files_uploader');
}

/**
 * Assign article to reviewers
 */
if (!function_exists('peer_review_system_assign_article_reviewer')) {
    function peer_review_system_assign_article_reviewer()
    {
        global $current_user;
        $json          = array();
        $user_id       = $current_user->ID;
        $userType      = apply_filters('PeerReviewSystemGetUserType', $user_id);
        $reviewer_ids  = !empty($_POST['reviewer_ids']) ? array_map('intval', $_POST['reviewer_ids']) : array();
        $article_id    = !empty($_POST['article_id']) ? intval($_POST['article_id'])  : 0;
        $editor_id     = !empty($_POST['editor_id']) ? intval($_POST['editor_id'])  : 0;
        $do_check      = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json, 203);
        }

        /* check is demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        if ($userType == 'editor') {
            $saved_reviwers = get_post_meta($article_id, 'prs_article_assigned_reviwers', true);
            $saved_reviwers = !empty($saved_reviwers) ? array_map('intval', $saved_reviwers) : array();

            /* sort the array for comparing */
            sort($reviewer_ids);
            if (!empty($saved_reviwers)) {
                $new_reviewer_ids_arr = $saved_reviwers;
                sort($saved_reviwers);
                if (array_values($reviewer_ids) !== array_values($saved_reviwers)) {
                    $new_added_ids    = array_diff($reviewer_ids, $saved_reviwers);

                    if (!empty($new_added_ids)) {
                        $new_reviewer_ids_arr = array_merge($saved_reviwers, $new_added_ids);
                        /* email to new added reviewers by the editor */
                        peer_review_system_email_to_users($new_added_ids, $article_id, $editor_id);
                    }

                    $new_removed_ids    = array_diff($saved_reviwers, $reviewer_ids);
                    if (!empty($new_removed_ids)) {
                        foreach ($new_removed_ids as $key => $val) {
                            $key = array_search((int)$val, $saved_reviwers);
                            if ($key !== false) {
                                unset($new_reviewer_ids_arr[$key]);
                            }
                        }
                    }
                    update_post_meta($article_id, 'prs_article_assigned_reviwers', $new_reviewer_ids_arr);
                }
            } else {
                update_post_meta($article_id, 'prs_article_assigned_reviwers', $reviewer_ids);
                /* email to added reviewers by the editor */
                peer_review_system_email_to_users($reviewer_ids, $article_id, $editor_id);
            }

            /*
             * Make sure every assigned account can actually review. Authors selected
             * from the reviewer pool only have the `article_author` role, so they would
             * never see the reviewer dashboard. Grant the reviewer role additively.
             */
            if (function_exists('peer_review_system_grant_reviewer_role')) {
                peer_review_system_grant_reviewer_role($reviewer_ids);
            }

            $json['type']           = 'success';
            $json['title']          = esc_html__("Success!", 'peer-review-system');
            $json['message']        = esc_html__("Congratulation! reviewers assigned to this article", 'peer-review-system');
            wp_send_json($json, 200);
        } else {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Sorry you are not allowed', 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
    add_action('wp_ajax_peer_review_system_assign_article_reviewer', 'peer_review_system_assign_article_reviewer');
}

/**
 * Invite an independent (external) reviewer by email and assign them to a manuscript.
 *
 * Creates an `article_reviewer` account when the email is not yet registered,
 * sends a password-setup link, assigns them to the article and emails the
 * standard reviewer invitation.
 */
if (!function_exists('peer_review_system_invite_external_reviewer')) {
    function peer_review_system_invite_external_reviewer()
    {
        global $current_user;
        $json       = array();
        $user_id    = $current_user->ID;
        $userType   = apply_filters('PeerReviewSystemGetUserType', $user_id);
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']    = 'error';
            $json['title']   = esc_html__("Oops!", 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json, 203);
        }

        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        if ($userType !== 'editor' && $userType !== 'administrator') {
            $json['type']    = 'error';
            $json['title']   = esc_html__("Oops!", 'peer-review-system');
            $json['message'] = esc_html__('Sorry you are not allowed', 'peer-review-system');
            wp_send_json($json, 203);
        }

        $article_id = !empty($_POST['article_id']) ? intval($_POST['article_id']) : 0;
        $editor_id  = !empty($_POST['editor_id']) ? intval($_POST['editor_id']) : $user_id;
        $first_name = !empty($_POST['first_name']) ? sanitize_text_field(wp_unslash($_POST['first_name'])) : '';
        $last_name  = !empty($_POST['last_name']) ? sanitize_text_field(wp_unslash($_POST['last_name'])) : '';
        $email      = !empty($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';

        if (empty($article_id)) {
            $json['type']    = 'error';
            $json['title']   = esc_html__("Oops!", 'peer-review-system');
            $json['message'] = esc_html__('Something went wrong. Please refresh the page and try again.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        if (empty($email) || !is_email($email)) {
            $json['type']    = 'error';
            $json['title']   = esc_html__("Oops!", 'peer-review-system');
            $json['message'] = esc_html__('Please enter a valid reviewer email address.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        $existing_id = email_exists($email);
        $is_new_user = false;

        if (!empty($existing_id)) {
            $reviewer_id = (int) $existing_id;
        } else {
            if (empty($first_name)) {
                $json['type']    = 'error';
                $json['title']   = esc_html__("Oops!", 'peer-review-system');
                $json['message'] = esc_html__('Please enter the reviewer first name.', 'peer-review-system');
                wp_send_json($json, 203);
            }

            $full_name   = trim($first_name . ' ' . $last_name);
            $reviewer_id = wp_insert_user(array(
                'user_login'    => $email,
                'user_pass'     => wp_generate_password(16, true),
                'user_email'    => $email,
                'user_nicename' => sanitize_title($full_name),
                'display_name'  => $full_name,
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'role'          => 'article_reviewer',
            ));

            if (is_wp_error($reviewer_id)) {
                $json['type']    = 'error';
                $json['title']   = esc_html__("Oops!", 'peer-review-system');
                $json['message'] = $reviewer_id->get_error_message();
                wp_send_json($json, 203);
            }

            update_user_meta($reviewer_id, '_is_verified', 'yes');
            update_user_meta($reviewer_id, 'identity_verified', 1);
            update_user_meta($reviewer_id, 'show_admin_bar_front', false);
            update_user_meta($reviewer_id, '_prs_external_reviewer', 1);
            $is_new_user = true;

            /* Send a password-setup link so the invited reviewer can log in. */
            retrieve_password(get_userdata($reviewer_id)->user_login);
        }

        /* Make sure the account can access the reviewer dashboard. */
        if (function_exists('peer_review_system_grant_reviewer_role')) {
            peer_review_system_grant_reviewer_role(array($reviewer_id));
        }

        /* Assign to the manuscript (avoid duplicates). */
        $assigned = get_post_meta($article_id, 'prs_article_assigned_reviwers', true);
        $assigned = !empty($assigned) ? array_map('intval', (array) $assigned) : array();

        if (in_array($reviewer_id, $assigned, true)) {
            $json['type']    = 'error';
            $json['title']   = esc_html__("Oops!", 'peer-review-system');
            $json['message'] = esc_html__('This reviewer is already assigned to the manuscript.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        $assigned[] = $reviewer_id;
        update_post_meta($article_id, 'prs_article_assigned_reviwers', $assigned);

        /* Standard reviewer invitation email. */
        peer_review_system_email_to_users(array($reviewer_id), $article_id, $editor_id);

        $reviewer_name = peer_review_system_get_username($reviewer_id);

        $json['type']         = 'success';
        $json['title']        = esc_html__("Success!", 'peer-review-system');
        $json['message']      = $is_new_user
            ? esc_html__('Independent reviewer invited, account created and assigned to this manuscript.', 'peer-review-system')
            : esc_html__('Existing reviewer assigned to this manuscript.', 'peer-review-system');
        $json['reviewer_id']   = $reviewer_id;
        $json['reviewer_name'] = ucfirst($reviewer_name);
        wp_send_json($json, 200);
    }
    add_action('wp_ajax_peer_review_system_invite_external_reviewer', 'peer_review_system_invite_external_reviewer');
}

/**
 * Load article
 */
if (!function_exists('peer_review_system_load_article_price')) {
    function peer_review_system_load_article_price()
    {
        $article_data   = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check is demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $article_id     = !empty($_POST['article_id']) ? intval($_POST['article_id'])  : 0;
        if (empty($article_id)) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Something went wrong. Please try again after refresh page.', 'peer-review-system');
            wp_send_json($json);
        }

        if ($article_id) {
            $article                = get_post($article_id);
            $sj_paid_article        = get_post_meta($article_id, 'sj_paid_article', true);
            $article_download_url   = get_post_meta($article_id, 'article_download_url', true);
            $article_price          = get_post_meta($article_id, 'article_price', true);

            $sj_free_checked        = 'checked';
            $sj_download_checked    = '';
            $sj_price_checked       = '';

            if (!empty($sj_paid_article) && $sj_paid_article == 'download') {
                $sj_download_checked    = 'checked';
                $sj_free_checked        = '';
                $sj_price_checked       = '';
            }

            if (!empty($sj_paid_article) && $sj_paid_article == 'price') {
                $sj_price_checked       = 'checked';
                $sj_free_checked       = '';
                $sj_download_checked    = '';
            }

            $article_data   = array(
                'article_id'            => $article_id,
                'article_price'         => $article_price,
                'sj_paid_article'         => $sj_paid_article,
                'article_download_url'  => $article_download_url,
                'sj_free_checked'       => $sj_free_checked,
                'sj_download_checked'   => $sj_download_checked,
                'sj_price_checked'      => $sj_price_checked,
            );

            $json['data']           = $article_data;
            $json['type']           = 'success';
            $json['title']          = esc_html__("Success!", 'peer-review-system');
            $json['message']        = esc_html__("", 'peer-review-system');
            wp_send_json($json, 200);
        } else {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Sorry you are not allowed', 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
    add_action('wp_ajax_peer_review_system_load_article_price', 'peer_review_system_load_article_price');
}


/**
 * Edit edition
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_edit_article_price')) {
    function peer_review_system_edit_article_price()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $attachment_    = array();
        $do_check       = check_ajax_referer('ajax_nonce', 'security', false);

        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);

        if ($user_type != 'editor' || empty($user_id)) {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Sorry! you are not allowed.', 'peer-review-system');
            wp_send_json($json);
        }
        $post_data   = !empty($_POST['data']) ? $_POST['data']  : '';
        parse_str($post_data, $data);
        $article_id     = !empty($data['article_id']) ?  intval($data['article_id'])  : '';
        $sj_paid_article   = !empty($data['sj_paid_article']) ?  sanitize_text_field($data['sj_paid_article'])  : 'free';
        $article_download_url   = !empty($data['article_download_url']) ?  sanitize_text_field($data['article_download_url'])  : '';
        $article_price  = !empty($data['article_price']) ?  sanitize_text_field($data['article_price'])  : '';

        /* validation form fields */
        if($sj_paid_article == 'download'){
            $validations = array(
                'article_download_url'   => esc_html__('Article third party download URL is required.', 'peer-review-system'),
            );
        } elseif($sj_paid_article == 'price'){
            $validations = array(
                'article_price'  => esc_html__('article price is required.', 'peer-review-system'),
            );
        }

        foreach ($validations as $key => $value) {
            if (isset($data[$key]) && empty($data[$key])) {
                $json['title']    = esc_html__("Oops!", 'peer-review-system');
                $json['type']     = 'error';
                $json['message']  = $value;
                wp_send_json($json);
            }
        }

        if (!is_wp_error($article_id)) {
                
            update_post_meta($article_id, 'sj_paid_article', $sj_paid_article);
            update_post_meta($article_id, 'article_download_url', $article_download_url);
            update_post_meta($article_id, 'article_price', $article_price);

            $json['type']    = 'success';
            $json['title']   = esc_html__("Success", 'peer-review-system');
            $json['message'] = esc_html__('Congratulation! article download info has been updated.!', 'peer-review-system');
            wp_send_json($json);
        } else {
            $json['type']     = 'error';
            $json['title']    = esc_html__("Oops!", 'peer-review-system');
            $json['message']  = esc_html__('Something went wrong.', 'peer-review-system');
            wp_send_json($json);
        }

        
    }
     add_action('wp_ajax_peer_review_system_edit_article_price', 'peer_review_system_edit_article_price');
}

/**
 * Load Edition
 */
if (!function_exists('peer_review_system_load_edition')) {
    function peer_review_system_load_edition()
    {
        $edition_data   = array();
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check is demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $edition_id     = !empty($_POST['edition_id']) ? intval($_POST['edition_id'])  : 0;
        if (empty($edition_id)) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Something went wrong. Please try again after refresh page.', 'peer-review-system');
            wp_send_json($json);
        }
        if (has_post_thumbnail($edition_id)) {
            $feature_image  = get_the_post_thumbnail_url($edition_id, 'sj_profile_thumbnail');
            $image_file        = $feature_image;
            $isProfile_img  =  1;
            $edition_data['feature_image'] = $feature_image;
        } else {
            $defaultAvatar = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/default-avatar.jpg';
            $edition_data['feature_image'] = $defaultAvatar;
        }

        if ($edition_id) {
            $edition            = get_post($edition_id);
            $edition_price      = get_post_meta($edition_id, 'edition_price', true);
            $edition_date       = get_post_meta($edition_id, 'edition_date', true);

            $edition_data['ID']                 = $edition_id;
            $edition_data['title']              = $edition->post_title;
            $edition_data['edition_price']      = $edition_price;
            $edition_data['edition_date']       = $edition_date;

            $json['data']           = $edition_data;
            $json['type']           = 'success';
            $json['title']          = esc_html__("Success!", 'peer-review-system');
            $json['message']        = esc_html__("Congratulation! reviewers assigned to this article", 'peer-review-system');
            wp_send_json($json, 200);
        } else {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('Sorry you are not allowed', 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
    add_action('wp_ajax_peer_review_system_load_edition', 'peer_review_system_load_edition');
}

/**
 * Load assign article
 */
if (!function_exists('peer_review_system_load_assign_article')) {
    function peer_review_system_load_assign_article()
    {
        global $current_user;
        $do_check   = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type']    = 'error';
            $json['title']   = esc_html__("Oops!", 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        /* check is demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $user_id    = $current_user->ID;
        $user_id    = !empty($user_id) ? intval($user_id) : '';
        $edition_id = !empty($_POST['edition_id']) ? intval($_POST['edition_id'])  : 0;

        if (empty($edition_id) || empty($user_id)) {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oopssss!", 'peer-review-system');
            $json['message']    = esc_html__('Something went wrong. Please try again after refresh page.', 'peer-review-system');
            wp_send_json($json);
        }
        $per_page   = get_option('posts_per_page');
        $pg_page    = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
        $pg_paged   = get_query_var('paged') ? get_query_var('paged') : 1;
        $paged      = max($pg_page, $pg_paged);
        /* getting all articles */
        $query_args = array(
            'post_type'             => 'articles',
            'post_status'           => 'publish',
            'posts_per_page'        => $per_page,
            'paged'                 => $paged,
            'orderby'               => 'date',
            'order'                 => 'DESC',
            'author'                => $user_id,
        );
        $articles_data = new WP_Query(apply_filters('peer_review_system_my_articles_filter', $query_args));
        $articles_info      = $selected_articles = $edition_data = array();
        $selected_articles  = get_post_meta($edition_id, 'assign_articles', true);

        if (!empty($articles_data) || !empty($selected_articles)) {
            while ($articles_data->have_posts()) {
                $articles_data->the_post();
                global $post;
                $author_id          = $post->post_author;
                $post_id            = !empty($post->ID) ? intval($post->ID) : '';
                $title              = esc_html(get_the_title());
                $articles_info[$post_id] = $title;
            }

            $edition_data['edition_id']        = $edition_id;
            $edition_data['author_id']         = $user_id;
            $edition_data['articles_info']     = $articles_info;
            $edition_data['selected_articles'] = $selected_articles;

            $json['type']       = 'success';
            $json['title']      = esc_html__('Success!', 'peer-review-system');
            $json['message']    = esc_html__('Article data', 'peer-review-system');
            $json['data']       = $edition_data;
            wp_send_json($json);
        } else {
            $json['type']       = 'error';
            $json['title']      = esc_html__("Oops!", 'peer-review-system');
            $json['message']    = esc_html__('No data available', 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
    add_action('wp_ajax_peer_review_system_load_assign_article', 'peer_review_system_load_assign_article');
}

/**
 * Download all doc files for editor
 */
if (!function_exists('peer_review_system_download_files_as_zip')) {
    function peer_review_system_download_files_as_zip()
    {
        /* check is demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $json    = array();
        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type'] = 'error';
            $json['title'] = esc_html__('Failed!', 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        $json             = array();
        $article_id         = !empty($_POST['article_id']) ? intval($_POST['article_id']) : 0;
        $json['message']  = esc_html__('Download files', 'peer-review-system');

        if (!empty($article_id)) {
            $attachments_files  = get_post_meta($article_id, 'attachments', true);
            $attachments_files  = !empty($attachments_files) ? array_reverse($attachments_files) : array();
            if (!empty($attachments_files)) {

                if (class_exists('ZipArchive')) {
                    $zip                = new ZipArchive();
                    $uploadspath        = wp_upload_dir();
                    $folderRalativePath = $uploadspath['baseurl'] . "/downloads";
                    $folderAbsolutePath = $uploadspath['basedir'] . "/downloads";
                    wp_mkdir_p($folderAbsolutePath);
                    $filename    = 'article-' . $article_id . '-' . round(microtime(true)) . '.zip';
                    $zip_name   = $folderAbsolutePath . '/' . $filename;
                    $zip->open($zip_name,  ZipArchive::CREATE);
                    $download_url    = $folderRalativePath . '/' . $filename;

                    foreach ($attachments_files as $key => $value) {
                        if (!empty($value['attachment_url'])) {
                            $file_url       = $value['attachment_url'];
                            $response       = wp_remote_get($file_url);
                            $filedata       = wp_remote_retrieve_body($response);
                            $zip->addFromString(basename($file_url), $filedata);
                        }
                    }

                    $zip->close();
                } else {
                    $json['type']           = 'error';
                    $json['message']        = esc_html__('Oops', 'peer-review-system');
                    $json['message_desc']   = esc_html__('Zip library is not installed on the server, please contact to hosting provider', 'peer-review-system');
                    wp_send_json($json);
                }
            }

            $json['type']           = 'success';
            $json['attachment']     = peer_review_system_add_http_protcol($download_url);
            $json['message_desc']   = esc_html__('Your files have been donwloaded', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_peer_review_system_download_files_as_zip', 'peer_review_system_download_files_as_zip');
}

/**
 * @init            Import user
 * @package         Peer_Review_System
 * @subpackage      Peer_Review_System/Public/Partials
 * @since           1.0
 */
if (!function_exists('peer_review_system_import_users')) {
    function  peer_review_system_import_users()
    {
        $import_user    = new PeerReviewSystemImportUser();
        $import_user->peer_review_system_import_user();
        //security check
        $do_check = check_ajax_referer('ajax_nonce', 'security', false);

        $json    = array();
        if ($do_check == false) {
            $json['type'] = 'error';
            $json['title'] = esc_html__('Failed!', 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        $json['type']       = 'success';
        $json['title']      = esc_html__('Success!', 'peer-review-system');
        $json['message']    = esc_html__('Users have been imported successfully', 'peer-review-system');
        wp_send_json($json);
    }
    add_action('wp_ajax_peer_review_system_import_users', 'peer_review_system_import_users');
}

/**
 * Download article papers
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_download_zip_file')) {
    function peer_review_system_download_zip_file()
    {
        /* check is demo site */
        if (function_exists('peer_review_system_is_demo_site')) {
            peer_review_system_is_demo_site();
        }

        $json    = array();
        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type'] = 'error';
            $json['title'] = esc_html__('Failed!', 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        $json             = array();
        $article_id       = !empty($_POST['article_id']) ? intval($_POST['article_id']) : 0;
        $json['message']  = esc_html__('Download files', 'peer-review-system');

        if (!empty($article_id)) {
            $attachments_files  = get_post_meta($article_id, 'attachments', true);
            $attachments_files  = !empty($attachments_files) ? array_reverse($attachments_files) : array();
            if (!empty($attachments_files)) {

                if (class_exists('ZipArchive')) {
                    $zip                = new ZipArchive();
                    $uploadspath        = wp_upload_dir();
                    $folderRalativePath = $uploadspath['baseurl'] . "/downloads";
                    $folderAbsolutePath = $uploadspath['basedir'] . "/downloads";
                    wp_mkdir_p($folderAbsolutePath);
                    $filename    = 'article-' . $article_id . '-' . round(microtime(true)) . '.zip';
                    $zip_name   = $folderAbsolutePath . '/' . $filename;
                    $zip->open($zip_name,  ZipArchive::CREATE);
                    $download_url    = $folderRalativePath . '/' . $filename;

                    foreach ($attachments_files as $key => $value) {
                        if ($key == '0') {
                            if (!empty($value['attachment_url'])) {
                                $file_url    = $value['attachment_url'];
                                $response    = wp_remote_get($file_url);
                                $filedata   = wp_remote_retrieve_body($response);
                                $zip->addFromString(basename($file_url), $filedata);
                            }
                        }
                    }

                    $zip->close();
                } else {
                    $json['type']           = 'error';
                    $json['message']        = esc_html__('Oops', 'peer-review-system');
                    $json['message_desc']   = esc_html__('Zip library is not installed on the server, please contact to hosting provider', 'peer-review-system');
                    wp_send_json($json);
                }
            }

            $json['type']           = 'success';
            $json['attachment']     = peer_review_system_add_http_protcol($download_url);
            $json['message_desc']   = esc_html__('Your files have been donwloaded', 'peer-review-system');
            wp_send_json($json);
        }  else {
            $json['type']       = 'error';
            $json['title']      = esc_html__('Failed!', 'peer-review-system');
            $json['message']    = esc_html__('Something went wrong.', 'peer-review-system');
            wp_send_json($json);
        }
    }
    add_action('wp_ajax_peer_review_system_download_zip_file', 'peer_review_system_download_zip_file');
}

/**
 * Save production manuscript content before publishing.
 */
if (!function_exists('peer_review_system_save_production_manuscript')) {
    function peer_review_system_save_production_manuscript()
    {
        global $current_user;
        $json      = array();
        $user_id   = $current_user->ID;
        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);
        $post_data = !empty($_POST['data']) ? wp_unslash($_POST['data']) : '';
        parse_str($post_data, $data);

        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        if ($user_type !== 'publisher' && $user_type !== 'administrator') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('You are not allowed to perform this action.', 'peer-review-system');
            wp_send_json($json);
        }

        $article_id = !empty($data['prs_article_id']) ? absint($data['prs_article_id']) : 0;
        if (empty($article_id) || get_post_meta($article_id, 'prs_article_editor_status', true) !== 'approved') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Invalid manuscript.', 'peer-review-system');
            wp_send_json($json);
        }

        $published_excerpt = !empty($data['published_excerpt']) ? sanitize_textarea_field($data['published_excerpt']) : '';
        $published_title   = !empty($data['published_title']) ? sanitize_text_field($data['published_title']) : '';

        if (!empty($published_title)) {
            wp_update_post(
                array(
                    'ID'           => $article_id,
                    'post_title'   => $published_title,
                    'post_excerpt' => $published_excerpt,
                )
            );
        } elseif ($published_excerpt !== '') {
            wp_update_post(
                array(
                    'ID'           => $article_id,
                    'post_excerpt' => $published_excerpt,
                )
            );
        }

        peer_review_system_save_production_uploads($article_id, $data);
        peer_review_system_maybe_save_article_doi($article_id, $data);
        peer_review_system_maybe_save_article_citation($article_id, $data);
        update_post_meta($article_id, 'prs_publisher_id', $user_id);
        update_post_meta($article_id, 'prs_publishing_status', 'in_production');

        $json['type']    = 'success';
        $json['title']   = esc_html__('Saved!', 'peer-review-system');
        $json['message'] = esc_html__('Production draft saved successfully.', 'peer-review-system');
        wp_send_json($json);
    }
    add_action('wp_ajax_peer_review_system_save_production_manuscript', 'peer_review_system_save_production_manuscript');
}

/**
 * Normalise a DOI submitted by the publisher: strip a doi.org URL or "doi:" prefix
 * and keep just the bare identifier (e.g. 10.1234/abc).
 *
 * @param string $raw Raw DOI input.
 * @return string Sanitised DOI, or empty string if it doesn't look like a DOI.
 */
if (!function_exists('peer_review_system_sanitize_doi')) {
    function peer_review_system_sanitize_doi($raw)
    {
        $doi = trim((string) $raw);
        if ($doi === '') {
            return '';
        }

        $doi = preg_replace('#^\s*(?:https?://)?(?:dx\.)?doi\.org/#i', '', $doi);
        $doi = preg_replace('#^\s*doi:\s*#i', '', $doi);
        $doi = trim($doi);

        // A valid DOI always starts with "10." followed by a registrant code.
        if (!preg_match('#^10\.\S+/\S+#', $doi)) {
            return '';
        }

        return sanitize_text_field($doi);
    }
}

/**
 * Persist a DOI from a submitted production/DOI form if one was provided.
 * An empty submission clears any stored DOI.
 *
 * @param int   $article_id Article ID.
 * @param array $data       Parsed form data.
 * @return void
 */
if (!function_exists('peer_review_system_maybe_save_article_doi')) {
    function peer_review_system_maybe_save_article_doi($article_id, $data)
    {
        if (!isset($data['prs_article_doi'])) {
            return;
        }

        $raw = trim((string) $data['prs_article_doi']);
        if ($raw === '') {
            delete_post_meta($article_id, 'prs_article_doi');
            return;
        }

        $doi = peer_review_system_sanitize_doi($raw);
        if ($doi !== '') {
            update_post_meta($article_id, 'prs_article_doi', $doi);
        }
    }
}

/**
 * Persist optional bibliographic citation fields without touching other post data.
 *
 * @param int   $article_id Article ID.
 * @param array $data       Parsed form data.
 */
if (!function_exists('peer_review_system_maybe_save_article_citation')) {
    function peer_review_system_maybe_save_article_citation($article_id, $data)
    {
        $map = array(
            'prs_citation_volume' => 'prs_citation_volume',
            'prs_citation_issue'  => 'prs_citation_issue',
            'prs_citation_pages'  => 'prs_citation_pages',
            'prs_citation_year'   => 'prs_citation_year',
        );

        foreach ($map as $field => $meta_key) {
            if (!isset($data[$field])) {
                continue;
            }
            $value = sanitize_text_field((string) $data[$field]);
            if ($value === '') {
                delete_post_meta($article_id, $meta_key);
            } else {
                update_post_meta($article_id, $meta_key, $value);
            }
        }
    }
}

/**
 * Save / update the DOI for an already-published article from the live view.
 */
if (!function_exists('peer_review_system_save_article_doi')) {
    function peer_review_system_save_article_doi()
    {
        global $current_user;
        $json      = array();
        $user_id   = $current_user->ID;
        $user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);
        $post_data = !empty($_POST['data']) ? wp_unslash($_POST['data']) : '';
        parse_str($post_data, $data);

        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        if ($user_type !== 'publisher' && $user_type !== 'administrator') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('You are not allowed to perform this action.', 'peer-review-system');
            wp_send_json($json);
        }

        $article_id = !empty($data['prs_article_id']) ? absint($data['prs_article_id']) : 0;
        if (empty($article_id) || get_post_type($article_id) !== 'articles') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Invalid manuscript.', 'peer-review-system');
            wp_send_json($json);
        }

        $raw = isset($data['prs_article_doi']) ? trim((string) $data['prs_article_doi']) : '';
        peer_review_system_maybe_save_article_citation($article_id, $data);
        if ($raw === '') {
            delete_post_meta($article_id, 'prs_article_doi');
            $json['type']    = 'success';
            $json['title']   = esc_html__('Saved!', 'peer-review-system');
            $json['message'] = esc_html__('Citation details updated.', 'peer-review-system');
            wp_send_json($json);
        }

        $doi = peer_review_system_sanitize_doi($raw);
        if ($doi === '') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('That does not look like a valid DOI. It should start with "10." (e.g. 10.1234/jasetui.2026.001).', 'peer-review-system');
            wp_send_json($json);
        }

        update_post_meta($article_id, 'prs_article_doi', $doi);
        peer_review_system_maybe_save_article_citation($article_id, $data);

        $json['type']    = 'success';
        $json['title']   = esc_html__('Saved!', 'peer-review-system');
        $json['message'] = esc_html__('Citation details updated.', 'peer-review-system');
        wp_send_json($json);
    }
    add_action('wp_ajax_peer_review_system_save_article_doi', 'peer_review_system_save_article_doi');
}

/**
 * Publishing team actions: start production or publish to website.
 */
if (!function_exists('peer_review_system_publishing_action')) {
    function peer_review_system_publishing_action()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $user_type      = apply_filters('PeerReviewSystemGetUserType', $user_id);
        $post_data      = !empty($_POST['data']) ? wp_unslash($_POST['data']) : '';
        parse_str($post_data, $data);
        $article_id     = !empty($data['prs_article_id']) ? absint($data['prs_article_id']) : 0;
        $publish_action = !empty($data['prs_publish_action']) ? sanitize_key($data['prs_publish_action']) : '';

        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json);
        }

        if ($user_type !== 'publisher' && $user_type !== 'administrator') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('You are not allowed to perform this action.', 'peer-review-system');
            wp_send_json($json);
        }

        if (empty($article_id) || get_post_meta($article_id, 'prs_article_editor_status', true) !== 'approved') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Invalid manuscript.', 'peer-review-system');
            wp_send_json($json);
        }

        if ($publish_action === 'start_production') {
            update_post_meta($article_id, 'prs_publishing_status', 'in_production');
            update_post_meta($article_id, 'prs_publisher_id', $user_id);

            $json['type']    = 'success';
            $json['title']   = esc_html__('Started!', 'peer-review-system');
            $json['message'] = esc_html__('Manuscript moved to in production.', 'peer-review-system');
            wp_send_json($json);
        }

        if ($publish_action === 'publish_to_website') {
            $existing_pdf = peer_review_system_get_production_pdf($article_id);
            $has_new_pdf  = !empty($data['production_pdf']['file']);
            if (empty($existing_pdf['attachment_url']) && !$has_new_pdf) {
                $json['type']    = 'error';
                $json['title']   = esc_html__('Oops!', 'peer-review-system');
                $json['message'] = esc_html__('Please upload a formatted PDF before publishing.', 'peer-review-system');
                wp_send_json($json);
            }

            peer_review_system_save_production_uploads($article_id, $data);
            peer_review_system_maybe_save_article_doi($article_id, $data);
            peer_review_system_maybe_save_article_citation($article_id, $data);

            $published_title   = !empty($data['published_title']) ? sanitize_text_field($data['published_title']) : '';
            $published_excerpt = !empty($data['published_excerpt']) ? sanitize_textarea_field($data['published_excerpt']) : '';
            if (!empty($published_title) || $published_excerpt !== '') {
                wp_update_post(
                    array(
                        'ID'           => $article_id,
                        'post_title'   => !empty($published_title) ? $published_title : get_the_title($article_id),
                        'post_excerpt' => $published_excerpt,
                    )
                );
            }

            update_post_meta($article_id, 'prs_publishing_status', 'published');
            update_post_meta($article_id, 'prs_publisher_id', $user_id);
            wp_update_post(
                array(
                    'ID'          => $article_id,
                    'post_status' => 'publish',
                )
            );

            $article_author_id = get_post_field('post_author', $article_id);
            $author_detail     = get_userdata($article_author_id);
            $author_email      = !empty($author_detail->user_email) ? $author_detail->user_email : get_option('admin_email', 'info@example.com');
            $author_name       = !empty($author_detail->display_name) ? $author_detail->display_name : '';

            if (class_exists('PeerReviewSystemAuthorEmail')) {
                $article_link = Peer_Review_System_Profile_Menu::peer_review_system_profile_admin_menu_link('', $article_author_id, true, 'article-details', $article_id);
                $email_helper = new PeerReviewSystemAuthorEmail();
                $email_helper->email_author_article_approved(
                    array(
                        'email'        => $author_email,
                        'article_name' => get_the_title($article_id),
                        'user_name'    => $author_name,
                        'editor_name'  => $current_user->display_name,
                        'article_link' => get_permalink($article_id),
                    )
                );
            }

            $json['type']    = 'success';
            $json['title']   = esc_html__('Published!', 'peer-review-system');
            $json['message'] = esc_html__('Manuscript is now live on the website.', 'peer-review-system');
            wp_send_json($json);
        }

        $json['type']    = 'error';
        $json['title']   = esc_html__('Oops!', 'peer-review-system');
        $json['message'] = esc_html__('Unknown publishing action.', 'peer-review-system');
        wp_send_json($json);
    }
    add_action('wp_ajax_peer_review_system_publishing_action', 'peer_review_system_publishing_action');
}

/**
 * Post-publication management: retract, withdraw, unpublish, republish, remove.
 *
 * Keeps an audit trail and (for retraction/withdrawal) drives the public notice.
 */
if (!function_exists('peer_review_system_post_publication_action')) {
    function peer_review_system_post_publication_action()
    {
        global $current_user;
        $json           = array();
        $user_id        = $current_user->ID;
        $user_type      = apply_filters('PeerReviewSystemGetUserType', $user_id);
        $post_data      = !empty($_POST['data']) ? wp_unslash($_POST['data']) : '';
        parse_str($post_data, $data);
        $article_id     = !empty($data['prs_article_id']) ? absint($data['prs_article_id']) : 0;
        $action_type    = !empty($data['prs_pub_action']) ? sanitize_key($data['prs_pub_action']) : '';
        $reason         = !empty($data['prs_pub_reason']) ? sanitize_textarea_field($data['prs_pub_reason']) : '';

        $do_check = check_ajax_referer('ajax_nonce', 'security', false);
        if ($do_check == false) {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Security check failed, this could be because of your browser cache. Please clear the cache and check it again', 'peer-review-system');
            wp_send_json($json, 203);
        }

        if ($user_type !== 'publisher' && $user_type !== 'administrator') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('You are not allowed to perform this action.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        if (empty($article_id) || get_post_type($article_id) !== 'articles') {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Invalid manuscript.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        $reason_required = array('retract', 'withdraw', 'remove', 'correct');
        if (in_array($action_type, $reason_required, true) && empty($reason)) {
            $json['type']    = 'error';
            $json['title']   = esc_html__('Oops!', 'peer-review-system');
            $json['message'] = esc_html__('Please provide a reason. It is kept in the audit trail and shown in the public notice where applicable.', 'peer-review-system');
            wp_send_json($json, 203);
        }

        switch ($action_type) {
            case 'unpublish':
                wp_update_post(array('ID' => $article_id, 'post_status' => 'pending'));
                update_post_meta($article_id, 'prs_publishing_status', 'in_production');
                update_post_meta($article_id, 'prs_post_pub_state', 'unpublished');
                $message = esc_html__('Manuscript temporarily unpublished and removed from the public website.', 'peer-review-system');
                break;

            case 'republish':
                wp_update_post(array('ID' => $article_id, 'post_status' => 'publish'));
                update_post_meta($article_id, 'prs_publishing_status', 'published');
                delete_post_meta($article_id, 'prs_post_pub_state');
                delete_post_meta($article_id, 'prs_post_pub_reason');
                delete_post_meta($article_id, 'prs_post_pub_date');
                $message = esc_html__('Manuscript republished and live on the website again.', 'peer-review-system');
                break;

            case 'retract':
                /* Stays public, with a retraction notice. */
                wp_update_post(array('ID' => $article_id, 'post_status' => 'publish'));
                update_post_meta($article_id, 'prs_publishing_status', 'published');
                update_post_meta($article_id, 'prs_post_pub_state', 'retracted');
                update_post_meta($article_id, 'prs_post_pub_reason', $reason);
                update_post_meta($article_id, 'prs_post_pub_date', current_time('mysql'));
                $message = esc_html__('Manuscript retracted. A retraction notice now appears on the public page.', 'peer-review-system');
                break;

            case 'clear_retraction':
                delete_post_meta($article_id, 'prs_post_pub_state');
                delete_post_meta($article_id, 'prs_post_pub_reason');
                delete_post_meta($article_id, 'prs_post_pub_date');
                $message = esc_html__('Retraction notice cleared.', 'peer-review-system');
                break;

            case 'withdraw':
                /* Tombstone: page shows a withdrawal notice instead of the content. */
                wp_update_post(array('ID' => $article_id, 'post_status' => 'publish'));
                update_post_meta($article_id, 'prs_publishing_status', 'published');
                update_post_meta($article_id, 'prs_post_pub_state', 'withdrawn');
                update_post_meta($article_id, 'prs_post_pub_reason', $reason);
                update_post_meta($article_id, 'prs_post_pub_date', current_time('mysql'));
                $message = esc_html__('Manuscript withdrawn. A withdrawal notice is shown in place of the content.', 'peer-review-system');
                break;

            case 'remove':
                update_post_meta($article_id, 'prs_post_pub_state', 'removed');
                update_post_meta($article_id, 'prs_post_pub_reason', $reason);
                update_post_meta($article_id, 'prs_post_pub_date', current_time('mysql'));
                wp_trash_post($article_id);
                $message = esc_html__('Manuscript permanently removed from the website (moved to trash).', 'peer-review-system');
                break;

            case 'correct':
                /* Correction / erratum: article stays live; any newly uploaded
                   cover/PDF is saved and a dated correction notice is shown. */
                peer_review_system_save_production_uploads($article_id, $data);
                update_post_meta($article_id, 'prs_post_correction_note', $reason);
                update_post_meta($article_id, 'prs_post_correction_date', current_time('mysql'));
                $message = esc_html__('Correction published. A dated correction notice now appears on the public page.', 'peer-review-system');
                break;

            case 'clear_correction':
                delete_post_meta($article_id, 'prs_post_correction_note');
                delete_post_meta($article_id, 'prs_post_correction_date');
                $message = esc_html__('Correction notice cleared.', 'peer-review-system');
                break;

            default:
                $json['type']    = 'error';
                $json['title']   = esc_html__('Oops!', 'peer-review-system');
                $json['message'] = esc_html__('Unknown action.', 'peer-review-system');
                wp_send_json($json, 203);
        }

        peer_review_system_log_post_publication_action($article_id, $action_type, $reason, $user_id);

        $json['type']    = 'success';
        $json['title']   = esc_html__('Done!', 'peer-review-system');
        $json['message'] = $message;
        wp_send_json($json, 200);
    }
    add_action('wp_ajax_peer_review_system_post_publication_action', 'peer_review_system_post_publication_action');
}
