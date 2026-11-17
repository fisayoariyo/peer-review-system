<?php

/**
 * Class 'PeerReviewSystemReviewerEmail' defines email functions
 *
 * @version     1.0
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @since       1.0
 */


if (!class_exists('PeerReviewSystemReviewerEmail')) {
    class PeerReviewSystemReviewerEmail extends Peer_Review_System_Email_Helper
    {
        public function __construct()
        {
            //do stuff here
        }

        /* Email to reviewer on assign article */
        public function email_reviewer_assign_article($params = '')
        {
            global $prs_settings;
            extract($params);
            $email_to               = !empty($email) ? $email : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $reviewer_name          = !empty($user_name) ? $user_name : '';
            $editor_name            = !empty($editor_name) ? $editor_name : '';

            $subject_default  = esc_html__('A new article has been received for review', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('You have received a new article from {{editor_name}} for review, you can review and leave your comments on it', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['reviewer_assigned_article_subject']) ? $prs_settings['reviewer_assigned_article_subject'] : $subject_default; //getting subject
            $email_content  = !empty($prs_settings['reviewer_assigned_article_content']) ? $prs_settings['reviewer_assigned_article_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{editor_name}}", $editor_name, $email_content);
            $email_content = str_replace("{{reviewer_name}}", $reviewer_name, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $reviewer_name;
            $greeting['greet_option_key']   = 'reviewer_assigned_article_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_reviewer_assign_article_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /* Email to reviewer for further review request */
        public function email_reviewer_further_review_request($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $reviewer_name          = !empty($user_name) ? $user_name : '';
            $editor_name            = !empty($editor_name) ? $editor_name : '';

            $subject_default  = esc_html__('Further review required', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('The editor “{{editor_name}}” has requested to review the article “{{article_name}}” again. You can review the article and leave your comments with the status', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['reviewer_further_review_subject']) ? $prs_settings['reviewer_further_review_subject'] : $subject_default; //getting subject
            $email_content  = !empty($prs_settings['reviewer_further_review_content']) ? $prs_settings['reviewer_further_review_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{editor_name}}", $editor_name, $email_content);
            $email_content = str_replace("{{reviewer_name}}", $reviewer_name, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $reviewer_name;
            $greeting['greet_option_key']   = 'reviewer_further_review_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_reviewer_further_review_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }
    }
}
