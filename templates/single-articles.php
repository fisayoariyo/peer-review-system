<?php
/**
 *
 * The template used for article detail
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
    global $post; 
    $article_id     = $post->ID;
 	$user_data      = get_post_meta($article_id, 'profile_details', true);
	$user_data      = !empty($user_data) ? $user_data : array();
    $article_title      = get_the_title($post->ID);
    $article_date       = get_the_date('M j, Y');
    $article_terms      = wp_get_post_terms($post->ID, 'article_categories');
    $article_author_id  = get_post_field('post_author', $post->ID);
    $author_name        = peer_review_system_get_username($article_author_id);
    $avatar             = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 100, 'height' => 100), $article_author_id), array('width' => 100, 'height' => 100));
    
    $article_args    = array();   
    $article_args['article_id']         = $article_id;
    $article_args['article_title']      = $article_title;
    $article_args['article_date']       = $article_date;
    $article_args['article_terms']      = $article_terms;
    $article_args['article_author_id']  = $article_author_id;
    $article_args['author_name']        = $author_name;
    $article_args['avatar']             = $avatar;
	$article_args['user_id']            = $user_id;
	$article_args['profile_details']    = $user_data;
  	$article_args['userType']           = $user_type;
    ?>
    <section class="sj-main-section">
         <div class="container">
            <div class="row">
                <?php peer_review_system_get_template( 'single-articles/article-content.php',$article_args);?> 
                <?php peer_review_system_get_template( 'single-articles/article-sidebar-detail.php',$article_args);?> 
            </div>
        </div>
    </section>
    <?php     
endwhile;
get_footer();
