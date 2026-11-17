(function($) {
    var loader_html = '<div class="sj-preloader"><div class="sj-preloader_holder"><div class="sj-loader"></div></div></div>';
    var prsMaxUploadSize = (typeof scripts_vars !== 'undefined' && scripts_vars.upload_size) ? scripts_vars.upload_size : '25mb';

    'use strict';
    /**
     * add article form
     */
   

    //Editions menubar
    jQuery(".sj-actionbtn").on("click", function(e) {
        // Close all open windows
        e.stopPropagation();
        jQuery(".sj-actiondropdown").stop().slideUp(300);
        // Toggle this window open/close
        jQuery(this).next(".sj-actiondropdown").stop().slideToggle(300);
    });

    // assign toggle
    jQuery(".sj-quickedit").on("click", function() {
        jQuery(this).closest(".sj-editions").find(".sj-quick-edit").slideToggle();
        jQuery(this).closest(".sj-editions").find(".sj-asign-edit").hide();
    });

    jQuery(".sj-asignedit").on("click", function() {
        jQuery(this).closest(".sj-editions").find(".sj-asign-edit").slideToggle();
        jQuery(this).closest(".sj-editions").find(".sj-quick-edit").hide();
    });

    /**
     * Get tinyMce content
     */
    function sj_tmce_getContent(editor_id, textarea_id) {
        if (typeof editor_id == "undefined") {
            editor_id = wpActiveEditor;
        }

        if (typeof textarea_id == "undefined") {
            textarea_id = editor_id;
        }

        if (jQuery("#wp-" + editor_id + "-wrap").hasClass("tmce-active") && tinyMCE.get(editor_id)) {
            return tinyMCE.get(editor_id).getContent();
        } else {
            return jQuery("#" + textarea_id).val();
        }
    }

    // Article submit
    jQuery(document).on("click", "#submit-add-article-form", function(e) {
        e.preventDefault();
        let article_id = jQuery("#submit-add-article-form").data("article_id");
        let dataArray = jQuery("#sj-add-article-form").serialize();

        /* get value from tinymce editor */
        let description = sj_tmce_getContent(
            "sj-introduction-description",
            "introduction"
        );
        /* selected cats */
        let selected_cats = [];
        let categories = jQuery("#sj-add-article-form .sj_selected_items");
        /* process selected categories */
        categories.each(function(i, field) {
            let cat_id = jQuery(field).data("cat_id");
            selected_cats.push(parseInt(cat_id));
        });

        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_add_article",
                security: scripts_vars.ajax_nonce,
                data: dataArray,
                selected_cats: selected_cats,
                description: description,
                article_id: article_id,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 3000,
                    });
                    location.reload();
                    jQuery("#sj-add-article-form")[0].reset();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 3000,
                    });
                }
            },
        });
    });

    /**
     * Assign article to edition
     */
    jQuery(document).on("click", "#submit-article-assign", function(e) {
        e.preventDefault();
        let this_ = jQuery(this);
        let edition_id = this_.data("edition-id");
        let author_id = this_.data("author-id");
        let form_id = "#sj-edit-assign-article-" + edition_id;
        /* selected article */
        let selected_article = [];
        let articles = jQuery(form_id + " .sj_selected_items");
        /* process selected categories */
        articles.each(function(i, field) {
            let article_id = jQuery(field).data("cat_id");
            selected_article.push(parseInt(article_id));
        });
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_assign_article",
                security: scripts_vars.ajax_nonce,
                edition_id: edition_id,
                author_id: author_id,
                selected_article: selected_article,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 3000,
                    });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 3000,
                    });
                }
            },
        });
    });

    //Edition datepicker
    if (jQuery('.sj-datepicker').length > 0) {
        initDatePicker("sj-datepicker", "YYYY-MM-DD");
    }
    /**
     * Edition form submit
     */
    jQuery(document).on("click", "#submit-new-edition-form", function(e) {
        e.preventDefault();
        let dataArray = jQuery("#sj-new-edition-form").serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_new_edition",
                security: scripts_vars.ajax_nonce,
                data: dataArray,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 3000,
                    });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 3000,
                    });
                }
            },
        });
    });

    /**
     * Assign article to reviewer
     */
    jQuery(document).on("click", "#sj-article-assign-reviewer", function(e) {
        e.preventDefault();
        let article_id = jQuery(this).data("article_id");
        let editor_id = jQuery(this).data("editor_id");
        /* selected cats */
        let selected_cats = [];
        let reviewers = jQuery("#sj-assign-reviewer-form .sj_selected_items");
        /* process selected categories */
        reviewers.each(function(i, field) {
            let cat_id = jQuery(field).data("cat_id");
            selected_cats.push(parseInt(cat_id));
        });
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_assign_article_reviewer",
                security: scripts_vars.ajax_nonce,
                reviewer_ids: selected_cats,
                article_id: article_id,
                editor_id: editor_id,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 3000,
                    });
                    location.reload();
                    jQuery("#sj-add-article-form")[0].reset();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 3000,
                    });
                }
            },
        });
    });

    /**
     * Invite an independent (external) reviewer by email and assign them
     */
    jQuery(document).on("click", "#prs-invite-reviewer", function(e) {
        e.preventDefault();
        let $btn = jQuery(this);
        let article_id = $btn.data("article_id");
        let editor_id = $btn.data("editor_id");
        let $form = jQuery("#prs-invite-reviewer-form");
        let email = jQuery.trim($form.find('[name="invite_email"]').val() || "");
        let first_name = jQuery.trim($form.find('[name="invite_first_name"]').val() || "");
        let last_name = jQuery.trim($form.find('[name="invite_last_name"]').val() || "");

        if (!email) {
            stickyAlert("Oops!", "Please enter a valid reviewer email address.", {
                classList: "danger",
                autoclose: 3000,
            });
            return;
        }

        jQuery("body").append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_invite_external_reviewer",
                security: scripts_vars.ajax_nonce,
                article_id: article_id,
                editor_id: editor_id,
                email: email,
                first_name: first_name,
                last_name: last_name,
            },
            dataType: "json",
            success: function(response) {
                jQuery("body").find(".sj-preloader").remove();
                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 4000,
                    });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 4000,
                    });
                }
            },
            error: function() {
                jQuery("body").find(".sj-preloader").remove();
                stickyAlert("Oops!", "Something went wrong. Please try again.", {
                    classList: "danger",
                    autoclose: 4000,
                });
            },
        });
    });

    /**
     * reply on revision without status
     */
    jQuery(document).on("click", ".peer-veview-revision-single-reply", function(e) {
        e.preventDefault();
        let _serialize = jQuery("#prs_revision_single_reply_form").serialize();
        jQuery("body").append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_revision_reply",
                security: scripts_vars.ajax_nonce,
                data: _serialize,
            },
            dataType: "json",
            success: function(response) {
                jQuery("body").find(".sj-preloader").remove();
                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 5000,
                    });
                    location.reload();
                    jQuery("#prs_revision_single_reply_form")[0].reset();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 5000,
                    });
                }
            },
        });
    });

    /**
     * reply on revision with status
     */
    jQuery(document).on("click", ".peer-veview-revision-reply", function(e) {
        e.preventDefault();
        let _serialize = jQuery("#prs_revision_reply_form").serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_author_article_conversation",
                security: scripts_vars.ajax_nonce,
                data: _serialize,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 5000,
                    });
                    location.reload();
                    jQuery("#prs_revision_reply_form")[0].reset();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 5000,
                    });
                }
            },
        });
    });

    /**
     * add specific reviewer id
     * in url for load replys
     */
    jQuery("#sj-load-reviewer-replys").on("change", function() {
        let reviewer_id = this.value;
        var url = window.location.href;
        var url = removeParam("reviewer_id", url);
        if (url.indexOf("?") > -1) {
            url += "&reviewer_id=" + reviewer_id;
        } else {
            url += "?reviewer_id=" + reviewer_id;
        }
        window.location = url;
    });


    /**
     * Add more author fields
     */
    let article_author_count = jQuery(".author-fields-addmore").data("author_fields_count");
    article_author_count = article_author_count != 0 ? article_author_count : 0;
    jQuery(document).on("click", ".sj-addmore-btn span", function(e) {
        e.preventDefault();
        ++article_author_count;
        let prs_add_more_authors = wp.template('add-more-article-authors');
        prs_add_more_authors = prs_add_more_authors(article_author_count);
        jQuery('#sj-author-email').append(prs_add_more_authors);
    });

    // Save profile settings
    jQuery(document).on("click", ".sj-personal-detail", function(e) {
        e.preventDefault();
        var _this = jQuery(this);
        let dataArray = jQuery("#sj-persoanl-detail-form").serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_personal_setting",
                security: scripts_vars.ajax_nonce,
                data: dataArray,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();

                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 3000,
                    });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 3000,
                    });
                }
            },
        });
    });

    // Change profile password
    jQuery(document).on("click", ".sj-change-password", function(e) {
        e.preventDefault();
        var _this = jQuery(this);
        let dataArray = jQuery("#sj-change-password-form").serialize();
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_change_password",
                security: scripts_vars.ajax_nonce,
                data: dataArray,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();

                if (response.type === "success") {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 3000,
                    });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "danger",
                        autoclose: 3000,
                    });
                }
            },
        });
    });

    /**
     * Publish edition
     */
    jQuery(document).on('click', '.sj-edition-action', function(e) {
        e.preventDefault();
        let this_ = jQuery(this);
        let edition_id = this_.data("edition-id");
        let action = this_.data("action");
        jQuery('body').append(loader_html);
        let title = scripts_vars.publish_edition_title;
        let description = scripts_vars.publish_edition_description;

        if (action == 'remove') {
            title = scripts_vars.remove_edition_title;
            description = scripts_vars.remove_edition_description;
        }
        executeConfirmAjaxRequest({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'peer_review_system_edition_action',
                'security': scripts_vars.ajax_nonce,
                'edition_id': edition_id,
                'edition_action': action,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === 'success') {
                    stickyAlert(response.title, response.message, { classList: 'success', autoclose: 3000 });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, { classList: 'danger', autoclose: 3000 });
                }
            }
        }, title, description);
        jQuery('body').find('.sj-preloader').remove();
    });

    

    //Article price edit
    jQuery(".sj-priceEdit").on("click", function(e) {
        e.preventDefault();
        let _this = jQuery(this);
        let article_id = _this.data('article_id');
        jQuery('body').append(loader_html);
        executeAjaxRequest({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'peer_review_system_load_article_price',
                'security': scripts_vars.ajax_nonce,
                'article_id': article_id,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === 'success') {
                    if (response.data) {
                        let data = response.data;
                        var load_article_price_popup = wp.template('load-article-price-edit');
                        load_article_price_popup = load_article_price_popup(data);
                        jQuery('#peer-review-system-modal-popup #peer-review-system-model-body').html(load_article_price_popup);

                        let download_option = data.sj_paid_article;
                        if(download_option == 'download'){
                            jQuery(".sj-article-paid-url").show();
                            jQuery(".sj-article-paid-custom-price").hide();
                        } else if(download_option == 'price'){
                            jQuery(".sj-article-paid-url").hide();
                            jQuery(".sj-article-paid-custom-price").show();
                        } else {
                            jQuery(".sj-article-paid-url").hide();
                            jQuery(".sj-article-paid-custom-price").hide();
                        }
                        jQuery('#peer-review-system-modal-popup').modal('show');

                        jQuery("input:radio[name=sj_paid_article]").on("click", function() {
                            let download_option = jQuery(this).val();
                            if(download_option == 'download'){
                                jQuery(".sj-article-paid-url").show();
                                jQuery(".sj-article-paid-custom-price").hide();
                            } else if(download_option == 'price'){
                                jQuery(".sj-article-paid-url").hide();
                                jQuery(".sj-article-paid-custom-price").show();
                            } else {
                                jQuery(".sj-article-paid-url").hide();
                                jQuery(".sj-article-paid-custom-price").hide();
                            }
                        });
                        
                    }
                } else {
                    stickyAlert(response.title, response.message, { classList: 'danger', autoclose: 3000 });
                }
            }
        });
    });

     //Article price submit
    jQuery(document).on('click', '#submit-article-price-form', function(e) {
        e.preventDefault();
        let this_ = jQuery(this);
        let article_id = this_.data("article_id");
        let dataArray = jQuery('#sj-edit-article-price-form-' + article_id).serialize() + '&article_id=' + article_id;
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'peer_review_system_edit_article_price',
                'security': scripts_vars.ajax_nonce,
                'data': dataArray,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();

                if (response.type === 'success') {
                    jQuery('#peer-review-system-modal-popup').modal('hide');
                    stickyAlert(response.title, response.message, { classList: 'success', autoclose: 3000 });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, { classList: 'danger', autoclose: 3000 });
                }
            }
        });
    });

    

    //Editions edit
    jQuery(".sj-quickedit").on("click", function(e) {
        e.preventDefault();
        let _this = jQuery(this);
        let edition_id = _this.data('edition_id');
        jQuery('body').append(loader_html);
        executeAjaxRequest({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'peer_review_system_load_edition',
                'security': scripts_vars.ajax_nonce,
                'edition_id': edition_id,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === 'success') {
                    if (response.data) {
                        let data = response.data;
                        var load_edition_popup = wp.template('load-editon-quick-edit');
                        load_edition_popup = load_edition_popup(data);
                        jQuery('#peer-review-system-modal-popup #peer-review-system-model-body').html(load_edition_popup);
                        jQuery('#peer-review-system-modal-popup').modal('show');
                        let parems_cover = {
                            btnID: 'sj_edition_cover_image-edit',
                            containerID: 'peer-edition-image-edit',
                            dropareaID: 'sj_edition_cover_droparea-edit',
                            type: 'file_name',
                            previewID: 'sj_edition_avatar_processing-edit',
                            templateID: 'edition-cover-image',
                            multiSelection: false,
                            filetype: 'jpg,jpeg,gif,png',
                            maxFileCount: '1',
                            fileSize: prsMaxUploadSize,
                            fileSizes: ['sj_profile_thumbnail'],
                            dragDrop: true,
                            uploadedFileFun: 'updateEditionCover',
                            minWidth: '',
                            maxWidth: '',
                            maxHeight: '',
                            minHeight: '',
                        }
                        plupUpload(parems_cover);
                    }
                } else {
                    stickyAlert(response.title, response.message, { classList: 'danger', autoclose: 3000 });
                }
            }
        });
    });

    /**
     * Delete Article
     */
    jQuery('.prs-remove-article').on('click', function(e) {
        e.preventDefault();
        let article_id = jQuery(this).data('article_id');
        let title = scripts_vars.article_delete_title;
        let description = scripts_vars.article_delete_desc;
        jQuery('body').append(loader_html);
        executeConfirmAjaxRequest({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'peer_review_system_delete_article',
                'security': scripts_vars.ajax_nonce,
                'article_id': article_id,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === 'success') {
                    stickyAlert(response.title, response.message, { classList: 'success', autoclose: 3000 });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, { classList: 'danger', autoclose: 3000 });
                }
            }
        }, title, description);
        jQuery('body').find('.sj-preloader').remove();
    });

    jQuery(document).on('click', '#sj-datepicker-edt', function(e) {
        e.preventDefault();
        var picker_id = jQuery(this).children("input").attr("id");
        var sj_datepick = document.querySelector('#' + picker_id);
        if (sj_datepick !== null) {
            new Litepicker({
                element: document.querySelector('#' + picker_id),
                start: new Date(),
                previousMonth: true,
                nextMonth: true,
                selectForward: true,
                tooltipText: {
                    one: "night",
                    other: "nights",
                },
                years: {
                    min: 1900,
                    max: null,
                },
                months: true,

                tooltipNumber: (totalDays) => {
                    return totalDays - 1;
                },
            });
        }
    });

    // Assign articles to edition
    jQuery(".sj-asignedit").on("click", function(e) {
        e.preventDefault();
        let _this = jQuery(this);
        let edition_id = _this.data('edition_id');
        jQuery('body').append(loader_html);
        executeAjaxRequest({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'peer_review_system_load_assign_article',
                'security': scripts_vars.ajax_nonce,
                'edition_id': edition_id,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === 'success') {
                    let data = response.data;
                    var load_edition_popup = wp.template('load-editon-assign-articles');
                    load_edition_popup = load_edition_popup(data);
                    jQuery('#peer-review-system-modal-popup #peer-review-system-model-body').html(load_edition_popup);
                    jQuery('#peer-review-system-modal-popup').modal('show');
                    jQuery('#sj-article-dropdown').select2({ allowClear: true });
                } else {
                    stickyAlert(response.title, response.message, { classList: 'danger', autoclose: 3000 });
                }
            }
        });
    });

    //Edition submit
    jQuery(document).on('click', '#submit-edit-edition-form', function(e) {
        e.preventDefault();
        let this_ = jQuery(this);
        let edition_id = this_.data("edition-id");
        let dataArray = jQuery('#sj-edit-edition-form-' + edition_id).serialize() + '&edition_id=' + edition_id;
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                'action': 'peer_review_system_edit_edition',
                'security': scripts_vars.ajax_nonce,
                'data': dataArray,
            },
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();

                if (response.type === 'success') {
                    stickyAlert(response.title, response.message, { classList: 'success', autoclose: 3000 });
                    location.reload();
                } else {
                    stickyAlert(response.title, response.message, { classList: 'danger', autoclose: 3000 });
                }
            }
        });
    });

    /**
     * Download all article doc as a zip
     */
    jQuery('.sj-download-article-docs').on('click', function(e) {
        e.preventDefault();
        var article_id = jQuery(this).data('id');
        jQuery('body').append(loader_html);
        var dataString = 'security=' + scripts_vars.ajax_nonce + '&article_id=' + article_id + '&action=peer_review_system_download_files_as_zip';
        jQuery('body').append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: dataString,
            dataType: "json",
            success: function(response) {
                jQuery('body').find('.sj-preloader').remove();
                if (response.type === 'success') {
                    window.location = response.attachment;
                } else {
                    stickyAlert(response.title, response.message, {
                        classList: "success",
                        autoclose: 3000,
                    });
                }
            }
        });
    });

    // funtion for upload media atttachments/document
    let params = {
        btnID: 'peer-attachment-btn',
        containerID: 'peer-upload-attachment',
        dropareaID: 'peer-droparea',
        type: 'file_name',
        previewID: 'peer-fileprocessing',
        templateID: 'load-peer-media-attachments',
        multiSelection: false,
        filetype: scripts_vars.default_file_extensions ? scripts_vars.default_file_extensions : 'pdf,doc',
        maxFileCount: '1',
        fileSize: prsMaxUploadSize,
        fileSizes: ['sj_profile_thumbnail'],
        dragDrop: true,
        uploadedFileFun: 'appendMediaGalleryAttachments',
        minWidth: '',
        maxWidth: '',
        maxHeight: '',
        minHeight: '',
    };
    plupUpload(params);

    // fucntion for edition cover image
    let parems_cover = {
        btnID: 'uploadedition',
        containerID: 'peer-edition-image',
        dropareaID: 'peer-edition-droparea',
        type: 'file_name',
        previewID: 'peer-edtprocessing',
        templateID: 'load-edition-image',
        multiSelection: true,
        filetype: 'jpg,jpeg,gif,png',
        maxFileCount: '1',
        fileSize: prsMaxUploadSize,
        fileSizes: ['sj_profile_thumbnail'],
        dragDrop: true,
        uploadedFileFun: 'appendEditionCoverImage',
        minWidth: '',
        maxWidth: '',
        maxHeight: '',
        minHeight: '',
    }
    plupUpload(parems_cover);


    // function for article cover photo upload
    let params_article_cover = {
        btnID: 'sj_article_cover_image',
        containerID: 'peer-article-image',
        dropareaID: 'sj_article_cover_droparea',
        type: 'file_name',
        previewID: 'sj_avatar_processing',
        templateID: 'article-cover-image',
        multiSelection: false,
        filetype: 'jpg,jpeg,gif,png',
        maxFileCount: '1',
        fileSize: prsMaxUploadSize,
        fileSizes: ['sj_profile_thumbnail'],
        dragDrop: true,
        uploadedFileFun: 'updateArticleCover',
        minWidth: '',
        maxWidth: '',
        maxHeight: '',
        minHeight: '',
    };
    plupUpload(params_article_cover);


    if (jQuery('#prs-publisher-production-form').length) {
        let params_publisher_cover = {
            btnID: 'prs_publisher_cover_image',
            containerID: 'prs-publisher-cover',
            dropareaID: 'prs_publisher_cover_droparea',
            type: 'file_name',
            previewID: 'prs_publisher_cover_processing',
            templateID: 'article-cover-image',
            multiSelection: false,
            filetype: 'jpg,jpeg,gif,png',
            maxFileCount: '1',
            fileSize: prsMaxUploadSize,
            fileSizes: ['sj_profile_thumbnail'],
            dragDrop: true,
            uploadedFileFun: 'updatePublisherCover',
            minWidth: '',
            maxWidth: '',
            maxHeight: '',
            minHeight: '',
        };
        plupUpload(params_publisher_cover);

        let params_publisher_pdf = {
            btnID: 'prs_publisher_pdf_input',
            containerID: 'prs-publisher-pdf-upload',
            dropareaID: 'prs-publisher-pdf-droparea',
            type: 'file_name',
            previewID: 'prs-publisher-pdf-processing',
            templateID: 'prs-publisher-pdf-attachment',
            multiSelection: false,
            filetype: 'pdf',
            maxFileCount: '1',
            fileSize: prsMaxUploadSize,
            fileSizes: ['sj_profile_thumbnail'],
            dragDrop: true,
            uploadedFileFun: 'updatePublisherPdf',
            minWidth: '',
            maxWidth: '',
            maxHeight: '',
            minHeight: '',
        };
        plupUpload(params_publisher_pdf);
    }


    // fucntion for edit edition photo 
    let params_edition_cover = {
        btnID: 'sj_edition_cover_image',
        containerID: 'peer-edition-image',
        dropareaID: 'sj_edition_cover_droparea',
        type: 'file_name',
        previewID: 'sj_edition_avatar_processing',
        templateID: 'edition-cover-image',
        multiSelection: false,
        filetype: 'jpg,jpeg,gif,png',
        maxFileCount: '1',
        fileSize: prsMaxUploadSize,
        fileSizes: ['sj_profile_thumbnail'],
        dragDrop: true,
        uploadedFileFun: 'addEditionCover',
        minWidth: '',
        maxWidth: '',
        maxHeight: '',
        minHeight: '',
    };
    plupUpload(params_edition_cover);

    // profile avatar upload
    function peer_review_system_upload_profile_image(parems) {
        let {
            btnID,
            containerID,
            dropareaID,
            type,
            previewID,
            templateID,
            _type,
            defaultTemplateID,
            filetype,
            isCropped
        } = parems; // extends variables

        if (typeof plupload === 'object') {
            var sys_upload_nonce = scripts_vars.sys_upload_nonce;
            var ProjectUploaderArguments = {
                browse_button: btnID, // this can be an id of a DOM element or the DOM element itself
                file_data_name: type,
                container: containerID,
                drop_element: dropareaID,
                multipart_params: {
                    "type": type,
                },
                multi_selection: _type,
                url: scripts_vars.ajaxurl + "?action=peer_review_system_temp_file_uploader&ajax_nonce=" + scripts_vars.ajax_nonce,
                filters: {
                    mime_types: [
                        { title: 'file', extensions: filetype }
                    ],
                    max_file_size: scripts_vars.upload_size,
                    max_file_count: 1,
                    prevent_duplicates: false
                }
            };
            var ProjectUploader = new plupload.Uploader(ProjectUploaderArguments);
            ProjectUploader.init();
            //bind
            ProjectUploader.bind('FilesAdded', function(up, files) {
                var _Thumb = "";
                plupload.each(files, function(file) {
                    var load_thumb = wp.template(defaultTemplateID);
                    var _size = bytesToSize(file.size);
                    var data = { id: file.id, size: _size, name: file.name, percentage: file.percent };
                    load_thumb = load_thumb(data);
                    _Thumb += load_thumb;
                });

                if (_type == false) {
                    jQuery('#' + previewID).html(_Thumb);
                } else {
                    jQuery('#' + previewID).append(_Thumb);
                }

                jQuery('#' + previewID).removeClass('sj-empty-uploader');
                jQuery('#' + previewID).addClass('sj-infouploading');
                up.refresh();
                ProjectUploader.start();
            });
            //bind
            ProjectUploader.bind('UploadProgress', function(up, file) {
                var _html = ' <span class="progress-bar uploadprogressbar" style="width:' + file.percent + '%"></span>';
                jQuery('#thumb-' + file.id + ' .progress .uploadprogressbar').replaceWith(_html);
            });
            //Error
            ProjectUploader.bind('Error', function(up, err) {
                var errorMessage = err.message
                if (err.code == '-600') {
                    errorMessage = scripts_vars.file_size_error
                }
                let extra_params = {};
                extra_params['note_desc'] = errorMessage;

            });
            //display data
            ProjectUploader.bind('FileUploaded', function(up, file, ajax_response) {
                try {
                    var response = jQuery.parseJSON(ajax_response.response);
                    if (response.type === 'success') {
                        var load_thumb = wp.template(templateID);
                        var _size = bytesToSize(file.size);
                        var data = { id: file.id, size: _size, name: file.name, percentage: file.percent, url: response.thumbnail };

                        if (isCropped) {
                            cropImagePopup(data);
                        } else {
                            var load_thumb = load_thumb(data);
                            jQuery("#thumb-" + file.id).html(load_thumb);

                            if (btnID == 'sj-profile-attachment-btn') {
                                jQuery('.sj-remove-profile-img').css('display', '');
                            }
                        }

                    } else {
                        StickyAlert('', response.message, { classList: 'danger', autoclose: 5000 });
                    }
                } catch (err) {
                    stickyAlert('', scripts_vars.invalid_image, { classList: 'danger', autoclose: 3000 });
                }

            });
        }
    }
    // Image upload fun end	

    /* init profile avatar object parems */
    parems = {
            btnID: 'profile-avatar-icon',
            containerID: 'prs-asideprostatusv2',
            dropareaID: 'prs-droparea',
            type: 'file_name',
            previewID: 'sj-profile-upload-attachment-preview',
            templateID: 'load-profile-avatar',
            _type: 'true',
            filetype: scripts_vars.default_image_extensions ? scripts_vars.default_image_extensions : 'jpg,jpeg,gif,png',
            isCropped: true,
            defaultTemplateID: 'load-default-image',
        }
        /* init profile avatar object */
    peer_review_system_upload_profile_image(parems);

    /* cropped image popup modal */
    function cropImagePopup(data) {
        let load_profile_avatar = wp.template('load-profile-avatar');
        jQuery('#peer-review-system-modal-popup #peer-review-system-model-body').html(load_profile_avatar);
        jQuery('#peer-review-system-modal-popup').modal('show');
        let width = parseInt(scripts_vars.croppie_uploaded_file_width, 10);
        let height = parseInt(scripts_vars.croppie_uploaded_file_height, 10);
        image_crop = jQuery('#crop_img_area').croppie({
            enableOrientation: true,
            enableExif: true,
            viewport: {
                width: width,
                height: height,
                type: 'square'
            },
            exif: true,
            mouseWheelZoom: true,
            boundary: {
                width: 500,
                height: 300
            },
            url: data.url,
        });
    }

    /**
     * Cropped Image save
     */
    jQuery(document).on('click', '#save-profile-img', function(e) {
        jQuery('body').append(loader_html);
        image_crop.croppie('result', { type: 'base64', format: 'jpg' }).then(function(base64) {
            jQuery.ajax({
                type: "POST",
                url: scripts_vars.ajaxurl,
                data: {
                    action: 'peer_review_system_update_avatar',
                    image_url: base64
                },
                dataType: "json",
                success: function(response) {
                    jQuery('.sj-preloader').remove();
                    if (response.type === 'success') {
                        jQuery('#profile-avatar-menue-icon img').attr('src', response.avatar_50_x_50);
                        jQuery('#user_profile_avatar').attr('src', response.avatar_150_x_150);
                        jQuery('#peer-review-system-modal-popup').modal('hide');
                    } else {
                        StickyAlert(response.message, response.message_desc, { classList: 'danger', autoclose: 5000 });
                    }
                }
            }, true);
        });
    });

    //convert bytes to KB< MB,GB,TB
    function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    };


    // btnID, containerID, dropareaID, type, previewID, templateID
    function plupUpload(params) {
        let {
            btnID,
            containerID,
            dropareaID,
            previewID,
            templateID,
            multiSelection,
            filetype,
            maxFileCount,
            fileSize,
            fileSizes,
            dragDrop,
            uploadedFileFun,
            minWidth,
            maxWidth,
            maxHeight,
            minHeight,
            onlyForImages,
        } = params;
        if (!filetype) {
            filetype = "pdf,doc,docx,xls,xlsx,ppt,pptx,zip,csv,jpg,jpeg,gif,png,mp4,mp3,3gp,flv,ogg,wmv,avi";
        }
        var UploaderArguments = {
            browse_button: btnID, // this can be an id of a DOM element or the DOM element itself
            dragdrop: dragDrop, // boolean value
            multi_selection: multiSelection,
            container: containerID,
            drop_element: dropareaID,
            multipart_params: {
                fileSizes
            },


            // multi_selection: _type,
            url: scripts_vars.ajaxurl + "?action=peer_review_system_temp_multiple_files_uploader&ajax_nonce=" + scripts_vars.ajax_nonce,
            headers: {
                Authorization: `Bearer `,
            },
            filters: {
                mime_types: [
                    { extensions: filetype }
                ],
                max_file_size: fileSize,
                max_file_count: maxFileCount,
                prevent_duplicates: false,
                min_width: minWidth,
                max_width: maxWidth,
                max_height: maxHeight,
                min_height: minHeight,
            }

        };
        var plupUploader = new plupload.Uploader(UploaderArguments);
        plupUploader.init();
        if (onlyForImages) {
            plupload.addFileFilter('min_width', function(minwidth, file, cb) {
                var self = this,
                    img = new o.Image();

                function finalize(result) {
                    // cleanup
                    img.destroy();
                    img = null;

                    // if rule has been violated in one way or another, trigger an error
                    if (!result) {
                        stickyAlert('Error', "Image width should be more than " + minwidth + " pixels.", { classList: 'danger', autoclose: 3000 });
                    }
                    cb(result);
                }
                img.onload = function() {
                    // check if resolution cap is not exceeded
                    finalize(img.width >= minwidth);
                };
                img.onerror = function() {
                    finalize(false);
                };
                img.load(file.getSource());
            });

            plupload.addFileFilter('min_height', function(minheight, file, cb) {

                var self = this,
                    img = new o.Image();

                function finalize(result) {
                    // cleanup
                    img.destroy();
                    img = null;

                    // if rule has been violated in one way or another, trigger an error
                    if (!result) {
                        stickyAlert('Error', "Image height should be more than " + minheight + " pixels.", { classList: 'danger', autoclose: 3000 });
                    }
                    cb(result);
                }
                img.onload = function() {
                    // check if resolution cap is not exceeded
                    finalize(img.height >= minheight);
                };
                img.onerror = function() {
                    finalize(false);
                };
                img.load(file.getSource());
            });

            plupload.addFileFilter('max_width', function(maxwidth, file, cb) {
                var self = this,
                    img = new o.Image();

                function finalize(result) {
                    // cleanup
                    img.destroy();
                    img = null;

                    // if rule has been violated in one way or another, trigger an error
                    if (!result) {
                        stickyAlert('Error', "Image width should not be more than " + maxwidth + " pixels.", { classList: 'danger', autoclose: 3000 });
                    }
                    cb(result);
                }
                img.onload = function() {
                    // check if resolution cap is not exceeded
                    finalize(img.width <= maxwidth);
                };
                img.onerror = function() {
                    finalize(false);
                };
                img.load(file.getSource());
            });

            plupload.addFileFilter('max_height', function(maxheight, file, cb) {
                var self = this,
                    img = new o.Image();

                function finalize(result) {
                    // cleanup
                    img.destroy();
                    img = null;

                    // if rule has been violated in one way or another, trigger an error
                    if (!result) {
                        stickyAlert('Error', "Image hieght should not be more than " + maxheight + " pixels.", { classList: 'danger', autoclose: 3000 });
                    }
                    cb(result);
                }
                img.onload = function() {
                    // check if resolution cap is not exceeded
                    finalize(img.height <= maxheight);
                };
                img.onerror = function() {
                    finalize(false);
                };
                img.load(file.getSource());
            });
        }
        //bind
        plupUploader.bind('FilesAdded', function(up, files) {
            var _Thumb = "";
            var i = files.length,
                maxCountError = false;
            plupload.each(files, function(file) {
                if (plupUploader.settings.filters.max_file_count && i > plupUploader.settings.filters.max_file_count) {
                    maxCountError = true;
                    setTimeout(function() { up.removeFile(file); }, 50);
                }
                i++;
            });

            if (maxCountError) {
                plupUploader.refresh();
                stickyAlert('Error', 'Too many files uploaded', { classList: 'danger', autoclose: 3000 });
            }

            if (templateID == 'usefull-load-attachment') {
                jQuery('#tu-attachments-fileprocessing').removeClass('d-none');
            }

            if (maxFileCount == 1) { // for single file
                plupload.each(files, function(file) {
                    if (typeof wp === 'undefined' || typeof wp.template !== 'function') {
                        stickyAlert('Error', 'Upload tools failed to load. Please refresh the page.', { classList: 'danger', autoclose: 5000 });
                        return;
                    }
                    var load_thumb = wp.template(templateID);
                    var _size = bytesToSize(file.size);
                    var data = { id: file.id, size: _size, name: file.name, percentage: file.percent, fileType: file.type };
                    load_thumb = load_thumb(data);
                    _Thumb = load_thumb;
                });
            } else if (maxFileCount > 1) {
                let counter = 0;
                plupload.each(files, function(file) {
                    let prevous_files = jQuery('#' + previewID + ' li').length;
                    let file_count = maxFileCount - prevous_files;
                    if (maxFileCount < 1 || counter < file_count) {
                        var load_thumb = wp.template(templateID);
                        var _size = bytesToSize(file.size);
                        var data = { id: file.id, size: _size, name: file.name, percentage: file.percent, fileType: file.type.split('/')[0] };

                        load_thumb = load_thumb(data);
                        _Thumb += load_thumb;
                    }
                    if (maxFileCount > 1) {
                        counter++;
                    }
                });
            }

            if (multiSelection) {
                jQuery('#' + previewID).append(_Thumb);
            } else {
                if (btnID !== 'peer-attachment-btn') {
                    jQuery('#' + previewID).addClass('sj-hasimgloader');
                }
                jQuery('#' + previewID).html(_Thumb);
            }

            jQuery('#' + previewID).addClass('sj-imguploaded');
            if (maxCountError) {
                if (btnID !== 'peer-attachment-btn') {
                    jQuery('.sj-profile-img').removeClass('sj-hasimgloader');
                }
                maxCountError = false;
            } else {
                jQuery('.sj-update-record').attr("disabled", true);
                jQuery('.sj-update-record').addClass("tu-btnloader");
            }
            up.refresh();
            plupUploader.start();
        });

        // file removed
        plupUploader.bind('FilesRemoved', function(up, files) {
            if (maxFileCount > 1) {
                let prevous_files = jQuery('#' + previewID + ' li').length;
                if (up.files.length >= maxFileCount) {
                    jQuery('#' + containerID).show('slow');
                }
            }
        });

        //upload progress
        plupUploader.bind('UploadProgress', function(up, file) {
            var _html = '<span class="progress-bar uploadprogressbar" style="width:' + file.percent + '%"></span>';
            jQuery('#thumb-' + file.id + ' .progress .uploadprogressbar').replaceWith(_html);
        });

        //Error
        plupUploader.bind('Error', function(up, err) {
            var errorMessage = err.message
            if (err.code == '-600') {
                errorMessage = 'You can only upload ' + scripts_vars.upload_max_file_size + ' maximum file size';
            }
            stickyAlert('Error', errorMessage, { classList: 'danger', autoclose: 3000 });
        });

        // uploaded File
        plupUploader.bind('FileUploaded', function(up, file, ajax_response) {
            try {
                var response = jQuery.parseJSON(ajax_response.response);
                if (response.type === 'success') {
                    window[uploadedFileFun](up, file, response);
                } else {
                    stickyAlert('', response.message, { classList: 'danger', autoclose: 3000 });
                }
            } catch (err) {
                // Do something about the exception here
                jQuery('li#tu_file_' + file.id).remove();
                stickyAlert('', scripts_vars.invalid_image, { classList: 'danger', autoclose: 3000 });
            }

        });
    }

    // Execute ajax resuests 
    function executeAjaxRequest(custom_ajax) {
        if (jQuery(custom_ajax['data']).length) {
            custom_ajax_data = custom_ajax['data'];
            custom_ajax_data.app_type = 'web';
            custom_ajax['data'] = custom_ajax_data;
        }
        jQuery.ajax(custom_ajax);
    }

    // Alert the notification
    function stickyAlert($title = '', $message = '', data) {
        var $icon = 'ti-face-sad';
        var $class = 'dark';

        if (data.classList === 'success') {
            $icon = 'icon icon-check';
            $class = 'green';
        } else if (data.classList === 'danger') {
            $icon = 'icon icon-x';
            $class = 'red';
        }

        jQuery.confirm({
            icon: $icon,
            closeIcon: true,
            theme: 'modern',
            animation: 'scale',
            type: $class, //red, green, dark, orange
            title: $title,
            content: $message,
            autoClose: 'close|' + data.autoclose,
            buttons: {
                close: {
                    text: scripts_vars.close_text,
                    btnClass: 'sj-sticky-alert'
                },
            }
        });
    }

    // date picker
    function initDatePicker(selector, dateformat = "MM/DD/YYYY", single_mode = true, callback = "") {
        let date_fields = jQuery("." + selector);
        var picker_id = date_fields.children("input").attr("id");

        var sj_datepick = document.querySelector('#' + picker_id);
        if (sj_datepick !== null) {
            new Litepicker({
                element: document.querySelector('#' + picker_id),
                // start: new Date(),
                // maxDate: new Date(),
                minDate : new Date(),
                previousMonth: true,
                nextMonth: true,
                selectForward: true,
                tooltipText: {
                    one: "night",
                    other: "nights",
                },
                years: {
                    min: 1900,
                    max: null,
                },
                months: true,

                tooltipNumber: (totalDays) => {
                    return totalDays - 1;
                },
            });
        }
    }

    /**
     * Handle publisher AJAX responses and always remove loader.
     */
    function prsHandlePublisherAjaxResponse(response) {
        jQuery("body").find(".sj-preloader").remove();

        if (!response || typeof response !== "object") {
            stickyAlert(scripts_vars.error_title, scripts_vars.message_error, {
                classList: "danger",
                autoclose: 5000,
            });
            return;
        }

        stickyAlert(response.title, response.message, {
            classList: response.type === "success" ? "success" : "danger",
            autoclose: 5000,
        });

        if (response.type === "success") {
            location.reload();
        }
    }

    /**
     * Save publisher production draft
     */
    jQuery(document).on("click", "#prs-save-production-manuscript", function(e) {
        e.preventDefault();
        let dataArray = jQuery("#prs-publisher-production-form").serialize();
        jQuery("body").append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_save_production_manuscript",
                security: scripts_vars.ajax_nonce,
                data: dataArray,
            },
            dataType: "json",
        })
            .done(prsHandlePublisherAjaxResponse)
            .fail(function() {
                prsHandlePublisherAjaxResponse(null);
            });
    });

    /**
     * Publisher actions: start production / publish to website
     */
    jQuery(document).on("click", ".prs-publisher-action", function(e) {
        e.preventDefault();
        let publishAction = jQuery(this).data("action");
        let dataArray = jQuery("#prs-publisher-production-form").serialize();
        dataArray += "&prs_publish_action=" + publishAction;
        jQuery("body").append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_publishing_action",
                security: scripts_vars.ajax_nonce,
                data: dataArray,
            },
            dataType: "json",
        })
            .done(prsHandlePublisherAjaxResponse)
            .fail(function() {
                prsHandlePublisherAjaxResponse(null);
            });
    });

    /**
     * Post-publication management: retract / withdraw / unpublish / republish / remove
     */
    jQuery(document).on("click", ".prs-post-pub-action", function(e) {
        e.preventDefault();
        let $btn = jQuery(this);
        let action = $btn.data("action");
        let confirmMsg = $btn.data("confirm");
        let formSelector = $btn.data("form") || "#prs-post-pub-form";
        let $form = jQuery(formSelector).first();

        if (!$form.length) {
            return;
        }

        if (confirmMsg && !window.confirm(confirmMsg)) {
            return;
        }

        let dataArray = $form.serialize();
        if ($form.find('[name="prs_pub_action"]').length === 0) {
            dataArray += "&prs_pub_action=" + encodeURIComponent(action);
        }

        jQuery("body").append(loader_html);
        jQuery.ajax({
            type: "POST",
            url: scripts_vars.ajaxurl,
            data: {
                action: "peer_review_system_post_publication_action",
                security: scripts_vars.ajax_nonce,
                data: dataArray,
            },
            dataType: "json",
        })
            .done(prsHandlePublisherAjaxResponse)
            .fail(function() {
                prsHandlePublisherAjaxResponse(null);
            });
    });

})(jQuery);

