<?php

/**
 * Article information
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$article_date       = date_i18n(get_option('date_format'), strtotime(get_the_date(get_option('date_format'), $article_id)));
$article_author_id  = get_post_field('post_author', $article_id);
$author_name        = peer_review_system_get_username($article_author_id);
if (!empty($author_name) || !empty($article_date) || !empty($article_terms)) { ?>
    <ul class="sj-publish-content">
         <?php if (!empty($author_name)) { ?>
            <li>
                <i class="icon-user"></i>
                <a href="javascript:void(0)">
                    <?php echo esc_html($author_name); ?>
                </a>
            </li>
        <?php } ?>
        <?php if (!empty($article_date)) { ?>
            <li>
                <i class="icon-calendar"></i>
                <span><?php echo esc_html($article_date); ?></span>
            </li>
        <?php } ?>
        <?php peer_review_system_get_template('article-loop/article-categories.php', array('article_id' => $article_id)); ?>
    </ul>
<?php }
