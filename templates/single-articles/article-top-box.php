<?php
/**
 * Article detail term info
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Articles
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!empty($args) && is_array($args)) {
    extract($args);
}
$article_terms = wp_get_post_terms($article_id, 'article_categories');
$avatar        = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 100, 'height' => 100), $article_author_id), array('width' => 100, 'height' => 100));
?>
<?php if(!empty($article_title)){?>
    <div class="sj-article-detail_title">
        <h3><?php echo esc_html($article_title)?></h3>
    </div>
<?php } ?>
<?php if(!empty($author_name)|| !empty($article_date) || !empty($article_terms)){?>
    <ul class="sj-articles_info">
        <?php if(!empty($author_name)){?>
            <li>
                <span><i class="icon icon-user"></i><?php echo esc_html($author_name)?></span>
            </li>
        <?php } ?>
        <?php if(!empty($article_date)){?>
            <li>
                <span><i class="icon icon-calendar"></i><?php echo do_shortcode($article_date)?></span>
            </li>
        <?php } ?>
        <?php peer_review_system_get_template('article-loop/article-categories.php', array('article_id' => $article_id)); ?>
    </ul>
    <?php
    if (function_exists('jase_peer_review_format_article_citation')) {
        $cite_line = jase_peer_review_format_article_citation($article_id);
        if ($cite_line) {
            echo '<p class="jase-pub-item__cite">' . esc_html($cite_line) . '</p>';
        }
    }
    ?>
<?php } ?>