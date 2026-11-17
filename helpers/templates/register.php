<?php

/**
 * Class 'PeerReviewSystemRegistrationEmail' defines email functions
 *
 * @version     1.0
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @since       1.0
 */

if (!class_exists('PeerReviewSystemRegistrationEmail')) {
    class PeerReviewSystemRegistrationEmail extends Peer_Review_System_Email_Helper
    {
        public function __construct()
        {
            //do stuff here
        }

        /* email to user on registration with auto approve */
        public function registration_user_auto_approve_email($params = '')
        {
            global $prs_settings;
            extract($params);
            $email_to       = !empty($email) ? $email : '';
            $name           = !empty($name) ? $name : '';
            $sitename       = !empty($site) ? $site : '';
            $login_url      = !empty($login_url) ? $login_url : '';

            $subject_default  = esc_html__('Thank you for registration at {{sitename}}', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('Congratulation You have successfully signed up at "{{sitename}}.', 'peer-review-system'), //default email content
                array(
                    'a' => array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'        => array(),
                    'em'        => array(),
                    'strong'    => array(),
                )
            );

            $subject        = !empty($prs_settings['user_registration_auto_approve_subject']) ? $prs_settings['user_registration_auto_approve_subject'] : $subject_default; //getting subject
            $subject        = str_replace("{{sitename}}", $sitename, $subject); //getting subject
            $email_content  = !empty($prs_settings['user_registration_auto_approve_content']) ? $prs_settings['user_registration_auto_approve_content'] : $contact_default; //getting content

            $email_content = str_replace("{{name}}", $name, $email_content);
            $email_content = str_replace("{{email}}", $email, $email_content);
            $email_content = str_replace("{{sitename}}", $sitename, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'name';
            $greeting['greet_value']        = $name;
            $greeting['greet_option_key']   = 'email_user_registration_auto_approve_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_user_registration_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* email to admin on user registration */
        public function new_user_register_admin_email($params = '')
        {
            global $prs_settings;
            extract($params);
            $email_to       = !empty($prs_settings['admin_email_address_registration']) ? $prs_settings['admin_email_address_registration'] : get_option('admin_email', 'info@example.com'); //admin email
            $name           = !empty($name) ? $name : '';
            $sitename       = !empty($site) ? $site : '';
            $login_url      = !empty($login_url) ? $login_url : '';
            $user_email     = !empty($email) ? $email : '';

            $subject_default  = esc_html__('New registration at {{sitename}}', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('A new user has been registered on the site with the name {{name}} and email address {{email}}', 'peer-review-system'), //default email content
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject        = !empty($prs_settings['admin_registration_subject']) ? $prs_settings['admin_registration_subject'] : $subject_default; //getting subject
            $subject        = str_replace("{{sitename}}", $sitename, $subject); //getting subject
            $email_content  = !empty($prs_settings['admin_registration_content']) ? $prs_settings['admin_registration_content'] : $contact_default; //getting content

            $email_content = str_replace("{{name}}", $name, $email_content);
            $email_content = str_replace("{{sitename}}", $sitename, $email_content);
            $email_content = str_replace("{{email}}", $email, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = '';
            $greeting['greet_value']        = '';
            $greeting['greet_option_key']   = 'email_admin_registration_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_user_registration_admin_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /* Email to user for Account Approve Request */
        public function registration_account_approval_request($params = '')
        {
            global $prs_settings;
            extract($params);
            $email_to             = !empty($email) ? $email : '';
            $user_name          = !empty($name) ? $name : '';
            $user_password      = !empty($password) ? $password : '';
            $user_email         = $email_to;
            $site_name          = !empty($site) ? $site : '';
            $subject_default    = esc_html__('Thank you for registration at {{sitename}}', 'peer-review-system'); //default email subject
            $contact_default    = wp_kses(
                __('Thank you for the registration at "{{sitename}}". Your account will be approved after the verification.', 'peer-review-system'), //default email content
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject        = !empty($prs_settings['user_account_approval_subject']) ? $prs_settings['user_account_approval_subject'] : $subject_default; //getting subject
            $subject        = str_replace("{{sitename}}", $site_name, $subject); //getting subject
            $email_content  = !empty($prs_settings['user_account_approval_content']) ? $prs_settings['user_account_approval_content'] : $contact_default; //getting content

            $email_content = str_replace("{{name}}", $user_name, $email_content);
            $email_content = str_replace("{{email}}", $user_email, $email_content);
            $email_content = str_replace("{{password}}", $user_password, $email_content);
            $email_content = str_replace("{{sitename}}", $site_name, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'name';
            $greeting['greet_value']        = $user_name;
            $greeting['greet_option_key']   = 'user_account_approval_request_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_user_account_approval_email_content', $body);

            wp_mail($email_to, $subject, $body); //send Email
        }

        /* Email to user on account approved */
        public function user_account_approved($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $user_name              = !empty($name) ? $name : '';
            $user_email             = $email_to;
            $site_name              = !empty($site) ? $site : '';
            $subject_default        = esc_html__('Account approved', 'peer-review-system'); //default email subject
            $contact_default         = wp_kses(
                __('Congratulations! <br/> Your account has been approved by the admin.', 'peer-review-system'), //default email content
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject          = !empty($prs_settings['user_account_approved_subject']) ? $prs_settings['user_account_approved_subject'] : $subject_default; //getting subject
            $email_content    = !empty($prs_settings['user_account_approved_content']) ? $prs_settings['user_account_approved_content'] : $contact_default; //getting content

            $email_content = str_replace("{{name}}", $user_name, $email_content);
            $email_content = str_replace("{{email}}", $user_email, $email_content);
            $email_content = str_replace("{{sitename}}", $site_name, $email_content);
            /* data for greeting */
            $greeting['greet_keyword']      = 'name';
            $greeting['greet_value']        = $user_name;
            $greeting['greet_option_key']   = 'user_account_approved_greeting';
            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_user_account_approved_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /* email to user on account reject by admin */
        public function user_reject_account($params = ''){
            global $prs_settings;
            extract($params);
            $email_to               = !empty($email) ? $email : '';
            $$name                  = !empty($name) ? $name : '';
            $sitename               = !empty($sitename) ? $sitename : '';
            $subject_default 	    = esc_html__('Account rejected', 'peer-review-system'); //default email subject
            $contact_default 	    = wp_kses(__('Your account has been rejected by the admin.', 'peer-review-system'),
            array(
                'a'       => array(
                'href'  => array(),
                'title' => array()
                ),
                'br'      => array(),
                'em'      => array(),
                'strong'  => array(),
                )
            );

            $subject		    = !empty( $prs_settings['user_mail_account_reject_subject'] ) ? $prs_settings['user_mail_account_reject_subject'] : $subject_default; //getting subject
            $email_content      = !empty( $prs_settings['reject_user_account_content'] ) ? $prs_settings['reject_user_account_content'] : $contact_default; //getting content
    
            $email_content = str_replace("{{name}}", $name, $email_content);
            $email_content = str_replace("{{email}}", $email, $email_content);
            $email_content = str_replace("{{sitename}}", $sitename, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'name';
            $greeting['greet_value']        = $name;
            $greeting['greet_option_key']   = 'user_email_account_reject_greeting';
    
            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_user_account_reject_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /* Email to Admin on User Registration if verify by admin */
        public function registration_verify_by_admin_email($params = '')
        {
            global $prs_settings;
            extract($params);
            $email_to           = !empty($prs_settings['admin_email_address_verify_user']) ? $prs_settings['admin_email_address_verify_user'] : get_option('admin_email', 'info@example.com'); //admin email
            $user_name          = !empty($name) ? $name : '';
            $user_email         = !empty($email) ? $email : '';
            $site_name          = !empty($site) ? $site : '';
            $login_url          = !empty($login_url) ? $login_url : '';
            $subject_default    = esc_html__('New registration approval request at {{sitename}}', 'peer-review-system'); //default email subject
            $contact_default    = wp_kses(
                __('A new user has been registered on the site with the name {{name}} and email address {{email}}. <br /> The registration is pending for approval, you can login  {{login_url}} to the admin to approve the account.', 'peer-review-system'), //default email content
                array(
                    'a' => array(
                        'href' => array(),
                        'title' => array()
                    ),
                    'br' => array(),
                    'em' => array(),
                    'strong' => array(),
                )
            );

            $subject            = !empty($prs_settings['admin_verify_register_user_subject']) ? $prs_settings['admin_verify_register_user_subject'] : $subject_default; //getting subject
            $subject            = str_replace("{{sitename}}", $site_name, $subject); //getting subject
            $email_content      = !empty($prs_settings['admin_verify_user_registration_content']) ? $prs_settings['admin_verify_user_registration_content'] : $contact_default; //getting content
            $login_link_        = $this->process_email_links($login_url, esc_html__('Login', 'peer-review-system')); //task/post link

            $email_content = str_replace("{{name}}", $user_name, $email_content);
            $email_content = str_replace("{{email}}", $user_email, $email_content);
            $email_content = str_replace("{{sitename}}", $site_name, $email_content);
            $email_content = str_replace("{{login_url}}", $login_link_, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = '';
            $greeting['greet_value']        = '';
            $greeting['greet_option_key']   = 'email_admin_verify_user_registration_greeting';

            $body   = $this->peer_review_system_email_body($email_content, $greeting);
            $body   = apply_filters('peer_review_system_admin_verify_user_registration_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /* Email to user for verification by link */
        public function user_registration_verify_link_email($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to                   = !empty($email) ? $email : '';
            $user_name                  = !empty($name) ? $name : '';
            $user_password              = !empty($password) ? $password : '';
            $user_email                 = $email_to;
            $user_verification_link     = !empty($verification_link) ? $verification_link : '';
            $site_name                  = !empty($site) ? $site : '';
            $subject_default            = esc_html__('Thank you for registration at {{sitename}}', 'peer-review-system'); //default email subject
            $contact_default            = wp_kses(
                __('Thank you for the registration at "{{sitename}}". Please click below link to verify your account<br/> {{verification_link}}', 'peer-review-system'),
                array(
                    'a'       => array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )
            );

            $subject                = !empty($prs_settings['user_registration_verify_link_subject']) ? $prs_settings['user_registration_verify_link_subject'] : $subject_default; //getting subject
            $subject                = str_replace("{{sitename}}", $site_name, $subject); //getting subject
            $email_content          = !empty($prs_settings['user_registration_verify_link_content']) ? $prs_settings['user_registration_verify_link_content'] : $contact_default; //getting content
            $verification_link_     = $this->process_email_links($user_verification_link, esc_html__('Verification link', 'peer-review-system')); //verification link

            $email_content = str_replace("{{name}}", $user_name, $email_content);
            $email_content = str_replace("{{email}}", $user_email, $email_content);
            $email_content = str_replace("{{password}}", $user_password, $email_content);
            $email_content = str_replace("{{sitename}}", $site_name, $email_content);
            $email_content = str_replace("{{verification_link}}", $verification_link_, $email_content);
            /* data for greeting */
            $greeting['greet_keyword']  = 'name';
            $greeting['greet_value']    = $user_name;
            $greeting['greet_option_key'] = 'email_user_registration_verify_link_greeting';
            $body   = $this->peer_review_system_email_body($email_content, $greeting);
            $body   = apply_filters('peer_review_system_user_registration_verify_link_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email    
        }

        /* Email to User on Reset Password */
        public function user_reset_password($params = '')
        {
            global $prs_settings;
            extract($params);
            $email_to           = !empty($email) ? $email : '';
            $name                 = !empty($name) ? $name : '';
            $sitename             = !empty($sitename) ? $sitename : '';
            $reset_link         = !empty($reset_link) ? $reset_link : '';
            $subject_default    = esc_html__('Reset password - {{sitename}}', 'peer-review-system'); //default email subject
            $content_default    = wp_kses(
                __('Someone requested to reset the password of following account: <br/> Email Address: {{email}} <br/>If this was a mistake, just ignore this email and nothing will happen.<br/>To reset your password, click reset link below:<br/>{{reset_link}}', 'peer-review-system'),
                array(
                    'a'       => array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )
            );
            $subject          = !empty($prs_settings['user_password_reset_subject']) ? $prs_settings['user_password_reset_subject'] : $subject_default; //getting subject
            $subject          = str_replace("{{sitename}}", $sitename, $subject); //getting subject
            $email_content  = !empty($prs_settings['user_reset_password_content']) ? $prs_settings['user_reset_password_content'] : $content_default; //getting content
            $reset_link_    = $this->process_email_links($reset_link, esc_html__('Reset link', 'peer-review-system')); //Reset link

            $email_content = str_replace("{{name}}", $name, $email_content);
            $email_content = str_replace("{{sitename}}", $sitename, $email_content);
            $email_content = str_replace("{{email}}", $email, $email_content);
            $email_content = str_replace("{{reset_link}}", $reset_link_, $email_content);
            $email_content = str_replace("{{email}} ", $email, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']  = 'name';
            $greeting['greet_value']    = $name;
            $greeting['greet_option_key'] = 'user_reset_password_greeting';

            $body   = $this->peer_review_system_email_body($email_content, $greeting);
            $body   = apply_filters('peer_review_system_user_reset_password_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Email to user on Registration by social */
        public function social_registration_user_email($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to             = !empty($email) ? $email : '';
            $user_name          = !empty($name) ? $name : '';
            $login_url          = !empty($login_url) ? $login_url : '';
            $user_email         = $email_to;
            $site_name          = !empty($site) ? $site : '';
            $subject_default    = esc_html__('Registration at {{sitename}} via google account', 'peer-review-system'); //default email subject
            $contact_default    = wp_kses(
                __('Thank you for the registration at "{{sitename}}" Your account has been created. ', 'peer-review-system'),
                array(
                    'a'       => array(
                        'href'  => array(),
                        'title' => array()
                    ),
                    'br'      => array(),
                    'em'      => array(),
                    'strong'  => array(),
                )
            );

            $subject            = !empty($prs_settings['subject_social_registration_user_email']) ? $prs_settings['subject_social_registration_user_email'] : $subject_default; //getting subject
            $subject            = str_replace("{{sitename}}", $site_name, $subject); //getting subject
            $email_content  = !empty($prs_settings['content_social_registration_user_email']) ? $prs_settings['content_social_registration_user_email'] : $contact_default; //getting content

            $email_content = str_replace("{{name}}", $user_name, $email_content);
            $email_content = str_replace("{{email}}", $user_email, $email_content);
            $email_content = str_replace("{{login_url}}", $login_url, $email_content);
            $email_content = str_replace("{{sitename}}", $site_name, $email_content);
            /* data for greeting */
            $greeting['greet_keyword'] = 'name';
            $greeting['greet_value'] = $user_name;
            $greeting['greet_option_key'] = 'greeting_social_registration_user_email';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_user_social_registration_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

    }
}
