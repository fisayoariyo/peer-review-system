<?php

/**
 * Article description
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
remove_filter( 'the_content', 'wpautop' );
$article_content    = apply_filters('the_content', get_post($article_id)->post_content);
if (!empty($article_content)) { ?>
    <div class="sj-box-two-desc">
        <?php echo apply_filters('the_content', $article_content);?>
    </div>
<?php }
add_filter( 'the_content', 'wpautop' );