/* remove params from url */
function removeParam(key, sourceURL) {
    var rtn = sourceURL.split("?")[0],
        param,
        params_arr = [],
        queryString =
        sourceURL.indexOf("?") !== -1 ? sourceURL.split("?")[1] : "";

    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (var i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }

    return rtn;
}

//Ater upload file
function appendMediaGalleryAttachments(up, file, response) {
    let attachment = response.attachments;
    jQuery('#peer-fileprocessing').removeClass('sj-hasimgloader');
    jQuery('#thumb-' + file.id).removeClass('sj-uploading');
    jQuery('#thumb-' + file.id + ' .sj-doclist_content').removeClass('sj-doclist_loader');
    jQuery('#thumb-' + file.id).addClass('sj-file-uploaded');
    jQuery('#thumb-' + file.id + ' img').attr('src', attachment.sizes.sj_profile_thumbnail);
    jQuery('#thumb-' + file.id + ' .attachment_url').val(attachment.file);
    jQuery('#thumb-' + file.id + ' .attachment_name').val(attachment.fileName);
    jQuery('#thumb-' + file.id + ' .attachment_size').val(attachment.fileSize);
}

//Avatar upload file
function appendEditionCoverImage(up, file, response) {
    let attachment = response.attachments;

    jQuery('#thumb-' + file.id).removeClass('sj-uploading');
    jQuery('#thumb-' + file.id).removeClass('sj-uploading');
    jQuery('#thumb-' + file.id + ' .sj-doclist_content').removeClass('sj-doclist_loader');
    jQuery('#thumb-' + file.id).addClass('sj-file-uploaded');
    jQuery('#thumb-' + file.id + ' img').attr('src', attachment.sizes.sj_profile_thumbnail);
    jQuery('#thumb-' + file.id + ' .attachment_url').val(attachment.file);
    jQuery('#thumb-' + file.id + ' .attachment_name').val(attachment.fileName);
    jQuery('#thumb-' + file.id + ' .attachment_size').val(attachment.fileSize);
}

