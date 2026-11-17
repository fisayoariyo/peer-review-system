<?php
/**
 * The template used for article detail
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$article_id = !empty($_GET['article_id']) ? $_GET['article_id'] : 0;
$article_author_id = get_post_field('post_author', $article_id);
/* approved by editor */
$article_title      = !empty(get_the_title($article_id)) ? get_the_title($article_id) : '';
$is_editor_approved = get_post_meta($article_id, 'prs_comment_article_review_status', true);
$tag_class  = '';
if(!empty($is_editor_approved)){
    if($is_editor_approved == 'minor_revision'){
        $is_editor_approved = 'Minor revision';
        $tag_class  = 'sj-orange';
    }
    if($is_editor_approved == 'approved'){
        $is_editor_approved = 'Approved';
        $tag_class  = 'sj-approved';
    }
    if($is_editor_approved == 'major_revision'){
        $is_editor_approved = 'Major revision';
        $tag_class  = 'sj-decline';
    }
    if($is_editor_approved == 'rejected'){
        $is_editor_approved = 'Rejected';
        $tag_class  = 'sj-decline';
    }
    if($is_editor_approved == 'under_revision'){
        $is_editor_approved = 'Under review';
        $tag_class  = 'sj-queue';
    }
} else {
    $is_editor_approved = 'Under review';
    $tag_class  = 'sj-queue';

}
$args['article_id'] = $article_id;
?>
<div class="sj-box-two">
    <div class="sj-box-two_header">
        <?php if (has_post_thumbnail($article_id)) : ?>
            <figure>
                <img src="<?php echo get_the_post_thumbnail_url($article_id, 'sj_user_profile'); ?>" alt="<?php echo esc_attr($article_title); ?>">
            </figure>
        <?php endif; ?>
        <div class="sj-box-two-title">
            <?php if (!empty($is_editor_approved)) { ?>
                <span class="sj-tag <?php echo esc_attr($tag_class) ?>"><?php echo do_shortcode($is_editor_approved); ?></span>
            <?php } ?>
            <?php peer_review_system_get_template('article-loop/article-title.php', array('article_id' => $article_id)); ?>
            <?php peer_review_system_get_template('article-loop/article-info.php', array('article_id' => $article_id)); ?>
        </div>
    </div>
    <div class="sj-box-two_content">
        <h4><?php esc_html_e('Article details', 'peer-review-system'); ?></h4>
        <?php peer_review_system_get_template('article-loop/article-description.php', array('article_id' => $article_id)); ?>
        <?php peer_review_system_get_template('article-loop/article-documents.php', array('article_id' => $article_id)); ?>
    </div>
</div>

<?php peer_review_system_get_template('article-loop/article-feedback.php', array('article_id' => $article_id));
