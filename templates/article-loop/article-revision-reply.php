<?php

/**
 * Article revision reply
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
if (!empty($args) && is_array($args)) {
    extract($args);
}

$user_id            = !empty($_GET['useridentity']) ? $_GET['useridentity'] : $user_identity;
$revision_status    = apply_filters('peer_review_system_revision_statuses', '');
$userType           = apply_filters('PeerReviewSystemGetUserType', $user_id);
$post_author_id     = get_post_field('post_author', $article_id);

if ($userType == 'editor') {
    $author_id                  = get_post_field('post_author', $article_id);
    $is_editor_approved         = get_post_meta($article_id, 'prs_article_editor_status', true);
    $editor_approved_status     = !empty($is_editor_approved) ? $is_editor_approved : '';
    if ($editor_approved_status != 'approved') {
        ?>
        <div class="sj-box-two">
            <div class="sj-box-two_content">
                <h4><?php esc_html_e('Reply to author with status', 'peer-review-system'); ?></h4>
                <form name="prs_revision_reply_form" id="prs_revision_reply_form">
                    <?php if ($userType == 'editor') { ?>
                        <div class="sj-select">
                            <select id="prs_revision_status" name="prs_revision_status" data-placeholderinput="<?php esc_attr_e('Select revision status', 'peer-review-system') ?>" data-placeholder="<?php esc_attr_e('Select revision status', 'peer-review-system'); ?>" class="form-control" required>
                                <option label="<?php esc_attr_e('Select revision status', 'peer-review-system'); ?>"></option>
                                <?php foreach ($revision_status as $rev_key => $rev_value) {
                                    if ($userType != 'editor' && $rev_key == 'publish') {
                                        continue;
                                    }
                                    
                                    if (!empty($rev_key)) { ?>
                                        <option value="<?php echo esc_attr($rev_key); ?>"><?php echo esc_html($rev_value); ?></option>
                                    <?php } ?>
                                <?php } ?>
                            </select>
                        </div>
                    <?php } ?>
                    <div class="sj-revision-box">
                        <div class="sj-placeholderholder">
                            <textarea type="text" name="prs_revision_status_desc" id="prs_revision_status_desc" class="form-control" placeholder="<?php esc_attr_e('Add feedback to revision', 'peer-review-system'); ?>" required></textarea>
                            <div class="sj-placeholder">
                                <span><?php esc_html_e('Add feedback to revision', 'peer-review-system'); ?></span>
                                <em>*</em>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="prs_article_id" value="<?php echo esc_attr($article_id); ?>">
                    <input type="hidden" name="prs_single_receiver" value="<?php echo intval($author_id); ?>">
                    <div class="sj-assignbtn">
                        <button class="sj-secondery-btn-lg peer-veview-revision-reply" id="peer-veview-revision-reply"><?php esc_html_e('Submit now', 'peer-review-system'); ?></button>
                        <p><?php esc_html_e('Hit the “Submit now” button to add your reply', 'peer-review-system'); ?></p>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}
