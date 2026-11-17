<?php

/**
 * Shortcode
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/elementor/shortcodes
 * @author     Amentotech <theamentotech@gmail.com>
 */

namespace Elementor;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Peer_Review_System_Authentication')) {
    class Peer_Review_System_Authentication extends Widget_Base
    {
        /**
         *
         * @since    1.0.0
         * @access   static
         * @var      base
         */
        public function get_name()
        {
            return 'prs_element_authentication';
        }

        /**
         *
         * @since    1.0.0
         * @access   static
         * @var      title
         */
        public function get_title()
        {
            return esc_html__('Authentication', 'peer-review-system');
        }

        /**
         *
         * @since    1.0.0
         * @access   public
         * @var      icon
         */
        public function get_icon()
        {
            return 'eicon-lock-user';
        }

        /**
         *
         * @since    1.0.0
         * @access   public
         * @var      category of shortcode
         */
        public function get_categories()
        {
            return ['peer-review-system-elements'];
        }

        /**
         * Register category controls.
         * @since    1.0.0
         * @access   protected
         */
        protected function register_controls()
        {

            //Content
            $this->start_controls_section(
                'content_section',
                [
                    'label' => esc_html__('Content', 'peer-review-system'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'form_type',
                [
                    'label' => esc_html__('Form type', 'peer-review-system'),
                    'type'  => Controls_Manager::SELECT,
                    'default' => 'register',
                    'options' => [
                        'register'     => esc_html__('Register form', 'peer-review-system'),
                        'login'     => esc_html__('Login form', 'peer-review-system'),
                        'forgot'     => esc_html__('Forgot password form', 'peer-review-system')
                    ],
                ]
            );

            $this->add_control(
                'bg_image',
                [
                    'label' => esc_html__('Background image', 'peer-review-system'),
                    'type'  => Controls_Manager::MEDIA
                ]
            );

            // logo on top of signup
            $this->add_control(
                'logo',
                [
                    'type' => Controls_Manager::MEDIA,
                    'label' => __('Choose logo', 'peer-review-system'),
                    'description' => esc_html__('Add logo. leave it empty to hide.', 'peer-review-system'),
                ]
            );


            $this->add_control(
                'left_text',
                [
                    'type'          => Controls_Manager::TEXT,
                    'label'         => esc_html__('Left bottom text', 'peer-review-system'),
                    'description'   => esc_html__('Add text. leave it empty to hide.', 'peer-review-system'),
                ]
            );

            $this->add_control(
                'left_tagline',
                [
                    'type'          => Controls_Manager::TEXT,
                    'label'         => esc_html__('Left bottom tagline', 'peer-review-system'),
                    'description'   => esc_html__('Add tagline. leave it empty to hide.', 'peer-review-system'),
                ]
            );
            $this->add_control(
                'welcome_text',
                [
                    'type'          => Controls_Manager::TEXT,
                    'label'         => esc_html__('Welcome text', 'peer-review-system'),
                    'description'   => esc_html__('Add welcome text. leave it empty to hide.', 'peer-review-system'),
                ]
            );
            $this->add_control(
                'terms_page',
                [
                    'label' => esc_html__('Link', 'peer-review-system'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'placeholder' => esc_html__('https://example.com', 'peer-review-system'),
                    'default' => [
                        'url' => '',
                        'is_external' => true,
                        'nofollow' => true,
                        'custom_attributes' => '',
                    ],
                    'condition' => [
                        'form_type' => 'register'
                    ],
                ]
            );
            $this->add_control(
                'tagline',
                [
                    'type'        => Controls_Manager::TEXTAREA,
                    'label'       => esc_html__('Short description', 'peer-review-system'),
                    'description' => esc_html__('Add short description. leave it empty to hide.', 'peer-review-system'),
                ]
            );

            $this->end_controls_section();
        }

        /**
         * Render shortcode
         *
         * @since 1.0.0
         * @access protected
         */
        protected function render()
        {
            $settings       = $this->get_settings_for_display();
            $form_type      = !empty($settings['form_type'])        ? $settings['form_type']        : '';
            $bg_image       = !empty($settings['bg_image']['url'])  ? $settings['bg_image']['url']  : '';
            $logo           = !empty($settings['logo']['url'])      ? $settings['logo']['url']      : '';
            $tagline        = !empty($settings['tagline'])          ? $settings['tagline']          : '';
            $welcome_text   = !empty($settings['welcome_text'])     ? $settings['welcome_text']     : '';
            $left_text      = !empty($settings['left_text'])        ? $settings['left_text']        : '';
            $left_tagline   = !empty($settings['left_tagline'])     ? $settings['left_tagline']     : '';
            $terms_page     = !empty($settings['terms_page']['url'])     ? $settings['terms_page']['url']    : '';
            $user_id        = is_user_logged_in()  ? get_current_user_id() : 0;
            $user_type      = !empty($user_id) ? apply_filters('PeerReviewSystemGetUserType', $user_id) : '';
            
            if (!current_user_can( 'administrator' ) && is_user_logged_in()) {
                if (!empty($user_type)) {

                    if ($user_type === 'author' || $user_type === 'reviewer' || $user_type === 'editor') {
                        $page_url    = peer_review_system_dashboard_page_uri($user_type);
                    } else {
                        $page_url    = home_url('/');
                    }

                    if (!empty($_GET['redirect'])) {
                        $page_url    = !empty($_GET['redirect']) ? $_GET['redirect'] : '';
                        $chat_id    = !empty($_GET['chat_id']) ? $_GET['chat_id'] : '';

                        if (!empty($chat_id)) {
                            $page_url   = add_query_arg('chat_id', $chat_id, $page_url);
                        }
                    }
                    ?>
                    <script>
                        jQuery(document).ready(function() {
                            window.location.href = "<?php echo esc_url_raw($page_url); ?>";
                        });
                    </script>
                <?php
                }
            } else {
                if (!current_user_can( 'administrator' ) && !empty($_GET['redirect'])) {
                    $prs_inbox_url    = !empty($_GET['redirect']) ? $_GET['redirect'] : '';
                    $chat_id    = !empty($_GET['chat_id']) ? $_GET['chat_id'] : '';

                    if (!empty($chat_id)) {
                        $prs_inbox_url   = add_query_arg('chat_id', $chat_id, $prs_inbox_url);
                    }
                    set_transient('prs_redirect_page_url', $prs_inbox_url, 200);
                }
                ?>
                <div class="sj-sc-shortcode sj-haslayout">
                    <?php
                    if (!empty($form_type) && $form_type === 'register') {
                        echo do_shortcode('[peer_review_system_registration background="' . $bg_image . '" logo="' . $logo . '" tagline="' . $tagline . '" left_text="' . $left_text . '" left_tagline="' . $left_tagline . '" welcome_text="' . $welcome_text . '" terms_page="' . $terms_page . '" ]');
                    } elseif (!empty($form_type) && $form_type === 'login') {
                        echo do_shortcode('[peer_review_system_signin background="' . $bg_image . '" logo="' . $logo . '" tagline="' . $tagline . '" left_text="' . $left_text . '" left_tagline="' . $left_tagline . '" welcome_text="' . $welcome_text . '" ]');
                    } elseif (!empty($form_type) && $form_type === 'forgot') {
                        echo do_shortcode('[peer_review_system_forgot_password background="' . $bg_image . '" logo="' . $logo . '" tagline="' . $tagline . '" left_text="' . $left_text . '" left_tagline="' . $left_tagline . '" welcome_text="' . $welcome_text . '" ]');
                    }
                    ?>
                </div>
                <?php
            }
        }
    }
    Plugin::instance()->widgets_manager->register(new Peer_Review_System_Authentication);
}
