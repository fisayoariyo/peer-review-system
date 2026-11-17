var loader_html = '<div class="sj-preloader"><div class="sj-preloader_holder"><div class="sj-loader"></div></div></div>';
(function ($) {
    "use strict";
    
    $(function () {
        // select2
        jQuery(".sj-selectv").select2({
            width: "165",
            minimumResultsForSearch: Infinity,
        });

        //Select2
        var config = {
            "#prs_revision_status": { allowClear: true },
            "#sj-assign-reviewers": { allowClear: true },
            "#sj-article-categories": { allowClear: true },
            "#sj-article-dropdown": { allowClear: true },
            ".sj-select select": { allowClear: true },
            "#search_article_status": { allowClear: true },
        };        
        for (var selector in config) {
            jQuery(selector).select2(config[selector]);
        }

        //Placeholder input
        jQuery("[data-placeholderinput]").each(function (item) {
            var data_placeholder = jQuery("[data-placeholderinput]")[item];
            var sj_id = jQuery(data_placeholder).attr("id");
            var sj_placeholder = jQuery(data_placeholder).attr(
                "data-placeholderinput"
            );
            jQuery("#" + sj_id).on("select2:open", function (e) {
                jQuery("input.select2-search__field").prop(
                    "placeholder",
                    sj_placeholder
                );
            });
        });

        // Select2 open
        jQuery("select").on("select2:open", function (e) {
            jQuery(".select2-results__options").mCustomScrollbar("destroy");
            setTimeout(function () {
                jQuery(".select2-results__options").mCustomScrollbar();
            }, 0);
        });
 
        // on hover
        jQuery(".prs-navbarbtn.sub-menu-holder").hover(function(e){
            var hovered = $(this).is(":hover");
            e.stopPropagation();
            e.preventDefault();
            if (hovered) {
                jQuery(this).children("ul").stop().slideDown(300);
            } else {
                jQuery(this).children("ul").stop().slideUp(300);
            }
            
        })
        // show more author description
        jQuery(document).on("click", ".show-more", function (e) {
            e.preventDefault();
            jQuery("#author-description-less").addClass("d-none");
            jQuery("#author-description-more").removeClass("d-none");
          
        });
        // show less author description
        jQuery(document).on("click", ".show-less", function (e) {
            e.preventDefault();
            jQuery("#author-description-more").addClass("d-none");
            jQuery("#author-description-less").removeClass("d-none");
        });

        if (jQuery(".sj-widget .sj-category-filter li").length < 5) {
            jQuery(".sj-show_more").hide();
        } else {
            jQuery(".sj-widget .sj-category-filter li:nth-child(n+5)").hide();
        }

        //Article sort by status
        jQuery(document).on('change', '.article-sort-by-status', function (e) {
			let sort_val = jQuery(this).val();
			if (sort_val) {
				var url = window.location.href;
				var url = removeParam("sort_by", url);
				if (url.indexOf('?') > -1) {
					url += '&sort_by=' + sort_val
				} else {
					url += '?sort_by=' + sort_val
				}
				window.location.href = url;
			}
			e.preventDefault();
		});

        //load more sub categories
        jQuery(document).on("click", ".sj-show_more", function (e) {
            let show_more = jQuery(this).data("show_more");
            let show_less = jQuery(this).data("show_less");
            jQuery(this).text($(this).text() == show_less ? show_more : show_less);
            jQuery(this) .closest(".sj-widget") .find(".sj-category-filter li:nth-child(n+6)").slideToggle();
        });

        // search keyword top form
        jQuery(document).on("click", "#sj-search-keyword", function (e) {
            e.preventDefault();
            jQuery("#sj-edition-keyword-search-form").submit();
        });
          // search keyword top form
        jQuery(document).on("click", "#sj-goto-submit", function (e) {
            e.preventDefault();
            jQuery("#sj-goto-page").submit();
        });

        // search keyword top form
        jQuery(document).on("click", ".sj-search-articles-btn", function (e) {
            e.preventDefault();
            jQuery("form#sj-articles-search").submit();
        });

        //articles search listings sort
        jQuery(document).on("change", "#sj-sort-articles", function (e) {
            let sort_val = jQuery(this).val();
            if (sort_val) {
                var url = window.location.href;
                var url = removeParam("sort_by", url);
                if (url.indexOf("?") > -1) {
                url += "&sort_by=" + sort_val;
                } else {
                url += "?sort_by=" + sort_val;
                }
                window.location.href = url;
            }
            e.preventDefault();
        });

        // download attachments
		jQuery(document).on('click', '.sj-article-download-btn', function(e){
			e.preventDefault();
			var _this = jQuery(this);
			var _article_id = _this.data('id');

			if( _article_id == '' || _article_id == 'undefined' || _article_id == null ){
                stickyAlert(scripts_vars.error_title, scripts_vars.message_error, {
                    classList: "danger",
                    autoclose: 3000,
                });
				return false;
			}
			//Send request
			var dataString 	  = 'security='+scripts_vars.ajax_nonce+'&article_id='+_article_id+'&action=peer_review_system_download_zip_file';
			jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: dataString,
				dataType: "json",
				success: function (response) {
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

        /**
         * CHECKOUT
         */
         jQuery(document).on('click', '#sj-article-checkout', function(e) {
            e.preventDefault();
            let this_ = jQuery(this);
            let article_id = this_.data("article_id");
            let userId = this_.data("userid");
            let author_id = this_.data("author_id");

            if( article_id == '' || article_id == 'undefined' || article_id == null ) {
                stickyAlert(scripts_vars.error_title, scripts_vars.message_error, {
                    classList: "danger",
                    autoclose: 3000,
                });
				return false;
			}

            if( userId == '' || userId == 'undefined' || userId == null ) {
                stickyAlert(scripts_vars.error_title, scripts_vars.message_error, {
                    classList: "danger",
                    autoclose: 3000,
                });
				return false;
			}
            
            if( author_id == '' || author_id == 'undefined' || author_id == null ) {
                stickyAlert(scripts_vars.error_title, scripts_vars.message_error, {
                    classList: "danger",
                    autoclose: 3000,
                });
				return false;
			}

            jQuery('body').append(loader_html);
            jQuery.ajax({
                type: "POST",
                url: scripts_vars.ajaxurl,
                data: {
                    'action': 'peer_review_system_article_checkout',
                    'security': scripts_vars.ajax_nonce,
                    'userId': userId,
                    'article_id': article_id,
                    'author_id': author_id,
                },
                dataType: "json",
                success: function(response) {                     
                    jQuery('body').find('.sj-preloader').remove();
                    if (response.type == 'success') {
                        window.location.replace(response.checkout_url);
                    } else {
                        stickyAlert(response.title, response.message, { classList: 'danger', autoclose: 3000 });
                    }
                }
            });
        });

        //articles search listings sort
        jQuery(document).on("click", ".sj-articles-sortorder", function (e) {
            let sort_order = jQuery(this).data("sort_order");
            if (sort_order) {
                var url = window.location.href;
                var url = removeParam("sort_order", url);
                if (url.indexOf("?") > -1) {
                url += "&sort_order=" + sort_order;
                } else {
                url += "?sort_order=" + sort_order;
                }
                window.location.href = url;
            }
            e.preventDefault();
        });
        // close menubar
        jQuery(window).on("click", function() {
            // Close all open windows
            jQuery(".sj-actiondropdown").stop().slideUp(300);
        });

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

        /* tippy */
        function tooltipInitialization(selecter) {
            if (typeof tippy === "function") {
                tippy(selecter, {
                    allowHTML: true,
                    animation: "scale",
                    content(reference) {
                        const id = reference.getAttribute("data-template");
                        const template = document.getElementById(id);
                        return template.innerHTML;
                    },
                });
            }
        }

        /* initialize tooltip */
        tooltipInitialization(".sj-tooltip-tags");

        /**
         * Registration
        */
        jQuery('form.sj-signup-form').on('keydown', 'input', function (e) {
			if (e.keyCode === 13) {
				e.preventDefault();
				jQuery('.sj-submit-registration').trigger('click');
			}
		});

        jQuery(document).on("click", ".sj-submit-registration", function (e) {
            e.preventDefault();
            var _serialize = jQuery("form.sj-signup-form").serialize();
            jQuery('body').append(loader_html);
            jQuery.ajax({
                type: "POST",
                url: scripts_vars.ajaxurl,
                data: {
                action: "peer_review_system_user_register",
                security: scripts_vars.ajax_nonce,
                data: _serialize,
                },
                dataType: "json",
                success: function (response) {
                    jQuery('body').find('.sj-preloader').remove();
                    if (response.type === "success") {
                        stickyAlert(response.title, response.message, {
                            classList: "success",
                            autoclose: 5000,
                        });
                        window.location.replace(response.redirect);
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
         * social login after chooose user type
         */
        jQuery(document).on( "change", 'input[name="choose_usertype"]:radio', function (e) {
            e.preventDefault();
            let userType = jQuery('input[name="choose_usertype"]:checked').val();
            let userDetail = JSON.parse(jQuery("#social-userdetail").val());
            userDetail["user_type"] = userType;
            jQuery('body').append(loader_html);
            jQuery.ajax({
                type: "POST",
                url: scripts_vars.ajaxurl,
                data: {
                    action: "peer_review_system_social_login",
                    security: scripts_vars.ajax_nonce,
                    data: userDetail,
                },
                dataType: "json",
                success: function (response) {
                    jQuery('body').find('.sj-preloader').remove();
                    if (response.type === "success") {
                        stickyAlert(response.title, response.message, {
                            classList: "success",
                            autoclose: 3000,
                        });
                        window.location.replace(response.redirect);
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
         * Login
        */
        jQuery('form.sj-login-form').on('keydown', 'input', function (e) {
			if (e.keyCode === 13) {
				e.preventDefault();
				jQuery('.sj-user-login').trigger('click');
			}
		});

        jQuery(document).on("click", ".sj-user-login", function (e) {
            e.preventDefault();
            var _serialize = jQuery("form.sj-login-form").serialize();
            jQuery('body').append(loader_html);
            jQuery.ajax({
                type: "POST",
                url: scripts_vars.ajaxurl,
                data: {
                    action: "peer_review_system_user_signin",
                    security: scripts_vars.ajax_nonce,
                    data: _serialize,
                },
                dataType: "json",
                success: function (response) {
                    jQuery('body').find('.sj-preloader').remove();
                    if (response.type === "success") {
                        stickyAlert(response.title, response.message, { classList: "success", autoclose: 5000, });
                        var url = response.redirect;
                        window.location = url.replace("&#038;", "&");
                    } else {
                        stickyAlert(response.title, response.message, {
                            classList: "danger",
                            autoclose: 5000,
                        });
                    }
                },
            });
        });

		$('form.sj-forgot-password').on('keydown', 'input', function (e) {
			if (e.key === 'Enter' || e.keyCode === 13) {
				e.preventDefault();
				$('.sj-password-forgot').first().trigger('click');
			}
		});

        /**
         * Forgot Password
         */
        jQuery(document).on("click", ".sj-password-forgot", function (e) {
            e.preventDefault();
            let dataArray = jQuery(".sj-forgot-password").serialize();
            jQuery('body').append(loader_html);
            jQuery.ajax({
                type: "POST",
                url: scripts_vars.ajaxurl,
                data: {
                    action: "peer_review_system_password_reset",
                    security: scripts_vars.ajax_nonce,
                    data: dataArray,
                },
                dataType: "json",
                timeout: 45000,
            })
                .done(function (response) {
                    jQuery('body').find('.sj-preloader').remove();
                    if (response && response.type === "success") {
                        showResetSentModal(
                            response.title || scripts_vars.reset_sent_title || "Check your email",
                            response.message ||
                                scripts_vars.reset_sent_message ||
                                "A password reset link has been sent. Please check your inbox."
                        );
                    } else {
                        stickyAlert(
                            response && response.title ? response.title : scripts_vars.error_title,
                            response && response.message ? response.message : scripts_vars.message_error,
                            { classList: "danger", autoclose: 5000 }
                        );
                    }
                })
                .fail(function () {
                    jQuery('body').find('.sj-preloader').remove();
                    stickyAlert(scripts_vars.error_title, scripts_vars.message_error, {
                        classList: "danger",
                        autoclose: 5000,
                    });
                });
        });

        /**
         * password recover after forgot
         */
        jQuery(document).on("click", ".sj-password-recover", function (e) {
            e.preventDefault();
            let dataArray = jQuery(".sj-password-reset-form").serialize();
            jQuery('body').append(loader_html);
            jQuery.ajax({
                type: "POST",
                url: scripts_vars.ajaxurl,
                data: {
                    action: "peer_review_system_recover_password",
                    security: scripts_vars.ajax_nonce,
                    data: dataArray,
                },
                dataType: "json",
                timeout: 45000,
            })
                .done(function (response) {
                    jQuery('body').find('.sj-preloader').remove();
                    if (response && response.type === "success") {
                        stickyAlert(response.title, response.message, {
                            classList: "success",
                            autoclose: 3000,
                        });
                        if (response.redirect) {
                            window.location.replace(response.redirect);
                        }
                    } else {
                        stickyAlert(
                            response && response.title ? response.title : scripts_vars.error_title,
                            response && response.message ? response.message : scripts_vars.message_error,
                            { classList: "danger", autoclose: 5000 }
                        );
                    }
                })
                .fail(function () {
                    jQuery('body').find('.sj-preloader').remove();
                    stickyAlert(scripts_vars.error_title, scripts_vars.message_error, {
                        classList: "danger",
                        autoclose: 5000,
                    });
                });
        });

        //toggle two classes on mobile menu
        jQuery(".sj-closeicon").on("click", function () {
            jQuery(".sj-aside").toggleClass("sj-opendbmenu");
        });

        //  article description textarea counter
        jQuery(document).on("keyup", "#sj-introduction-description", function (e) {
            sjMaxLengthCounter(e.target.id);
        });

        // remove uploaded attachments
        jQuery(document).on("click", "#sj_delete_attachment", function (e) {
            e.preventDefault();
            let _this = jQuery(this);
            _this.parents("li").remove();
        });

        // remove uploaded attachments
        jQuery(document).on("click", "#sj_delete_edition_attachment", function (e) {
            e.preventDefault();
            let _this = jQuery(this);
            _this.parents("li").remove();
            let record = jQuery("#peer-edtprocessing li");
            if (record.length == 0) {
                jQuery("#peer-edtprocessing").addClass("d-none");
            }
        });       

        /**
         * on change assign reviewer
         * call methods
         */
        jQuery(document).on("change", "#sj-assign-reviewers", function () {
            openSelectCat("assign-reviewers", "#sj-article-assigned-reviewer");
        });

        /**
         * on change article category
         * call methods
         */
        jQuery(document).on("change", "#sj-article-categories", function () {
            openSelectCat("article-categories", "#sj-article-selected-categories");
        });

        /**
         * on change assign article
         * call methods
         */
        jQuery(document).on("change", "#sj-article-dropdown", function () {
            openSelectCat("article-dropdown", "#sj-assign-selected-articles");
        });

        /**
         * on change article category prepend
         * selected categories in ul
         */
        function openSelectCat(name, ul_append_id) {
            jQuery("#sj-" + name).on("select2:select", function (e) {
                var data = e.params.data;
                var html = "";
                let verifySelection = jQuery(ul_append_id).find("#" + data.id);
                if (!verifySelection.length) {
                html = "<li id=" + data.id + "><span>" + data.text;
                html +=
                    '<a href="javascript:void(0);" class="select2-remove-article-cat"><i class="icon-x"></i></a></span>';
                html +=
                    '<input class="sj_selected_items" type="hidden" data-cat_id="' +
                    data.id +
                    '" value="' +
                    data.text +
                    '"></li>';
                jQuery(ul_append_id).prepend(html);
                }
            });
        }

        /**
         * remove selected category
         */
        jQuery(document).on("click", ".select2-remove-article-cat", function (e) {
            e.preventDefault();
            let _this = jQuery(this);
            _this.parents("li").remove();
        });

        //Add more authors
        jQuery(document).on("click", ".sj-deletlist", function (e) {
            $(this).parents(".sj-addslot-list").remove();
            e.preventDefault();
        });

        // remove
        jQuery(".sj-asignreview-tags a").on("click", function () {
            jQuery(this).closest("li").remove();
        });


         // Task add cart button
        jQuery(".btn-checkout").on("click", function () {
  			let edition_id      = jQuery(this).data('edition_id');
			let edition_price   = jQuery(this).data('edition_price');
			let author_id       = jQuery(this).data('author_id');
 			let user_identity   = jQuery(this).data('user_identity');
			let dataString      = 'edition_id='+edition_id + '&edition_price=' + edition_price + '&author_id=' + author_id + '&user_identity=' + user_identity;
            jQuery('body').append(loader_html);
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: {
					'action': 'peer_review_system_checkout',
					'security': scripts_vars.ajax_nonce,
					'data': dataString,
				},
				dataType: "json",
				success: function (response) {
                    jQuery('body').find('.sj-preloader').remove();
					if (response.type === 'success') {
						window.location.replace(response.checkout_url);
					} else {
						if (response.button){
							StickyAlertBtn(response.message, response.message_desc, {classList: 'danger', autoclose: 5000,button:response.button});
						} else {
							StickyAlert(response.message, response.message_desc, {classList: 'danger', autoclose: 5000});
						}
					}
				}
			});
		});

        // Tippy Start
        const sj_industrypro = document.getElementById("sj-moretags");
        if (sj_industrypro !== null) {
            tippy('[data-tippy-html="#sj-showmore"]', {
                content: sj_industrypro.innerHTML,
                allowHTML: true,
                animation: "scale",
            });
        }

        //Menu children
        jQuery(".menu-item-has-children").hover(function () {
            var isHovered = jQuery(this).is(":hover");
            if (isHovered) {
                jQuery(this).children("ul").stop().slideDown();
            } else {
                jQuery(this).children("ul").stop().slideUp();
            }
        });
    });
})(jQuery);

// Confirm before submit
function executeConfirmAjaxRequest(ajax, title = 'Confirm', message = '') {
    jQuery.confirm({
        title: title,
        content: message,
        class: 'blue',
        theme: 'modern',
        animation: 'scale',
        closeIcon: true, // hides the close icon.
        'buttons': {
            'Yes': {
                'btnClass': 'btn-dark sv-yesbtn',
                'action': function () {
                    if (loader_html) {
                        jQuery('body').append(loader_html);
                    }
                    jQuery.ajax(ajax);
                }
            },
            'No': {
                'btnClass': 'btn-default sv-nobtn',
                'action': function () {
                return true;
                }
            },
        }
    });
}
/**
 *
 * Character count
 */
function sjMaxLengthCounter( id = "", currentCharClass = ".sj_current_number",maxCharClass = ".sj_maximum_number") {
    if (id) {
        var maxCharCount = jQuery("#" + id).val().length;
        var currentChar = jQuery("#" + id).parents(".sj-message-text").find(currentCharClass);
        var maximumChar = jQuery("#" + id).parents(".sj-message-text").find(maxCharClass);
        var maxCharLength = jQuery("#" + id).attr("maxlength");

        if (maxCharLength) {
            var changeColor = 0.75 * maxCharLength;
            currentChar.text(maxCharCount);

            if (maxCharCount > changeColor && maxCharCount < maxCharLength) {
                currentChar.css("color", "#FF4500");
                currentChar.css("fontWeight", "bold");
            } else if (maxCharCount >= maxCharLength) {
                currentChar.css("color", "#B22222");
                currentChar.css("fontWeight", "bold");
            } else {
                var char_color = maximumChar.css("color");
                var char_font = maximumChar.css("fontWeight");
                currentChar.css("color", char_color);
                currentChar.css("fontWeight", char_font);
            }
        }
    }
}
/**
 * Success modal for password reset request.
 */
function showResetSentModal(title, message) {
    var existing = document.querySelector(".jase-reset-modal");
    if (existing) {
        existing.remove();
    }

    var closeLabel = scripts_vars.close_text || "OK";
    var overlay = document.createElement("div");
    overlay.className = "jase-reset-modal";
    overlay.setAttribute("role", "dialog");
    overlay.setAttribute("aria-modal", "true");
    overlay.innerHTML =
        '<div class="jase-reset-modal__backdrop" data-jase-reset-modal-close></div>' +
        '<div class="jase-reset-modal__panel">' +
        '<h2 class="jase-reset-modal__title"></h2>' +
        '<p class="jase-reset-modal__message"></p>' +
        '<button type="button" class="jase-reset-modal__btn" data-jase-reset-modal-close></button>' +
        "</div>";

    overlay.querySelector(".jase-reset-modal__title").textContent = title || "";
    overlay.querySelector(".jase-reset-modal__message").textContent = message || "";
    overlay.querySelector(".jase-reset-modal__btn").textContent = closeLabel;
    document.body.appendChild(overlay);
    document.body.classList.add("jase-reset-modal-open");

    function closeModal() {
        overlay.remove();
        document.body.classList.remove("jase-reset-modal-open");
    }

    overlay.querySelectorAll("[data-jase-reset-modal-close]").forEach(function (node) {
        node.addEventListener("click", closeModal);
    });
}

/**
 * Alert the notification
 */
function stickyAlert($title = "", $message = "", data) {
    var $icon = "ti-face-sad";
    var $class = "dark";

    if (data.classList === "success") {
        $icon = "icon icon-check";
        $class = "green";
    } else if (data.classList === "danger") {
        $icon = "icon icon-x";
        $class = "red";
    }

    jQuery.confirm({
        icon: $icon,
        closeIcon: true,
        theme: "modern",
        animation: "scale",
        type: $class, //red, green, dark, orange
        title: $title,
        content: $message,
        autoClose: "close|" + data.autoclose,
        buttons: {
            close: {
                text: scripts_vars.close_text,
                btnClass: "sj-sticky-alert",
            },
        },
    });
}