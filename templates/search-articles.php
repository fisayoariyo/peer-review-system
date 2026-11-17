<?php
/**
 * Search articles 
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $prs_settings,$current_user;
get_header();
$tax_query_args     = array();
$pg_page            = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged           = get_query_var('paged') ? get_query_var('paged') : 1;
$paged              = max($pg_page, $pg_paged);
$base_url           = get_the_permalink();
$current_url        = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$per_page           = get_option('posts_per_page');
$categories         = !empty($_GET['categories']) ? $_GET['categories'] : array();
$search_keyword     = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
$sort_by            = !empty($_GET['sort_by']) ? esc_attr($_GET['sort_by']) : 'ID';
$sort_order         = !empty($_GET['sort_order']) ? esc_attr($_GET['sort_order']) : 'DESC';
$user_identity      = $current_user->ID;
$dashboard_page_url = !empty($prs_settings['tpl_dashboard']) ? get_permalink($prs_settings['tpl_dashboard']) : '';
$userType           = apply_filters('PeerReviewSystemGetUserType', $user_identity);

if (!empty($user_identity) && !empty($userType) && !empty($userType === 'author')) {
    $dashboard_page_url = add_query_arg(array('useridentity' => $user_identity), $dashboard_page_url);
    $dashboard_page_url = add_query_arg(array('tab' => 'add-article'), $dashboard_page_url);  
}

if (!empty($categories) && is_array($categories)) {
    $categories    = array_map('esc_attr', $categories);
    $query_relation = array('relation' => 'OR',);
    $type_args      = array();
    foreach ($categories as $key => $type) {
        $type_args[] = array(
            'taxonomy' => 'article_categories',
            'field'    => 'slug',
            'terms'    => $type,
        );
    }
    $tax_query_args = array_merge($query_relation, $type_args);
}

$query_args = array(
    'posts_per_page'        => $per_page,
    'paged'                 => $paged,
    'post_type'             => 'articles',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'orderby'               => $sort_by,
    'order'                 => $sort_order,
    'meta_query'            => peer_review_system_public_article_meta_query(),
);

// if keyword field is set in search then append its args in $query_args
if (!empty($search_keyword)) {
    $query_args['s'] = $search_keyword;
    add_filter('posts_where', 'peer_review_system_advance_search_where_article');
    add_filter('posts_join', 'peer_review_system_advance_search_join');
    add_filter('posts_groupby', 'peer_review_system_advance_search_groupby');
}

//Taxonomy Query
if (!empty($tax_query_args)) {
    $query_relation = array('relation' => 'AND',);
    $query_args['tax_query'] = array_merge($query_relation, $tax_query_args);
}
$articles_data = new WP_Query(apply_filters('peer_review_system_instructor_search_filter', $query_args));

$total_currnt_posts = $articles_data->post_count;
$article_count_start        = 1;

if($paged>1){
    $article_count_start    = ($paged-1) * $per_page;
    $total_currnt_posts     = $article_count_start+$total_currnt_posts;
}

$total_posts = $articles_data->found_posts;
?>
<!-- Main start -->
<main class="sj-main sj-lightbg">
    <div class="sj-main-section sj-search-article">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-6 col-xl-3 order-md-2 order-xl-1">
                    <!-- Widget Start -->
                    <aside class="sj-asidebar">
                        <form id="sj-articles-search" action="<?php the_permalink();?>" method="GET">
                            <?php peer_review_system_get_template('article-search/search-keyword.php', array('keyword' => $search_keyword)); ?>
                            <?php peer_review_system_get_template('article-search/search-category.php', array('categories' => $categories)); ?>
                            <div class="sj-widget_btn">
                                <a href="javascript:void(0);" class="sj-secondery-btn sj-search-articles-btn"><?php esc_html_e('Apply filter', 'peer-review-system');?></a>
                                <a href="<?php the_permalink(); ?>" class="sj-resetbtn"><?php esc_html_e('Reset filter', 'peer-review-system');?></a>
                            </div>
                        </form>
                    </aside>
                    <!-- Widget End -->
                </div>
                <!-- Articles listing Start -->
                <div class="col-md-12 col-xl-9 order-md-3 order-xl-2">
                    <div class="sj-sorting">
                        <?php if($articles_data->have_posts()){?>
                            <div class="sj-sorting_title">                              
                                <h5><?php echo sprintf(__('Showing results <span>%d to %d</span> of %d articles','peer-review-system'), $article_count_start, $total_currnt_posts, $total_posts); ?></h5>
                            </div>
                        <?php }?>
                        <div class="sj-sorting_field">
                            <span class="sj-select">
                                <select id="sj-sort-articles" data-placeholder="<?php esc_attr_e('Select article category', 'peer-review-system');?>" data-placeholderinput="<?php esc_attr_e('Sort by', 'peer-review-system');?>" class="form-control">
                                    <option value="ID" <?php if($sort_by == 'ID'){echo esc_attr('selected');}?>><?php esc_html_e('Sort by ID', 'peer-review-system');?></option>
                                    <option value="name" <?php if($sort_by == 'name'){echo esc_attr('selected');}?>><?php esc_html_e('Sort by name', 'peer-review-system');?></option>
                                    <option value="date" <?php if($sort_by == 'date'){echo esc_attr('selected');}?>><?php esc_html_e('Sort by date', 'peer-review-system');?></option>
                                </select>
                            </span>
                            <?php if($sort_order == 'ASC'){?>
                                <a href="javascript:void(0);" class="sj-articles-sortorder" data-sort_order="DESC"><i class="fa fa-sort-amount-down-alt"></i></a>
                            <?php } else {?>
                                <a href="javascript:void(0);" class="sj-articles-sortorder" data-sort_order="ASC"><i class="fa fa-sort-amount-up-alt"></i></a>
                            <?php }?>
                        </div>
                    </div>
                    <?php if(!empty($userType) && $userType =='author'){?>
                        <div class="sj-ad-banner">
                            <h5><?php esc_html_e('Do You Want To Upload Your Article?', 'peer-review-system');?></h5>
                            <h4><?php esc_html_e('Submit Now & Make Your Online Presence', 'peer-review-system');?></h4>
                            <a href="<?php echo esc_url($dashboard_page_url)?>" class="sj-secondery-btn"><?php esc_html_e('Submit now', 'peer-review-system');?></a>
                        </div>
                    <?php } ?>
                    <div class="sj-articles-list">
                        <?php
                            if($articles_data->have_posts()){
                                while ($articles_data->have_posts()) {
                                    $articles_data->the_post();
                                    peer_review_system_get_template_part('article-search/article-view');
                                }

                                if (!empty($total_posts)) {
                                    peer_review_system_paginate($articles_data, 'sj-pagination');
                                }
                            } else {
                                peer_review_system_get_template_part('article-search/empty-articles-listing');
                            }                        
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<!-- MAIN END -->
<?php
get_footer();