// Update artilce cover photo
function updateArticleCover(up, file, response) {
    jQuery('#sj_avatar_processing').removeClass('sj-hasimgloader');
    jQuery('#sj_avatar_processing img').attr('src', response.attachments.sizes.sj_profile_thumbnail);
    jQuery('#sj_avatar_processing input#attachment_src').attr('value', response.attachments.file);
    jQuery('#sj_avatar_processing input#attachment_thumb_src').attr('value', response.attachments.sizes.sj_profile_thumbnail);
    jQuery('#sj_avatar_processing input#attachment_name_src').attr('value', JSON.stringify(response.attachments.fileName));
}

function updatePublisherCover(up, file, response) {
    jQuery('#prs_publisher_cover_processing').removeClass('sj-hasimgloader');
    jQuery('#prs-publisher-cover-thumbnail').attr('src', response.attachments.sizes.sj_profile_thumbnail);
    jQuery('#prs_cover_attachment_src').val(response.attachments.file);
    jQuery('#prs_cover_attachment_thumb_src').val(response.attachments.sizes.sj_profile_thumbnail);
    jQuery('#prs_cover_attachment_name_src').val(JSON.stringify(response.attachments.fileName));
}

function updatePublisherPdf(up, file, response) {
    let attachment = response.attachments;
    jQuery('#prs-publisher-pdf-processing').removeClass('sj-hasimgloader');
    jQuery('#thumb-' + file.id).removeClass('sj-uploading');
    jQuery('#thumb-' + file.id + ' .sj-doclist_content').removeClass('sj-doclist_loader');
    jQuery('#thumb-' + file.id).addClass('sj-file-uploaded');
    if (attachment.sizes && attachment.sizes.sj_profile_thumbnail) {
        jQuery('#thumb-' + file.id + ' img').attr('src', attachment.sizes.sj_profile_thumbnail);
    }
    jQuery('#thumb-' + file.id + ' .attachment_url').val(attachment.file);
    jQuery('#thumb-' + file.id + ' .attachment_name').val(attachment.fileName);
    jQuery('#thumb-' + file.id + ' .attachment_size').val(attachment.fileSize);
    jQuery('#thumb-' + file.id + ' .sj-doclist_title span').text(attachment.fileSize || '');
    jQuery('#prs-publisher-pdf-droparea').addClass('prs-upload-drop--done');
}

