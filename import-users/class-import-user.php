<?php

/**
 * @Import Users
 * @return{}
 */

$autoload_path = PEER_REVIEW_SYSTEM_DIRECTORY . 'libraries/PhpSpreadsheet/vendor/autoload.php';
if ( file_exists( $autoload_path ) ) {
	require $autoload_path;
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!class_exists('PeerReviewSystemImportUser')) {
    class PeerReviewSystemImportUser
    {
        function __construct()
        {
            // Constructor Code here..
        }

        /*
		 * @import users
		 */
        public function peer_review_system_import_user()
        {
            global $wpdb, $wpdb_data_table;
            $imported_user_data = array();
            // User data fields list used to differentiate with user meta
            $userdata_fields       = array(
                'ID',
                'username',
                'user_pass',
                'user_email',
                'user_url',
                'user_nicename',
                'display_name',
                'user_registered',
                'first_name',
                'last_name',
                'nickname',
                'description',
                'rich_editing',
                'comment_shortcuts',
                'admin_color',
                'use_ssl',
                'show_admin_bar_front',
                'show_admin_bar_admin',
                'role'
            );

            $wp_user_table        = $wpdb->prefix . 'users';
            $wp_usermeta_table    = $wpdb->prefix . 'usermeta';

            if (isset($_FILES['users_csv']['tmp_name'])) {
                $file       = $_FILES['users_csv']['tmp_name'];
                $name       = !empty($_FILES['users_csv']['name']) ? $_FILES['users_csv']['name'] : '';
                $filetype   = '';

                if (!empty($name)) {
                    $filetype = pathinfo($name, PATHINFO_EXTENSION);
                }

                $import_type    = 'upload';
                
            } else {
                $file           = PEER_REVIEW_SYSTEM_DIRECTORY . '/import-users/users.xlsx';
                $filetype       = 'xlsx';
                $import_type    = 'dummy';
            }

            try {
                /* Load the excel(.xls/.xlsx) file */
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            $worksheet = $spreadsheet->getActiveSheet();
            /* Get the highest row and column numbers referenced in the worksheet */
            $total_rows     = $worksheet->getHighestRow();
            $highest_column = $worksheet->getHighestColumn();
            $first              = true;
            $rkey               = 0;

            for ($row = 1; $row <= $total_rows; $row++) {
                /* If the first line is empty abort it. If another line is empty, just skip it */
                if (empty($row)) {
                    if ($first) {
                        break;
                    } else {
                        continue;
                    }
                }

                $data    = array();
                /* If we are on the first line, the columns are the headers */
                if ($first) {
                    $line = $spreadsheet->getActiveSheet()
                        ->rangeToArray(
                            'A' . $row . ':' . $highest_column . $row,     // The worksheet range that we want to retrieve
                            NULL,        // Value that should be returned for empty cells
                            TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                            FALSE,       // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                            FALSE        // Should the array be indexed by cell row and cell column
                        );
                    $headers     = !empty($line[0]) ? $line[0] : array();
                    $first         = false;
                    continue;
                } else {
                    $data = $spreadsheet->getActiveSheet()
                        ->rangeToArray(
                            'A' . $row . ':' . $highest_column . $row,     // The worksheet range that we want to retrieve
                            NULL,        // Value that should be returned for empty cells
                            TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                            FALSE,       // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                            FALSE        // Should the array be indexed by cell row and cell column
                        );
                }

                // Separate user data from meta
                $userdata = $usermeta = array();

                if (!empty($data[0])) {
                    foreach ($data[0] as $ckey => $column) {
                        $column_name = trim($headers[$ckey]);
                        $column = trim($column);

                        if (in_array($column_name, $userdata_fields)) {
                            $userdata[$column_name] = $column;
                        } else {
                            $usermeta[$column_name] = $column;
                        }
                    }

                    /* If no user data, bailout! */
                    if (empty($userdata)) {
                        continue;
                    }

                    $user = $user_id = false;

                    if (!$user) {

                        if (isset($userdata['username'])) {
                            $user = get_user_by('login', $userdata['username']);
                        }

                        if (!$user && isset($userdata['user_email'])) {
                            $user = get_user_by('email', $userdata['user_email']);
                        }
                    }

                    $update = false;
                    if ($user) {
                        $userdata['ID'] = $user->ID;
                        $update = true;
                    }

                    /* If creating a new user and no password was set, let auto-generate one! */
                    if (!$update && $update == false  && empty($userdata['user_pass'])) {
                        $userdata['user_pass'] = wp_generate_password(12, false);
                    }

                    if (isset($update) && $update == true) {
                        $user_id    = wp_update_user($userdata);
                        $new_user   = new WP_User($user_id);
                        $new_user->set_role('subscriber');
                        $display_name    = $userdata['first_name'] . ' ' . $userdata['last_name'];
                    } else {
                        global $prs_settings;
                        $display_name           = $userdata['first_name'] . ' ' . $userdata['last_name'];
                        $db_user_id             = !empty($usermeta['user_id']) ? $usermeta['user_id'] : '';
                        $db_username            = !empty($userdata['username']) ? $userdata['username'] : '';
                        $db_user_pass           = !empty($userdata['user_pass']) ? $userdata['user_pass'] : '123456';
                        $db_user_email          = !empty($userdata['user_email']) ? $userdata['user_email'] : '';
                        $db_user_url            = !empty($userdata['user_url']) ? $userdata['user_url'] : '';
                        $db_nicename            = !empty($userdata['user_nicename']) ? sanitize_title($userdata['user_nicename']) : $db_username;
                        $display_name           = !empty($userdata['display_name']) ? $userdata['display_name'] : $display_name;
                        $user_role              = !empty($userdata['role']) ? $userdata['role'] : 'subscriber';
                        $login_type             = !empty($usermeta['login_type']) ? $usermeta['login_type'] : 'article_author';
                        if (empty($db_user_email)) {
                            continue;
                        }

                        $sql = "INSERT INTO $wp_user_table (user_login, 
															user_pass, 
															user_email, 
															user_registered,
															user_status, 
															display_name, 
															user_nicename, 
															user_url
															) VALUES ('" . $db_username . "',
															'" . md5($db_user_pass) . "',
															'" . $db_user_email . "',
															'" . date('Y-m-d H:i:s') . "',
															0,
															'" . $display_name . "',
															'" . $db_nicename . "',
															'" . $db_user_url . "'
														)";

                        $wpdb->query($sql);
                        $lastid     = $wpdb->insert_id;
                        $new_user     = new WP_User($lastid);
                        $new_user->set_role($user_role);
                        $user_id        = $lastid;

                        update_user_meta($user_id, 'show_admin_bar_front', 'false');
                        update_user_meta($user_id, 'full_name', $display_name);
                        update_user_meta($user_id, 'first_name', $userdata['first_name']);
                        update_user_meta($user_id, 'last_name', $userdata['last_name']);
                        update_user_meta($user_id, 'rich_editing', 'true');
                        update_user_meta($user_id, 'nickname', $display_name);
                        update_user_meta($user_id, '_is_verified', 'yes');
                        update_user_meta($user_id, 'identity_verified', 1);
                        update_user_meta($user_id, 'login_type', $login_type);

                        /* new imported user data */
                        $imported_user_data[] = array(
                            'id'        => $user_id,
                            'email'     => $db_user_email,
                            'user_type' => $login_type,
                            'role'      => $user_role,
                        );
                    }
                    $rkey++;
                }
            }
        }
    }
}
