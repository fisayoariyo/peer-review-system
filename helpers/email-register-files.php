<?php
/**
 * Register Email Templates
 *
 * @version     1.0
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @since       1.0
*/
$dir = PEER_REVIEW_SYSTEM_DIRECTORY;
require plugin_dir_path( __FILE__ ) . 'email-helper.php';
$scan_PostTypes = glob( $dir . 'helpers/templates/*.*' );
if ( ! empty( $scan_PostTypes ) ) {
	foreach ( $scan_PostTypes as $filename ) {
		$file = pathinfo( $filename );
		if ( ! empty( $file['filename'] ) ) {
			$template_path = $dir . 'helpers/templates/' . $file['filename'] . '.php';
			if ( file_exists( $template_path ) ) {
				@include $template_path;
			}
		}
	}
}