<?php

/**
 * Article status
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$is_editor_approved = get_post_meta($article_id, 'prs_comment_article_review_status', true);

 
$tag_class  = '';
if(!empty($is_editor_approved)){
    if($is_editor_approved == 'minor_revision'){
        $is_editor_approved = 'Minor revision';
        $tag_class  = 'sj-orange';
    }

    if($is_editor_approved == 'approved'){
        $is_editor_approved = 'Approved';
        $tag_class  = 'sj-approved';
    }

    if($is_editor_approved == 'major_revision'){
        $is_editor_approved = 'Major revision';
        $tag_class  = 'sj-decline';
    }

    if($is_editor_approved == 'rejected'){
        $is_editor_approved = 'Rejected';
        $tag_class  = 'sj-queue';
    }
    
    if($is_editor_approved == 'under_revision'){
        $is_editor_approved = 'Under review';
        $tag_class  = 'sj-under-review';
    }
} else {
    $is_editor_approved = 'Under review';
    $tag_class  = 'sj-under-review';

}
if (!empty($is_editor_approved)) { ?>
    <span class="sj-tag <?php echo esc_attr($tag_class) ?>"><?php echo do_shortcode($is_editor_approved); ?></span>
    <?php 
} 
