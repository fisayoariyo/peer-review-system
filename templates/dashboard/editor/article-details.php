<?php
/**
 * The template used for article detail
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
$user_id            = $current_user->ID;
$article_id         = !empty($_GET['article_id']) ? $_GET['article_id'] : 0;
$article_author_id  = get_post_field('post_author', $article_id);
$article_title      = !empty(get_the_title($article_id)) ?  get_the_title($article_id) : '';
/* approved by editor */
$is_editor_approved = get_post_meta($article_id, 'prs_article_editor_status', true);
$editor_approved_status   = (!empty($is_editor_approved) && $is_editor_approved == 'approved') ? esc_html__('Approved', 'peer-review-system') : '';
if (!empty($article_id)) {
    update_post_meta($article_id, 'prs_article_readed_editor', '1');
}
?>
<div class="sj-box-two">
    <div class="sj-box-two_header">
        <?php if (has_post_thumbnail($article_id)) : ?>
            <figure>
                <img src="<?php echo get_the_post_thumbnail_url($article_id, 'sj_user_profile'); ?>" alt="<?php echo esc_attr($article_title); ?>">
            </figure>
        <?php endif; ?>
        <div class="sj-box-two-title">
            <?php if (!empty($editor_approved_status)) { ?>
                <span class="sj-tag sj-approved"><?php echo do_shortcode($editor_approved_status); ?></span>
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

<?php peer_review_system_get_template('article-loop/article-feedback.php', array('article_id' => $article_id)); ?>
<?php peer_review_system_get_template('article-loop/article-assign-reviewer.php', array('article_id' => $article_id)); ?>
<?php peer_review_system_get_template('article-loop/author-article-feedback.php', array('article_id' => $article_id, 'user_id' => $user_id));?>
<?php peer_review_system_get_template('article-loop/article-revision-reply.php', array('article_id' => $article_id));
