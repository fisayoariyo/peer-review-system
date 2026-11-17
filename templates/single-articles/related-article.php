<?php
/**
 * Related article template 
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Articles
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!empty($args) && is_array($args)) {
    extract($args);
}

$related_cat    = array();
$terms          = get_the_terms($article_id, 'article_categories');

if (!empty($terms)) {
    foreach ($terms as $term) {
        $related_cat[]    = $term->term_id;
    }
}
$query_args = array(
    'post_type'         => 'articles',
    'post_status'       => 'publish',
    'posts_per_page'    => 5,
    'post__not_in'      => array($article_id),
    'orderby'           => 'date',
    'order'             => 'DESC',

);
if (!empty($related_cat)) {
    $query_args['tax_query']  =  array(
        'taxonomy' => 'article_categories',
        'field'    => 'term_id',
        'terms'    => $related_cat,
    );
}
$articles_data = get_posts($query_args);
if (!empty($articles_data)) { ?>
    <div class="sj-aside-holder">
        <div class="sj-noticeboard">
            <div class="sj-widget_title">
                <h2><?php esc_html_e('Related articles', 'peer-review-system') ?> </h2>
            </div>
            <div class="sj-widget_content">
                <ul class="sj-related-article-list">
                    <?php
                    foreach ($articles_data as $article) {
                        $article_id     = $article->ID;
                        $article_title  = get_the_title($article_id);
                        $article_date   = get_the_date('M j, Y', $article_id);
                        $article_date   = date_i18n(get_option('date_format'), strtotime($article_date));
                        $permalink      = get_permalink($article_id);
                        $Image          = '';

                        if (!empty($article_title) || !empty($article_date)) { ?>
                            <li>
                                <?php if (!empty($Image)) { ?>
                                    <figure>
                                        <img src="<?php echo esc_url($Image); ?>" alt="<?php echo esc_attr($article_title); ?>">
                                    </figure>
                                <?php } ?>
                                <div class="sj-related-article">
                                    <?php if (!empty($article_title)) { ?>
                                        <h6><a href="<?php echo esc_url($permalink) ?>"><?php echo esc_html($article_title) ?></a></h6>
                                    <?php } ?>
                                    <?php if (!empty($article_date)) { ?>
                                        <span><?php echo do_shortcode($article_date) ?></span>
                                    <?php } ?>
                                </div>
                            </li><?php
                            }
                        } ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>