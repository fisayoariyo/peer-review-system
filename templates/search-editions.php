<?php
/**
 * Search editions 
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user, $post;
get_header();
$per_page           = get_option('posts_per_page');
$pg_page            = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged           = get_query_var('paged') ? get_query_var('paged') : 1;
$paged 			    = max($pg_page, $pg_paged);
$search_keyword     = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
$sort_order          = !empty($_GET['sort_order']) ? esc_html($_GET['sort_order']) : 'DESC';
$sort_order         = !empty($sort_order) && $sort_order == 'DESC' ? 'DESC' : 'ASC';
$edition_query_args = array(
    'posts_per_page'        => $per_page,
    'paged'                 => $paged,
    'post_type'             => 'editions',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'orderby'               => 'date',
    'order'                 => $sort_order,
);

if (!empty($search_keyword)) {
    $edition_query_args['s'] = $search_keyword;
}
$edition_data = new WP_Query(apply_filters('peer_review_system_edition_search_filter', $edition_query_args));
$total_posts = $edition_data->found_posts;
$defaultIamage = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/placeholder/400x400.png';
?>
<!-- Main start -->
<main class="sj-main sj-lightbg">
    <div class="sj-main-section">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <!-- explore artical section -->
                    <div class="sj-boxwrapper sj-edition">
                        <div class="sj-boxtitle">
                            <h3><?php the_title();?></h3>
                            <form id="sj-edition-keyword-search-form" action="<?php the_permalink();?>" method="GET">
                                <div class="sj-sorting_field">
                                    <div class="sj-sorting_search">
                                        <input type="search" class="form-control" name="keyword" value="<?php echo esc_attr($search_keyword);?>" placeholder="<?php esc_attr_e('Search with keyword', 'peer-review-system'); ?>">
                                        <a href="javascript:void(0);"><i class="icon icon-search" id="sj-search-keyword"></i></a>
                                    </div>
                                    <?php if (!empty($sort_order) && $sort_order == 'ASC') { ?>
                                        <a href="javascript:void(0);" class="sj-articles-sortorder" data-sort_order="DESC"><i class="fa fa-sort-amount-down-alt"></i></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0);" class="sj-articles-sortorder" data-sort_order="ASC"><i class="fa fa-sort-amount-up-alt"></i></a>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                        <?php if ($edition_data->have_posts()) { ?>
                            <ul class="sj-article-list sj-editions-list">
                                <?php
                                while ($edition_data->have_posts()) {
                                    $edition_data->the_post();
                                    global $post;
                                    $author_id          = $post->post_author;
                                    $post_id            = !empty($post->ID) ? intval($post->ID) : '';
                                    $attchemnts_info    = get_post_meta($post_id, 'attachments', true);
                                    $edition_date       = get_post_meta($post_id, 'edition_date', true);
                                    $edition_price      = get_post_meta($post_id, 'edition_price', true);
                                    $author_name        = get_the_author_meta('display_name', $author_id);
                                    $edition_date       = !empty($edition_date) ?  date_i18n(get_option('date_format'), strtotime($edition_date)) : '';
                                    $thumbnail  = !empty(has_post_thumbnail( $post_id )) ? get_the_post_thumbnail_url($post_id, 'sj_edition_thumbnail') : $defaultIamage ?>
                                    <li>
                                        <div class="sj-article-list_content">
                                            <?php if (!empty($thumbnail)){?>
                                                <figure>
                                                    <img src="<?php echo esc_url($thumbnail)?>" alt="<?php echo esc_attr(get_the_title());?>">
                                                </figure>
                                            <?php }?>                                           
                                            <div class="sj-article-list-info">
                                                <h5><?php the_title();?></h5>
                                                <?php if (!empty($edition_date) || !empty($author_name)) { ?>
                                                    <ul class="sj-publish-content">
                                                        <?php if(!empty($edition_date)){?>
                                                            <li>
                                                                <i class="icon-calendar"></i>
                                                                <span><?php echo esc_html($edition_date)?></span>
                                                            </li>
                                                        <?php } ?>
                                                        <?php if(!empty($author_name)){?>
                                                            <li>
                                                                <i class="icon-users"></i>
                                                                <span><?php echo esc_html($author_name) ?></span>
                                                            </li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="sj-editions_head_option">
                                            <a href="<?php the_permalink();?>" class="sj-actionbtn"><span><?php esc_html_e('View edition','peer-review-system')?></span></a>
                                        </div>
                                    </li>
                                <?php } wp_reset_postdata(); ?>
                            </ul><?php
                            if (!empty($total_posts)) {
                                peer_review_system_paginate($edition_data, 'sj-pagination');
                            }
                        } else {
                            peer_review_system_get_template_part('editions-search/empty-editions-listing');
                        } ?>

                    </div>
                    
                </div>
                </div>
        </div>
    </div>
</main>
<!-- MAIN END -->
<?php
get_footer();
