<?php
/**
 * Article quick action
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global  $current_user;
$user_id        = $current_user->ID;
$editor_status  = get_post_meta($article_id, 'prs_article_editor_status', true);
$user_type      = !empty($user_id) ? apply_filters('PeerReviewSystemGetUserType', $user_id) : '';
$edit_article   = !empty($edit_article) ? esc_url($edit_article) : '' ;
$article_detail = !empty($article_detail) ? esc_url($article_detail) : '' ;
?>
<?php if(!empty($user_type) && $user_type == 'author'){?>
    <?php if(!empty($editor_status) && $editor_status == 'approved') {?>
        <div class="sj-editions_head_option">
            <a href="javascript:void(0);" class="sj-actionbtn"><span><?php esc_html_e('Quick action','peer-review-system')?> </span><i class="icon icon-chevron-down"></i></a>
            <ul class="sj-actiondropdown">
                <li>
                    <a href="<?php echo esc_url($article_detail)?>"><?php esc_html_e('View history','peer-review-system')?></a>
                </li>            
            </ul>
        </div>
    <?php } else {?>
        <div class="sj-editions_head_option">
            <a href="javascript:void(0);" class="sj-actionbtn"><span><?php esc_html_e('Quick action','peer-review-system')?> </span><i class="icon icon-chevron-down"></i></a>
            <ul class="sj-actiondropdown">
                <li>
                    <a href="<?php echo esc_url($article_detail)?>"><?php esc_html_e('View article','peer-review-system')?></a>
                </li>
                <?php if(!empty($user_type) && $user_type == 'author'){?>
                    <?php if($editor_status != 'approved' && $editor_status != 'rejected'){?>
                        <li>
                            <a href="<?php echo esc_url($edit_article); ?>"><?php esc_html_e('Edit article','peer-review-system') ?></a>
                        </li>
                    <?php } ?>
                    <li>
                        <a href="javascript:void(0);" class="prs-remove-article" data-article_id="<?php echo esc_attr($article_id);?>"><?php esc_html_e('Remove article','peer-review-system')?></a>
                    </li>
                <?php } ?>
            
            </ul>
        </div>
    <?php }
} else { ?>
    <div class="sj-editions_head_option">
        <?php if(!empty($user_type) && $user_type == 'editor' && !empty($editor_status) && $editor_status == 'approved') {?>
            <a href="javascript:void(0);" class="sj-actionbtn"><span><?php esc_html_e('Quick action','peer-review-system')?> </span><i class="icon icon-chevron-down"></i></a>
            <ul class="sj-actiondropdown">
                <li>
                    <a href="<?php echo esc_url($article_detail)?>"><?php esc_html_e('View history','peer-review-system')?></a>
                </li>   
                <li>
                    <a href="javascript:void(0);" class="sj-priceEdit" data-article_id="<?php echo esc_attr($article_id);?>"><?php esc_html_e('Article price','peer-review-system')?></a>
                </li>            
            </ul>
        <?php } else { ?>
            <div class="sj-editions_head_option">
                <a href="<?php echo esc_url($article_detail)?>" class="sj-actionbtn"><span><?php esc_html_e('View article','peer-review-system')?></span></a>
            </div>  
        <?php } ?>
    </div>
    <?php
}
