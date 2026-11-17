<?php
/**
 * Publisher queue listings.
 *
 * @package Peer_Review_System
 */
$publishing_queue   = !empty($publishing_queue) ? sanitize_key($publishing_queue) : 'awaiting_production';
$meta_query_args    = array();
$article_cat_args   = array();
$keyword            = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
$category           = !empty($_GET['category']) ? esc_html($_GET['category']) : '';
$tax_query_args     = array();

if (!empty($category)) {
	$article_cat_args[] = array(
		'taxonomy' => 'article_categories',
		'field'    => 'id',
		'terms'    => $category,
	);
	$tax_query_args[] = $article_cat_args;
}

$args['category'] = $category;
$args['keyword']  = $keyword;

$posts_per_page = !empty(get_option('posts_per_page')) ? get_option('posts_per_page') : 10;
$pg_page        = get_query_var('page') ? get_query_var('page') : 1;
$pg_paged       = get_query_var('paged') ? get_query_var('paged') : 1;
$paged          = max($pg_page, $pg_paged);

$meta_query_args[] = array(
	'key'     => 'prs_article_editor_status',
	'value'   => 'approved',
	'compare' => '=',
);

if ($publishing_queue === 'published') {
	$post_status     = 'publish';
	$meta_query_args[] = array(
		'key'     => 'prs_publishing_status',
		'value'   => 'published',
		'compare' => '=',
	);
	$heading = esc_html__('Published on site', 'peer-review-system');
} elseif ($publishing_queue === 'in_production') {
	$post_status     = 'pending';
	$meta_query_args[] = array(
		'key'     => 'prs_publishing_status',
		'value'   => 'in_production',
		'compare' => '=',
	);
	$heading = esc_html__('In production', 'peer-review-system');
} else {
	$post_status     = 'pending';
	$meta_query_args[] = array(
		'relation' => 'OR',
		array(
			'key'     => 'prs_publishing_status',
			'value'   => 'awaiting_production',
			'compare' => '=',
		),
		array(
			'key'     => 'prs_publishing_status',
			'compare' => 'NOT EXISTS',
		),
	);
	$heading = esc_html__('Awaiting production', 'peer-review-system');
}

$query_args = array(
	'post_type'      => 'articles',
	'post_status'    => $post_status,
	'posts_per_page' => $posts_per_page,
	'paged'          => $paged,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'meta_query'     => $meta_query_args,
);

if (!empty($keyword)) {
	$query_args['s'] = $keyword;
	add_filter('posts_where', 'peer_review_system_advance_search_where_article');
	add_filter('posts_join', 'peer_review_system_advance_search_join');
	add_filter('posts_groupby', 'peer_review_system_advance_search_groupby');
}

if (!empty($tax_query_args)) {
	$query_args['tax_query'] = $tax_query_args;
}

$articles_data = new WP_Query(apply_filters('peer_review_system_publisher_queue_filter', $query_args, $publishing_queue));
$total_posts   = $articles_data->found_posts;
?>
<div class="sj-boxwrapper">
	<div class="sj-boxtitle">
		<h3><?php echo esc_html($heading); ?>
			<span class="sj-newtag"><?php echo intval($total_posts); ?><?php esc_html_e(' New', 'peer-review-system'); ?></span>
		</h3>
	</div>
	<?php peer_review_system_get_template_part('dashboard/article', 'search-form', $args); ?>
	<?php if ($total_posts > 0 && $articles_data->have_posts()) { ?>
		<ul class="sj-article-list">
			<?php
			while ($articles_data->have_posts()) {
				$articles_data->the_post();
				global $post;
				$args['article_id'] = get_the_ID();
				peer_review_system_get_template('article-loop/article-list-view-one.php', $args);
			}
			wp_reset_postdata();
			?>
		</ul>
	<?php } else {
		do_action('peer_review_system_empty_record');
	} ?>
</div>
<?php
if (!empty($total_posts) && !empty($posts_per_page) && $total_posts > $posts_per_page) {
	peer_review_system_paginate($articles_data, 'sj-pagination');
}
