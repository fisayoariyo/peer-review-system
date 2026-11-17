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
$current_userType   = apply_filters('PeerReviewSystemGetUserType', $user_id);
$comments_per_page  = !empty(get_option('comments_per_page')) ? get_option('comments_per_page') : 10;
$total_comments     = get_comments(array('post_id' => intval($article_id)));
$total_comments     = !empty($total_comments) ? count($total_comments) : 0;
$paged              = !empty($_GET['comment_page']) ? intval($_GET['comment_page']) : 1;
$pages              = ceil($total_comments / $comments_per_page);
$offset             = ($paged * $comments_per_page) - $comments_per_page;
$article_author_id  = get_post_field('post_author', $article_id);
$article_author_id  = !empty($article_author_id) ? $article_author_id : 0;
?>
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
                        'value' => array($user_id, $article_author_id),
                        'compare' => 'IN'
                    ),
                    array(
                        'key'   => 'receiver_id',
                        'value' => $article_author_id,
                        'compare' => '='
                    )
                ),
                array(
                    'relation'  => 'AND',
                    array(
                        'key'   => 'sender_id',
                        'value' => $article_author_id,
                        'compare' => '='
                    ),
                    array(
                        'key'   => 'receiver_id',
                        'value' => array($user_id, $article_author_id),
                        'compare' => 'IN'
                    )
                ),

            );
        } elseif ($current_userType == 'author') {
            $comment_args['meta_query'] = array(
                'relation'  => 'OR',
                array(
                    'key'   => 'sender_id',
                    'value' => $user_id
                ),
                array(
                    'key'   => 'receiver_id',
                    'value' => $user_id
                )
            );
        }

        $comments_query       = new WP_Comment_Query;
        $article_reviews       = $comments_query->query($comment_args);

        if ($article_reviews) {
            $coment_count = 0;
            $status = 0;

            foreach ($article_reviews as $comment) {
                $user_id                = $comment->user_id;
                $userType               = apply_filters('PeerReviewSystemGetUserType', $comment->user_id);
                $userType               = !empty($userType) ? $userType : '';
                $post_commentor_id      = $comment->user_id;
                $user_name              = $comment->comment_author;
                $comment_content        = $comment->comment_content;
                $avatar                 = apply_filters('peer_review_system_avatar_fallback', peer_review_system_get_user_avatar(array('width' => 50, 'height' => 50), $post_commentor_id), array('width' => 50, 'height' => 50));
                $article_editor_status  = get_comment_meta( $comment->comment_ID,'prs_comment_article_review_status',true );
                
                /* showing editor status */
                switch ($article_editor_status) {
                    case "approved":
                        $status_class   = 'sj-approved';
                        $article_status = esc_html__('Approved', 'peer-review-system');
                        break;
                    case "minor_revision":
                        $status_class   = 'sj-orange';
                        $article_status = esc_html__('Minor revision', 'peer-review-system');
                        break;
                    case "major_revision":
                        $status_class   = 'sj-decline';
                        $article_status = esc_html__('Major revision', 'peer-review-system');
                        break;
                    case "rejected":
                        $status_class   = 'sj-queue';
                        $article_status = esc_html__('Rejected', 'peer-review-system');
                        break;
                    default:
                        $article_status = '';
                        $status_class = '';
                }

                if (!empty($article_reviews)) {?>
                    <div class="sj-userreview-holder">
                        <div class="sj-userreview_title">
                            <div class="sj-userreview-info">
                                <img src="<?php echo esc_url($avatar); ?>" alt="<?php echo esc_html($user_name); ?>">
                                <div class ="sj-usertime">
                                    <h5><?php echo esc_html($user_name); ?> <span class="sj-user-type">(<?php echo esc_html(ucfirst($userType)); ?>)</span></h5>
                                    <em></em>
                                    <span><?php printf(_x('%s ago', '%s', 'peer-review-system'), human_time_diff(strtotime($comment->comment_date), current_time('timestamp', 1))); ?></span>
                                </div>
                            </div>
                            <?php if (!empty($article_status) && $userType == 'editor') { ?>
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
            esc_html_e('No Comments found!', 'peer-review-system');
        }
        ?>
    </div>
</div>