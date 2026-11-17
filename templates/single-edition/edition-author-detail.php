<?php
/**
 * Edition author detail 
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Edition
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!empty($args) && is_array($args)) {
    extract($args);
}

$author_id          = get_post_field('post_author', $edition_id);
$user_info          = get_userdata($author_id);
$user_info          = !empty($user_info) ? $user_info : array();
$description        = !empty($user_info) ? esc_html($user_info->description) : ''; 
$display_name       = !empty($user_info) ? esc_html($user_info->display_name) : ''; 
$avatar             = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 100, 'height' => 100), $author_id), array('width' => 100, 'height' => 100));
$paragraph_lenght   = !empty($description) ? intval(strlen($description)) : '';
?>
<div class="sj-userimpact">
    <?php if(!empty($avatar) || !empty($display_name) || !empty($description)){?>
        <div class="sj-userimpact_info">
            <?php if(!empty($avatar) || !empty($display_name)){?>
                <div class="sj-user">
                    <?php if(!empty($avatar)){?>
                        <img src="<?php echo esc_url($avatar)?>" alt="<?php echo esc_attr($display_name)?>">
                    <?php }?>
                    <?php if(!empty($display_name)){?>
                        <h5><?php echo esc_html($display_name)?></h5>
                    <?php }?>
                </div>
            <?php } ?>
            <?php if(!empty($description)){?>
                <div class="author-description-less" id="author-description-less">
                    <?php $description_ = substr($description, 0, 100);?>
                    <p class="author-desciption-less"><?php echo esc_html($description_)?></p>   
                    <a href="javascript:void(0);" class="show-more"><?php esc_html_e('Show more', 'peer-review-system');?></a>
                </div>
                <div class="author-description-more d-none" id="author-description-more">
                    <p class="author-desciption-more"><?php echo esc_html($description)?></p>   
                    <a href="javascript:void(0);" class="show-less"><?php esc_html_e('Show less', 'peer-review-system');?></a>
                </div>
            <?php }?>
        </div>
    <?php } ?>
</div>