<?php
/**
 * The template used for mange editions
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$per_page          = get_option('posts_per_page');
$pg_page           = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged          = get_query_var('paged') ? get_query_var('paged') : 1;
$paged 			   = max($pg_page, $pg_paged);
$search_keyword    = !empty($_GET['keyword']) ? esc_html($_GET['keyword']) : '';
$sort_order        = !empty($_GET['sort_order']) ? esc_html($_GET['sort_order']) : 'DESC';
$sort_order        = !empty($sort_order) && $sort_order == 'DESC' ? 'DESC' : 'ASC';
 
$edition_query_args = array(
    'posts_per_page'        => $per_page,
    'paged'                 => $paged,
    'author'                => $user_identity,
    'post_type'             => 'editions',
    'post_status'           => 'any',
    'ignore_sticky_posts'   => 1,
    'orderby'               => 'date',
    'order'                 => $sort_order,
);

if (!empty($search_keyword)) {
    $edition_query_args['s'] = $search_keyword;
}
 
$edition_data = new WP_Query(apply_filters('peer_review_system_edition_search_filter', $edition_query_args));
$total_posts = $edition_data->found_posts;
$defaultAvatar = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/default-avatar.jpg';
/* getting all articles */
$query_args = array(
    'post_type'             => 'articles',
    'post_status'           => 'publish',
    'posts_per_page'        => -1,
    'paged'                 => 1,
    'orderby'               => 'date',
    'order'                 => 'DESC',
    'meta_query'            => peer_review_system_public_article_meta_query(),
);
$articles_info  = array();
$selected_articles = array();
 
