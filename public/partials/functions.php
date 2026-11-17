<?php

/**
 * Provide a public-facing funtions
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @since      1.0.0
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/public/partials
 */

/**
 * Custom image sizes
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
add_image_size('sj_article_listing', 832, 500, true);
add_image_size('sj_article_featured', 1272, 384, true);
add_image_size('sj_edition_thumbnail', 400, 400, true);
add_image_size('sj_user_profile', 200, 200, true);
add_image_size('sj_profile_thumbnail', 100, 100, true);

if (!function_exists('peer_review_system_max_upload_size_bytes')) {
	function peer_review_system_max_upload_size_bytes()
	{
		return (int) apply_filters('peer_review_system_max_upload_size_bytes', 25 * MB_IN_BYTES);
	}
}

if (!function_exists('peer_review_system_max_upload_size_label')) {
	function peer_review_system_max_upload_size_label()
	{
		return size_format(peer_review_system_max_upload_size_bytes(), 0);
	}
}

if (!function_exists('peer_review_system_max_upload_size_plupload')) {
	function peer_review_system_max_upload_size_plupload()
	{
		return (int) (peer_review_system_max_upload_size_bytes() / MB_IN_BYTES) . 'mb';
	}
}


/**
 * Get template page uri
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_get_page_uri')) {
    function peer_review_system_get_page_uri($type = '')
    {
        global $prs_settings;
        $tpl_page        = !empty($prs_settings['tpl_' . $type]) ? $prs_settings['tpl_' . $type] : '';
        $search_page     = !empty($tpl_page) ? get_permalink((int) $tpl_page) : '';
        return $search_page;
    }
}

/**
 * Get dashboard page uri
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_dashboard_page_uri')) {
    function peer_review_system_dashboard_page_uri($user_type = '')
    {
        $redirect       = peer_review_system_get_page_uri('dashboard');
        $redirect       = !empty($redirect) ? esc_url_raw($redirect) : home_url('/');
        $current_user   = get_current_user_id();
        $allowed_types  = array('author', 'editor', 'reviewer');
        $resolved_type  = in_array($user_type, $allowed_types, true) ? $user_type : '';

        if (empty($resolved_type) && !empty($current_user)) {
            $resolved_type = apply_filters('PeerReviewSystemGetUserType', $current_user);
        }

        if (!empty($current_user)) {
            $redirect = add_query_arg('useridentity', $current_user, $redirect);
        }

        $default_tab = 'personal_details';
        if ($resolved_type === 'reviewer') {
            $default_tab = 'assigned-articles';
        }

        $redirect = add_query_arg('tab', $default_tab, $redirect);
        return esc_url($redirect);
    }
}
if (!function_exists('peer_review_system_count_under_review_articles')) {
    function peer_review_system_count_under_review_articles($user_id = 0, $post_status = 'pending')
    {
        $query_args  = array(
            'post_type'           => 'articles',
            'post_status'         => $post_status,
            'posts_per_page'      => 1,
            'fields'              => 'ids',
            'ignore_sticky_posts' => 1,
            'meta_query'          => array(
                'relation' => 'OR',
                array(
                    'key'     => 'prs_article_editor_status',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key'     => 'prs_article_editor_status',
                    'value'   => array('minor_revision', 'major_revision', 'rejected'),
                    'compare' => 'NOT IN',
                ),
            ),
        );
        $user_type   = apply_filters('PeerReviewSystemGetUserType', $user_id);

        if ($user_type == 'author' && !empty($user_id)) {
            $query_args['author'] = $user_id;
        }

        if ($user_type == 'reviewer' && !empty($user_id)) {
            $query_args['meta_query'] = array(
                'relation' => 'AND',
                array(
                    'key'       => 'prs_article_assigned_reviwers',
                    'value'     => $user_id,
                    'compare'   => 'LIKE',
                ),
                array(
                    'relation' => 'OR',
                    array(
                        'key'     => 'prs_article_editor_status',
                        'compare' => 'NOT EXISTS',
                    ),
                    array(
                        'key'     => 'prs_article_editor_status',
                        'value'   => array('minor_revision', 'major_revision', 'rejected'),
                        'compare' => 'NOT IN',
                    ),
                ),
            );
        }

        $articles_data = new WP_Query(apply_filters('peer_review_system_under_review_articles_count', $query_args));
        return (int) $articles_data->found_posts;
    }
}


/**
 * Get Country List array
 * @author Amentotech <theamentotech@gmail.com>
 * @return crop sizes
 */
