<?php
/**
 * Google singup user type JS template
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
wp_enqueue_script('wp-util');
?>
<script type="text/template" id="tmpl-sj-social-user-type">
    <div class="modal-header">
        <h5><?php esc_html_e('Choose user type', 'peer-review-system'); ?></h5>
        <a href="javascript:void(0);" class="sj-close" type="button" data-bs-dismiss="modal" aria-label="Close"><i class="icon icon-x"></i></a>
    </div>
    <div class="modal-body">
        <form class="sj-themeform">
            <fieldset>
                <div class="sj-themeform__wrap">
                    <div class="form-group registration-user-type">
                    <div class="sj-check sj-radiosm">
                            <input id="usertype_author" class="sj-usertype-radio" type="radio" name="choose_usertype" value="article_author">
                            <label for="usertype_author"><?php esc_html_e('Author', 'peer-review-system'); ?></label>
                        </div>
                        <div class="sj-check">
                            <input id="user_type_editor" type="radio" name="registration[user_type]" value="article_editor">
                            <label for="user_type_editor"><?php esc_html_e('Editor', 'peer-review-system'); ?></label>
                        </div>
                        <div class="sj-check sj-radiosm">
                            <input id="usertype_reviewer" class="sj-usertype-radio" type="radio" name="choose_usertype" value="article_reviewer">
                            <label for="usertype_reviewer"><?php esc_html_e('Reviewer', 'peer-review-system'); ?></label>
                        </div>
                        <div class="sj-check">
                            <input id="user_type_subscriber" type="radio" name="registration[user_type]" value="subscriber">
                            <label for="user_type_subscriber"><?php esc_html_e('Subscriber', 'peer-review-system'); ?></label>
                        </div>
                    </div>
                    <input type="hidden" id="social-userdetail" name="social_userdetail" value="{{data.userData}}">
                </div>
            </fieldset>
        </form>
    </div>
</script>