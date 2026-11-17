<?php

/**
 * Article attached documents
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

$attachments = get_post_meta($article_id, 'attachments', true);
$file_img = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/file.svg';
if (!empty($attachments) && is_array($attachments)) {
    ?>  
    <ul class="sj-docupload">
        <?php foreach ($attachments as $key => $val) { ?>
            <li>
                <div class="sj-doclist_content">
                    <img src="<?php echo esc_url($file_img); ?>" alt="<?php echo esc_attr($val['attachment_name']); ?>">
                    <div class="sj-doclist_title">
                        <h6><?php echo esc_html($val['attachment_name']); ?></h6>
                        <span><?php echo esc_html($val['attachment_size']); ?></span>
                    </div>
                </div>
                <div class="sj-doc-download"><a class="sj-secondery-btn" href="<?php echo esc_url($val['attachment_url']) ?>" download><?php esc_html_e('Download', 'peer-review-system'); ?><i class="icon-download"></i> </a></div>
            </li>
        <?php }

        if (count($attachments) > 1) {
            ?>
            <li class="sj-download-all">
                <a href="javascript:void(0)" data-id="<?php echo intval($article_id); ?>" class="sj-btn-line sj-download-article-docs"><?php esc_html_e('Download all', 'peer-review-system'); ?> <i class="icon icon-download"></i> </a>
            </li>
            <?php 
        } ?>
    </ul>
<?php }
