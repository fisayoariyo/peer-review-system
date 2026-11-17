(function ($) {
	var loader_html = '<div class="sj-preloader-section"><div class="sj-preloader-holder"><div class="sj-loader"></div></div></div>';
	'use strict';

	 $(function() {
		// Appearance menu
		if(jQuery('#menu-management').length>0){
			if( wp.template == null ) return;
			var html =  wp.template('prs-menus-settings');
			if(html){
				jQuery('#post-body-content').append( html );
			}
		}

		//import dummy users
		jQuery(document).on('click', '.doc-import-users', function () {
			jQuery.confirm({
				title: admin_scripts_vars.import,
				content: admin_scripts_vars.import_message,
				boxWidth: '500px',
				useBootstrap: false,
				typeAnimated: true,
				closeIcon: function () {
					return false;
				},
				closeIcon: 'aRandomButton',
				buttons: {
					yes: {
						text: admin_scripts_vars.yes,
						action: function () {
							var jc = this;
							jc.showLoading();
							var dataString = 'security=' + admin_scripts_vars.ajax_nonce + '&action=peer_review_system_import_users';
							jQuery.ajax({
								type: "POST",
								url: ajaxurl,
								dataType: "json",
								data: dataString,
								success: function (response) {
									jQuery('#import-users').find('.inportusers').remove();
									stickyAdminAlert(response.title, response.message, { classList: 'success', autoclose: 3000 });
									setTimeout(function () {
										window.location.reload();
									}, 2000);
								}
							});
							return false;
						}
					},
					no: {
						close: {
							text: admin_scripts_vars.close
						}
					}
				}
			});
		});

		/**
		 * veryfy profile
		 */
		jQuery(document).on('click', '.do_verify_user_confirm', function (e) {
			var _this = jQuery(this);
			var _type = _this.data('type');
			var _user_id = _this.data('user_id');
			var _id = _this.data('id');
			var _user_id = _this.data('user_id');
			var dataString = 'security=' + admin_scripts_vars.ajax_nonce + '&type=' + _type + '&id=' + _id + '&user_id=' + _user_id + '&action=peer_review_system_approve_profile';
			let approve_text_title = admin_scripts_vars.approve_account;
			let approve_text = admin_scripts_vars.approve_account;

			if (_type == 'approve') {
				approve_text_title = admin_scripts_vars.approve_account;
				approve_text = admin_scripts_vars.approve_account_message;
			} else {
				approve_text_title = admin_scripts_vars.reject_account;
				approve_text = admin_scripts_vars.reject_account_message;
			}
			jQuery.confirm({
				title: approve_text_title,
				content: approve_text,
				boxWidth: '500px',
				useBootstrap: false,
				typeAnimated: true,
				closeIcon: function () {
					return false;
				},
				closeIcon: 'aRandomButton',
				buttons: {
					yes: {
						text: admin_scripts_vars.yes,
						action: function () {
							if (loader_html) { jQuery('body').append(loader_html); }
							jQuery.ajax({
								type: "POST",
								url: admin_scripts_vars.ajaxurl,
								data: dataString,
								dataType: "json",
								success: function (response) {
									jQuery('.sj-preloader-section').remove();
									if (response.type === 'success') {
										stickyAdminAlert(response.message, response.message_desc, { classList: 'success', autoclose: 2000 });
										setTimeout(function () {
											window.location.reload();
										}, 2000);
									} else {
										jQuery('body').find('.sticky-queue').remove();
										stickyAdminAlert(response.message, response.message_desc, { classList: 'danger', autoclose: 5000 });

									}
								}
							});

						}
					},
					no: {
						close: {
							text: admin_scripts_vars.close
						}
					}
				}
			});
			e.preventDefault();
		});

	});

})(jQuery);

/**
 * 
 * Sticky admin alert
 */
function stickyAdminAlert($title = '', $message = '', data) {
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
			close: { btnClass: 'sj-sticky-alert' }
		}
	});
}