if (!function_exists('peer_review_system_country_array')) {
    function peer_review_system_country_array()
    {
        $countries_arr = array();
        $countries_arr  = apply_filters('peer_review_system_countries', array(
            'AF' => __('Afghanistan', 'peer-review-system'),
            'AX' => __('&#197;land Islands', 'peer-review-system'),
            'AL' => __('Albania', 'peer-review-system'),
            'DZ' => __('Algeria', 'peer-review-system'),
            'AD' => __('Andorra', 'peer-review-system'),
            'AO' => __('Angola', 'peer-review-system'),
            'AI' => __('Anguilla', 'peer-review-system'),
            'AQ' => __('Antarctica', 'peer-review-system'),
            'AG' => __('Antigua and Barbuda', 'peer-review-system'),
            'AR' => __('Argentina', 'peer-review-system'),
            'AM' => __('Armenia', 'peer-review-system'),
            'AW' => __('Aruba', 'peer-review-system'),
            'AU' => __('Australia', 'peer-review-system'),
            'AT' => __('Austria', 'peer-review-system'),
            'AZ' => __('Azerbaijan', 'peer-review-system'),
            'BS' => __('Bahamas', 'peer-review-system'),
            'BH' => __('Bahrain', 'peer-review-system'),
            'BD' => __('Bangladesh', 'peer-review-system'),
            'BB' => __('Barbados', 'peer-review-system'),
            'BY' => __('Belarus', 'peer-review-system'),
            'BE' => __('Belgium', 'peer-review-system'),
            'PW' => __('Belau', 'peer-review-system'),
            'BZ' => __('Belize', 'peer-review-system'),
            'BJ' => __('Benin', 'peer-review-system'),
            'BM' => __('Bermuda', 'peer-review-system'),
            'BT' => __('Bhutan', 'peer-review-system'),
            'BO' => __('Bolivia', 'peer-review-system'),
            'BQ' => __('Bonaire, Saint Eustatius and Saba', 'peer-review-system'),
            'BA' => __('Bosnia and Herzegovina', 'peer-review-system'),
            'BW' => __('Botswana', 'peer-review-system'),
            'BV' => __('Bouvet Island', 'peer-review-system'),
            'BR' => __('Brazil', 'peer-review-system'),
            'IO' => __('British Indian Ocean Territory', 'peer-review-system'),
            'VG' => __('British Virgin Islands', 'peer-review-system'),
            'BN' => __('Brunei', 'peer-review-system'),
            'BG' => __('Bulgaria', 'peer-review-system'),
            'BF' => __('Burkina Faso', 'peer-review-system'),
            'BI' => __('Burundi', 'peer-review-system'),
            'KH' => __('Cambodia', 'peer-review-system'),
            'CM' => __('Cameroon', 'peer-review-system'),
            'CA' => __('Canada', 'peer-review-system'),
            'CV' => __('Cape Verde', 'peer-review-system'),
            'KY' => __('Cayman Islands', 'peer-review-system'),
            'CF' => __('Central African Republic', 'peer-review-system'),
            'TD' => __('Chad', 'peer-review-system'),
            'CL' => __('Chile', 'peer-review-system'),
            'CN' => __('China', 'peer-review-system'),
            'CX' => __('Christmas Island', 'peer-review-system'),
            'CC' => __('Cocos (Keeling) Islands', 'peer-review-system'),
            'CO' => __('Colombia', 'peer-review-system'),
            'KM' => __('Comoros', 'peer-review-system'),
            'CG' => __('Congo (Brazzaville)', 'peer-review-system'),
            'CD' => __('Congo (Kinshasa)', 'peer-review-system'),
            'CK' => __('Cook Islands', 'peer-review-system'),
            'CR' => __('Costa Rica', 'peer-review-system'),
            'HR' => __('Croatia', 'peer-review-system'),
            'CU' => __('Cuba', 'peer-review-system'),
            'CW' => __('Cura&Ccedil;ao', 'peer-review-system'),
            'CY' => __('Cyprus', 'peer-review-system'),
            'CZ' => __('Czech Republic', 'peer-review-system'),
            'DK' => __('Denmark', 'peer-review-system'),
            'DJ' => __('Djibouti', 'peer-review-system'),
            'DM' => __('Dominica', 'peer-review-system'),
            'DO' => __('Dominican Republic', 'peer-review-system'),
            'EC' => __('Ecuador', 'peer-review-system'),
            'EG' => __('Egypt', 'peer-review-system'),
            'SV' => __('El Salvador', 'peer-review-system'),
            'GQ' => __('Equatorial Guinea', 'peer-review-system'),
            'ER' => __('Eritrea', 'peer-review-system'),
            'EE' => __('Estonia', 'peer-review-system'),
            'ET' => __('Ethiopia', 'peer-review-system'),
            'FK' => __('Falkland Islands', 'peer-review-system'),
            'FO' => __('Faroe Islands', 'peer-review-system'),
            'FJ' => __('Fiji', 'peer-review-system'),
            'FI' => __('Finland', 'peer-review-system'),
            'FR' => __('France', 'peer-review-system'),
            'GF' => __('French Guiana', 'peer-review-system'),
            'PF' => __('French Polynesia', 'peer-review-system'),
            'TF' => __('French Southern Territories', 'peer-review-system'),
            'GA' => __('Gabon', 'peer-review-system'),
            'GM' => __('Gambia', 'peer-review-system'),
            'GE' => __('Georgia', 'peer-review-system'),
            'DE' => __('Germany', 'peer-review-system'),
            'GH' => __('Ghana', 'peer-review-system'),
            'GI' => __('Gibraltar', 'peer-review-system'),
            'GR' => __('Greece', 'peer-review-system'),
            'GL' => __('Greenland', 'peer-review-system'),
            'GD' => __('Grenada', 'peer-review-system'),
            'GP' => __('Guadeloupe', 'peer-review-system'),
            'GT' => __('Guatemala', 'peer-review-system'),
            'GG' => __('Guernsey', 'peer-review-system'),
            'GN' => __('Guinea', 'peer-review-system'),
            'GW' => __('Guinea-Bissau', 'peer-review-system'),
            'GY' => __('Guyana', 'peer-review-system'),
            'HT' => __('Haiti', 'peer-review-system'),
            'HM' => __('Heard Island and McDonald Islands', 'peer-review-system'),
            'HN' => __('Honduras', 'peer-review-system'),
            'HK' => __('Hong Kong', 'peer-review-system'),
            'HU' => __('Hungary', 'peer-review-system'),
            'IS' => __('Iceland', 'peer-review-system'),
            'IN' => __('India', 'peer-review-system'),
            'ID' => __('Indonesia', 'peer-review-system'),
            'IR' => __('Iran', 'peer-review-system'),
            'IQ' => __('Iraq', 'peer-review-system'),
            'IE' => __('Republic of Ireland', 'peer-review-system'),
            'IM' => __('Isle of Man', 'peer-review-system'),
            'IL' => __('Israel', 'peer-review-system'),
            'IT' => __('Italy', 'peer-review-system'),
            'CI' => __('Ivory Coast', 'peer-review-system'),
            'JM' => __('Jamaica', 'peer-review-system'),
            'JP' => __('Japan', 'peer-review-system'),
            'JE' => __('Jersey', 'peer-review-system'),
            'JO' => __('Jordan', 'peer-review-system'),
            'KZ' => __('Kazakhstan', 'peer-review-system'),
            'KE' => __('Kenya', 'peer-review-system'),
            'KI' => __('Kiribati', 'peer-review-system'),
            'KW' => __('Kuwait', 'peer-review-system'),
            'KG' => __('Kyrgyzstan', 'peer-review-system'),
            'LA' => __('Laos', 'peer-review-system'),
            'LV' => __('Latvia', 'peer-review-system'),
            'LB' => __('Lebanon', 'peer-review-system'),
            'LS' => __('Lesotho', 'peer-review-system'),
            'LR' => __('Liberia', 'peer-review-system'),
            'LY' => __('Libya', 'peer-review-system'),
            'LI' => __('Liechtenstein', 'peer-review-system'),
            'LT' => __('Lithuania', 'peer-review-system'),
            'LU' => __('Luxembourg', 'peer-review-system'),
            'MO' => __('Macao S.A.R., China', 'peer-review-system'),
            'MK' => __('Macedonia', 'peer-review-system'),
            'MG' => __('Madagascar', 'peer-review-system'),
            'MW' => __('Malawi', 'peer-review-system'),
            'MY' => __('Malaysia', 'peer-review-system'),
            'MV' => __('Maldives', 'peer-review-system'),
            'ML' => __('Mali', 'peer-review-system'),
            'MT' => __('Malta', 'peer-review-system'),
            'MH' => __('Marshall Islands', 'peer-review-system'),
            'MQ' => __('Martinique', 'peer-review-system'),
            'MR' => __('Mauritania', 'peer-review-system'),
            'MU' => __('Mauritius', 'peer-review-system'),
            'YT' => __('Mayotte', 'peer-review-system'),
            'MX' => __('Mexico', 'peer-review-system'),
            'FM' => __('Micronesia', 'peer-review-system'),
            'MD' => __('Moldova', 'peer-review-system'),
            'MC' => __('Monaco', 'peer-review-system'),
            'MN' => __('Mongolia', 'peer-review-system'),
            'ME' => __('Montenegro', 'peer-review-system'),
            'MS' => __('Montserrat', 'peer-review-system'),
            'MA' => __('Morocco', 'peer-review-system'),
            'MZ' => __('Mozambique', 'peer-review-system'),
            'MM' => __('Myanmar', 'peer-review-system'),
            'NA' => __('Namibia', 'peer-review-system'),
            'NR' => __('Nauru', 'peer-review-system'),
            'NP' => __('Nepal', 'peer-review-system'),
            'NL' => __('Netherlands', 'peer-review-system'),
            'AN' => __('Netherlands Antilles', 'peer-review-system'),
            'NC' => __('New Caledonia', 'peer-review-system'),
            'NZ' => __('New Zealand', 'peer-review-system'),
            'NI' => __('Nicaragua', 'peer-review-system'),
            'NE' => __('Niger', 'peer-review-system'),
            'NG' => __('Nigeria', 'peer-review-system'),
            'NU' => __('Niue', 'peer-review-system'),
            'NF' => __('Norfolk Island', 'peer-review-system'),
            'KP' => __('North Korea', 'peer-review-system'),
            'NO' => __('Norway', 'peer-review-system'),
            'OM' => __('Oman', 'peer-review-system'),
            'PK' => __('Pakistan', 'peer-review-system'),
            'PS' => __('Palestinian Territory', 'peer-review-system'),
            'PA' => __('Panama', 'peer-review-system'),
            'PG' => __('Papua New Guinea', 'peer-review-system'),
            'PY' => __('Paraguay', 'peer-review-system'),
            'PE' => __('Peru', 'peer-review-system'),
            'PH' => __('Philippines', 'peer-review-system'),
            'PN' => __('Pitcairn', 'peer-review-system'),
            'PL' => __('Poland', 'peer-review-system'),
            'PT' => __('Portugal', 'peer-review-system'),
            'QA' => __('Qatar', 'peer-review-system'),
            'RE' => __('Reunion', 'peer-review-system'),
            'RO' => __('Romania', 'peer-review-system'),
            'RU' => __('Russia', 'peer-review-system'),
            'RW' => __('Rwanda', 'peer-review-system'),
            'BL' => __('Saint Barth&eacute;lemy', 'peer-review-system'),
            'SH' => __('Saint Helena', 'peer-review-system'),
            'KN' => __('Saint Kitts and Nevis', 'peer-review-system'),
            'LC' => __('Saint Lucia', 'peer-review-system'),
            'MF' => __('Saint Martin (French part)', 'peer-review-system'),
            'SX' => __('Saint Martin (Dutch part)', 'peer-review-system'),
            'PM' => __('Saint Pierre and Miquelon', 'peer-review-system'),
            'VC' => __('Saint Vincent and the Grenadines', 'peer-review-system'),
            'SM' => __('San Marino', 'peer-review-system'),
            'ST' => __('S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'peer-review-system'),
            'SA' => __('Saudi Arabia', 'peer-review-system'),
            'SN' => __('Senegal', 'peer-review-system'),
            'RS' => __('Serbia', 'peer-review-system'),
            'SC' => __('Seychelles', 'peer-review-system'),
            'SL' => __('Sierra Leone', 'peer-review-system'),
            'SG' => __('Singapore', 'peer-review-system'),
            'SK' => __('Slovakia', 'peer-review-system'),
            'SI' => __('Slovenia', 'peer-review-system'),
            'SB' => __('Solomon Islands', 'peer-review-system'),
            'SO' => __('Somalia', 'peer-review-system'),
            'ZA' => __('South Africa', 'peer-review-system'),
            'GS' => __('South Georgia/Sandwich Islands', 'peer-review-system'),
            'KR' => __('South Korea', 'peer-review-system'),
            'SS' => __('South Sudan', 'peer-review-system'),
            'ES' => __('Spain', 'peer-review-system'),
            'LK' => __('Sri Lanka', 'peer-review-system'),
            'SD' => __('Sudan', 'peer-review-system'),
            'SR' => __('Suriname', 'peer-review-system'),
            'SJ' => __('Svalbard and Jan Mayen', 'peer-review-system'),
            'SZ' => __('Swaziland', 'peer-review-system'),
            'SE' => __('Sweden', 'peer-review-system'),
            'CH' => __('Switzerland', 'peer-review-system'),
            'SY' => __('Syria', 'peer-review-system'),
            'TW' => __('Taiwan', 'peer-review-system'),
            'TJ' => __('Tajikistan', 'peer-review-system'),
            'TZ' => __('Tanzania', 'peer-review-system'),
            'TH' => __('Thailand', 'peer-review-system'),
            'TL' => __('Timor-Leste', 'peer-review-system'),
            'TG' => __('Togo', 'peer-review-system'),
            'TK' => __('Tokelau', 'peer-review-system'),
            'TO' => __('Tonga', 'peer-review-system'),
            'TT' => __('Trinidad and Tobago', 'peer-review-system'),
            'TN' => __('Tunisia', 'peer-review-system'),
            'TR' => __('Turkey', 'peer-review-system'),
            'TM' => __('Turkmenistan', 'peer-review-system'),
            'TC' => __('Turks and Caicos Islands', 'peer-review-system'),
            'TV' => __('Tuvalu', 'peer-review-system'),
            'UG' => __('Uganda', 'peer-review-system'),
            'UA' => __('Ukraine', 'peer-review-system'),
            'AE' => __('United Arab Emirates', 'peer-review-system'),
            'GB' => __('United Kingdom', 'peer-review-system'),
            'US' => __('United States', 'peer-review-system'),
            'UY' => __('Uruguay', 'peer-review-system'),
            'UZ' => __('Uzbekistan', 'peer-review-system'),
            'VU' => __('Vanuatu', 'peer-review-system'),
            'VA' => __('Vatican', 'peer-review-system'),
            'VE' => __('Venezuela', 'peer-review-system'),
            'VN' => __('Vietnam', 'peer-review-system'),
            'WF' => __('Wallis and Futuna', 'peer-review-system'),
            'EH' => __('Western Sahara', 'peer-review-system'),
            'WS' => __('Western Samoa', 'peer-review-system'),
            'YE' => __('Yemen', 'peer-review-system'),
            'ZM' => __('Zambia', 'peer-review-system'),
            'ZW' => __('Zimbabwe', 'peer-review-system')
        ));

        return $countries_arr;
    }
}

/**
 * Plugin template part
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_get_template_part')) {
    function peer_review_system_get_template_part($slug = '', $name = '', $args = '', $template_path = 'peer-review-system', $default_path = '')
    {
        $template = '';
        if ($name) {
            $template = peer_review_system_locate_template("{$slug}-{$name}.php", $template_path, $default_path);
        }

        if (!$template) {
            $template = peer_review_system_locate_template("{$slug}.php", $template_path, $default_path);
        }

        $template = apply_filters('peer_review_system_get_template_part', $template, $slug, $name, $args);
        if ($template) {
            load_template($template, FALSE, $args);
        }
    }
}

/**
 * Get template
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */

if (!function_exists('peer_review_system_get_template')) {
    function peer_review_system_get_template($template_name, $args = array(), $template_path = 'peer-review-system', $default_path = '')
    {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        $located = peer_review_system_locate_template($template_name, $template_path, $default_path);

        if (!empty($return) && $return === true) {
            return $located;
        } else {
            include($located);
        }
    }
}

/**
 * Locate template
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_locate_template')) {
    function peer_review_system_locate_template($template_name = '', $template_path = 'peer-review-system', $default_path = '')
    {
        $template   = locate_template(
            array(
                trailingslashit($template_path) . $template_name,
            )
        );

        if (!$template && $default_path !== false) {
            $default_path = $default_path ? $default_path : untrailingslashit(plugin_dir_path(dirname(__DIR__))) . '/templates/';

            if (file_exists(trailingslashit($default_path) . $template_name)) {
                $template = trailingslashit($default_path) . $template_name;
            }
        }
        return apply_filters('peer_review_system_locate_template', $template, $template_name, $template_path);
    }
}

/**
 * @init            Site demo content
 * @package         Peer_Review_System
 * @since           1.0
 */
if (!function_exists('peer_review_system_is_demo_site')) {
    function peer_review_system_is_demo_site()
    {
        $json   = array();
        if (isset($_SERVER["SERVER_NAME"]) && $_SERVER["SERVER_NAME"] === 'houzillo.com') {
            $json['type']       = 'error';
            $json['title']      = esc_html__('Restricted access!', 'peer-review-system');
            $json['message']    = esc_html__('You are not allowed to update the record on the demo site', 'peer-review-system');
            wp_send_json($json, 203);
        }
    }
}

/**
 * Password validation
 * userRegistration
 * @since    1.0.0
 */
function userPasswordValidation($password = '')
{
    global $prs_settings;
    $json               = array();
    $password_strength  = !empty($prs_settings['password_strength']) ? $prs_settings['password_strength'] : '';
    $password_strength  = !empty($password_strength) ? $password_strength : array('length');
    $choices            = array(
        'length'            => wp_kses(__('Password must be 8 characters<br>', 'peer-review-system'), array('a' => array('href' => array(), 'title' => array()), 'br' => array(), 'em' => array(), 'strong' => array(),)),
        'upper'             => wp_kses(__('1 upper case<br>', 'peer-review-system'), array('a' => array('href' => array(), 'title' => array()), 'br' => array(), 'em' => array(), 'strong' => array(),)),
        'lower'             => wp_kses(__('1 lower case<br>', 'peer-review-system'), array('a' => array('href' => array(), 'title' => array()), 'br' => array(), 'em' => array(), 'strong' => array(),)),
        'special_character' => wp_kses(__('1 special character<br>', 'peer-review-system'), array('a' => array('href' => array(), 'title' => array()), 'br' => array(), 'em' => array(), 'strong' => array(),)),
        'number'            => wp_kses(__('1 number<br>', 'peer-review-system'), array('a' => array('href' => array(), 'title' => array()), 'br' => array(), 'em' => array(), 'strong' => array(),)),
    );

    if (!empty($password)) {
        $number         = preg_match('@[0-9]@', $password);
        $uppercase      = preg_match('@[A-Z]@', $password);
        $lowercase      = preg_match('@[a-z]@', $password);
        $specialChars   = preg_match('@[^\w]@', $password);
        $errors         = '';

        foreach ($password_strength as $key => $item) {
            if ($item === 'length') {
                if (strlen($password) < 8) {
                    $errors .= $choices[$item];
                }
            } else if ($item === 'upper' && !$uppercase) {
                $errors .= $choices[$item];
            } else if ($item === 'lower' && !$lowercase) {
                $errors .= $choices[$item];
            } else if ($item === 'number' && !$number) {
                $errors .= $choices[$item];
            } else if ($item === 'special_character' && !$specialChars) {
                $errors .= $choices[$item];
            }
        }

        if (!empty($errors)) {
            $json['type']         = 'error';
            $json['message']     = $errors;
            return $json;
        }
    }
}

/**
 * Get user type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_get_linked_profile_id')) {
    function peer_review_system_get_linked_profile_id($id = 0, $type = 'users', $role = '')
    {
        if ($type === 'post') {
            $linked_profile = get_post_meta($id, '_linked_profile', true);
        } else {
            $linked_profile = get_user_meta($id, '_linked_profile', true);
        }

        $linked_profile = !empty($linked_profile) ? $linked_profile : '';
        return intval($linked_profile);
    }
}

/**
 * User short/full name
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_get_username')) {
    function peer_review_system_get_username($user_id = 0)
    {
        $name = '';
        if (!empty($user_id)) {
            global $prs_settings;
            $shortname_option  =  !empty($prs_settings['shortname_option']) ? $prs_settings['shortname_option'] : '';
            $user_info = get_userdata($user_id);

            if (is_object($user_info)) {
                if (!empty($shortname_option)) {
                    $name = $user_info->first_name;
                } else {
                    $name = $user_info->display_name;
                }
            }
        }
        return $name;
    }
}

/**
 * Get user avatar
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_get_user_avatar')) {
    function peer_review_system_get_user_avatar($sizes = array(), $user_identity = '')
    {
        global    $prs_settings;
        extract(shortcode_atts(array(
            "width" => '100',
            "height" => '100',
        ), $sizes));
        $thumb_id   = get_user_meta($user_identity, 'profile_attachment_id', true);
        $user_type  = apply_filters('PeerReviewSystemGetUserType', $user_identity);
        $user_dp    = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/default-avatar.jpg';

        if (!empty($thumb_id)) {
            $thumb_url = wp_get_attachment_image_src($thumb_id, array($width, $height), true);

            if ($thumb_url[1] == $width and $thumb_url[2] == $height) {
                return !empty($thumb_url[0]) ? $thumb_url[0] : '';
            } else {
                $thumb_url = wp_get_attachment_image_src($thumb_id, 'full', true);

                if (strpos($thumb_url[0], 'media/default.png') !== false) {
                    return $user_dp;
                } else {
                    return !empty($thumb_url[0]) ? $thumb_url[0] : '';
                }
            }
        } else {
            $avatar = $user_dp;
            if ($user_type == 'author' || $user_type == 'reviewer' || $user_type == 'editor') {
                if (!empty($prs_settings['default_user_profile']['id'])) {
                    $placeholder_id        = !empty($prs_settings['default_user_profile']['id']) ? $prs_settings['default_user_profile']['id'] : '';
                    $img_atts           = wp_get_attachment_image_src($placeholder_id, 'sj_user_profile');
                    $avatar             = !empty($img_atts['0']) ? $img_atts['0'] : '';
                } else {
                    $avatar = !empty($prs_settings['default_user_profile']['url']) ? $prs_settings['default_user_profile']['url'] : $user_dp;
                }
            }

            if (!empty($user_type) && $user_type === 'administrator') {
                $avatar = get_avatar_url($user_identity, array('size' => 200));
            }
            return $avatar;
        }
    }
}


/**
 * Upload temp files to WordPress media
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_temp_upload_to_media')) {
    function peer_review_system_temp_upload_to_media($file_url, $post_id, $encrypt_file = true)
    {
        global $wp_filesystem, $current_user;
        if (empty($wp_filesystem)) {
            require_once(ABSPATH . '/wp-admin/includes/file.php');
            WP_Filesystem();
        }

        if (!is_user_logged_in()) {
            $json['type']           = 'error';
            $json['message']        = esc_html__('Restricted Access', 'peer-review-system');
            $json['message_desc']   = esc_html__('You are not allowed to perform this action.', 'peer-review-system');
            wp_send_json($json);
        }

        $json       =  array();
        $upload_dir = wp_upload_dir();
        $folderRalativePath = $upload_dir['baseurl'] . "/peer-review-system-temp";
        $folderAbsolutePath = $upload_dir['basedir'] . "/peer-review-system-temp";

        $args = array(
            'timeout'    => 15,
            'headers'    => array('Accept-Encoding' => ''),
            'sslverify'  => false
        );

        $response   = wp_remote_get($file_url, $args);
        $file_data  = wp_remote_retrieve_body($response);

        if (empty($file_data)) {
            $json['attachment_id']  = '';
            $json['url']            = '';
            $json['name']           = '';
            return $json;
        }

        $filename       = basename($file_url);
        $temp_filename  = $filename;

        if (wp_mkdir_p($upload_dir['path'])) {
            $file = $upload_dir['path'] . '/' . $filename;
        } else {
            $file = $upload_dir['basedir'] . '/' . $filename;
        }

        $file_detail        = Peer_Review_System_file_permission::getEncryptFile($file, $post_id, true, $encrypt_file);
        $new_filename       = $file_detail['name'];
        $new_path           = $upload_dir['path'] . '/' . $new_filename;
        $file               = $new_path;
        $filename           = basename($file);
        $actual_filename    = pathinfo($file, PATHINFO_FILENAME);

        //put content to the file
        file_put_contents($file, $file_data);
        $wp_filetype = wp_check_filetype($filename, null);

        $attachment = array(
            'post_mime_type'     => $wp_filetype['type'],
            'post_title'         => sanitize_file_name($filename),
            'post_content'       => '',
            'post_status'        => 'inherit'
        );

        $attach_id = wp_insert_attachment($attachment, $file, $post_id);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);
        $post_type = get_post_type($post_id);
        update_post_meta($attach_id, 'is_encrypted', '1');
        $json['attachment_id']  = $attach_id;
        $json['url']            = $upload_dir['url'] . '/' . basename($filename);
        $json['name']           = $filename;
        $target_path            = $folderAbsolutePath . "/" . $temp_filename;
        unlink($target_path); //delete file after upload
        return $json;
    }
}

/**
 * Count post statuses
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com> 
 */
if (!function_exists('peer_review_system_count_article_statuses')) {
    function peer_review_system_count_article_statuses($status_value = '', $user_id = 0, $post_status = 'pending')
    {
        $total_posts = 0;
        $reviewer_query_args = array();
        $userType           = apply_filters('PeerReviewSystemGetUserType', $user_id);
        if (!empty($status_value)) {
            /* getting all articles */
            $query_args = array(
                'post_type'             => 'articles',
                'orderby'               => 'date',
                'order'                 => 'DESC',
                'paginate'              => true,
            );

            if (is_array($post_status)) {
                $query_args['post_status'] = $post_status;
            } else {
                $query_args['post_status'] = $post_status;
            }

            if ($userType == 'author') {
                $query_args['author']  = $user_id;
            }

            /* editor status */
            if(is_array($status_value)){
                $meta_query_article_status[] = array(
                    'key'       => 'prs_article_editor_status',
                    'value'     => $status_value,
                    'compare'   => 'IN'
                );
            } else {
                $meta_query_article_status[] = array(
                    'key'       => 'prs_article_editor_status',
                    'value'     => $status_value,
                    'compare'   => '='
                );
            }
          
            /* user type is reviewer */
            if ($userType == 'reviewer') {
                /* assigned articles */
                $meta_query_article_assigned[] = array(
                    'key'       => 'prs_article_assigned_reviwers',
                    'value'     => $user_id,
                    'compare'   => 'LIKE',
                );
            }

            if (!empty($meta_query_article_assigned)) {
                $reviewer_query_args      = array_merge($meta_query_article_status, $meta_query_article_assigned);
                $query_relation           = array('relation' => 'AND');
                $reviewer_query_args      = array_merge($query_relation, $reviewer_query_args);
                $query_args['meta_query'] = $meta_query_article_assigned;
            } else {
                $query_args['meta_query'] = $meta_query_article_status;
            }

            $articles_data = new WP_Query(apply_filters('peer_review_system_article_statuses', $query_args));
            $total_posts = $articles_data->found_posts;
        }
        return $total_posts;
    }
}

/**
 * Plugin pagination
 *
 * @return
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 */
if (!function_exists('peer_review_system_paginate')) {
    function peer_review_system_paginate($prs_query, $class = '')
    {
        $tab           = !empty($_GET['tab']) ? esc_attr($_GET['tab']) : '';
        $paged         = get_query_var('paged') ? get_query_var('paged') : 1;
        $page          = get_query_var('page') ? get_query_var('page') : 1;
        $useridentity  = !empty($_GET['useridentity']) ? intval($_GET['useridentity']) : '';
        
        if ($prs_query) {
            $prs_total = $prs_query->max_num_pages;
        } else {
            global $wp_query;
            $prs_total = $wp_query->max_num_pages;
        }

        if ($prs_total > 1) { ?>
            <div class='sj-pagination-section'>
                <?php if (!empty($class)) { ?>
                    <div class="<?php echo esc_attr($class); ?>">
                <?php } else { ?>
                    <div class="sj-pagination">
                <?php }
                    echo paginate_links(array(
                        'base'         => str_replace(999999999, '%#%', esc_url_raw(get_pagenum_link(999999999))),
                        'total'        => $prs_total,
                        'current'      => max($paged, $page),
                        'format'       => '?paged=%#%',
                        'show_all'     => false,
                        'type'         => 'list',
                        'end_size'     => 2,
                        'mid_size'     => 1,
                        'prev_next'    => true,
                        'prev_text'    => sprintf('<i class="icon icon-chevron-left"></i>'),
                        'next_text'    => sprintf('<i class="icon icon-chevron-right"></i>'),
                        'add_args'     => false,
                        'add_fragment' => '',
                    ));
                    ?>
                </div>
             </div>
        <?php
        }
    }
}

/**
 * Add http from URL
 */
if (!function_exists('peer_review_system_add_http_protcol')) {

    function peer_review_system_add_http_protcol($url)
    {
        $protolcol = is_ssl() ? "https" : "http";
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = $protolcol . ':' . $url;
        }

        return $url;
    }
}

