<?php
/**
 * Buy article 
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Articles
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $post,$current_user,$prs_settings;
$sj_un_paid_article = $article_download_url = $sj_custom_paid_article = $article_price = '' ;
$user_id    = $current_user->ID;
$user_id    = !empty($user_id) ? intval($user_id) : 0 ;
$article_id = !empty($post->ID) ? intval($post->ID) : '';
$article_author_id          = get_post_field('post_author', $article_id);
$sj_un_paid_article         = get_post_meta($article_id, 'sj_paid_article', true);
$article_download_url       = get_post_meta($article_id, 'article_download_url', true);
$sj_custom_paid_article     = get_post_meta($article_id, 'sj_custom_paid_article', true);
$article_price              = get_post_meta($article_id, 'article_price', true);

$download_article_title = !empty($prs_settings['purchase_title']) ? $prs_settings['purchase_title'] : '';
$download_article_desc  = !empty($prs_settings['purchase_description']) ? $prs_settings['purchase_description'] : '';
?>
<?php if(!empty($sj_un_paid_article) || !empty($sj_custom_paid_article)){?>
    <div class="sj-aside-holder">
        <div class="sj-contactus">
            <div class="sj-contactus_info">
                <?php if(!empty($download_article_title)){?>
                    <h4><?php echo esc_html($download_article_title);?></h4>
                <?php } ?>
                <?php if(!empty($download_article_desc)){?>
                    <span><?php echo esc_html($download_article_desc)?></span>
                <?php } ?>
                <?php if($sj_un_paid_article == 'free'){?>
                    <a href="javascript:void(0);" class="sj-approve-btn sj-article-download-btn" data-id="<?php echo intval($article_id)?>" data-userid="<?php echo intval($user_id)?>" data-author-id = "<?php echo intval($article_author_id)?>"><?php esc_html_e('Download article','peer-review-system');?> <i class="icon icon-download"></i></a>
                    <?php
                } elseif($sj_un_paid_article == 'download'){?>
                    <a href="<?php echo esc_url($article_download_url);?>" class="sj-approve-btn" data-article-id="<?php echo intval($article_id)?>" data-userid="<?php echo intval($user_id)?>" data-author-id = "<?php echo intval($article_author_id)?>"><?php esc_html_e('Download article','peer-review-system');?> <i class="icon icon-download"></i></a>
                    <?php 
                } elseif($sj_un_paid_article == 'price' && !empty($article_price)){
                    if(!empty($article_price)){?>
                        <a href="javascript:void(0)" class="sj-approve-btn" id="sj-article-checkout" data-article_id="<?php echo intval($article_id)?>" data-userId="<?php echo intval($user_id)?>" data-author_id = "<?php echo intval($article_author_id)?>"><i class="icon icon-shopping-cart"></i> <span><?php peer_review_system_price_format($article_price)?></span><?php esc_html_e('Buy now','peer-review-system');?></a>
                    <?php } }?>
            </div>  
        </div>
    </div> 
<?php 
}