// Add edition cover
function addEditionCover(up, file, response) {
    jQuery('#sj_edition_avatar_processing').removeClass('sj-hasimgloader');
    jQuery('#sj_edition_avatar_processing img').attr('src', response.attachments.sizes.sj_profile_thumbnail);
    jQuery('#sj_edition_avatar_processing input#attachment_src').attr('value', response.attachments.file);
    jQuery('#sj_edition_avatar_processing input#attachment_thumb_src').attr('value', response.attachments.sizes.sj_profile_thumbnail);
    jQuery('#sj_edition_avatar_processing input#attachment_name_src').attr('value', JSON.stringify(response.attachments.fileName));
}

//Update edition cover
function updateEditionCover(up, file, response) {
    jQuery('#sj_edition_avatar_processing-edit').removeClass('sj-hasimgloader');
    jQuery('#sj_edition_avatar_processing-edit img').attr('src', response.attachments.sizes.sj_profile_thumbnail);
    jQuery('#sj_edition_avatar_processing-edit input#attachment_src').attr('value', response.attachments.file);
    jQuery('#sj_edition_avatar_processing-edit input#attachment_thumb_src').attr('value', response.attachments.sizes.sj_profile_thumbnail);
    jQuery('#sj_edition_avatar_processing-edit input#attachment_name_src').attr('value', JSON.stringify(response.attachments.fileName));
}