<?php
/**
 * The template used for add article
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $prs_settings;

$article_id     = !empty($_GET['article_id']) ? $_GET['article_id'] : 0;
$article_mode   = !empty($_GET['mode']) ? $_GET['mode'] : 'add';
$form_heading   = esc_html__('Add a new article', 'peer-review-system');
$introduction   = $article_title = $article_authors = $excerpt = $accept_terms = $article_download_url = $sj_paid_article = $article_price  = $sj_custom_paid_article_checked ='';
$selected_cats  = $attached_docs = array();
$defaultAvatar  = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/default-avatar.jpg';
$terms_conditions_url      = !empty($prs_settings['tpl_terms_conditions']) ? get_permalink($prs_settings['tpl_terms_conditions']) : '';
$editor_status  = '';
if ( $article_mode === 'edit' || $article_mode === 'resubmit') {
    $form_heading       = esc_html__('Edit article', 'peer-review-system');
    $article_title      = !empty(get_the_title($article_id)) ? get_the_title($article_id) : '';
    $selected_cats      = wp_get_post_terms($article_id, 'article_categories', array('fields' => 'all'));
    $article_authors    = get_post_meta($article_id, 'prs_article_authors', true);
    $introduction       = get_post_field('post_content', $article_id);
    $introduction       = !empty($introduction) ? $introduction : '';
    $excerpt            = get_the_excerpt($article_id);
    $excerpt            = !empty($excerpt) ? $excerpt : '';
    $attached_docs      = get_post_meta($article_id, 'attachments', true);
    $attached_docs      = !empty($attached_docs) ? $attached_docs : array();
    $accept_terms       = get_post_meta($article_id, 'prs_accept_terms', true);
    $sj_paid_article        = get_post_meta($article_id, 'sj_paid_article', true);
    $article_download_url   = get_post_meta($article_id, 'article_download_url', true);
    $editor_status          = get_post_meta($article_id, 'prs_article_editor_status', true);
    $article_price          = get_post_meta($article_id, 'article_price', true);
    $custom_download        = get_post_meta($article_id, 'sj_custom_paid_article', true);

    if (has_post_thumbnail($article_id)) {
        $feature_image  = get_the_post_thumbnail_url($article_id, 'sj_profile_thumbnail');
        $defaultAvatar  = $feature_image;
    }
}

$sj_paid_article_checked    = '';
$article_download_style     = 'style="display:none;"';
if (!empty($sj_paid_article) && $sj_paid_article == 'on') {
    $sj_paid_article_checked    = 'checked';
    $article_download_style     = 'style="display:block;"';
}

$cp_article_download_style  = 'style="display:none;"';
if (!empty($custom_download) && $custom_download == 'on') {
    $sj_custom_paid_article_checked    = 'checked';
    $cp_article_download_style     = 'style="display:block;"';

}

if (!empty($accept_terms) && $accept_terms == 'on') {
    $accept_terms    = 'checked';
}

/* get all cats */
$arguments      =  array(
    'taxonomy'      => 'article_categories',
    'hide_empty'    => false,
);
$article_categories = get_terms($arguments);
$doc_image   = PEER_REVIEW_SYSTEM_DIRECTORY_URI . '/public/images/file.svg';

