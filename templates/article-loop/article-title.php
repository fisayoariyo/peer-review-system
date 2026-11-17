<?php
/**
 * Article title
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$article_title      = !empty(get_the_title($article_id)) ? get_the_title($article_id) : '';
if (!empty($article_title)) { ?>
    <h3><?php echo esc_html($article_title); ?></h3>
    <?php
}
