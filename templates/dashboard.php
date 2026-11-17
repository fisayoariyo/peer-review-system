<?php
/**
 *
 * The template used for user profile settings
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
$current_page_link  = get_permalink();
if (!is_user_logged_in()) {
    if (!empty($_GET['tab'])) {
        $current_page_link  = add_query_arg('tab', esc_html($_GET['tab']), $current_page_link);
    }

    $redirect_url = peer_review_system_get_page_uri('login');
    if (empty($redirect_url)) {
        $redirect_url   = get_home_url();
    }

    set_transient('prs_redirect_page_url', esc_url_raw($current_page_link), 200);

    if (!empty($redirect_url)) {
        wp_redirect($redirect_url);
        exit;
    }
}

do_action('peer_review_system_dashboard_head');
$user_id = $current_user->ID;

if (!empty($_GET['useridentity'])) {
    $user_id = intval($_GET['useridentity']);
}

if (!empty($_GET['tab'])) {
    $current_page_link  = add_query_arg(array('useridentity' => (int)$user_id, 'tab' => esc_html($_GET['tab'])), $current_page_link);
}

$profile_tab        = !empty($_GET['tab']) ? esc_html($_GET['tab']) : 'personal_details';
$profile_mode       = !empty($_GET['mode']) ? esc_html($_GET['mode']) : '';
$id                 = !empty($_GET['id']) ? intval($_GET['id']) : '';
$userType           = apply_filters('PeerReviewSystemGetUserType', $user_id);
$user_name          = peer_review_system_get_username($user_id);
$package_info       = apply_filters('peer_review_system_user_package', $user_id);
$package_info       = !empty($package_info) ? $package_info : array();
$dashboard_args        = array(
    'user_identity'     => $user_id,
    'user_name'         => $user_name,
    'package_info'      => $package_info,
    'userType'          => $userType,
    'id'                => $id,
    'current_page_link' => $current_page_link,
);

if (empty($userType) || $userType == 'administrator') {
    $userType   = 'reviewer';
}

get_header();
while (have_posts()) : the_post(); ?>
    <section class="sj-main-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-xl-3">
                    <?php peer_review_system_get_template('dashboard/menus/' . $userType . '/dashboard-menus.php', $dashboard_args); ?>
                </div>
                <div class="col-lg-8 col-xl-9">
                    <div class="prs-profilewrapper">
                        <?php do_action('peer_review_system_profile_settings_notice', $dashboard_args); ?>
                        <div class="prs-boxwrapper">
                            <?php if ($profile_tab == 'personal_details') {
                                peer_review_system_get_template('dashboard/personal-details.php', $dashboard_args);
                            } elseif ($profile_tab == 'add-article' && $userType == 'author') {
                                peer_review_system_get_template('dashboard/' . $userType . '/add-article.php', $dashboard_args);
                            } elseif ($profile_tab == 'articles' && $userType == 'author') {
                                peer_review_system_get_template('dashboard/' . $userType . '/articles.php', $dashboard_args);
                            } elseif ($profile_tab == 'assigned-articles' && $userType == 'reviewer') {
                                peer_review_system_get_template('dashboard/' . $userType . '/assigned-articles.php', $dashboard_args);
                            } elseif ($profile_tab == 'add-edition') {
                                peer_review_system_get_template('dashboard/' . $userType . '/articles-review-listings.php', $dashboard_args);
                            } elseif ($profile_tab == 'article-details' && ($userType == 'author' || $userType == 'editor' || $userType == 'reviewer' || $userType == 'publisher')) {
                                peer_review_system_get_template('dashboard/' . $userType . '/article-details.php', $dashboard_args);
                            } elseif ($profile_tab == 'awaiting-production' && $userType == 'publisher') {
                                peer_review_system_get_template('dashboard/publisher/article-queue-listings.php', array_merge($dashboard_args, array('publishing_queue' => 'awaiting_production')));
                            } elseif ($profile_tab == 'in-production' && $userType == 'publisher') {
                                peer_review_system_get_template('dashboard/publisher/article-queue-listings.php', array_merge($dashboard_args, array('publishing_queue' => 'in_production')));
                            } elseif ($profile_tab == 'published-manuscripts' && $userType == 'publisher') {
                                peer_review_system_get_template('dashboard/publisher/article-queue-listings.php', array_merge($dashboard_args, array('publishing_queue' => 'published')));
                            } elseif ($profile_tab == 'review-articles' && $userType != 'reviewer') {
                                peer_review_system_get_template('dashboard/' . $userType . '/articles-review-listings.php', $dashboard_args);
                            } elseif ($profile_tab == 'approved-articles') {
                                peer_review_system_get_template('dashboard/' . $userType . '/approved-article-listings.php', $dashboard_args);
                            } elseif ($profile_tab == 'minor-revisions') {
                                peer_review_system_get_template('dashboard/' . $userType . '/minor-revision-article-listings.php', $dashboard_args);
                            } elseif ($profile_tab == 'major-revisions') {
                                peer_review_system_get_template('dashboard/' . $userType . '/major-revision-article-listings.php', $dashboard_args);
                            } elseif ($profile_tab == 'rejected-articles') {
                                peer_review_system_get_template('dashboard/' . $userType . '/rejected-article-listings.php', $dashboard_args);
                            } elseif ($profile_tab == 'editions' && $userType == 'editor') {
                                peer_review_system_get_template('dashboard/' . $userType . '/manage-editions.php', $dashboard_args);
                            } elseif ($profile_tab == 'editions' && $userType == 'reviewer') {
                                peer_review_system_get_template('dashboard/personal-details.php', $dashboard_args);
                            } elseif($profile_tab == 'invoices'){
                                if(!empty($profile_mode) && $profile_mode == 'detail'){
                                    peer_review_system_get_template( 'dashboard/invoice-detail.php',$dashboard_args);
                                } else {
                                    peer_review_system_get_template( 'dashboard/user-invoices.php',$dashboard_args);
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
endwhile;
wp_reset_postdata();
get_footer();
