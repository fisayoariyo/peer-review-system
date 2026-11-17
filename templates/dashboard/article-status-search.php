<?php
/**
 * The template used for searching articles by status
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$article_status     = !empty($_GET['sort_by']) ? esc_html($_GET['sort_by']) : 'any';
?>
<form id="article-search-by-status" action="">
    <div class="sj-selectwrapper">
        <span><?php esc_html_e('Show only', 'peer-review-system'); ?>:</span>
        <div class="sj-select">
            <select name="article_status" id="search_article_status" data-placeholder="<?php esc_attr_e('All status', 'peer-review-system');?>" class="form-control article-sort-by-status" >
                <option value="any" <?php if (!empty($article_status) && $article_status == 'any') { echo esc_attr('selected');} ?>><?php esc_html_e('All status', 'peer-review-system'); ?></option>
                <option value="pending" <?php if (!empty($article_status) && $article_status == 'pending') { echo esc_attr('selected');} ?>><?php esc_html_e('Pending', 'peer-review-system'); ?></option>
                <option value="publish" <?php if (!empty($article_status) && $article_status == 'publish') {echo esc_attr('selected'); } ?>><?php esc_html_e('Published', 'peer-review-system'); ?></option>
            </select>
        </div>
    </div>
</form>