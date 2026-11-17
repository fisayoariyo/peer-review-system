<?php

/**
 * Article listing view one
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

if (!empty($args) && is_array($args)) {
    extract($args);
}
$article_status   = get_post_meta($article_id, 'article_submitted_status', true); 
$article_status   = !empty($article_status) ? esc_html($article_status) : ''; 
$editor_status    = get_post_meta($article_id, 'prs_article_editor_status', true); 
$edit_article     = add_query_arg(array('tab' => 'add-article', 'article_id' => $article_id, 'mode' => 'edit'), $current_page_link);
$edit_article     = !empty($edit_article) ? esc_html($edit_article) : ''; 
$article_detail   = add_query_arg(array('tab' => 'article-details', 'article_id' => $article_id), $current_page_link);
$article_detail     = !empty($article_detail) ? esc_html($article_detail) : ''; 
$current_page_link  = get_permalink();
$article_title      = get_the_title($article_id);
$article_author_id  = get_post_field('post_author', $article_id);
$avatar = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 100, 'height' => 100), $article_author_id), array('width' => 100, 'height' => 100));
?>
<li>
    <div class="sj-article-list_content">
        <?php if (has_post_thumbnail($article_id)) : ?>
            <figure>
                <img src="<?php echo get_the_post_thumbnail_url($article_id, 'sj_user_profile'); ?>" alt="<?php echo esc_attr($article_title); ?>">
            </figure>
        <?php endif; ?>
        <div class="sj-article-list-info">
            <?php if (!empty($article_title)) { ?>
                <h5><?php echo esc_html($article_title); ?></h5>
            <?php }
            peer_review_system_get_template('article-loop/article-info.php', array('article_id' => $article_id)); ?>
        </div>
    </div>
    <?php peer_review_system_get_template('article-loop/article-quick-action.php', array('article_id' => $article_id, 'edit_article' => $edit_article, 'article_detail' => $article_detail))?>
</li>