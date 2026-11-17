    <?php
    /**
     * The template used for login
     *
     * @package     Peer_Review_System
     * @subpackage  Peer_Review_System/Templates
     * @author      Amentotech <info@amentotech.com>
     * @link       https://themeforest.net/user/amentotech/portfolio
     * @version     1.0
     * @since       1.0
     */
    if (!function_exists('peer_review_system_forgot_password')) {
        add_shortcode('peer_review_system_forgot_password', 'peer_review_system_forgot_password');
        function peer_review_system_forgot_password($atts)
        {
            if (function_exists('jase_peer_review_is_forgot_page') && jase_peer_review_is_forgot_page()) {
                return '';
            }

            global $prs_settings, $wpdb;
            $bg_image           = !empty($atts['background']) ? $atts['background'] : '';
            $logo               = !empty($atts['logo']) ? $atts['logo'] : '';
            $tagline            = !empty($atts['tagline']) ? $atts['tagline'] : '';
            $left_text          = !empty($atts['left_text']) ? $atts['left_text'] : '';
            $left_tagline       = !empty($atts['left_tagline']) ? $atts['left_tagline'] : '';
            $welcome_text       = !empty($atts['welcome_text']) ? $atts['welcome_text'] : '';
            $login_url          = !empty($prs_settings['tpl_login']) ? get_permalink($prs_settings['tpl_login']) : wp_login_url();
            $tpl_registration   = !empty($prs_settings['tpl_registration']) ? get_permalink($prs_settings['tpl_registration']) : wp_login_url('/');
            $has_brand          = !empty($logo) || !empty($bg_image) || !empty($left_text) || !empty($left_tagline);
            $shell_classes      = 'jase-forgot-shortcode' . ($has_brand ? '' : ' jase-forgot-shortcode--compact');

            if (!empty($_GET['key']) && (isset($_GET['action']) && $_GET['action'] == 'reset_pwd')) {
                $reset_key  = sanitize_text_field($_GET['key']);
                $user_data  = $wpdb->get_row($wpdb->prepare("SELECT ID, user_login, user_email FROM $wpdb->users WHERE user_activation_key = %s", $reset_key));
                if (!empty($user_data)) { ?>
                    <div class="<?php echo esc_attr($shell_classes); ?>">
                    <div class="sj-main-login">
                        <div class="sj-login-left">
                            <?php if (!empty($logo)) { ?>
                                <strong>
                                    <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo) ?>" alt="<?php esc_attr_e('Images', 'peer-review-system') ?>"></a>
                                </strong>
                            <?php }
                            if (!empty($bg_image)) { ?>
                                <figure>
                                    <img src="<?php echo esc_url($bg_image) ?>" alt="<?php esc_attr_e('Images', 'peer-review-system') ?>">
                                </figure>
                            <?php }
                            if (!empty($left_tagline) || !empty($left_text)) { ?>
                                <div class="sj-login-left_title">
                                    <?php if (!empty($left_text)) { ?>
                                        <h2><?php echo esc_html($left_text) ?></h2>
                                    <?php } ?>
                                    <?php if (!empty($left_tagline)) { ?>
                                        <span><?php echo esc_html($left_tagline) ?></span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="sj-login-right">
                            <div class="jase-forgot-shortcode__head">
                                <?php if (!empty($welcome_text)) { ?>
                                    <h2 class="jase-forgot-shortcode__title"><?php echo esc_html($welcome_text); ?></h2>
                                <?php } else { ?>
                                    <h2 class="jase-forgot-shortcode__title"><?php esc_html_e('Set a new password', 'peer-review-system'); ?></h2>
                                <?php } ?>
                                <?php if (!empty($tagline)) { ?>
                                    <p class="jase-forgot-shortcode__lead"><?php echo esc_html($tagline); ?></p>
                                <?php } else { ?>
                                    <p class="jase-forgot-shortcode__lead"><?php esc_html_e('Choose a strong password for your account.', 'peer-review-system'); ?></p>
                                <?php } ?>
                            </div>
                            <form class="sj-themeform sj-login-form sj-password-reset-form jase-forgot-shortcode__form">
                                <fieldset>
                                    <div class="sj-themeform__wrap">
                                        <div class="form-group-wrap">
                                            <div class="form-group jase-forgot-shortcode__field">
                                                <label class="jase-forgot-shortcode__label" for="prs-reset-password"><?php esc_html_e('New password', 'peer-review-system'); ?></label>
                                                <input id="prs-reset-password" type="password" name="password" class="form-control jase-forgot-shortcode__input" required="required" autocomplete="new-password">
                                            </div>
                                            <div class="form-group">
                                                <a href="javascript:void(0);" class="sj-secondery-btn sj-primbtn-lg sj-password-recover"><span><?php esc_html_e('Recover password', 'peer-review-system') ?></span><i class="icon icon-arrow-right"></i></a>
                                            </div>
                                            <?php if (!empty($login_url) || !empty($tpl_registration)) { ?>
                                                <div class="sj-lost-password form-group">
                                                    <?php if (!empty($tpl_registration)) { ?>
                                                        <a href="<?php echo esc_url($tpl_registration) ?>"><?php esc_html_e('Join us today', 'peer-review-system') ?> </a>
                                                    <?php } ?>
                                                    <?php if (!empty($login_url)) { ?>
                                                        <a href="<?php echo esc_url($login_url) ?>"><?php esc_html_e('Sign in', 'peer-review-system') ?></a>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </fieldset>
                                <input type="hidden" name="key" value="<?php echo esc_attr($reset_key); ?>">
                            </form>
                        </div>
                    </div>
                    </div>
                <?php }
            } else { ?>
                <div class="<?php echo esc_attr($shell_classes); ?>">
                <div class="sj-main-login">
                    <div class="sj-login-left">
                        <?php if (!empty($logo)) { ?>
                            <strong>
                                <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo) ?>" alt="<?php echo esc_attr($left_tagline);?>"></a>
                            </strong>
                        <?php }
                        if (!empty($bg_image)) { ?>
                            <figure>
                                <img src="<?php echo esc_url($bg_image) ?>" alt="<?php echo esc_attr($left_text); ?>">
                            </figure>
                        <?php }
                        if (!empty($left_tagline) || !empty($left_text)) { ?>
                            <div class="sj-login-left_title">
                                <?php if (!empty($left_text)) { ?>
                                    <h2><?php echo esc_html($left_text); ?></h2>
                                <?php }
                                if (!empty($left_tagline)) { ?>
                                    <span><?php echo esc_html($left_tagline); ?></span>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="sj-login-right">
                        <div class="jase-forgot-shortcode__head">
                            <?php if (!empty($welcome_text)) { ?>
                                <h2 class="jase-forgot-shortcode__title"><?php echo esc_html($welcome_text); ?></h2>
                            <?php } else { ?>
                                <h2 class="jase-forgot-shortcode__title"><?php esc_html_e('Reset your password', 'peer-review-system'); ?></h2>
                            <?php } ?>
                            <?php if (!empty($tagline)) { ?>
                                <p class="jase-forgot-shortcode__lead"><?php echo esc_html($tagline); ?></p>
                            <?php } else { ?>
                                <p class="jase-forgot-shortcode__lead"><?php esc_html_e('Enter your account email and we will send you a reset link.', 'peer-review-system'); ?></p>
                            <?php } ?>
                        </div>
                        <form class="sj-themeform sj-login-form sj-forgot-password jase-forgot-shortcode__form">
                            <fieldset>
                                <div class="sj-themeform__wrap">
                                    <div class="form-group-wrap">
                                        <div class="form-group jase-forgot-shortcode__field">
                                            <label class="jase-forgot-shortcode__label" for="prs-forgot-email"><?php esc_html_e('Email address', 'peer-review-system'); ?></label>
                                            <input id="prs-forgot-email" type="email" name="email" class="form-control jase-forgot-shortcode__input" required="required" autocomplete="username" inputmode="email">
                                        </div>
                                        <div class="form-group jase-forgot-shortcode__field jase-forgot-shortcode__field--submit">
                                            <button type="button" class="sj-secondery-btn sj-primbtn-lg sj-password-forgot jase-forgot-shortcode__submit"><span><?php esc_html_e('Send reset link', 'peer-review-system') ?></span><i class="icon icon-arrow-right" aria-hidden="true"></i></button>
                                        </div>
                                        <?php if (!empty($login_url) || !empty($tpl_registration)) { ?>
                                            <div class="sj-lost-password form-group jase-forgot-shortcode__links">
                                                <?php if (!empty($login_url)) { ?>
                                                    <a href="<?php echo esc_url($login_url) ?>"><?php esc_html_e('Back to log in', 'peer-review-system') ?></a>
                                                <?php } ?>
                                                <?php if (!empty($tpl_registration)) { ?>
                                                    <a href="<?php echo esc_url($tpl_registration) ?>"><?php esc_html_e('Create account', 'peer-review-system') ?></a>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
                </div>
                <?php
            }
        }
    }
