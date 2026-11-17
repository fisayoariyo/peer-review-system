<?php
/**
 * @Rename Menu
 * @return {}
 */
if (!function_exists('peer_review_system_rename_admin_menus')) {
    add_action('admin_menu', 'peer_review_system_rename_admin_menus');
    function peer_review_system_rename_admin_menus()
    {
        global $menu, $submenu;

        if (!empty($menu)) {
            foreach ($menu as $key => $menu_item) {

                if ($menu_item[2] == 'edit.php?post_type=articles') {
                    $menu[$key][0] = esc_html__('Peer Review System', 'peer-review-system');
                }
            }
        }

        add_submenu_page(
            'edit.php?post_type=articles',
            esc_html__('Import user', 'peer-review-system'),
            esc_html__('Import user', 'peer-review-system'),
            'manage_options',
            'import_users',
            'peer_review_system_import_users_template'
        );
    }
}

/**
 * Demo content unyson import path
 *
 * @since    1.0.0
 */
if (!function_exists('peer_review_system_fw_ext_backups_demos')) {
    add_filter('fw_ext_backups_demo_dirs', 'peer_review_system_fw_ext_backups_demos');
    function peer_review_system_fw_ext_backups_demos($demo_path    = array())
    {
        if (!defined('FW')) return $demo_path;
        $demo_path    = array(
            fw_fix_path(PEER_REVIEW_SYSTEM_DIRECTORY) . '/demo-content'
            =>
            PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'demo-content',
        );
        return $demo_path;
    }
}

/**
 * Advance Wild card search for taxonomy
 * $Where
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_advance_search_where_article')) {
    function peer_review_system_advance_search_where_article($where)
    {
        global $wpdb;
        $exact_match    = false;
        $keyword        = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
        $keyword        = esc_sql($wpdb->esc_like($keyword));

        if ($exact_match) {
            $where  .= " AND (( p1.meta_key = 'prs_article_authors' AND p1.meta_value LIKE '" . $keyword . "' ) OR ({$wpdb->posts}.post_title LIKE '" . $keyword . "') OR ({$wpdb->posts}.post_content LIKE '" . $keyword . "') OR ({$wpdb->posts}.post_excerpt LIKE '" . $keyword . "'))";
        } else {
            $where  .= " AND (( p1.meta_key = 'prs_article_authors' AND p1.meta_value LIKE '%" . $keyword . "%' ) OR ({$wpdb->posts}.post_title LIKE '%" . $keyword . "%') OR ({$wpdb->posts}.post_content LIKE '%" . $keyword . "%') OR ({$wpdb->posts}.post_excerpt LIKE '%" . $keyword . "%'))";
        }
        return $where;
    }
}

/**
 * Advance Wild card search for taxonomy
 * $join
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_advance_search_join')) {
    function peer_review_system_advance_search_join($join)
    {
        global $wpdb;
        $join .= " INNER JOIN {$wpdb->postmeta} p1 ON {$wpdb->posts}.ID= p1.post_id ";
        return $join;
    }
}

/**
 * Advance Wild card search for taxonomy
 * $groupby
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_advance_search_groupby')) {
    function peer_review_system_advance_search_groupby($groupby)
    {
        global $wpdb;
        // we need to group on post ID
        $groupby_id = "{$wpdb->posts}.ID";
        if (!is_search() || strpos($groupby, $groupby_id) !== false) return $groupby;
        // groupby was empty, use ours
        if (!strlen(trim($groupby))) return $groupby_id;
        // wasn't empty, append ours
        return $groupby . ", " . $groupby_id;
    }
}

/**
 * Empty record
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_empty_record')) {
    function peer_review_system_empty_record($args)
    {
        peer_review_system_get_template_part('article-search/empty-articles-listing',$args);
    }
    add_action('peer_review_system_empty_record', 'peer_review_system_empty_record');
}

/**
 * Non-editable article template
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_non_editable_article')) {
    function peer_review_system_non_editable_article()
    {
        peer_review_system_get_template_part('article-search/article-not-editable');
    }
    add_action('peer_review_system_non_editable_article', 'peer_review_system_non_editable_article');
}

/**
 * Revision statuses
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_revision_statuses')) {
    function peer_review_system_revision_statuses()
    {
        $revision_status = array(
            'approved'          => esc_html__('Approved', 'peer-review-system'),
            'minor_revision'    => esc_html__('Minor revision', 'peer-review-system'),
            'major_revision'    => esc_html__('Major revisions', 'peer-review-system'),
            'rejected'          => esc_html__('Rejected', 'peer-review-system'),
        );
        return $revision_status;
    }
    add_filter('peer_review_system_revision_statuses', 'peer_review_system_revision_statuses', 10);
}

/**
 * @init            tab url
 * @package         Peer_Review_System
 * @subpackage      Peer_Review_System/Public/Partials
 * @since           1.0
 * @desc            Display The Tab System URL
 */
if (!function_exists('peer_review_system_prepare_final_url')) {
    function peer_review_system_prepare_final_url($tab = '', $page = 'import_users')
    {
        $permalink = '';
        $permalink = add_query_arg(
            array(
                '?page'  =>   urlencode($page),
                'tab'    =>   urlencode($tab),
            )
        );
        return esc_url($permalink);
    }
}

/**
 * @init            Bulk import Users
 * @package         Peer_Review_System
 * @subpackage      Peer_Review_System/Public/Partials
 * @since           1.0
 */