$articles           = get_posts( $query_args );
foreach($articles as $article){
    $articles_info[$article->ID] = $article->post_title;
}
?>
<div>
    <form class="sj-themeform sj-editionform sj-new-edition-form" id="sj-new-edition-form">
        <fieldset>
            <div class="form-group-wrap">
                <div class="form-group form-group-title">
                    <h4><?php esc_html_e('Add new edition', 'peer-review-system');?></h4>
                </div>
                <div class="form-group">
                    <label class="sj-label"><?php esc_html_e('Upload cover photo','peer-review-system') ?></label>
                    <div class="sj-uploadphoto" id="peer-edition-image">
                        <div class="sj-uploaddesc">
                            <div id="sj_edition_avatar_processing" class="sj-profile-img">
                                <img id="edition-cover-thumbnail" src="<?php echo esc_url($defaultAvatar)?>" alt="<?php esc_attr_e('Image','peer-review-system')?>">
                                <input type="hidden" id="attachment_src" data-attachment_src="" value="0">
                            </div>
                            <h5 id="sj_edition_cover_droparea"><?php esc_html_e('Drag or ', 'peer-review-system');?><input type="file" name="uploadProfileimage" id="sj_edition_cover_image" accept="image/*"><label for="sj_edition_cover_image" ><?php esc_html_e('click here', 'peer-review-system');?></label> <?php esc_html_e('to upload photo', 'peer-review-system');?></h5>
                            <p><?php echo esc_html(sprintf(__('Document file size does not exceed %s and you can upload any document file format for users to download', 'peer-review-system'), peer_review_system_max_upload_size_label())); ?></p>
                            </div>
                        <svg><rect width="100%" height="100%"></rect></svg>
                    </div>
                </div>
                <div class="form-group form-group-3half">
                    <label class="form-title sj-required"><?php esc_html_e('Edition title', 'peer-review-system');?></label>
                    <input type="text" name="edition_name" class="form-control" placeholder="<?php esc_attr_e('Enter edition title here', 'peer-review-system');?>" required="">
                </div>
                <div class="form-group form-group-3half">
                    <label class="form-title sj-required"><?php esc_html_e('Edition launch date', 'peer-review-system');?></label>
                    <div class="sj-placeholderholder">
                        <div class="sj-calendar sj-datepicker">
                            <input type="text" name="edition_date" id="edition_date" class="datepicker form-control" placeholder=" " readonly>
                            <div class="sj-placeholder">
                                <span><?php esc_html_e('Select edition date', 'peer-review-system');?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group form-group-3half">
                    <button type="submit" class="sj-approve-btn" id="submit-new-edition-form"><?php esc_html_e('Add edition now', 'peer-review-system');?></button>
                </div>
            </div>
        </fieldset>
    </form>

    <?php if($total_posts>0){?>
        <div class="sj-boxwrapper">
            <div class="sj-boxtitle">
                <h3><?php esc_html_e('Manage editions', 'peer-review-system');?></h3>
                <form id="sj-edition-keyword-search-form" method="get" action="<?php echo esc_url($current_page_link);?>">
                    <input type="hidden" name="tab" value="<?php echo esc_html($_GET['tab']);?>">
                    <input type="hidden" name="useridentity" value="<?php echo intval($user_identity);?>">
                    <div class="sj-sorting_field">
                        <div class="sj-sorting_search">
                            <input type="text" class="form-control" value="<?php echo esc_attr($search_keyword);?>" name="keyword" placeholder="<?php esc_attr_e('Search here', 'peer-review-system');?>">
                            <a href="javascript:void(0);"><i class="icon icon-search" id="sj-search-keyword"></i></a>
                        </div>
                        <?php if ($sort_order == 'ASC') { ?>
                            <a href="javascript:void(0);" class="sj-articles-sortorder" data-sort_order="DESC"><i class="fa fa-sort-amount-down-alt"></i></a>
                        <?php } else { ?>
                            <a href="javascript:void(0);" class="sj-articles-sortorder" data-sort_order="ASC"><i class="fa fa-sort-amount-up-alt"></i></a>
                        <?php
                        } ?>
                    </div>
                </form>
            </div>
            <div class="sj-editionswrap">
                <?php
                while ($edition_data->have_posts()) {
                    $edition_data->the_post();
                    global $post;
                    $author_id          = $post->post_author;  
                    $post_id            = !empty($post->ID) ? intval($post->ID) : '';
                    $edition_name       = get_the_title();
                    $attchemnts_info    = get_post_meta($post_id,'attachments',true);
                    $edition_date       = get_post_meta($post_id,'edition_date',true);
                    $edition_price      = get_post_meta($post_id,'edition_price',true);
                    $author_name        = get_the_author_meta( 'display_name', $author_id ); 
                    $status_            = !empty($post_id) ? esc_html($post->post_status) : ''; 
                    $status_class       = !empty($status_) && $status_ == 'publish' ?  'sj-approved' : 'sj-queue';    
                    $status             = !empty($status_) && $status_ == 'publish' ?  'Published' : 'In queue';    
                    $selected_articles  = get_post_meta($post_id,'assign_articles',true);
                    $edition_date       = !empty($edition_date) ?  date_i18n(get_option('date_format'), strtotime($edition_date)) : '' ;
                    ?>
                    <div class="sj-editions">
                        <div class="sj-editions_head">
                            <?php if (has_post_thumbnail( $post->ID ) ): ?>
                                <figure class="sj-editions_head_img">
                                    <img src="<?php echo get_the_post_thumbnail_url($post->ID, 'sj_profile_thumbnail'); ?>" alt="<?php echo esc_attr(get_the_title())?>">
                                </figure>
                            <?php endif;?>                            
                            <div class="sj-editions_head_title">
                                <?php if(!empty($status)){?>
                                    <span class="sj-tag <?php echo esc_attr($status_class)?>">
                                        <?php echo esc_html($status)?>
                                    </span>
                                <?php } ?>
                                <?php if(!empty($edition_name)) {?>
                                    <h5><?php echo esc_html($edition_name)?></h5>
                                <?php } ?>
                                <?php if(!empty($edition_date) || !empty($author_name)){?>
                                    <ul class="sj-editions_head_info">
                                        <?php if(!empty($edition_date)){?>
                                            <li>
                                                <i class="icon icon-calendar"></i>
                                                <span><?php echo esc_html($edition_date)?></span>
                                            </li>
                                        <?php } ?>
                                        <?php if(!empty($author_name)){?>
                                            <li>
                                                <i class="icon icon-users"></i>
                                                <span><?php echo esc_html($author_name) ?></span>
                                            </li>
                                        <?php  } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="sj-editions_head_option">
                            <a href="javascript:void(0);" class="sj-actionbtn"><span><?php esc_html_e('Quick action','peer-review-system')?> </span><i class="icon icon-chevron-down"></i></a>
                            <ul class="sj-actiondropdown">
                                <li>
                                    <a href="javascript:void(0);" class="sj-asignedit" data-edition_id="<?php echo intval($post_id)?>"><?php esc_html_e('Assign articles','peer-review-system')?> </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="sj-edition-action" data-action ="puslish" data-edition-id="<?php echo intval($post_id)?>"><?php esc_html_e('Publish edition','peer-review-system')?></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="sj-quickedit" data-edition_id="<?php echo intval($post_id)?>"><?php esc_html_e('Quick edit','peer-review-system')?></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="sj-edition-action" data-action ="remove" data-edition-id="<?php echo intval($post_id)?>" ><?php esc_html_e('Remove','peer-review-system') ?> </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php }       
                wp_reset_postdata();?>
            </div>
            <?php if (!empty($total_posts)) {
                peer_review_system_paginate($edition_data, 'sj-pagination');
            } ?> 
        </div>
    <?php } ?>
</div>
<script type="text/template" id="tmpl-load-edition-image">
    <li id="thumb-{{data.id}}" >
        <div class="sj-doclist_content sj-doclist_loader">
            <img src="<?php echo esc_url($defaultAvatar) ?>" alt="<?php esc_attr_e('Image', 'peer-review-system') ?>">
            <div class="sj-doclist_title">
                <h6>{{data.name}}</h6>
                <input type="hidden" class="attachment_url" name="attachments_file" value="{{data.file}}">
                <input type="hidden" class="attachment_name" name="attachments_fileName" value="{{data.fileName}}">
                <input type="hidden" class="attachment_size" name="attachments_fileSize" value="{{data.fileSize}}">
                <span>{{data.size}}</span>
            </div>
            <a href="javascript:void(0);" class="sj-remove-image" id="sj_delete_edition_attachment"><i class="icon-trash-2"></i></a>
        </div>
    </li>
</script>
<script type="text/template" id="tmpl-load-edition-avatar">
    <figure id="thumb-{{data.id}}">
        <img src="<?php echo esc_url($defaultAvatar) ?>" alt="<?php esc_attr_e('Image', 'peer-review-system') ?>" id="thumb-src">
        <input type="hidden" class="attachment_url" name="attachments[{{data.id}}][file]" id="thumb-file" value="">
        <input type="hidden" class="attachment_name" name="attachments[{{data.id}}][fileName]" id="thumb-fileName" value="">
        <input type="hidden" class="attachment_size" name="attachments[{{data.id}}][fileSize]" id="thumb-fileSize" value="">
        <figcaption>
            <a href="javascript:void(0);" class="sj-remove-image"><i class="icon-trash-2"></i></a>
        </figcaption>
    </figure>
</script>
<script type="text/template" id="tmpl-load-editon-quick-edit">
    <div class="modal-header">
        <h5><?php esc_html_e('Edit/update edition','peer-review-system')?></h5>
        <a href="javascript:void(0)" data-bs-dismiss="modal"><i class="icon-x"></i></a>
    </div>
    <div class="modal-body">
        <form class="sj-themeform" id="sj-edit-edition-form-{{data.ID}}">
            <fieldset>
                <div class="form-group-wrap">                      
                    <div class="form-group">
                        <label class="sj-label"><?php esc_html_e('Upload cover photo','peer-review-system') ?></label>
                        <div class="sj-uploadphoto" id="peer-edition-image-edit">
                            <div class="sj-uploaddesc">
                                <div id="sj_edition_avatar_processing-edit" class="sj-profile-img">                 
                                    <img id="edition-cover-thumbnail" src="{{data.feature_image}}" alt="<?php esc_attr_e('Image','peer-review-system')?>">
                                    <input type="hidden" id="attachment_src" data-attachment_src="" value="0">
                                </div>
                                <h5 id="sj_edition_cover_droparea-edit"><?php esc_html_e('Drag or ', 'peer-review-system');?><input type="file" name="uploadProfileimage" id="sj_edition_cover_image-edit" accept="image/*"><label for="sj_edition_cover_image-edit" ><?php esc_html_e('click here', 'peer-review-system');?></label> <?php esc_html_e('to upload photo', 'peer-review-system');?></h5>
                                <p><?php echo esc_html(sprintf(__('Document file size does not exceed %s and you can upload any document file format for users to download', 'peer-review-system'), peer_review_system_max_upload_size_label())); ?></p>
                                </div>
                            <svg><rect width="100%" height="100%"></rect></svg>
                        </div>
                    </div>                                  
                    <div class="form-group">
                        <label class="form-title sj-required"><?php esc_html_e('Edition title','peer-review-system')?> </label>
                        <input type="text" name="edition_name" class="form-control" placeholder="<?php esc_attr_e('Enter edition title here','peer-review-system')?>" required="" value="{{data.title}}">
                    </div>
                    <div class="form-group">
                        <label class="form-title sj-required"><?php esc_html_e('Edition launch date', 'peer-review-system');?></label>
                        <div class="sj-placeholderholder">
                            <div class="sj-calendar datepicker sj-datepicker" id="sj-datepicker-edt">
                                <input type="text" name="edition_date" id="datepicker" class="form-control" placeholder=" " readonly value="{{data.edition_date}}">
                                <div class="sj-placeholder">
                                    <span><?php esc_html_e('Select edition date', 'peer-review-system');?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="javascript:void(0);" class="sj-secondery-btn" id="submit-edit-edition-form" data-edition-id="{{data.ID}}"><?php esc_html_e('Save & update','peer-review-system'); ?></a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</script>
<script type="text/template" id="tmpl-load-editon-assign-articles">
    <div class="modal-header">
        <h5><?php esc_html_e('Assign articles','peer-review-system')?></h5>
        <a href="javascript:void(0)" data-bs-dismiss="modal"><i class="icon-x"></i></a>
    </div>
    <div class="modal-body">
        <form class="sj-themeform" id="sj-edit-assign-article-{{data.edition_id}}">
            <fieldset>
                <div class="form-group-wrap">                    
                    <div class="form-group">
                        <label class="form-title"><?php esc_html_e('Select articles from list below','peer-review-system')?> </label>
                        <div class="sj-select">
                            <select id="sj-article-dropdown"  class ="sj-article-dropdown" name="articles" data-placeholder="<?php esc_attr_e('Select reviewer from list','peer-review-system')?>"class="form-control" required>
                                <option value=""><?php esc_html_e('Select articles from list','peer-review-system')?></option>
                                <?php foreach ($articles_info as $key => $title) { ?>
                                    <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($title) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <ul class="sj-asignreview-tags" id="sj-assign-selected-articles"> 
                            <# if( !_.isEmpty(data.selected_articles) ) { #>
                                <# _.each(data.selected_articles, function(title,index) { #>
                                    <li id="{{index}}">
                                        <span>{{title}}<a href="javascript:void(0)" class="select2-remove-article-cat"><i class="icon icon-x"></i></a></span>
                                        <input class="sj_selected_items" type="hidden" data-cat_id="{{index}}" value="{{title}}">
                                    </li>
                                <# }); #>
                            <# } #>
                        </ul>
                    </div>
                    <div class="form-group form-group-3half">
                        <a href="javascript:void(0);" class="sj-secondery-btn" id="submit-article-assign" data-edition-id="{{data.edition_id}}" data-author-id="{{data.author_id}}"><?php esc_html_e('Assign now','peer-review-system'); ?></a>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</script>
<script type="text/template" id="tmpl-edition-cover-image">
    <img id="edition-cover-thumbnail" class="sj-imgborder" src="<?php echo esc_url($defaultAvatar); ?>" alt="<?php esc_attr_e('Cover image', 'peer-review-system');?>">
    <input type="hidden" name="cover_attachment" id="attachment_src" data-attachment_src="">
    <input type="hidden" name="cover_attachment_thumbnail" id="attachment_thumb_src" data-attachment_src="">
    <input type="hidden" name="cover_attachment_name" id="attachment_name_src" data-attachment_src="">
</script>