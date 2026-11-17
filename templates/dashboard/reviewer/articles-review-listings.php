<?php
/**
 * The template used for articles reviews listing
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $post;
if (!empty($args) && is_array($args)) {
    extract($args);
}

$meta_query_args    = array();
$keyword            = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
$date               = !empty($_GET['date']) ? esc_html($_GET['date']) : '';
$category           = !empty($_GET['category']) ? esc_html($_GET['category']) : '';
$article_status     = !empty($_GET['sort_by']) ? esc_html($_GET['sort_by']) : 'publish';
$tax_query_args     = array();

if (!empty($category)) {
    $article_cat_args[] = array(
        'taxonomy' => 'article_categories',
        'field'    => 'id',
        'terms'    => $category,
    );
    $tax_query_args[] = $article_cat_args;
}

if(!empty($category)){
    $args['category']   = $category;
}
if(!empty($keyword)){
    $args['keyword'] = $keyword;
}
$posts_per_page = !empty(get_option('posts_per_page')) ? get_option('posts_per_page') : 10;
$pg_page        = get_query_var('page') ? get_query_var('page') : 1;
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1;
$paged          = max($pg_page, $pg_paged);

/* is for editor */
$meta_query_args[] = array(
    'key'       => 'article_assign_editor',
    'value'     => 'editor',
    'compare'   => '='
);

/* getting all articles */
$query_args = array(
    'post_type'             => 'articles',
    'post_status'           => $article_status,
    'posts_per_page'        => $posts_per_page,
    'paged'                 => $paged,
    'orderby'               => 'date',
    'order'                 => 'DESC',
    'paginate'              => true,
    'ignore_sticky_posts'   => 1,
);

if (!empty($keyword)) {
    $query_args['s'] = $keyword;
    add_filter('posts_where', 'peer_review_system_advance_search_where_article');
    add_filter('posts_join', 'peer_review_system_advance_search_join');
    add_filter('posts_groupby', 'peer_review_system_advance_search_groupby');
}

if (!empty($meta_query_args)) {
    $query_args['meta_query'] = $meta_query_args;
}

//Taxonomy Query
if (!empty($tax_query_args)) {
    $query_args['tax_query'] = $tax_query_args;
}

$articles_data = new WP_Query(apply_filters('peer_review_system_article_search_filter', $query_args));
$total_posts = $articles_data->found_posts;
?>
<div class="sj-boxwrapper">
    <div class="sj-boxtitle">
        <h3><?php esc_html_e('Articles under review', 'peer-review-system'); ?>
            <span class="sj-newtag">2 New</span>
        </h3>
        <?php peer_review_system_get_template_part('dashboard/article', 'status-search'); ?>
    </div>
    <?php peer_review_system_get_template_part('dashboard/article', 'search-form', $args); ?>
    <?php if ($articles_data->have_posts()) { ?>
        <ul class="sj-article-list">
            <?php
            while ($articles_data->have_posts()) {
                $articles_data->the_post();
                $article_id         = $post->ID;
                $article_title      = get_the_title($article_id);
                $article_author_id  = get_post_field('post_author', $article_id);
                $avatar = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 100, 'height' => 100), $article_author_id), array('width' => 100, 'height' => 100));
                $article_detail = add_query_arg(array('tab' => 'article-details', 'article_id' => $article_id), $current_page_link);
                ?>
                <li>
                    <div class="sj-article-list_content">
                        <?php if (!empty($avatar)) { ?>
                            <figure><img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_attr($article_title); ?>"></figure>
                        <?php } ?>
                        <div class="sj-article-list-info">

                            <?php if (!empty($article_title)) { ?>
                                <h5><?php echo esc_html($article_title); ?></h5>
                            <?php } ?>

                            <?php peer_review_system_get_template('article-loop/article-info.php', array('article_id' => $article_id)); ?>

                        </div>
                    </div>
                    <div class="sj-tag-holder">
                        <span class="sj-notread"></span>
                        <a href="<?php echo esc_url($article_detail); ?>"><i class="icon-chevron-right"></i></a>
                    </div>
                </li>
            <?php }
            wp_reset_postdata(); ?>
        </ul>
    <?php } else {
        do_action('peer_review_system_empty_record');
    } ?>
</div>

<?php
if (!empty($total_posts) && !empty($posts_per_page) && $total_posts > $posts_per_page) {
    peer_review_system_paginate($articles_data, 'sj-pagination');
}