$sample_article_description = !empty($prs_settings['sample_article_description']) ? $prs_settings['sample_article_description'] : '';
$sample_article_file = !empty($prs_settings['sample_article_file']['url']) ? $prs_settings['sample_article_file']['url'] : '';
if($editor_status !== 'rejected' && $editor_status !== 'approved'){?>
    <div class="sj-boxwrapper">
        <div class="sj-boxtitle">
            <h3><?php echo do_shortcode($form_heading); ?></h3>
        </div>
        <?php if(!empty($sample_article_file)){?>
            <div class="sj-contactus_info sj-contactus">       
                <h4><?php echo esc_html_e('Sample article file', 'peer-review-system'); ?></h4>        
                <?php if(!empty($sample_article_description)){?>
                    <span><?php echo esc_html($sample_article_description)?></span>
                <?php }
                if(!empty($sample_article_file)){
                    ?>
                    <a href="<?php echo esc_url($sample_article_file);?>" target="_blank" class="sj-approve-btn" data-article-id="<?php echo intval($article_id)?>"><?php esc_html_e('Download article','peer-review-system');?> <i class="icon icon-download"></i></a>
                    <?php
                }
                ?>
            </div>
        <?php }?>
        <div class="sj-box" id="sj-verfication">
            
            <form id="sj-add-article-form" class="sj-themeform sj-dhbform sj-add-article-form">
                <fieldset>
                    <div class="sj-themeform__wrap">
                        <div class="form-group-wrap">
                            <div class="form-group">
                                <label class="sj-label"><?php echo esc_html_e('Article title', 'peer-review-system'); ?></label>
                                <div class="sj-placeholderholder">
                                    <input type="text" class="form-control" name="article_title" id="article_title" value="<?php echo esc_attr($article_title); ?>" placeholder=" " required>
                                    <div class="sj-placeholder">
                                        <span><?php echo esc_html_e('Enter articles title', 'peer-review-system'); ?></span>
                                        <em>*</em>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="sj-label"><?php echo esc_html_e('Select category', 'peer-review-system'); ?></label>
                                <div class="sj-select">
                                    <select id="sj-article-categories" class="form-control sj-article-categories" name="categories" data-placeholder="<?php esc_attr_e('Select article category', 'peer-review-system'); ?>">
                                        <option value=""><?php esc_html_e('Select category', 'peer-review-system'); ?></option>
                                        <?php foreach ($article_categories as $category) { ?>
                                            <option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name) ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <ul class="sj-labels sj-asignreview-tags" id="sj-article-selected-categories">
                                    <?php if (empty(!$selected_cats)) {
                                        foreach ($selected_cats as $cat_obj) { ?>
                                            <li id="<?php echo intval($cat_obj->term_id); ?>">
                                                <span><?php echo esc_html(ucfirst($cat_obj->name));  ?><a href="javascript:void(0)"><i class="icon icon-x"></i></a></span>
                                                <input class="sj_selected_items" type="hidden" data-cat_id="<?php echo intval($cat_obj->term_id); ?>" value="<?php echo esc_attr(ucfirst($cat_obj->name)); ?>">
                                            </li>
                                    <?php }
                                    } ?>
                                </ul>
                            </div>
                            <div class="form-group sj-title">
                                <span><?php echo esc_html_e('Add article author', 'peer-review-system'); ?></span>
                            </div>
                            <div class="form-group sj-addmore-wrapper" id="sj-author-email">
                                <?php
                                $author_counter = 0;
                                if (!empty($article_authors) && is_array($article_authors)) {
                                    foreach ($article_authors as $index => $author) {
                                        $author_counter = $index;
                                        if ($index > 0) { ?>
                                            <div class="sj-addmore-slot">
                                                <div class="sj-addslot-list">
                                                <?php } ?>
                                                <div class="form-group form-group-half">
                                                    <?php if ($index == 0) { ?>
                                                        <label class="sj-label"><?php echo esc_html_e('Author name', 'peer-review-system'); ?></label>
                                                    <?php } ?>
                                                    <div class="sj-placeholderholder">
                                                        <input type="text" name="article_author[<?php echo esc_attr($index); ?>][name]" value="<?php echo esc_attr($author['name']); ?>" class="form-control" placeholder="<?php esc_attr_e('Enter author name', 'peer-review-system') ?> " required>
                                                        <div class="sj-placeholder">
                                                            <span><?php echo esc_html_e('Enter author name', 'peer-review-system'); ?></span>
                                                            <em>*</em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group form-group-half">
                                                    <?php if ($index == 0) { ?>
                                                        <label class="sj-label"><?php echo esc_html_e('Author email ID', 'peer-review-system'); ?></label>
                                                    <?php } ?>
                                                    <div class="sj-placeholderholder">
                                                        <input type="text" name="article_author[<?php echo esc_attr($index); ?>][email]" value="<?php echo esc_attr($author['email']); ?>" class="form-control" placeholder="<?php esc_attr_e('Enter author email', 'peer-review-system') ?>" required>
                                                        <div class="sj-placeholder">
                                                            <span><?php echo esc_html_e('Enter author email', 'peer-review-system'); ?></span>
                                                            <em>*</em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php if ($index > 0) { ?>
                                                    <a href="javascript:void(0)" class="sj-deletlist"><i class="icon-trash-2"></i></a>
                                                </div>
                                            </div>
                                        <?php
                                                } ?>
                                    <?php }
                                } else { ?>
                                    <div class="form-group form-group-half">
                                        <label class="sj-label"><?php echo esc_html_e('Author name', 'peer-review-system'); ?></label>
                                        <div class="sj-placeholderholder">
                                            <input type="text" name="article_author[0][name]" class="form-control" placeholder="<?php esc_attr_e('Enter author name', 'peer-review-system') ?> " required>
                                            <div class="sj-placeholder">
                                                <span><?php echo esc_html_e('Enter author name', 'peer-review-system'); ?></span>
                                                <em>*</em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-half">
                                        <label class="sj-label"><?php echo esc_html_e('Author email ID', 'peer-review-system'); ?></label>
                                        <div class="sj-placeholderholder">
                                            <input type="text" name="article_author[0][email]" class="form-control" placeholder="<?php esc_attr_e('Enter author email', 'peer-review-system') ?>" required>
                                            <div class="sj-placeholder">
                                                <span><?php echo esc_html_e('Enter author email', 'peer-review-system'); ?></span>
                                                <em>*</em>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="sj-addmore-btn">
                                <span class="author-fields-addmore" data-author_fields_count="<?php echo esc_attr($author_counter); ?>"><?php esc_html_e('Add more', 'peer-review-system') ?> </span>
                            </div>
                            <div class="form-group">
								<label class="sj-label"><?php echo esc_html_e('Brief introduction', 'peer-review-system'); ?></label>
								<p style="margin:8px 0 12px; font-size:13px; color:#6b7280;">
									<?php esc_html_e('Use this section for the manuscript introduction or main body text. Do not add author bio details here.', 'peer-review-system'); ?>
								</p>
								<div class="sj-placeholderholder sj-briefintro">
									<?php
									$editor_id = 'sj-introduction-description';
									$settings =   array(
										'wpautop' => false,
										'media_buttons' => false,
										'textarea_name' => 'introduction',
										'textarea_rows' => 50,
										'textarea_cols' => 200,
										'tabindex' => '',
										'editor_css' => '',
										'editor_class' => '',
										'teeny' => false,
										'dfw' => false,
										'tinymce' => true,
										'quicktags' => true,
										'editor_height' => 200,
										'editor_width' => '100%',
									);
									wp_editor($introduction, $editor_id, $settings);
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="sj-label"><?php echo esc_html_e('Abstract', 'peer-review-system'); ?></label>
								<div class="sj-placeholderholder">
									<textarea class="form-control" name="excerpts" placeholder="<?php esc_attr_e('Enter abstract', 'peer-review-system') ?>" required><?php echo esc_html($excerpt); ?></textarea>
									<div class="sj-placeholder">
										<span><?php esc_html_e('Enter abstract', 'peer-review-system') ?> </span>
										<em>*</em>
									</div>
								</div>
							</div>
                            <div id="peer-upload-attachment" class="peer-fileuploader sj-uploadarea">
                                <div class="form-group" id="peer-droparea">
                                    <label class="sj-label"><?php echo esc_html_e('Upload documents', 'peer-review-system'); ?></label>
                                    <div class="sj-uploadphoto">
                                        <div class="sj-uploaddesc">
                                            <h5><?php esc_html_e('Drag or ', 'peer-review-system') ?><input type="file" id="file4"><label for="peer-attachment-btn" id="peer-attachment-btn"><?php esc_html_e('click here ', 'peer-review-system') ?></label><?php esc_html_e(' to Upload documents', 'peer-review-system') ?></h5>
                                            <p><?php echo esc_html(sprintf(__('Document file size does not exceed %s and you can upload any document file format for users to download', 'peer-review-system'), peer_review_system_max_upload_size_label())); ?></p>
                                        </div>
                                        <svg>
                                            <rect width="100%" height="100%"></rect>
                                        </svg>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <ul id="peer-fileprocessing" class="sj-docupload peer-fileprocessing" >
                                        <?php if (!empty($attached_docs)) {
                                            foreach ($attached_docs as $doc_key => $doc_val) {
                                                $attachment_url     = !empty($doc_val['attachment_url']) ? $doc_val['attachment_url'] : '';
                                                $attachment_name    = !empty($doc_val['attachment_name']) ? $doc_val['attachment_name'] : '';
                                                $attachment_size    = !empty($doc_val['attachment_size']) ? $doc_val['attachment_size'] : '';
                                        ?>
                                                <li class="article-attachments">
                                                    <div class="sj-doclist_content">
                                                        <img src="<?php echo esc_url($doc_image) ?>" alt="<?php echo esc_attr($attachment_name); ?>">
                                                        <div class="sj-doclist_title">
                                                            <h6><?php echo esc_html($doc_val['attachment_name']) ?></h6>
                                                            <input type="hidden" class="attachment_url" name="attachments[<?php echo intval($doc_key) ?>][file]" value="<?php echo esc_url($attachment_url) ?>">
                                                            <input type="hidden" class="attachment_name" name="attachments[<?php echo intval($doc_key) ?>][fileName]" value="<?php echo esc_html($attachment_name) ?>">
                                                            <input type="hidden" class="attachment_size" name="attachments[<?php echo intval($doc_key) ?>][fileSize]" value="<?php echo esc_html($attachment_size) ?>">
                                                            <span><?php echo esc_html($doc_val['attachment_size']) ?></span>
                                                        </div>
                                                        <a href="javascript:void(0);" id="sj_delete_attachment"><i class="icon-trash-2"></i></a>
                                                    </div>
                                                </li>
                                        <?php
                                            }
                                        } ?>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="sj-label"><?php echo esc_html_e('Message to reviewer', 'peer-review-system'); ?></label>
                                <div class="sj-placeholderholder">
                                    <textarea class="form-control" name="revision-reply" placeholder="<?php esc_attr_e('Enter revision comment', 'peer-review-system') ?>" required></textarea>
                                    <div class="sj-placeholder">
                                        <span><?php esc_html_e('Enter revision comment', 'peer-review-system') ?> </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group sj-checkform">
                                <div class="sj-check">
                                    <input type="checkbox" id="bhourscheck2" name="sj_accept_terms" <?php echo esc_html($accept_terms); ?>>
                                    <label for="bhourscheck2"><?php esc_html_e('I accept all the submiting article’s', 'peer-review-system'); ?><a href="<?php echo esc_url($terms_conditions_url); ?>"><?php esc_html_e(' Terms &amp; conditions', 'peer-review-system'); ?></a> </label>
                                </div>
                            </div>
                            <div class="form-group sj-formbtn">
                                <button type="submit" id="submit-add-article-form" data-article_id="<?php echo esc_attr($article_id); ?>" name="submit" class="sj-secondery-btn-lg sj-save-article-details"><?php esc_html_e('Submit article now', 'peer-review-system'); ?></button>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
            <script type="text/template" id="tmpl-load-peer-media-attachments">
                <li id="thumb-{{data.id}}" >
                    <div class="sj-doclist_content sj-doclist_loader">
                        <img src="<?php echo esc_url($doc_image) ?>" alt="<?php esc_attr_e('Image', 'peer-review-system') ?>">
                        <div class="sj-doclist_title">
                            <h6>{{data.name}}</h6>
                            <input type="hidden" class="attachment_url" name="attachments[{{data.id}}][file]" value="{{data.file}}">
                            <input type="hidden" class="attachment_name" name="attachments[{{data.id}}][fileName]" value="{{data.fileName}}">
                            <input type="hidden" class="attachment_size" name="attachments[{{data.id}}][fileSize]" value="{{data.fileSize}}">
                            <span>{{data.size}}</span>
                        </div>
                        <a href="javascript:void(0);" id='sj_delete_attachment'><i class="icon-trash-2"></i></a>
                    </div>
                </li>
            </script>
            <script type="text/template" id="tmpl-add-more-article-authors">
                <div class="sj-addslot-list">
                        <div class="form-group form-group-half">
                            <div class="sj-placeholderholder">
                                <input type="text" name="article_author[{{data}}][name]" class="form-control" placeholder="<?php esc_attr_e('Enter author name', 'peer-review-system'); ?>" />
                                <div class="sj-placeholder">
                                    <span><?php esc_html_e('Enter author name', 'peer-review-system'); ?></span>
                                    <em>*</em>
                                </div>
                            </div>
                        </div>
                        <div class="form-group sj-addslot">
                            <div class="sj-placeholderholder">
                                <input type="text" name="article_author[{{data}}][email]" class="form-control" placeholder="<?php esc_attr_e('Enter author email', 'peer-review-system'); ?>" />
                                <div class="sj-placeholder">
                                    <span><?php esc_html_e('Enter author email', 'peer-review-system') ?></span>
                                    <em>*</em>
                                </div>
                            </div>
                            <a href="javascript:void(0)" class="sj-deletlist"><i class="icon-trash-2"></i></a>
                        </div>
                    </div>
            </script>
            <script type="text/template" id="tmpl-article-cover-image">
                <img id="article-cover-thumbnail" class="sj-imgborder" src="<?php echo esc_url($defaultAvatar); ?>" alt="<?php esc_attr_e('Cover image', 'peer-review-system'); ?>">
                <input type="hidden" name="cover_attachment" id="attachment_src" data-attachment_src="">
                <input type="hidden" name="cover_attachment_thumbnail" id="attachment_thumb_src" data-attachment_src="">
                <input type="hidden" name="cover_attachment_name" id="attachment_name_src" data-attachment_src="">
            </script>
        </div>
    </div>
<?php } else {
    do_action('peer_review_system_non_editable_article');
}
