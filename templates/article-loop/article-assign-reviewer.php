<?php

/**
 * Article assign reviewer
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
$current_user = !empty($_GET['useridentity']) ? $_GET['useridentity'] : $current_user->ID;
$article_id = !empty($article_id) ? $article_id : 0;

/*
 * Reviewer pool.
 *
 * Corresponding authors register as `article_author` and dedicated reviewers as
 * `article_reviewer`. The editor must be able to pick from any registered
 * author/reviewer account, so query both roles instead of reviewers only.
 */
$article_author_id = $article_id ? (int) get_post_field('post_author', $article_id) : 0;
$user_reviewer = get_users(array(
    'role__in' => apply_filters('peer_review_system_assignable_reviewer_roles', array('article_reviewer', 'article_author')),
    'orderby'  => 'display_name',
    'order'    => 'ASC',
    'exclude'  => $article_author_id ? array($article_author_id) : array(),
));
$assigned_reviewer = get_post_meta($article_id, 'prs_article_assigned_reviwers', true);
$assigned_reviewer = !empty($assigned_reviewer) ? $assigned_reviewer : array();

$is_editor_approved = get_post_meta($article_id, 'prs_article_editor_status', true);
$editor_approved_status   = !empty($is_editor_approved) ? $is_editor_approved : '';
if ($editor_approved_status != 'approved') {
?>
    <div class="sj-box-two">
        <div class="sj-box-two_content">
            <h4><?php esc_html_e('Assign reviewer', 'peer-review-system'); ?></h4>
            <form id="sj-assign-reviewer-form" class="sj-themeform sj-assign-reviewer-form">
                <div class="sj-select">
                    <select id="sj-assign-reviewers" class="form-control sj-assign-reviewers" name="sj-assign-reviewers" data-placeholder="<?php esc_attr_e('Assign reviewer', 'peer-review-system'); ?>">
                        <option value=""><?php esc_html_e('Assign reviewer', 'peer-review-system'); ?></option>
                        <?php foreach ($user_reviewer as $user_reviewer_obj) { ?>
                            <option value="<?php echo esc_attr($user_reviewer_obj->ID); ?>"><?php echo esc_html(ucfirst($user_reviewer_obj->display_name)); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <ul class="sj-asignreview-tags sj-labels" id="sj-article-assigned-reviewer">
                    <?php if (empty(!$assigned_reviewer)) { ?>
                        <?php foreach ($assigned_reviewer as $user_id) {
                            $user_name = peer_review_system_get_username($user_id);
                        ?>
                            <li id="<?php echo intval($user_id); ?>">
                                <span><?php echo esc_html(ucfirst($user_name));  ?><a href="javascript:void(0)"><i class="icon icon-x"></i></a></span>
                                <input class="sj_selected_items" type="hidden" data-cat_id="<?php echo intval($user_id); ?>" value="<?php echo esc_html(ucfirst($user_name)); ?>">
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
                <div class="sj-assignbtn">
                    <button class="sj-approve-btn" data-editor_id="<?php echo esc_attr($current_user); ?>" data-article_id="<?php echo esc_attr($article_id); ?>" id="sj-article-assign-reviewer"><?php esc_html_e('Assign now', 'peer-review-system'); ?></button>
                    <p><?php esc_html_e('Hit the “Assign now” button to send invitation to the reviewers', 'peer-review-system'); ?></p>
                </div>
            </form>

            <div class="sj-invite-reviewer">
                <h4><?php esc_html_e('Invite an independent reviewer', 'peer-review-system'); ?></h4>
                <p class="prs-invite-reviewer__intro"><?php esc_html_e('Not in the list? Invite an external reviewer by email. We will create a reviewer account, email them a password setup link, and assign them to this manuscript.', 'peer-review-system'); ?></p>
                <form id="prs-invite-reviewer-form" class="sj-themeform">
                    <div class="form-group-wrap">
                        <div class="form-group">
                            <label class="sj-label"><?php esc_html_e('First name', 'peer-review-system'); ?></label>
                            <input type="text" class="form-control" name="invite_first_name" value="">
                        </div>
                        <div class="form-group">
                            <label class="sj-label"><?php esc_html_e('Last name', 'peer-review-system'); ?></label>
                            <input type="text" class="form-control" name="invite_last_name" value="">
                        </div>
                        <div class="form-group">
                            <label class="sj-label"><?php esc_html_e('Email address', 'peer-review-system'); ?></label>
                            <input type="email" class="form-control" name="invite_email" value="">
                        </div>
                    </div>
                    <div class="sj-assignbtn">
                        <button class="sj-approve-btn" data-editor_id="<?php echo esc_attr($current_user); ?>" data-article_id="<?php echo esc_attr($article_id); ?>" id="prs-invite-reviewer"><?php esc_html_e('Invite & assign reviewer', 'peer-review-system'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php }
