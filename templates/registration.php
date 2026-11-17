<?php
/**
 * The template used for registration
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

if (!function_exists('peer_review_system_registration')) {
    add_shortcode('peer_review_system_registration', 'peer_review_system_registration');
    function peer_review_system_registration($atts)
    {
        global $prs_settings;
        $bg_image      = !empty($atts['background']) ? $atts['background'] : '';
        $logo          = !empty($atts['logo']) ? $atts['logo'] : '';
        $tagline       = !empty($atts['tagline']) ? $atts['tagline'] : '';
        $left_text     = !empty($atts['left_text']) ? $atts['left_text'] : '';
        $left_tagline  = !empty($atts['left_tagline']) ? $atts['left_tagline'] : '';
        $welcome_text  = !empty($atts['welcome_text']) ? $atts['welcome_text'] : '';
        $terms_page    = !empty($atts['terms_page']) ? $atts['terms_page'] : '';
        $tpl_reset     = !empty($prs_settings['tpl_reset']) ? get_permalink($prs_settings['tpl_reset']) : home_url('/');
        $login_url     = !empty($prs_settings['tpl_login']) ? get_permalink($prs_settings['tpl_login']) : wp_login_url();
        $registration_roles     = !empty($prs_settings['allow_registration_roles']) ? $prs_settings['allow_registration_roles'] : array('subscriber');
        $defult_type            = !empty($prs_settings['defult_register_type']) ? $prs_settings['defult_register_type'] : 'subscriber';
        $list_roles     = apply_filters('peer_review_system_roles_list',array());
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
                        <img src="<?php echo esc_url($bg_image) ?>" alt="<?php echo esc_attr($left_tagline);?>">
                    </figure>
                <?php } ?>
                <?php if (!empty($left_text) || !empty($left_tagline)) { ?>
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
                <form class="sj-themeform sj-login-form sj-signup-form">
                    <fieldset>
                        <div class="sj-themeform__wrap">
                            <div class="form-group-wrap">
                                <div class="form-group">
                                    <div class="sj-placeholderholder">
                                        <input type="text" name="registration[fname]" class="form-control" required="required" placeholder=" ">
                                        <div class="sj-placeholder">
                                            <span><?php esc_html_e('First name', 'peer-review-system') ?> </span>
                                            <em><?php esc_html_e('*', 'peer-review-system') ?></em>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sj-placeholderholder">
                                        <input type="text" name="registration[lname]" required="required" class="form-control" placeholder=" ">
                                        <div class="sj-placeholder">
                                            <span><?php esc_html_e('Last name', 'peer-review-system') ?> </span>
                                            <em><?php esc_html_e('*', 'peer-review-system') ?> </em>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sj-placeholderholder">
                                        <input type="email" name="registration[email]" class="form-control" required="required" placeholder=" ">
                                        <div class="sj-placeholder">
                                            <span><?php esc_html_e('Enter your email address', 'peer-review-system') ?> </span>
                                            <em><?php esc_html_e('*', 'peer-review-system') ?> </em>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="sj-placeholderholder">
                                        <input type="password" name="registration[password]" class="form-control" required="required" placeholder=" ">
                                        <div class="sj-placeholder">
                                            <span><?php esc_html_e('Enter password', 'peer-review-system') ?> </span>
                                            <em><?php esc_html_e('*', 'peer-review-system') ?></em>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group sj-registration-wrapper">
                                    <h6><?php esc_html_e('Join as', 'peer-review-system'); ?>:</h6>
                                    <div class = "sj-registration-user">
                                        <?php if(!empty($registration_roles)){
                                            foreach($registration_roles as $key => $role){?>
                                                <div class="sj-check">
                                                    <input id="user_type_<?php echo esc_attr($role);?>" <?php checked( $defult_type, $list_roles ); ?> type="radio" name="registration[user_type]" value="<?php echo esc_attr($role);?>">
                                                    <label for="user_type_<?php echo esc_attr($role);?>"><?php echo esc_attr($list_roles[$role]); ?></label>
                                                </div>
                                        <?php }}?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="javascript:void(0);" class="sj-secondery-btn sj-submit-registration"><span><?php esc_html_e('Join now', 'peer-review-system') ?></span><i class="icon icon-arrow-right"></i></a>
                                </div>
                                <div class="form-group">
                                    <div class="sj-check sj-signup-check">
                                        <input type="hidden" name="registration[terms]" value="">
                                        <input type="checkbox" name="registration[terms]" id="sj-terms" name="prs-terms">
                                        <label for="sj-terms"><span><?php esc_html_e('I have read and agree to all', 'peer-review-system') ?></span> <a href="<?php echo esc_url($terms_page) ?>"><?php echo esc_html('Terms &amp; conditions', 'peer-review-system') ?></a></label>
                                    </div>
                                </div>
                                <div class="sj-lost-password form-group">
                                    <a href="<?php echo esc_url($login_url) ?>"><?php esc_html_e('Login now', 'peer-review-system'); ?></a>
                                    <a href="<?php echo esc_url($tpl_reset) ?>" class="sj-password-clr_light"><?php esc_html_e('Lost password?', 'peer-review-system'); ?>?</a>
                                </div>
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
