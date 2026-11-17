<?php
/**
 * Default Images Settings
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/Plugin_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/
$theme_version	= wp_get_theme();
Redux::set_section( $opt_name, array(
	'title'            => esc_html__( 'Default images', 'peer-review-system' ),
	'id'               => 'default_images_settings',
	'subsection'       => false,
	'icon'			   => 'el el-random',
	'fields'           => array(
		array(
			'id'       => 'default_user_profile',
			'type'     => 'media',
			'title'    => esc_html__('User default profile image', 'peer-review-system'),
			'default'  => array( 'url' => PEER_REVIEW_SYSTEM_DIRECTORY_URI.'public/images/default-avatar.jpg' ),
		),
		array(
			'id'       => 'croppie_uploaded_file_width',
			'type'     => 'text',
			'default'  => '400',
			'title'    => esc_html__( 'Image cropped file width', 'peer-review-system' )
		),
		array(
			'id'       => 'croppie_uploaded_file_height',
			'type'     => 'text',
			'default'  => '400',
			'title'    => esc_html__( 'Image cropped file height', 'peer-review-system' )
		),
	))
);
