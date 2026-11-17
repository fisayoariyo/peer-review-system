<?php
/**
 * The template used for showing articles
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

global $post, $current_user;
if (!empty($args) && is_array($args)) {
    extract($args);
}
$user_identity = !empty($user_identity) ? $user_identity : $current_user->ID;
$meta_query_args    = array();
$keyword            = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
$date               = !empty($_GET['date']) ? esc_html($_GET['date']) : '';
$category           = !empty($_GET['category']) ? esc_html($_GET['category']) : '';
$article_status     = !empty($_GET['sort_by']) ? esc_html($_GET['sort_by']) : 'any';
$tax_query_args     = array();


if (!empty($category)) {
    $tax_query_args[] = array(
        'taxonomy' => 'article_categories',
        'field'    => 'id',
        'terms'    => $category,
    );
}
$args['category']   = $category;
$args['keyword']    = $keyword;

$posts_per_page = !empty(get_option('posts_per_page')) ? get_option('posts_per_page') : 10;
$pg_page        = get_query_var('page') ? get_query_var('page') : 1;
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1;
$paged          = max($pg_page, $pg_paged);
/* getting all articles */
$query_args = array(
    'post_type'             => 'articles',
    'post_status'           => $article_status,
    'posts_per_page'        => $posts_per_page,
    'paged'                 => $paged,
    'orderby'               => 'date',
    'order'                 => 'DESC',
    'paginate'              => true,
    'author'                => $user_identity,
);

if (!empty($keyword)) {
    $query_args['s'] = $keyword;
    add_filter('posts_where', 'peer_review_system_advance_search_where_article');
    add_filter('posts_join', 'peer_review_system_advance_search_join');
    add_filter('posts_groupby', 'peer_review_system_advance_search_groupby');
}
//Taxonomy Query
if (!empty($tax_query_args)) {
    $query_args['tax_query'] = $tax_query_args;
}

$articles_data = new WP_Query(apply_filters('peer_review_system_my_articles_filter', $query_args));
$total_posts = $articles_data->found_posts;
$total_posts = !empty($total_posts) ? $total_posts : 0;
?>
<div class="sj-boxwrapper">
    <div class="sj-boxtitle">
        <h3><?php esc_html_e('Articles', 'peer-review-system'); ?>
            <span class="sj-newtag"> <?php echo intval($total_posts)?> <?php esc_html_e('New','peer-review-system') ?></span>
        </h3>
        <?php peer_review_system_get_template_part('dashboard/article', 'status-search'); ?>
    </div>
    <?php peer_review_system_get_template_part('dashboard/article', 'search-form', $args); ?>
    <?php if ($articles_data->have_posts()) { ?>
        <ul class="sj-article-list">
            <?php
            while ($articles_data->have_posts()) {
                $articles_data->the_post();
                $article_id         = get_the_ID();
                $article_title      = get_the_title($article_id);
                $article_author_id  = get_post_field('post_author', $article_id);
                $article_detail = add_query_arg(array('tab' => 'article-details', 'article_id' => $article_id), $current_page_link);
                $edit_article = add_query_arg(array('tab' => 'add-article', 'article_id' => $article_id, 'mode' => 'edit'), $current_page_link);
                $editor_status = get_post_meta($article_id, 'prs_article_editor_status', true);
                ?>
                <li>
                    <div class="sj-article-list_content">
                        <?php if (has_post_thumbnail($post->ID)) : ?>
                            <figure>
                                <img src="<?php echo get_the_post_thumbnail_url($post->ID, 'sj_user_profile'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                            </figure>
                        <?php endif; ?>
                        <div class="sj-article-list-info">
                            <?php if (!empty($article_title)) { ?>
                                <h5><?php echo esc_html($article_title); ?></h5>
                            <?php } ?>
                            <?php peer_review_system_get_template('article-loop/article-info.php', array('article_id' => $article_id)); ?>
                        </div>
                    </div>
                    <?php peer_review_system_get_template('article-loop/article-quick-action.php', array('article_id' => $article_id,'edit_article' =>  $edit_article,'article_detail'=>$article_detail))?>
                </li>
            <?php }
            wp_reset_postdata(); ?>           
        </ul>
    <?php } else {
        do_action('peer_review_system_empty_record');
    }  ?>
</div>
<?php
if (!empty($total_posts) && !empty($posts_per_page) && $total_posts > $posts_per_page) {
    peer_review_system_paginate($articles_data, 'sj-pagination');
}
wp_reset_postdata();
