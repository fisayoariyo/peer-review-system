<?php
/**
 * Header Settings
 *
 * @throws error
 * @author Amentotech <theamentotech@gmail.com>
 * @return 
 */
$theme_version 		= wp_get_theme();
if(!empty($theme_version->get( 'TextDomain' )) && ( $theme_version->get( 'TextDomain' ) === 'journalo' )){
	Redux::setSection( $opt_name, array(
			'title'            => esc_html__( 'Header settings', 'peer-review-system' ),
			'id'               => 'header_settings',
			'icon'			   => 'el el-align-justify',
			'subsection'       => false,
			'fields'           => array(
				array(
					'id'		=> 'main_logo',
					'type' 		=> 'media',
					'url'		=> true,
					'title' 	=> esc_html__('Logo', 'peer-review-system'),
					'desc' 		=> esc_html__('Upload site header logo.', 'peer-review-system'),
				)
			)
		)
	);
}