/**
 * email to users
 */
if (!function_exists('peer_review_system_email_to_users')) {
    function peer_review_system_email_to_users($user_ids = array(), $article_id = 0, $editor_id = 0)
    {
        if (!empty($user_ids) && class_exists('Peer_Review_System_Email_Helper')) {
            foreach ($user_ids as $id) {
                if (class_exists('Peer_Review_System_Email_Helper')) {
                    $editor_detail      = get_userdata($editor_id);
                    $editor_user_name   = !empty($editor_detail->display_name) ? $editor_detail->display_name : '';
                    $user_detail        = get_userdata($id);
                    $reviewer_user_name = !empty($user_detail->display_name) ? $user_detail->display_name : '';
                    $user_email         = !empty($user_detail->user_email) ? $user_detail->user_email : get_option('admin_email', 'info@example.com');
                    $login_url          = !empty($prs_settings['tpl_login']) ? $prs_settings['tpl_login'] : home_url('/');
                    $emailData  = array();
                    $emailData['user_name']     = $reviewer_user_name;
                    $emailData['email']         = $user_email;
                    $emailData['article_name']  = get_the_title($article_id);
                    $emailData['editor_name']   = $editor_user_name;

                    if (class_exists('PeerReviewSystemReviewerEmail')) {
                        $email_helper = new PeerReviewSystemReviewerEmail();
                        $email_helper->email_reviewer_assign_article($emailData);
                    }
                }
            }
        }
    }
}

