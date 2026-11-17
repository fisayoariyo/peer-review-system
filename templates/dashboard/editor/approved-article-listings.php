<?php
/**
 * The template used for approved article listing
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
$article_status     = !empty($_GET['sort_by']) ? esc_html($_GET['sort_by']) : array('pending', 'publish');
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
$meta_query_args[] = array(
    'key'       => 'prs_article_editor_status',
    'value'     => 'approved',
    'compare'   => '='
);

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
    $query_args['meta_query'] = $meta_query_args;
}

//Taxonomy Query
if (!empty($tax_query_args)) {
    $query_args['tax_query'] = $tax_query_args;
}
$articles_data = new WP_Query(apply_filters('peer_review_system_approved_articles_filter', $query_args));
$total_posts = $articles_data->found_posts;?>
<div class="sj-boxwrapper">
    <div class="sj-boxtitle">
        <h3><?php esc_html_e('Approved articles', 'peer-review-system'); ?>
            <span class="sj-newtag"><?php echo intval($total_posts)?><?php esc_html_e(' New','peer-review-system')?></span>
        </h3>
    </div>
    <?php peer_review_system_get_template_part('dashboard/article', 'search-form', $args); ?>
    <?php if($total_posts>0){?>
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
        <?php
        } else {
            do_action('peer_review_system_empty_record');
        }  
    } else {
        do_action('peer_review_system_empty_record');
    } ?>
</div>
<?php
if (!empty($total_posts) && !empty($posts_per_page) && $total_posts > $posts_per_page) {
    peer_review_system_paginate($articles_data, 'sj-pagination');
}
?>
<script type="text/template" id="tmpl-load-article-price-edit">
    <div class="modal-header">
        <h5><?php esc_html_e('Edit/update articel price','peer-review-system')?></h5>
        <a href="javascript:void(0)" data-bs-dismiss="modal"><i class="icon-x"></i></a>
    </div>
    <div class="modal-body">
        <form class="sj-themeform" id="sj-edit-article-price-form-{{data.article_id}}">
            <fieldset>
                <div class="form-group-wrap">  
                    <div class="form-group sj-checkform">
                        <div class="sj-check">
                            <input type="radio" id="sj-free-article" name="sj_paid_article" value="free" {{data.sj_free_checked}}>
                            <label for="sj-free-article"><?php esc_html_e('Free download', 'peer-review-system'); ?></label>
                        </div>
                        <div class="sj-check">
                            <input type="radio" id="sj-downloadticle" name="sj_paid_article"  value="download" {{data.sj_download_checked}}>
                            <label for="sj-downloadticle"><?php esc_html_e('Third party download URL', 'peer-review-system'); ?></label>
                        </div>
                        <div class="sj-check">
                            <input type="radio" id="sj-custom-price" name="sj_paid_article" value="price"  {{data.sj_price_checked}}>
                            <label for="sj-custom-price"><?php esc_html_e('Custom Price', 'peer-review-system'); ?></label>
                        </div>
                        
                    </div>
                    <div class="form-group sj-article-paid-url" style="display:none;">
                        <label class="sj-label"><?php esc_html_e('Article download URL', 'peer-review-system'); ?></label>
                        <div class="sj-placeholderholder">
                            <input type="text" class="form-control" name="article_download_url" id="article_download_url" value=" {{data.article_download_url}}" placeholder="<?php esc_attr_e('Article download URL', 'peer-review-system'); ?>">
                        </div>
                    </div>
                    <div class="form-group sj-article-paid-custom-price" style="display:none;">
                        <div class="form-group form-group">
                            <label class="sj-label"><?php esc_html_e('Article price', 'peer-review-system'); ?></label>
                            <div class="sj-placeholderholder">
                                <input type="text" class="form-control" name="article_price" id="article_price"  required value ="{{data.article_price}}" placeholder="<?php esc_attr_e('Article price', 'peer-review-system'); ?>">
                                <div class="sj-placeholder">
                                    <span><?php esc_html_e('Article price', 'peer-review-system') ?> </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <a href="javascript:void(0);" class="sj-secondery-btn" id="submit-article-price-form" data-article_id="{{data.article_id}}"><?php esc_html_e('Save & update','peer-review-system'); ?></a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</script>
