<?php
/**
 * Article detail term info
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Edition
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 * 
 */
if (!empty($args) && is_array($args)) {
    extract($args);
}
$articles_ID    = array();
if (!empty($assign_articles)) {
    foreach ($assign_articles as $key => $title) {
        $articles_ID[]  = $key;
    }
}
$query_args = array(
    'post_type'             => 'articles',
    'post_status'           => 'publish',
    'ignore_sticky_posts'   => 1,
    'post__in'              => $articles_ID,

);
$articles_data = new WP_Query($query_args);
$total_posts = $articles_data->post_count;
$defaultIamage = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/placeholder/500x500.png';
if ($articles_data->have_posts()) { ?>
    <div class="sj-blog-section">
        <div class="container">
            <div class="row">
                <?php
                while ($articles_data->have_posts()) {
                    $articles_data->the_post();
                    global $post;
                    $article_id         = $post->ID;
                    $article_title      = get_the_title($article_id);
                    $article_date       = get_the_date('M j, Y');
                    $article_terms      = wp_get_post_terms($article_id, 'article_categories');
                    $article_author_id  = get_post_field('post_author', $article_id);
                    $author_name        = peer_review_system_get_username($article_author_id);
                    $Image              = '';                            ?>
                    <div class="col-xl-4">
                        <div class="sj-blog-holder">
                            <?php if (!empty($Image)) { ?>
                                <figure>
                                    <img src="<?php echo esc_url($Image) ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                </figure>
                            <?php } ?>
                            <div class="sj-blog-content">
                                <?php if (!empty($author_name)) { ?>
                                    <strong><a href="javascript:void(0)"><?php echo esc_html($author_name) ?></a></strong>
                                <?php } ?>
                                <h5><?php echo esc_html(get_the_title($article_id)); ?></h5>
                                <ul class="sj-articles_info">
                                    <li>
                                        <span><i class="icon icon-calendar"></i><?php echo date_i18n(get_option('date_format'), strtotime($article_date)); ?></span>
                                    </li>
                                    <?php peer_review_system_get_template('article-loop/article-categories.php', array('article_id' => $article_id)); ?>
                                </ul>
                                <a href="<?php echo esc_url(get_permalink($article_id)) ?>" class="sj-actionbtn"><?php esc_html_e('View article', 'peer-review-system') ?></a>
                            </div>
                        </div>
                    </div><?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php
                if (!empty($total_posts)) {
                    peer_review_system_paginate($articles_data, 'sj-pagination');
                } ?>
            </div>
        </div>
    </div>
<?php } else {?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <?php peer_review_system_get_template_part('editions-search/empty-editions-listing'); ?>
            </div>
        </div>
    </div><?php
}
