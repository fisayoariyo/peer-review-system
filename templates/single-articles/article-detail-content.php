<?php
/**
 * Article detail content
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
if(!empty($article_id)){?>
    <div class="sj-article-detail_content">
        <?php echo peer_review_system_get_article_display_content($article_id); ?>
    </div>
<?php }
 