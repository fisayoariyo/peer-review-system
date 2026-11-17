<?php
/**
 * Aricle author detail 
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Articles
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!empty($args) && is_array($args)) {
    extract($args);
} 
$article_author_id  = !empty($args) ? intval($article_author_id) : '' ;
$author_name        = !empty($args) ? esc_html($author_name) : '' ;
$user_info          = get_userdata($article_author_id);
$user_info          = !empty($user_info) ? $user_info : array();
$description        = !empty($user_info) ? esc_html($user_info->description) : ''; 
$paragraph_lenght   = !empty($description) ? intval(strlen($description)) : '';
$avatar = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 100, 'height' => 100), $article_author_id), array('width' => 100, 'height' => 100));
?>
<div class="sj-aside-holder">
    <div class="sj-userimpact">
        <?php if(!empty($avatar) || !empty($author_name) || !empty($description)){?>
            <div class="sj-userimpact_info">
                <?php if(!empty($avatar) || !empty($author_name)){?>
                    <div class="sj-user">
                        <?php if(!empty($avatar)){?>
                            <img src="<?php echo esc_url($avatar)?>" alt="<?php echo esc_attr($author_name)?>">
                        <?php }?>
                        <?php if(!empty($author_name)){?>
                            <h5><?php echo esc_html($author_name)?></h5>
                        <?php }?>
                    </div>
                <?php } ?>
                <?php if(!empty($description)){?>
                    <div class="author-description-less" id="author-description-less">
                        <?php $description_ = substr($description, 0, 100);?>
                        <p class="author-desciption-less"><?php echo esc_html($description_)?></p>   
                        <a href="javascript:void(0);" class="show-more"><?php esc_html_e('Read more', 'peer-review-system');?></a>
                    </div>
                    <div class="author-description-more d-none" id="author-description-more">
                        <p class="author-desciption-more"><?php echo esc_html($description)?></p>   
                        <a href="javascript:void(0);" class="show-less"><?php esc_html_e('Read less', 'peer-review-system');?></a>
                    </div>
                <?php }?>
            </div>
        <?php } ?>
    </div>
</div>