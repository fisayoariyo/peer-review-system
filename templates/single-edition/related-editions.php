<?php
/**
 * Rwetaled editions
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Edition
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!empty($args) && is_array($args)) {
    extract($args);
}
$args = array(
    'post_type'         => 'editions',
    'post_status'       => 'publish',
    'post__not_in'      => array($edition_id),
    'orderby'           => 'date',
    'posts_per_page'    => 5
);
$related_editions = get_posts($args);

if (!empty($related_editions)) { ?>
    <div class="sj-noticeboard">
        <div class="sj-widget_title">
            <h2><?php esc_html_e('Related editions', 'peer-review-system') ?> </h2>
        </div>
        <div class="sj-widget_content">
            <ul class="sj-related-article-list">
                <?php
                foreach ($related_editions as $edition) {
                    $article_id     = $edition->ID;
                    $article_title  = get_the_title($article_id);
                    $article_date   = get_the_date('M j, Y');
                    $article_date   = date_i18n(get_option('date_format'), strtotime($article_date));
                    $permalink      = get_permalink($post->ID);
                    if (!empty($article_title) || !empty($article_date)) { ?>
                        <li>
                            <?php if (!empty($article_title)) { ?>
                                <h6><a href="<?php echo esc_url($permalink) ?>"><?php echo esc_html($article_title) ?></a></h6>
                            <?php } ?>
                            <?php if (!empty($article_date)) { ?>
                                <span><?php echo do_shortcode($article_date) ?></span>
                            <?php } ?>
                        </li><?php
                    }
                } ?>
            </ul>
        </div>
    </div>
<?php } ?>