if (!function_exists('peer_review_system_import_users_template')) {
    function  peer_review_system_import_users_template()
    {
        $permalink = add_query_arg(
            array(
                '&type=file',
            )
        );
        //Import users via file
        if (!empty($_FILES['users_csv']['tmp_name'])) {
            $import_user    = new PeerReviewSystemImportUser();
            $import_user->peer_review_system_import_user();
        ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e('User imported successfully', 'peer-review-system'); ?></p>
            </div>
        <?php
        }
        ?>
        <h3 class="theme-name"><?php esc_html_e('Import author/editor/reviewer', 'peer-review-system'); ?></h3>
        <div id="import-users" class="import-users">
            <div class="theme-screenshot">
                <img alt="<?php esc_attr_e('Import Users', 'peer-review-system'); ?>" src="<?php echo esc_url(PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/users.jpg'); ?>">
            </div>
            <h3 class="theme-name"><?php esc_html_e('Import users', 'peer-review-system'); ?></h3>
            <div class="user-actions">
                <a href="javascript:void(0);" class="button button-primary doc-import-users"><?php esc_html_e('Import dummy', 'peer-review-system'); ?></a>
            </div>
        </div>
        <?php
    }
}


/**
 * Custom user menu
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_custom_user_menu')) {
    function peer_review_system_custom_user_menu($items = '', $args = '')
    {
        $term_id = !empty($args->menu->term_id) ? intval($args->menu->term_id) : 0;
        $header_menu    = get_term_meta($term_id, 'main_header_menu', true);

        if (!empty($header_menu) && $header_menu == 'yes') {
            if (is_user_logged_in()) {
                $items .= apply_filters('peer_review_system_process_user_profile_menu', $items, $term_id);
            } else {
                $login = peer_review_system_get_page_uri('login');
                $items .= '<div class="prs-navbarbtn"><a href="' . esc_url($login) . '" class="prs-btn prs-login">' . esc_html__('Get started', 'peer-review-system') . '</a></div>';
            }
        }
        return $items;
    }
    add_filter('wp_nav_menu', 'peer_review_system_custom_user_menu', 10, 2);
}

/**
 * Get user menu details
 * @return
 */
if (!function_exists('peer_review_system_login_user_menu_details')) {
    function peer_review_system_login_user_menu_details($items = '', $term_id = '')
    {
        global $current_user, $prs_settings;
        ob_start();
        $prs_profile_menu_list      = Peer_Review_System_Profile_Menu::peer_review_system_get_dashboard_profile_menu();
        $sortorder                  = array_column($prs_profile_menu_list, 'sortorder');
        array_multisort($sortorder, SORT_ASC, $prs_profile_menu_list);

        $user_identity              = intval($current_user->ID);
        $prs_user_role              = apply_filters('PeerReviewSystemGetUserType', $user_identity);
        $dashboard_page_url         = !empty($prs_settings['tpl_dashboard']) ? get_permalink($prs_settings['tpl_dashboard']) : '';
        $user_name                  = peer_review_system_get_username($user_identity);

        if (is_user_logged_in()) {
            if ( !empty($prs_user_role) 
                && ( $prs_user_role === 'editor' 
                || $prs_user_role === 'reviewer' 
                || $prs_user_role === 'author' 
                || $prs_user_role === 'subscriber')
            ) {?>
                <div class="prs-headerwrap__right">
                    <div class="prs-navbarbtn sub-menu-holder">
                        <a href="javascript:void(0);" id="profile-avatar-menue-icon" class="prs-nav-signin">
                            <?php Peer_Review_System_Profile_Menu::peer_review_system_get_avatar(); ?>
                        </a>
                        <ul class="sub-menu">
                            <?php
                            if (!empty($prs_profile_menu_list)) {
                                foreach ($prs_profile_menu_list as $key => $menu_item) {
                                    if (!empty($menu_item['type']) && ($menu_item['type'] == $prs_user_role || $menu_item['type'] == 'none')) {
                                        $menu_item['id'] = $key;
                                        peer_review_system_get_template_part('dashboard/menus/menu', 'avatar-items', $menu_item);
                                    }
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php if (!empty($prs_user_role) && $prs_user_role == 'author') {
                        $user_identity      = !empty($user_identity) ? intval($user_identity) : '';
                        if (!empty($user_identity)) {
                            $dashboard_page_url   = add_query_arg(array('useridentity' => $user_identity), $dashboard_page_url);
                            $dashboard_page_url   = add_query_arg(array('tab' => 'add-article'), $dashboard_page_url);
                        } ?>
                        <a href="<?php echo esc_url($dashboard_page_url) ?>" class="sj-secondery-btn"><?php esc_html_e('Submit article', 'peer-review-system') ?> <i class="icon-plus"></i></a>
                    <?php
                    } ?>
                </div>
            <?php }
        } else {
            $login = peer_review_system_get_page_uri('login');
        ?>
            <div class="prs-navbarbtn"><a href="<?php echo esc_url($login); ?>" class="prs-btn prs-login"><?php esc_html_e('Get started', 'peer-review-system'); ?></a></div>
        <?php } ?>
    <?php
        return ob_get_clean();
    }
    add_filter('peer_review_system_process_user_profile_menu', 'peer_review_system_login_user_menu_details', 10, 2);
}

/**
 * @init            Check if chat solution installed
 * @package         Amentotech
 * @since           1.0
 * @desc            If plugin activated return true else false
 */
if (!function_exists('peer_review_system_chat_solution_guppy')) {
    add_filter('peer_review_system_chat_solution_guppy', 'peer_review_system_chat_solution_guppy', 10, 1);
    function peer_review_system_chat_solution_guppy($chat = false)
    {
        if (
            in_array('wp-guppy/wp-guppy.php', apply_filters('active_plugins', get_option('active_plugins')))
            || in_array('wpguppy-lite/wpguppy-lite.php', apply_filters('active_plugins', get_option('active_plugins')))
        ) {
            $chat = true;
        }

        return $chat;
    }
}