<?php

/**
 * Article reviewer feedback
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
$current_userType   = apply_filters('PeerReviewSystemGetUserType', $current_user->ID);
$comments_per_page  = !empty(get_option('comments_per_page')) ? get_option('comments_per_page') : 10;
$total_comments     = get_comments(array('post_id' => intval($article_id)));
$total_comments     = !empty($total_comments) ? count($total_comments) : 0;
$paged              = !empty($_GET['comment_page']) ? intval($_GET['comment_page']) : 1;
$pages              = ceil($total_comments / $comments_per_page);
$offset             = ($paged * $comments_per_page) - $comments_per_page;
$article_author_id  = get_post_field('post_author', $article_id);
$article_author_id  = !empty($article_author_id) ? $article_author_id : 0;
/* get only reviewers for dropdown */
$user_reviewer = get_users( array( 'role' => 'article_reviewer' ));

$current_reviewer_id    = !empty($_GET['reviewer_id']) ? intval($_GET['reviewer_id']) : 0;
$assigned_reviewer      = get_post_meta($article_id, 'prs_article_assigned_reviwers', true);
$assigned_reviewer      = !empty($assigned_reviewer) ? $assigned_reviewer : array();

if (empty($current_reviewer_id) && !empty($assigned_reviewer['0'])) {
    $current_reviewer_id = $assigned_reviewer['0'];
}

if (empty($current_reviewer_id)) {
    $current_reviewer_id = $user_reviewer['0']->ID;
}

$is_editor_approved = get_post_meta($article_id, 'prs_article_editor_status', true);
$editor_approved_status   = !empty($is_editor_approved) ? $is_editor_approved : '';

