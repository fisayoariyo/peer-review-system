<?php

/**
 * Class 'PeerReviewSystemEditorEmail' defines email functions
 *
 * @version     1.0
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/helpers/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @since       1.0
 */

if (!class_exists('PeerReviewSystemEditorEmail')) {
    class PeerReviewSystemEditorEmail extends Peer_Review_System_Email_Helper
    {
        public function __construct()
        {
            //do stuff here
        }

        /* Email to editor on post comment from the author */
        public function email_editor_author_comment_to_editor($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $editor_name            = !empty($user_name) ? $user_name : '';
            $author_name            = !empty($author_name) ? $author_name : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $author_comment         = !empty($author_comment) ? $author_comment : '';

            $subject_default  = esc_html__('Author comment on {{article_name}}', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('The author {{author_name}} send the comment on the article "{{article_name}}" and has left some comments “{{author_comment}}”', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['author_comment_editor_subject']) ? $prs_settings['author_comment_editor_subject'] : $subject_default; //getting subject
            $email_content  = str_replace("{{article_name}}", $article_name, $subject);
            $email_content  = !empty($prs_settings['author_comment_editor_content']) ? $prs_settings['author_comment_editor_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{author_name}}", $author_name, $email_content);
            $email_content = str_replace("{{author_comment}}", $author_comment, $email_content);
            $email_content = str_replace("{{editor_name}}", $editor_name, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $editor_name;
            $greeting['greet_option_key']   = 'author_comment_editor_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_author_comment_article_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Email to editor on post comment from reviewer against assigned article */
        public function email_editor_comment_on_assigned_article($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $editor_name              = !empty($user_name) ? $user_name : '';
            $reviewer_name              = !empty($author_name) ? $author_name : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $review_status              = !empty($review_status) ? $review_status : '';

            $subject_default  = esc_html__('Reviewer comment on assigned article', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('The review {{reviewer_name}} has been completed the review on the article "{{article_name}}" and has left some comments with the status “{{review_status}}”', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['reviewer_comment_editor_subject']) ? $prs_settings['reviewer_comment_editor_subject'] : $subject_default; //getting subject
            $email_content  = !empty($prs_settings['reviewer_comment_editor_content']) ? $prs_settings['reviewer_comment_editor_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{reviewer_name}}", $reviewer_name, $email_content);
            $email_content = str_replace("{{review_status}}", $review_status, $email_content);
            $email_content = str_replace("{{editor_name}}", $editor_name, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $editor_name;
            $greeting['greet_option_key']   = 'reviewer_comment_editor_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_reviewer_comment_article_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }

        /* Email to editor on new article received */
        public function email_editor_new_article_received($params = '')
        {
            global $prs_settings;
            extract($params);
            $email_to               = !empty($email) ? $email : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $editor_name              = !empty($user_name) ? $user_name : '';
            $author_name              = !empty($author_name) ? $author_name : '';

            $subject_default  = esc_html__('A new article has been received for review', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('You have received a new article from {{author_name}}, your review is required on this article', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['editor_new_article_posted_subject']) ? $prs_settings['editor_new_article_posted_subject'] : $subject_default; //getting subject
            $email_content  = !empty($prs_settings['editor_new_article_posted_content']) ? $prs_settings['editor_new_article_posted_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{author_name}}", $author_name, $email_content);
            $email_content = str_replace("{{user_name}}", $editor_name, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $editor_name;
            $greeting['greet_option_key']   = 'editor_new_article_posted_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_author_submit_article_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email
        }

        /* article resubmission from author */
        public function email_editor_resubmission_article($params = '')
        {
            global $prs_settings;
            extract($params);

            $email_to               = !empty($email) ? $email : '';
            $article_name           = !empty($article_name) ? $article_name : '';
            $editor_name            = !empty($user_name) ? $user_name : '';
            $author_name            = !empty($author_name) ? $author_name : '';

            $subject_default  = esc_html__('Article has been resubmitted for review', 'peer-review-system'); //default email subject
            $contact_default  = wp_kses(
                __('The author {{author_name}} has resubmitted the article “{{article_name}}”. You can review it and provide your review on this', 'peer-review-system'), //default email content
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

            $subject        = !empty($prs_settings['editor_article_resubmit_subject']) ? $prs_settings['editor_article_resubmit_subject'] : $subject_default; //getting subject
            $email_content  = !empty($prs_settings['editor_article_resubmit_content']) ? $prs_settings['editor_article_resubmit_content'] : $contact_default; //getting content

            $email_content = str_replace("{{article_name}}", $article_name, $email_content);
            $email_content = str_replace("{{author_name}}", $author_name, $email_content);
            $email_content = str_replace("{{editor_name}}", $editor_name, $email_content);

            /* data for greeting */
            $greeting['greet_keyword']      = 'user_name';
            $greeting['greet_value']        = $editor_name;
            $greeting['greet_option_key']   = 'editor_article_resubmit_greeting';

            $body = $this->peer_review_system_email_body($email_content, $greeting);
            $body  = apply_filters('peer_review_system_editor_article_resubmission_email_content', $body);
            wp_mail($email_to, $subject, $body); //send Email

        }
    }
}
