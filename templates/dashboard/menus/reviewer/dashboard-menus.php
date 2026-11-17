<?php
/**
 * The template used for author dashboard menu
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
$dashboard_page_url   = get_permalink();
$user_identity = !empty($user_identity) ? $user_identity : $current_user->ID;

if (!empty($user_identity)) {
    $dashboard_page_url   = add_query_arg(array('useridentity' => $user_identity), $dashboard_page_url);
}
$avatar             = apply_filters(
    'peer_review_system_avatar_fallback',
    peer_review_system_get_user_avatar(array('width' => 200, 'height' => 200), $user_identity),
    array('width' => 200, 'height' => 200)
);
$approved_articles          = peer_review_system_count_article_statuses('approved', $user_identity, array('pending', 'publish'));
$minor_revision_articles    = peer_review_system_count_article_statuses('minor_revision', $user_identity, 'pending');
$major_revision_articles    = peer_review_system_count_article_statuses('major_revision', $user_identity, 'pending');
$rejected_articles          = peer_review_system_count_article_statuses('rejected', $user_identity, 'pending');
$assigned_article           = peer_review_system_count_article_statuses(array ( 'approved', 'minor_revision', 'major_revision','rejected','under_revision'), $user_identity, 'any');
?>
<aside class="sj-aside">
    <a href="javascript:void(0);" class="sj-closeicon" aria-label="<?php esc_attr_e('Open dashboard menu', 'peer-review-system'); ?>">
		<span class="sj-closeicon__closed"><?php esc_html_e('Tap me', 'peer-review-system'); ?></span>
		<span class="sj-closeicon__open"><?php esc_html_e('Close', 'peer-review-system'); ?></span>
		<i class="icon-chevron-left"></i>
	</a>
    <div class="sj-asidelist-holder">
        <div class="sj-aside_header" id="prs-droparea">
            <div id="prs-asideprostatusv2" class="prs-asideprostatusv2">
                <?php if (!empty($avatar)) { ?>
                    <figure>
                        <img src="<?php echo esc_url($avatar); ?>" id="user_profile_avatar" alt="<?php echo esc_attr($user_name); ?>">
                        <figcaption class="prs-uploadimage">
                            <a href="javascript:void(0);" id="profile-avatar-icon"><i class="icon icon-camera"></i></a>
                        </figcaption>
                    </figure>
                <?php } ?>
            </div>
            <h4><?php echo esc_html($user_name); ?></h4>
            <span><?php echo esc_html(ucfirst($userType)); ?></span>
        </div>
        <ul class="sj-aside_list">
            <li class="nav-link<?php if (empty($_GET['tab']) || (!empty($_GET['tab']) && $_GET['tab'] == 'personal_details')) { echo esc_attr(' active');} ?>">
                <div class="sj-aside-info">
                    <i class="icon-home"></i>
                    <h5>
                        <a href="<?php echo esc_url(add_query_arg(array('tab' => 'personal_details'), $dashboard_page_url)); ?>"><?php esc_html_e('Dashboard', 'peer-review-system'); ?></a>
                    </h5>
                </div>
            </li>
            <li class="nav-link<?php if (!empty($_GET['tab']) && $_GET['tab'] == 'assigned-articles') { echo esc_attr(' active');} ?>">
                <div class="sj-aside-info">
                    <i class="icon-clock"></i>
                    <h5>
                        <a href="<?php echo esc_url(add_query_arg(array('tab' => 'assigned-articles'), $dashboard_page_url)); ?>"><?php esc_html_e('Assigned articles', 'peer-review-system'); ?></a>
                    </h5>
                </div>
                <span class="sj-reminder-tag"><?php echo intval($assigned_article); ?></span>
            </li>
            <li class="sj-logout">
                <div class="sj-aside-info">
                    <i class="icon-power"></i>
                    <h5>
                        <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><?php esc_html_e('Logout', 'peer-review-system'); ?></a>
                    </h5>
                </div>
            </li>  
        </ul>
    </div>
</aside>
<?php peer_review_system_get_template_part('dashboard/profile', 'avatar-popup');