/**
 * Grant the reviewer role to one or more users (additively) so they can access
 * the reviewer dashboard after being assigned to a manuscript.
 *
 * @param int[]|int $user_ids User id(s).
 */
if (!function_exists('peer_review_system_grant_reviewer_role')) {
    function peer_review_system_grant_reviewer_role($user_ids = array())
    {
        $user_ids = is_array($user_ids) ? $user_ids : array($user_ids);
        foreach ($user_ids as $user_id) {
            $user_id = (int) $user_id;
            if (empty($user_id)) {
                continue;
            }
            $user = get_userdata($user_id);
            if (!$user || in_array('administrator', (array) $user->roles, true)) {
                continue;
            }
            if (!in_array('article_reviewer', (array) $user->roles, true)) {
                $user->add_role('article_reviewer');
            }
        }
    }
}

/**
 * price format
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_price_format')) {
    function peer_review_system_price_format($price = '', $type = 'echo',$wcprice='')
    {
        $price  = !empty($price) ? str_replace( ',', '', $price) : $price;
        if (class_exists('WooCommerce')) {
            if(function_exists('wmc_get_price') && isset($wcprice)){
				$price = wc_price( wmc_get_price( $price ) ); //WooCommerce Multi Currency Compatibility
			}else{
				$price = wc_price($price);
			}
        } else {
            $currency   = peer_review_system_get_current_currency();
            $price      = !empty($currency['symbol']) ? $currency['symbol'] . $price : '$';
        }

        if ($type === 'return') {
            return wp_strip_all_tags($price);
        } else {
            echo wp_strip_all_tags($price);
        }
    }
    add_action('peer_review_system_price_format', 'peer_review_system_price_format', 10, 3);
    add_filter('peer_review_system_price_format', 'peer_review_system_price_format', 10, 3);
}

/**
 * Get woocommmerce currency settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_get_current_currency')) {
    function peer_review_system_get_current_currency()
    {
        $currency  = array();
        if (class_exists('WooCommerce')) {
            $currency['code']   = get_woocommerce_currency();
            $currency['symbol'] = get_woocommerce_currency_symbol();
        } else {
            $currency['code']   = 'USD';
            $currency['symbol'] = '$';
        }
        return $currency;
    }
}

/**
 * Get admin user
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */

