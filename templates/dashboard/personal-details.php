<?php
/**
 * Author personal detail
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;

if (!empty($args) && is_array($args)) {
    extract($args);
}

$user_identity  = !empty($user_identity) ? $user_identity : $current_user->ID;
$country_region = get_post_meta($user_identity, '_country_region', true);
$countries      = peer_review_system_country_array();
$countries      = !empty($countries) ? $countries : array();
$user_info      = get_userdata($user_identity);
$user_info      = !empty($user_info) ? $user_info : array();
$first_name     = !empty($user_info) ? esc_html($user_info->first_name) : '';
$last_name      = !empty($user_info) ? esc_html($user_info->last_name) : '';
$display_name   = !empty($user_info) ? esc_html($user_info->display_name) : ''; 
$description    = !empty($user_info) ? esc_html($user_info->description) : ''; 
$country_region = !empty($user_identity) ? get_user_meta( $user_identity, 'country', true ) : ''; 
$tagline        = !empty($user_identity) ? get_user_meta( $user_identity, 'tagline', true ) : ''; 
$address        = !empty($user_identity) ? get_user_meta( $user_identity, 'address', true ) : ''; 
$zipcode        = !empty($user_identity) ? get_user_meta( $user_identity, 'zipcode', true ) : '';
 
?>
<div class="sj-boxholder">
    <div class="sj-boxwrapper">
        <div class="sj-boxtitle">
            <h3><?php esc_html_e('Personal details', 'peer-review-system') ?></h3>
        </div>
        <div class="sj-box">
            <form id="sj-persoanl-detail-form" class="sj-themeform sj-dhbform sj-add-article-form">
                <fieldset>
                    <div class="sj-themeform__wrap">
                        <div class="form-group-wrap">
                            <div class="form-group form-group-half">
                                <label class="sj-label"><?php esc_html_e('First name', 'peer-review-system') ?> </label>
                                <div class="sj-placeholderholder">
                                    <input type="text" name="first_name" class="form-control" required="" placeholder="<?php esc_attr_e('Your first name', 'peer-review-system') ?>" value="<?php echo esc_attr($first_name);?>">
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Your first name', 'peer-review-system') ?> </span>
                                        <em>*</em>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-half">
                                <label class="sj-label"><?php esc_html_e('Last name', 'peer-review-system') ?></label>
                                <div class="sj-placeholderholder">
                                    <input type="text" name="last_name" class="form-control" required="" placeholder="<?php esc_attr_e('Your last name', 'peer-review-system') ?>" value="<?php echo esc_attr($last_name);?>">
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Your last name', 'peer-review-system') ?></span>
                                        <em>*</em>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="sj-label"><?php esc_html_e('Your tagline', 'peer-review-system') ?> </label>
                                <div class="sj-placeholderholder">
                                    <input type="text" name="tagline" class="form-control" required="" placeholder="<?php esc_attr_e('Add your tagline', 'peer-review-system') ?>" value="<?php echo esc_attr($tagline);?>">
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Add your tagline', 'peer-review-system') ?> </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group form-group-3half">
                                <label class="sj-label"><?php esc_html_e('Country', 'peer-review-system'); ?></label>
                                <div class="sj-select">
                                    <select id="sj_country" class="form-control" name="country" data-placeholder="<?php esc_attr_e('Select Country from list', 'peer-review-system'); ?>">
                                        <option value="" selected hidden disabled><?php esc_html_e('Choose country', 'peer-review-system'); ?></option>
                                        <?php foreach ($countries as $key => $country) { ?>
                                            <option <?php if (strtolower($country_region) == strtolower($key)) { echo esc_attr('selected'); }?>
                                                value="<?php echo esc_attr(strtolower($key)) ?>"><?php echo esc_html($country) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group form-group-3half">
                                <label class="sj-label"><?php esc_html_e('Address', 'peer-review-system'); ?></label>
                                <div class="sj-placeholderholder">
                                    <input type="text" name="address" value="<?php echo esc_attr($address) ?>" class="form-control" placeholder=" " required value="<?php echo esc_attr($address);?>">
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Enter your address', 'peer-review-system'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-3half">
                                <label class="sj-label"><?php esc_html_e('Zipcode', 'peer-review-system') ?> </label>
                                <div class="sj-placeholderholder">
                                    <input type="number" name="zipcode" class="form-control" required placeholder="<?php esc_attr_e('Enter zipcode', 'peer-review-system') ?>" value="<?php echo esc_attr($zipcode);?>">
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Enter zipcode', 'peer-review-system') ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="sj-label"><?php echo esc_html_e('Description', 'peer-review-system'); ?></label>
                                <div class="sj-placeholderholder">
                                    <textarea class="form-control" name="description"  placeholder="<?php esc_attr_e('Enter description', 'peer-review-system') ?>" required><?php echo esc_html($description); ?></textarea>
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Enter description', 'peer-review-system') ?> </span>
                                        <em>*</em>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group sj-addinfo">
                                <a href="javascript:void(0)" class="sj-secondery-btn-lg sj-save-article-details sj-personal-detail"><?php esc_html_e('Save & update', 'peer-review-system') ?> </a>
                                <span><?php esc_html_e('Save & update the latest changes to the live', 'peer-review-system') ?> </span>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class="sj-boxwrapper">
        <div class="sj-boxtitle">
            <h3><?php esc_html_e('Change password', 'peer-review-system') ?></h3>
        </div>
        <div class="sj-box">
            <form id="sj-change-password-form" class="sj-themeform sj-dhbform sj-add-article-form">
                <fieldset>
                    <div class="sj-themeform__wrap">
                        <div class="form-group-wrap">
                            <div class="form-group form-group-3half">
                                <label class="sj-label"><?php esc_html_e('Old password', 'peer-review-system'); ?></label>
                                <div class="sj-placeholderholder">
                                    <input type="password" name="old_password" class="form-control" placeholder="<?php esc_attr_e('Old password', 'peer-review-system')?>" required>
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Old password', 'peer-review-system'); ?></span>
                                        <em>*</em>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-3half">
                                <label class="sj-label"><?php esc_html_e('New password', 'peer-review-system'); ?></label>
                                <div class="sj-placeholderholder">
                                    <input type="password" name="new_password" class="form-control" placeholder="<?php esc_attr_e('New password', 'peer-review-system')?>" required>
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('New password', 'peer-review-system'); ?></span>
                                        <em>*</em>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group form-group-3half">
                                <label class="sj-label"><?php esc_html_e('Retype password', 'peer-review-system') ?> </label>
                                <div class="sj-placeholderholder">
                                    <input type="password" name="retype_password" class="form-control" required placeholder="<?php esc_attr_e('Retype password', 'peer-review-system')?>">
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Retype password', 'peer-review-system') ?></span>
                                        <em>*</em>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group sj-addinfo">
                                <a href="javascript:void(0)" class="sj-secondery-btn-lg sj-save-article-details sj-change-password"><?php esc_html_e('Update', 'peer-review-system') ?> </a>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>
