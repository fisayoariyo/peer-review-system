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
        'title'         => esc_html__('Article sample', 'peer-review-system'),
        'id'            => 'sample_article',
        'subsection'    => false,
        'icon'          => 'el el-globe',
        'fields'        =>  array(            
            array(
                'id'       => 'sample_article_upload_types',
                'type'     => 'info',
                'title' => esc_html__('Article sample file for the authors', 'peer-review-system'),
                'style' => 'info',
            ),
            array(
                'id'        => 'sample_article_description',
                'type'      => 'textarea',
                'title'     => esc_html__('Article description', 'peer-review-system'),
                'default'   => esc_html__('You can download the article sample file. It will describe article formating rules.', 'peer-review-system'),
                'subtitle'  => esc_html__('', 'peer-review-system'),
            ),
            array(
                'id'       => 'sample_article_file',
                'type'     => 'media',
                'mode'     => 'pdf',
                'preview'     => false,
                'title'    => esc_html__('Upload file', 'peer-review-system'),
                'default'  => array( 'url' => PEER_REVIEW_SYSTEM_DIRECTORY_URI.'public/images/sample-article.pdf' ),
                'subtitle'  => esc_html__('Upload article sample file', 'peer-review-system'),
            ),
           
        )
    )
);
 