if ($editor_approved_status != 'approved' && $editor_approved_status != 'rejected') {
?>
    <div class="sj-sorting-wrapper">
        <div class="sj-sorting">
            <div class="sj-sorting_title">
                <h4><?php esc_html_e('Reviewer Feedback', 'peer-review-system'); ?></h4>
            </div>
            <?php if ($current_userType == 'editor') {?>
                <div class="sj-sorting_field">
                    <?php if (!empty($assigned_reviewer)) { ?>
                        <strong><?php esc_html_e('Select reviewer', 'peer-review-system'); ?>:</strong>
                        <span class="sj-select">
                            <select id="sj-load-reviewer-replys" name="sj_load_reviewer_replys" data-placeholder="<?php esc_attr_e('Select reviewer', 'peer-review-system'); ?>" data-placeholderinput="<?php esc_attr_e('Sort by', 'peer-review-system'); ?>" class="form-control">
                                <?php
                                foreach ($assigned_reviewer as $user_reviewer_id) {
                                    $user_reviewer_obj = get_user_by('id', $user_reviewer_id);
                                    if (!empty($user_reviewer_obj)) {
                                        $is_selected = ($current_reviewer_id == $user_reviewer_obj->ID) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo esc_attr($user_reviewer_obj->ID); ?>" <?php echo esc_attr($is_selected); ?>><?php echo esc_html(ucfirst($user_reviewer_obj->display_name)); ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </span>
                    <?php }?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="sj-box-two">
        <div class="sj-box-two_content">
            <?php
            /* comment args */
            $comment_args = array(
                'post_id'       => $article_id,
                'post_type'     => array('articles'),
                'status'        => 'approve',
                'orderby'       => 'comment_date',
                'order'         => 'DESC',
                'type'          => 'prs_article_review',
                'parent'        => 0,
                'paged'         => $paged,
                'number'        => $comments_per_page,
                'offset'        => $offset,
            );

            if ($current_userType == 'editor') {
                $comment_args['meta_query'] = array(
                    'relation'  => 'OR',
                    array(
                        'relation'  => 'AND',
                        array(
                            'key'   => 'sender_id',
                            'value' => array($current_user->ID, $current_reviewer_id),
                            'compare' => 'IN'
                        ),
                        array(
                            'key'   => 'receiver_id',
                            'value' => $current_reviewer_id,
                            'compare' => '='
                        )
                    ),
                    array(
                        'relation'  => 'AND',
                        array(
                            'key'   => 'sender_id',
                            'value' => $current_reviewer_id,
                            'compare' => '='
                        )
                    ),

                );
            } elseif ($current_userType == 'reviewer') {
                $comment_args['meta_query'] = array(
                    'relation'  => 'OR',
                    array(
                        'key'   => 'sender_id',
                        'value' => $current_user->ID
                    ),
                    array(
                        'key'   => 'receiver_id',
                        'value' => $current_user->ID
                    )
                );
            }

            $comments_query       = new WP_Comment_Query;
            $article_reviews       = $comments_query->query($comment_args);

            if ($article_reviews) {
                $coment_count = 0;
                $status = 0;
                foreach ($article_reviews as $comment) {
                    $user_id            = $comment->user_id;
                    $userType           = apply_filters('PeerReviewSystemGetUserType', $comment->user_id);
                    $userType           = !empty($userType) ? $userType : '';
                    $post_commentor_id  = $comment->user_id;
                    $user_name          = $comment->comment_author;
                    $comment_content    = $comment->comment_content;
                    $avatar             = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 50, 'height' => 50), $post_commentor_id), array('width' => 50, 'height' => 50));
                    $comment_status     = get_comment_meta($comment->comment_ID, 'prs_comment_article_review_status', true);
                    $article_status     = !empty($comment_status) ? $comment_status : '';

                    /* showing reviewer/editor status */
                    if (!empty($comment_status)) {
                        if ($comment_status == 'approved') {
                            $status_class   = 'sj-approved';
                            $article_status = esc_html__('Approved', 'peer-review-system');
                        } elseif ($comment_status == 'minor_revision') {
                            $status_class   = 'sj-orange';
                            $article_status = esc_html__('Minor revision', 'peer-review-system');
                        } elseif ($comment_status == 'major_revision') {
                            $status_class   = 'sj-decline';
                            $article_status = esc_html__('Major revision', 'peer-review-system');
                        } elseif ($comment_status == 'rejected') {
                            $status_class   = 'sj-queue';
                            $article_status = esc_html__('Rejected', 'peer-review-system');
                        } else {
                            $article_status = '';
                            $status_class = '';
                        }
                    }

                    if (!empty($article_reviews)) {
                        ?>
                        <div class="sj-userreview-holder">
                            <div class="sj-userreview_title">
                                <div class="sj-userreview-info">
                                    <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_html($user_name); ?>">
                                    <div class="sj-usertime">
                                        <h5><?php echo esc_html($user_name); ?> <span class="sj-user-type">(<?php echo esc_html(ucfirst($userType)); ?>)</span></h5>
                                        <em></em>
                                        <span><?php printf(_x('%s ago', '%s', 'peer-review-system'), human_time_diff(strtotime($comment->comment_date), current_time('timestamp', 1))); ?></span>
                                    </div>
                                </div>
                                <?php if (!empty($article_status)) { ?>
                                    <div class="sj-userreview_status">
                                        <span><?php esc_html_e('Status', 'peer-review-system'); ?>:</span>
                                        <strong class="sj-tag <?php echo esc_attr($status_class); ?>"><?php echo do_shortcode($article_status); ?></strong>
                                    </div>
                                <?php } ?>
                            </div>
                            <p><?php echo apply_filters('the_content', $comment->comment_content); ?></p>
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="sj-review-pagination">
                    <?php
                    if ($total_comments >  $comments_per_page) {
                        $pagination_arg = array(
                            'base'         => @add_query_arg('comment_page', '%#%'),
                            'format'       => '?comment_page=%#%',
                            'total'        => $pages,
                            'current'      => $paged,
                            'show_all'     => false,
                            'end_size'     => 1,
                            'mid_size'     => 2,
                            'prev_next'    => true,
                            'prev_text'    => esc_html__('Prev', 'peer-review-system'),
                            'next_text'    => esc_html__('Next', 'peer-review-system'),
                            'type'         => 'list'
                        );
                        echo paginate_links($pagination_arg);
                    }
                    ?>
                </div>
            <?php
            } else {
                esc_html_e('No comments found!', 'peer-review-system');
            }
            ?>
        </div>
        <?php if ( !empty($assigned_reviewer) && ( $current_userType == 'reviewer' || $current_userType == 'editor')) { ?>
            <div class="sj-box-two">
                <div class="sj-box-two_content">
                    <?php if ($current_userType == 'reviewer') { ?>
                        <h4><?php esc_html_e('Reply to editor with status', 'peer-review-system'); ?></h4>
                    <?php } else {?>
                        <h4><?php esc_html_e('Reply to reviewer', 'peer-review-system'); ?></h4>
                    <?php }?>
                    <form name="prs_revision_single_reply_form" id="prs_revision_single_reply_form">
                        <?php 
                        if ($current_userType == 'reviewer') {
                            $revision_status    = apply_filters('peer_review_system_revision_statuses', '');
                            ?>
                            <div class="sj-select">
                                <select id="prs_revision_status" name="prs_revision_status" data-placeholderinput="<?php esc_attr_e('Select revision status', 'peer-review-system') ?>" data-placeholder="<?php esc_attr_e('Select revision status', 'peer-review-system'); ?>" class="form-control" required>
                                    <option label="<?php esc_attr_e('Select revision status', 'peer-review-system'); ?>"></option>
                                    <?php foreach ($revision_status as $rev_key => $rev_value) {    
                                        if (!empty($rev_key)) { ?>
                                            <option value="<?php echo esc_attr($rev_key); ?>"><?php echo esc_html($rev_value); ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } ?>
                        <div class="sj-revision-box">
                            <textarea class="form-control sj-textarea" name="prs_revision_status_desc" placeholder="<?php esc_attr_e('Enter description', 'peer-review-system'); ?>" required></textarea>
                        </div>
                        <input type="hidden" name="prs_article_id" value="<?php echo esc_attr($article_id); ?>">
                        <input type="hidden" name="prs_single_reply" value="<?php echo intval(1); ?>">
                        <input type="hidden" name="prs_single_sender" value="<?php echo intval($current_user->ID); ?>">
                        <input type="hidden" name="prs_single_receiver" value="<?php echo intval($current_reviewer_id); ?>">
                        <div class="sj-assignbtn">
                            <button class="sj-secondery-btn-lg peer-veview-revision-single-reply"><?php esc_html_e('Submit now', 'peer-review-system'); ?></button>
                            <p><?php esc_html_e('Hit the “Submit now” button to add your reply', 'peer-review-system'); ?></p>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
<?php }
