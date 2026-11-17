<?php
/**
 *
 * The template used for edtion detail
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $post, $current_user;
get_header();
$user_id = $current_user->ID;
$user_type = apply_filters('PeerReviewSystemGetUserType', $user_id);
while (have_posts()) : the_post();
    $edition_date       = get_post_meta($post->ID, 'edition_date', true);
    $edition_price      = get_post_meta($post->ID, 'edition_price', true);
 	$assign_articles    = get_post_meta($post->ID, 'assign_articles', true);
    $article_author_id  = get_post_field('post_author', $post->ID);
    $author_name        = peer_review_system_get_username($article_author_id);
    $edition_args['edition_id']         = $post->ID;
    $edition_args['edition_date']       = $edition_date;
	$edition_args['edition_price']      = $edition_price;
  	$edition_args['assign_articles']    = $assign_articles;
  	$edition_args['article_author_id']  = $article_author_id;
  	$edition_args['author_name']        = $author_name;
    ?>
    <main class="sj-main sj-lightbg">
        <!-- blog artical section -->
        <div class="sj-main-section">      
            <?php peer_review_system_get_template( 'single-edition/edition-top-box.php',$edition_args);?> 
            <?php peer_review_system_get_template( 'single-edition/edition-assign-articles.php',$edition_args);?>    
        </div>
    
    </main>
    <?php     
endwhile;
get_footer();
