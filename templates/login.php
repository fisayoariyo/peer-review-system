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
if (!function_exists('peer_review_system_signin')) {
    add_shortcode('peer_review_system_signin', 'peer_review_system_signin');
    function peer_review_system_signin($atts)
    {
        global $prs_settings;
        $bg_image        = !empty($atts['background']) ? $atts['background'] : '';
        $logo            = !empty($atts['logo']) ? $atts['logo'] : '';
        $tagline         = !empty($atts['tagline']) ? $atts['tagline'] : '';
        $left_text       = !empty($atts['left_text']) ? $atts['left_text'] : '';
        $left_tagline    = !empty($atts['left_tagline']) ? $atts['left_tagline'] : '';
        $welcome_text    = !empty($atts['welcome_text']) ? $atts['welcome_text'] : '';
        $tpl_reset          = !empty($prs_settings['tpl_reset']) ? get_permalink($prs_settings['tpl_reset']) : home_url('/');
        $tpl_registration   = !empty($prs_settings['tpl_registration']) ? get_permalink($prs_settings['tpl_registration']) : wp_login_url('/');
        ?>
        <div class="sj-main-login">
            <div class="sj-login-left">
                <?php if (!empty($logo)) { ?>
                    <strong>
                        <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($logo) ?>" alt="<?php echo esc_attr($tagline);?>"></a>
                    </strong>
                <?php } ?>
                <?php if (!empty($bg_image)) { ?>
                    <figure>
                        <img src="<?php echo esc_url($bg_image) ?>" alt="<?php echo esc_attr($left_text);?>">
                    </figure>
                <?php } ?>
                <?php if (!empty($left_text) || !empty($left_tagline)) { ?>
                    <div class="sj-login-left_title">
                        <?php if (!empty($left_text)) { ?>
                            <h2><?php echo esc_html($left_text);?></h2>
                        <?php } ?>
                        <?php if (!empty($left_tagline)) { ?>
                            <span><?php echo esc_html($left_tagline);?></span>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="sj-login-right">
                <?php if (!empty($welcome_text) || !empty($tagline)) { ?>
                    <div class="sj-login-right_title">
                        <?php if (!empty($welcome_text)) { ?>
                            <h2><?php echo esc_html($welcome_text) ?></h2>
                        <?php } ?>
                        <?php if (!empty($tagline)) { ?>
                            <h3><?php echo esc_html($tagline) ?></h3>
                        <?php } ?>
                    </div>
                <?php } ?>
                <form class="sj-themeform sj-login-form">
                    <fieldset>
                        <div class="sj-themeform__wrap">
                            <div class="form-group-wrap">
                                <div class="form-group">
                                    <div class="sj-placeholderholder">
                                        <input type="email" name="login[email]" class="form-control" required="required" placeholder=" ">
                                        <div class="sj-placeholder">
                                            <span><?php esc_html_e('Username/email', 'peer-review-system') ?></span>
                                            <em><?php esc_html_e('*', 'peer-review-system') ?></em>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sj-placeholderholder">
                                        <input type="password" name="login[password]" class="form-control" required="required" placeholder=" ">
                                        <div class="sj-placeholder">
                                            <span><?php esc_html_e('Your password', 'peer-review-system') ?></span>
                                            <em><?php esc_html_e('*', 'peer-review-system') ?></em>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="javascript:void(0);" class="sj-secondery-btn sj-user-login"><span><?php esc_html_e('Login now', 'peer-review-system') ?></span><i class="icon icon-arrow-right"></i></a>
                                </div>
                                <?php if (!empty($tpl_registration) || !empty($tpl_reset)) { ?>
                                    <div class="sj-lost-password form-group">
                                        <?php if (!empty($tpl_registration)) { ?>
                                            <a href="<?php echo esc_url($tpl_registration) ?>"><?php esc_html_e('Join us today', 'peer-review-system') ?></a>
                                        <?php } ?>
                                        <?php if (!empty($tpl_reset)) { ?>
                                            <a href="<?php echo esc_url($tpl_reset) ?>" class="sj-password-clr_light"><?php esc_html_e('Lost password?', 'peer-review-system') ?></a>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <a class="journal-auth-back" href="<?php echo esc_url(home_url('/')); ?>"><i class="icon icon-arrow-left"></i><?php esc_html_e('Go back', 'peer-review-system');?></a>
            </div>
        </div>
        <?php
        peer_review_system_get_template_part('user-type', 'js-templates');
    }
}
