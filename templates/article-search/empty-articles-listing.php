<?php
/**
 * Aricle not found 
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Article-Search
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
$dashboard_page_url = get_permalink();
$user_identity      = !empty($_GET['useridentity']) ? intval($_GET['useridentity']) : $current_user->ID;
$user_identity      = !empty($user_identity) ? intval($user_identity) : intval($current_user->ID);
$user_type          = '';
if (!empty($user_identity)) {
    $user_type = apply_filters('PeerReviewSystemGetUserType', $user_identity);
    if(!empty($user_type) && $user_type == 'author'){
        $dashboard_page_url   = add_query_arg(array('useridentity' => $user_identity), $dashboard_page_url);
        $dashboard_page_url   = add_query_arg(array('tab' => 'add-article'), $dashboard_page_url);
    }
} ?>
<div class="sj-nodata-emptylist">
    <div class="sj-nodata-title">
        <h4><?php esc_html_e('Oh snap!', 'peer-review-system'); ?></h4>
        <p><?php esc_html_e('There is no content to show.', 'peer-review-system'); ?></p>
        <?php if(!empty($user_type) && $user_type == 'author'){?>
            <a href="<?php echo esc_url($dashboard_page_url)?>" class="sj-secondery-btn-lg"><?php esc_html_e('Submit article', 'peer-review-system');?><i class= "icon-plus"></i></a>
        <?php } ?>
    </div>
</div>