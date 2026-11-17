<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Custom Scripts Settings
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/Plugin_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

Redux::set_section( $opt_name, array(
		'title'      => esc_html__( 'CSS/JS scripts', 'peer-review-system' ),
		'id'         => 'custom_code',
		'desc'       => '',
		'icon' 		 => 'el el-css',
		'subsection'       => false,
		'fields'     => array(
			array(
				'id'       => 'custom_css',
				'type'     => 'ace_editor',
				'title'    => esc_html__('Custom CSS', 'peer-review-system'),
				'subtitle' => esc_html__('Paste your CSS code here.', 'peer-review-system'),
				'mode'     => 'css',
				'theme'    => 'monokai',
				'desc'     => '',
				'default'  => ""
			),
			array(
				'id'       => 'custom_js',
				'type'     => 'ace_editor',
				'title'    => esc_html__('Custom JS', 'peer-review-system'),
				'subtitle' => esc_html__('Paste your JS code here.', 'peer-review-system'),
				'mode'     => 'css',
				'theme'    => 'monokai',
				'desc'     => '',
				'default'  => ""
			),
		)
	)
);
