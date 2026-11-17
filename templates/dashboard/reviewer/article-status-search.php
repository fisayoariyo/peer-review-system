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
$sort_order         = !empty($_GET['sort_order']) ? esc_html($_GET['sort_order']) : 'DESC';
$sort_order         = !empty($sort_order) && $sort_order == 'DESC' ? 'DESC' : 'ASC';
?>
<form id="article-search-by-status" action="">
    <div class="sj-selectwrapper sj-sorting_field">
        <span><?php esc_html_e('Show only', 'peer-review-system'); ?>:</span>
        <div class="sj-select">
            <select name="article_status" id="search_article_status" data-placeholder="<?php esc_attr_e('All status', 'peer-review-system');?>" class="form-control article-sort-by-status" >
                <option value="any" <?php if (!empty($article_status) && $article_status == 'any') { echo esc_attr('selected');} ?>><?php esc_html_e('All status', 'peer-review-system'); ?></option>
                <option value="approved" <?php if (!empty($article_status) && $article_status == 'approved') {echo esc_attr('selected'); } ?>><?php esc_html_e('Approved', 'peer-review-system'); ?></option>
                <option value="under_review" <?php if (!empty($article_status) && $article_status == 'under_review') { echo esc_attr('selected');} ?>><?php esc_html_e('Under review', 'peer-review-system'); ?></option>
                <option value="minor_revision" <?php if (!empty($article_status) && $article_status == 'minor_revision') {echo esc_attr('selected'); } ?>><?php esc_html_e('Minor revision', 'peer-review-system'); ?></option>
                <option value="major_revision" <?php if (!empty($article_status) && $article_status == 'major_revision') {echo esc_attr('selected'); } ?>><?php esc_html_e('Major revision', 'peer-review-system'); ?></option>
                <option value="rejected" <?php if (!empty($article_status) && $article_status == 'rejected') {echo esc_attr('selected'); } ?>><?php esc_html_e('Rejected', 'peer-review-system'); ?></option>
            </select>
        </div>
        <?php if ($sort_order == 'ASC') { ?>
            <a href="javascript:void(0);" class="sj-articles-sortorder" data-sort_order="DESC"><i class="fa fa-sort-amount-down-alt"></i></a>
        <?php } else { ?>
            <a href="javascript:void(0);" class="sj-articles-sortorder" data-sort_order="ASC"><i class="fa fa-sort-amount-up-alt"></i></a>
        <?php
        } ?>
    </div>
</form>