<?php
/**
 * sidebar detail 
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
} ?>
<!-- Articles detail End -->
<div class="col-xl-3">
    <!-- user info sidebar -->
    <aside>
        <?php peer_review_system_get_template('single-articles/article-buy-content.php', $args); ?>
        <!-- <?php peer_review_system_get_template('single-articles/article-author.php', $args); ?> -->
        <?php peer_review_system_get_template('single-articles/article-author-detail.php', $args); ?>
        <?php peer_review_system_get_template('single-articles/related-article.php', $args); ?>
    </aside>
    <!-- user info sidebar -->
</div>