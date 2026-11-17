<?php
/**
 * Article content
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
<div class="col-xl-9">
    <div class="sj-article-detail">
        <?php peer_review_system_get_template('single-articles/article-top-box.php', $args); ?>
        <?php peer_review_system_get_template('single-articles/article-detail-content.php', $args); ?>
    </div>
</div>