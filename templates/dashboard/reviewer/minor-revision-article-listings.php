<?php
/**
 * The template used for minor revision article listing
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$meta_query_args    = $article_cat_args = array();
$keyword            = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
$date               = !empty($_GET['date']) ? esc_html($_GET['date']) : '';
$category           = !empty($_GET['category']) ? esc_html($_GET['category']) : '';
$article_status     = !empty($_GET['sort_by']) ? esc_html($_GET['sort_by']) : 'pending';
$tax_query_args     = array();

if (!empty($category)) {
    $article_cat_args[] = array(
        'taxonomy' => 'article_categories',
        'field'    => 'id',
        'terms'    => $category,
    );
    $tax_query_args[] = $article_cat_args;
}

$args['category']   = $category;
$args['keyword']    = $keyword;

$posts_per_page = !empty(get_option('posts_per_page')) ? get_option('posts_per_page') : 10;
$pg_page        = get_query_var('page') ? get_query_var('page') : 1;
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1;
$paged          = max($pg_page, $pg_paged);

/* is for editor */
$meta_query_article_status[] = array(
    'key'       => 'prs_article_editor_status',
    'value'     => 'minor_revision',
    'compare'   => '='
);

/* assigned articles */
$meta_query_article_assigned[] = array(
    'key'       => 'prs_article_assigned_reviwers',
    'value'     => $user_identity,
    'compare'   => 'LIKE',
);
$meta_query_args          = array_merge($meta_query_article_status, $meta_query_article_assigned);

/* query args */
$query_args = array(
    'post_type'             => 'articles',
    'post_status'           => $article_status,
    'posts_per_page'        => $posts_per_page,
    'paged'                 => $paged,
    'orderby'               => 'date',
    'order'                 => 'DESC',
    'paginate'              => true,
);

if (!empty($keyword)) {
    $query_args['s'] = $keyword;
    add_filter('posts_where', 'peer_review_system_advance_search_where_article');
    add_filter('posts_join', 'peer_review_system_advance_search_join');
    add_filter('posts_groupby', 'peer_review_system_advance_search_groupby');
}

if (!empty($meta_query_args)) {
    $query_relation           = array('relation' => 'AND',);
    $meta_query_args          = array_merge($query_relation, $meta_query_args);
    $query_args['meta_query'] = $meta_query_args;
}

//Taxonomy Query
if (!empty($tax_query_args)) {
    $query_args['tax_query'] = $tax_query_args;
}

$articles_data  = new WP_Query(apply_filters('peer_review_system_minor_revision_articles_filter', $query_args));
$total_posts    = $articles_data->found_posts;

?>
<div class="sj-boxwrapper">
    <div class="sj-boxtitle">
        <h3><?php esc_html_e('Minor revision articles', 'peer-review-system'); ?>
            <span class="sj-newtag"><?php echo intval($total_posts) ?><?php esc_html_e(' New', 'peer-review-system') ?></span>
        </h3>
        <?php peer_review_system_get_template_part('dashboard/article', 'status-search'); ?>
    </div>
    <?php peer_review_system_get_template_part('dashboard/article', 'search-form', $args); ?>
    <?php if ($articles_data->have_posts()) { ?>
        <ul class="sj-article-list">
            <?php
            while ($articles_data->have_posts()) {
                $articles_data->the_post();
                global $post;
                $args['article_id'] = get_the_ID();
                peer_review_system_get_template('article-loop/article-list-view-one.php', $args);
            }
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
