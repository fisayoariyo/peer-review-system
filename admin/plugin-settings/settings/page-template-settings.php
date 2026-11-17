<?php
/**
 * Template Settings
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/Plugin_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
*/

/**
 * @init            Check if chat solution installed
 * @package         Amentotech
 * @since           1.0
 * @desc            If plugin activated return true else false
 */

Redux::set_section( $opt_name, array(
        'title'            => esc_html__( 'Template settings', 'peer-review-system' ),
        'id'               => 'template_settings',
        'desc'       	   => '',
		'icon' 			   => 'el el-search',
		'subsection'       => false,
        'fields'           => array(			
			array(
				'id'    	=> 'tpl_dashboard',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'User dashboard page', 'peer-review-system' ),
				'data'  	=> 'pages',
				'desc'      => esc_html__('Select page for the dashboard', 'peer-review-system'),
			),
			array(
				'id'    	=> 'tpl_search_articles',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'Articles search page', 'peer-review-system' ),
				'data'  	=> 'pages',
				'desc'      => esc_html__('Select page for the article search', 'peer-review-system'),
			),
			array(
				'id'    	=> 'tpl_search_editions',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'Editions search page', 'peer-review-system' ),
				'data'  	=> 'pages',
				'desc'      => esc_html__('Select page for the edition search', 'peer-review-system'),
			),
			array(
				'id'    	=> 'tpl_login',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'Login page', 'peer-review-system' ),
				'data'  	=> 'pages',
				'desc'      => esc_html__('Select login page', 'peer-review-system'),
			),
			array(
				'id'    	=> 'tpl_registration',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'Registration page', 'peer-review-system' ),
				'data'  	=> 'pages',
				'desc'      => esc_html__('Select registration page', 'peer-review-system'),
			),
      		array(
				'id'    	=> 'tpl_reset',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'Reset password page', 'peer-review-system' ),
				'data'  	=> 'pages',
				'desc'      => esc_html__('Select reset password page', 'peer-review-system'),
			),
      		array(
				'id'    	=> 'tpl_terms_conditions',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'Terms & conditions', 'peer-review-system' ),
				'data'  	=> 'pages',
				'desc'      => esc_html__('Select terms & conditions', 'peer-review-system'),
			),	
		)
	)
);
 