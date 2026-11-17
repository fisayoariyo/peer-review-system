<?php

/**
 * General Settings
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/admin/Plugin_Settings/Settings
 * @author      Amentotech <info@amentotech.com>
 * @link        http://amentotech.com/
 * @version     1.0
 * @since       1.0
 */

Redux::set_section(
    $opt_name,
    array(
        'title'         => esc_html__('General settings', 'peer-review-system'),
        'id'            => 'general_settings',
        'subsection'    => false,
        'icon'          => 'el el-globe',
        'fields'        =>  array(
            array(
                'id'        => 'user_account_approve',
                'type'      => 'switch',
                'title'     => esc_html__('User Account Approve', 'peer-review-system'),
                'subtitle'  => esc_html__('ON mean account auto approved!', 'peer-review-system'),
                'default'   => true,
            ),
            array(
                'id'        => 'email_user_registration',
                'type'      => 'select',
                'title'     => esc_html__('Send Email', 'peer-review-system'),
                'desc'      => esc_html__('Please select new user verification type', 'peer-review-system'),
                'options'   => array(
                  'verify_by_link'        => esc_html__('Verify by auto generated link', 'peer-review-system'),
                  'verify_by_admin'       => esc_html__('Verify by admin', 'peer-review-system'),
                ),
                'required'  => array('user_account_approve','equals',''),
            ),          
            array(
                'id'        => 'defult_register_type',
                'type'      => 'select',
                'title'     => esc_html__('Default user registration', 'peer-review-system'),
                'desc'      => esc_html__('Please select new user type for defult registration', 'peer-review-system'),
                'options'   => array(
                    'subscriber'        => esc_html__('Subscriber', 'peer-review-system'),
                    'article_author'    => esc_html__('Author', 'peer-review-system'),
                    'article_reviewer'  => esc_html__('Reviewer', 'peer-review-system'),
                    'article_editor'    => esc_html__('Editor', 'peer-review-system'),
                ),
                'default'   => 'subscriber',
            ),
            array(
                'id'        => 'allow_registration_roles',
                'type'      => 'select',
                'title'     => esc_html__('Allow roles types', 'peer-review-system'),
                'desc'      => esc_html__('Allow which role type of users can register on the front-end', 'peer-review-system'),
                'options'   => apply_filters('peer_review_system_roles_list',array()),
                'default'   => array('subscriber'),
                'multi'    => true,
            ),
            array(
                'id'       => 'password_strength',
                'type'     => 'select',
                'multi'    => true,
                'title'    => esc_html__('Password strength', 'peer-review-system'), 
                'desc'     => esc_html__('Select password strength', 'peer-review-system'),
                'options'  => array(
                    'length'   			=> wp_kses( __('Password must be 8 characters', 'peer-review-system' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
                    'upper'				=> wp_kses( __('1 upper case', 'peer-review-system' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
                    'lower'  			=> wp_kses( __('1 lower case', 'peer-review-system' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
                    'special_character' => wp_kses( __('1 special character', 'peer-review-system' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
                    'number'  			=> wp_kses( __('1 number', 'peer-review-system' ),array('a' => array('href' => array(),'title' => array()),'br' => array(),'em' => array(),'strong' => array(),)),
                ),
            ),
            array(
                'id'    => 'article_purchase_info',
                'type'  => 'info',
                'title' => esc_html__('Article purchase section', 'peer-review-system'),
                'style' => 'info',
            ),
            array(
                'id'        => 'purchase_title',
                'type'      => 'text',
                'title'     => esc_html__('Purchase article title', 'peer-review-system' ),
                'default'   => esc_html__('Like what you see?', 'peer-review-system'),
                'desc'      => esc_html__('Please add article purchase section title.', 'peer-review-system'),
            ),
            array(
                'id'        => 'purchase_description',
                'type'      => 'text',
                'title'     => esc_html__('Purchase article description.', 'peer-review-system'),
                'default'   => esc_html__('Consectetur adipisicing elitame sedo amodate tempor', 'peer-review-system'),
                'desc'      => esc_html__('Please add article purchase section description.', 'peer-review-system'),
            ),

            array(
                'id'       => 'file_upload_types',
                'type'     => 'info',
                'title' => esc_html__('Articles upload files types', 'peer-review-system'),
                'style' => 'info',
            ),
            array(
                'id'        => 'default_file_extensions',
                'type'      => 'textarea',
                'title'     => esc_html__('Article file extensions', 'peer-review-system'),
                'default'   => esc_html__('pdf,doc,docx', 'peer-review-system'),
                'subtitle'  => esc_html__('Add file extension by comma seperated text', 'peer-review-system'),
            ),
           
        )
    )
);
 