if (!function_exists('peer_review_system_get_administrator_user_id')) {
	function peer_review_system_get_administrator_user_id(){
		$args = array(
			'role'		=> 'administrator',
			'fields'	=> array( 'ID' ),
			'orderby' 	=> 'ID',
			'order'   	=> 'ASC'
		);
		$users 			= get_users( $args );
		$admin_user	= !empty($users[0]) ? $users[0] : '';
		if(!empty($admin_user)){
			return $admin_user->ID;
		}
	}
}

/**
 * Create product post
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (!function_exists('peer_review_system_instructor_service_create')) {
    function peer_review_system_instructor_service_create($total_price=1){
        if (class_exists('WooCommerce')) {
            $args = array(
                'limit'     => -1, // All products
                'status'    => 'publish',
                'type'      => 'article_download',
                'orderby'   => 'date',
                'order'     => 'DESC',
            );

            $prs_funds = wc_get_products( $args );

            $wallet_post    = !empty($prs_funds[0]) ? $prs_funds[0] : '';
            if(!empty($wallet_post)){
                return (int)$wallet_post->get_id();
            } else {

                $item = array(
                    'name' 		    => esc_html__('Article purchase','peer-review-system'),
                    'description' 	=> esc_html__('Article purchase','peer-review-system'),
                );
                $admin_user_id = peer_review_system_get_administrator_user_id();

                $post_args = array(
                    'post_author' 	=> intval($admin_user_id),
                    'post_title' 	=> $item['name'],
                    'post_content' 	=> $item['description'],
                    'post_status' 	=> 'publish',
                    'post_type' 	=> "product",
                );

                $post_id = wp_insert_post( $post_args );

                wp_set_object_terms( $post_id, 'article_download', 'product_type' );
                update_post_meta( $post_id, '_visibility', '' );
                update_post_meta( $post_id, '_downloadable', 'no' );
                update_post_meta( $post_id, '_virtual', 'yes' );
                update_post_meta( $post_id, '_regular_price', 1.0 );
                update_post_meta( $post_id, '_sale_price', '' );
                update_post_meta( $post_id, '_purchase_note', '' );
                update_post_meta( $post_id, '_featured', 'no' );
                update_post_meta( $post_id, '_product_attributes', array() );
                update_post_meta( $post_id, '_price', 1.0 );
                update_post_meta( $post_id, '_sold_individually', '' );
                update_post_meta( $post_id, '_manage_stock', 'no' );
                update_post_meta( $post_id, '_backorders', 'no' );
                update_post_meta( $post_id, '_stock', '' );
                return $post_id;
            }
        }
    }
}

/**
 * Check any prerequisites for our REST request.
 */
function check_prerequisites($userId='') {
    if ( defined( 'WC_ABSPATH' ) ) {
        // WC 3.6+ - Cart and other frontend functions are not included for REST requests.
        include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
        include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
        include_once WC_ABSPATH . 'includes/wc-template-hooks.php';
    }

    if ( null === WC()->session ) {
        $session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );

        WC()->session = new $session_class();
        WC()->session->init();
    }
    if ( null === WC()->customer ) {
        WC()->customer = new WC_Customer( $userId, true );
    }
    if ( null === WC()->cart ) {
        WC()->cart = new WC_Cart();
        // We need to force a refresh of the cart contents from session here (cart contents are normally refreshed on wp_loaded, which has already happened by this point).
        WC()->cart->get_cart();
    }
}

/**
 * Register article product type
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return
 */
if (class_exists('WC_Product') && !function_exists('register_articles_product_type')) {
    function register_articles_product_type()
    {
        class WC_Product_Buyer_Packages extends WC_Product
        {
            public function __construct($product)
            {
                $this->product_type = 'articles_product';
                parent::__construct($product);
            }

            public function get_type()
            {
                return 'articles_product';
            }
        }
    }
    add_action('init', 'register_articles_product_type');
}
