<?php

/**
 * Class 'PeerReviewSystemAuthorEmail' defines email functions
 *
 * @version     1.0
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @since       1.0
 */

if (!class_exists('PeerReviewSystemAuthorEmail')) {
    class PeerReviewSystemAuthorEmail extends Peer_Review_System_Email_Helper
    {
        public function __construct()
        {
            //do stuff here
        }

        /* Email to author on article submit */
        public function email_author_article_submit($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $user_name              = !empty($user_name) ? $user_name : '';

            $subject_default  = esc_html__('Article submitted', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('Congratulation your article has been submitted successfully!', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['author_article_submit_subject']) ? $prs_settings['author_article_submit_subject'] : $subject_default; //getting subject
            $email_content  = !empty($prs_settings['author_submit_article_content']) ? $prs_settings['author_submit_article_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{user_name}}", $user_name, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $user_name;
            $greeting['greet_option_key']   = 'author_submit_article_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_author_submit_article_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /* article approved by the editor */
        public function email_author_article_approved($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $author_name            = !empty($user_name) ? $user_name : '';
            $editor_name            = !empty($editor_name) ? $editor_name : '';
            $article_link            = !empty($article_link) ? $article_link : '';

            $subject_default  = esc_html__('Congratulations! Your article “{{article_name}}” has been approved', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('Congratulations!<br/> Your article with the name “{{article_name}}” has been approved. You can view your article here <br /> {{article_link}}', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['author_article_approved_subject']) ? $prs_settings['author_article_approved_subject'] : $subject_default; //getting subject
            $subject        = str_replace("{{article_name}}", $article_name, $subject);
            $email_content  = !empty($prs_settings['author_article_approved_content']) ? $prs_settings['author_article_approved_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{user_name}}", $author_name, $email_content);
            $email_content = str_replace("{{editor_name}}", $editor_name, $email_content);
            $email_content = str_replace("{{article_link}}", $article_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $author_name;
            $greeting['greet_option_key']   = 'author_article_approved_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_author_article_approved_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* edition published */
        public function email_author_edition_published($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $article_names          = !empty($article_names) ? $article_names : '';
            $author_name            = !empty($user_name) ? $user_name : '';
            $edition_name           = !empty($edition_name) ? $edition_name : '';
            $edition_link           = !empty($edition_link) ? $edition_link : '';

            $subject_default  = esc_html__('Congratulations! Your article “{{article_names}}” has been published', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('Congratulations!<br/>Your article with the name “{{article_names}}” has been published in the edition “{{edition_name}}”. You can view the edition here <br/>{{edition_link}}', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['author_edition_publish_subject']) ? $prs_settings['author_edition_publish_subject'] : $subject_default; //getting subject
            $subject        = str_replace("{{article_names}}", $article_names, $subject);
            $email_content  = !empty($prs_settings['author_edition_publish_content']) ? $prs_settings['author_edition_publish_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_names}}", $article_names, $email_content);
            $email_content = str_replace("{{user_name}}", $author_name, $email_content);
            $email_content = str_replace("{{edition_name}}", $edition_name, $email_content);
            $email_content = str_replace("{{edition_link}}", $edition_link, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $author_name;
            $greeting['greet_option_key']   = 'author_edition_publish_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_author_edition_published_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }


        /* article rejected by the editor */
        public function email_author_article_rejected($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $author_name            = !empty($user_name) ? $user_name : '';
            $editor_name            = !empty($editor_name) ? $editor_name : '';
            $review_status          = !empty($review_status) ? $review_status : '';
            $review_comment         = !empty($review_comment) ? $review_comment : '';

            $subject_default  = esc_html__('Your article “{{article_name}}” has been rejected', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('Thank you for your submission<br/> Your article with the name “{{article_name}}” has been hard rejected and you can’t resubmit it.', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['author_article_rejected_subject']) ? $prs_settings['author_article_rejected_subject'] : $subject_default; //getting subject
            $subject        = str_replace("{{article_name}}", $article_name, $subject);
            $email_content  = !empty($prs_settings['author_article_rejected_content']) ? $prs_settings['author_article_rejected_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{user_name}}", $author_name, $email_content);
            $email_content = str_replace("{{editor_name}}", $editor_name, $email_content);
            $email_content = str_replace("{{review_status}}", $review_status, $email_content);
            $email_content = str_replace("{{review_comment}}", $review_comment, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $author_name;
            $greeting['greet_option_key']   = 'author_article_rejected_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_author_article_rejected_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Revision required about article */
        public function email_author_article_revision_required($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $author_name            = !empty($user_name) ? $user_name : '';
            $editor_name            = !empty($editor_name) ? $editor_name : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $review_status          = !empty($review_status) ? $review_status : '';
            $feedback               = !empty($feedback) ? $feedback : '';

            $subject_default  = esc_html__('Article revision required', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('Thank you for your submission <br/>Your article with the name “{{article_name}}” needs improvements. You can review it again and resubmit it after the changes <br/>Here is the feedback:<br/>{{feedback}}', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['author_revision_required_subject']) ? $prs_settings['author_revision_required_subject'] : $subject_default; //getting subject

            $email_content  = !empty($prs_settings['author_revision_required_content']) ? $prs_settings['author_revision_required_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{user_name}}", $author_name, $email_content);
            $email_content = str_replace("{{editor_name}}", $editor_name, $email_content);
            $email_content = str_replace("{{review_status}}", $review_status, $email_content);
            $email_content = str_replace("{{feedback}}", $feedback, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $author_name;
            $greeting['greet_option_key']   = 'author_revision_required_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_author_article_revision_required_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }
    }
}
