<?php
/**
 * Publisher article detail and production form.
 *
 * @package Peer_Review_System
 */
global $current_user;
$article_id          = !empty($_GET['article_id']) ? absint($_GET['article_id']) : 0;
$article_title       = get_the_title($article_id);
$publishing_status   = peer_review_system_get_publishing_status($article_id);
$publishing_labels   = peer_review_system_publishing_statuses();
$publishing_label    = !empty($publishing_labels[$publishing_status]) ? $publishing_labels[$publishing_status] : '';
$published_excerpt   = get_post_field('post_excerpt', $article_id);
$is_live             = peer_review_system_is_article_publicly_published($article_id);
$pub_state           = function_exists('peer_review_system_get_post_publication_state') ? peer_review_system_get_post_publication_state($article_id) : '';
$pub_log             = get_post_meta($article_id, 'prs_post_pub_log', true);
$pub_log             = is_array($pub_log) ? $pub_log : array();
$pub_reason          = get_post_meta($article_id, 'prs_post_pub_reason', true);
$production_pdf      = get_post_meta($article_id, 'prs_production_pdf', true);
$production_pdf      = !empty($production_pdf) && is_array($production_pdf) ? $production_pdf : array();
$article_doi         = get_post_meta($article_id, 'prs_article_doi', true);
$citation_volume     = get_post_meta($article_id, 'prs_citation_volume', true);
$citation_issue      = get_post_meta($article_id, 'prs_citation_issue', true);
$citation_pages      = get_post_meta($article_id, 'prs_citation_pages', true);
$citation_year       = get_post_meta($article_id, 'prs_citation_year', true);
$correction_note     = get_post_meta($article_id, 'prs_post_correction_note', true);
$correction_date     = get_post_meta($article_id, 'prs_post_correction_date', true);
$default_avatar      = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/default-avatar.jpg';
$doc_image           = PEER_REVIEW_SYSTEM_DIRECTORY_URI . 'public/images/file.svg';
$cover_thumbnail     = has_post_thumbnail($article_id) ? get_the_post_thumbnail_url($article_id, 'sj_user_profile') : $default_avatar;
$cover_attachment    = '';
$cover_thumb_src     = $cover_thumbnail;
?>
<div class="sj-box-two">
	<div class="sj-box-two_header">
		<?php if (has_post_thumbnail($article_id)) : ?>
			<figure>
				<img src="<?php echo esc_url(get_the_post_thumbnail_url($article_id, 'sj_user_profile')); ?>" alt="<?php echo esc_attr($article_title); ?>">
			</figure>
		<?php endif; ?>
		<div class="sj-box-two-title">
			<?php if (!empty($publishing_label)) { ?>
				<span class="sj-tag sj-approved"><?php echo esc_html($publishing_label); ?></span>
			<?php } ?>
			<?php peer_review_system_get_template('article-loop/article-title.php', array('article_id' => $article_id)); ?>
			<?php peer_review_system_get_template('article-loop/article-info.php', array('article_id' => $article_id)); ?>
		</div>
	</div>
	<div class="sj-box-two_content">
		<h4><?php esc_html_e('Original submission', 'peer-review-system'); ?></h4>
		<?php peer_review_system_get_template('article-loop/article-description.php', array('article_id' => $article_id)); ?>
		<?php peer_review_system_get_template('article-loop/article-documents.php', array('article_id' => $article_id)); ?>
	</div>
</div>

<?php if (!$is_live && $pub_state === 'unpublished') { ?>
	<div class="sj-box-two">
		<div class="sj-box-two_content">
			<h4><?php esc_html_e('Temporarily unpublished', 'peer-review-system'); ?></h4>
			<p><?php esc_html_e('This manuscript has been taken offline and is not visible on the public website. You can republish it at any time.', 'peer-review-system'); ?></p>
			<form id="prs-post-pub-form-republish" class="sj-themeform">
				<input type="hidden" name="prs_article_id" value="<?php echo esc_attr($article_id); ?>">
				<input type="hidden" name="prs_pub_action" value="republish">
				<a href="javascript:void(0);" class="sj-primary-btn prs-post-pub-action" data-form="#prs-post-pub-form-republish"><?php esc_html_e('Republish to website', 'peer-review-system'); ?></a>
			</form>
		</div>
	</div>
<?php } ?>

<?php if (!$is_live) { ?>
	<div class="sj-box-two prs-publisher-production">
		<div class="sj-box-two_content">
			<h4><?php esc_html_e('Upload for publication', 'peer-review-system'); ?></h4>
			<p class="prs-production-intro"><?php esc_html_e('Download the submission files above, format them offline, then upload your finished cover image and PDF here. No in-browser formatting is required.', 'peer-review-system'); ?></p>
			<form class="sj-themeform" id="prs-publisher-production-form">
				<fieldset>
					<div class="form-group-wrap">
						<input type="hidden" name="prs_article_id" value="<?php echo esc_attr($article_id); ?>">
						<div class="form-group prs-production-field">
							<label class="sj-label"><?php esc_html_e('Published title', 'peer-review-system'); ?></label>
							<input type="text" class="form-control" name="published_title" value="<?php echo esc_attr($article_title); ?>">
						</div>
						<div class="form-group prs-production-field">
							<label class="sj-label"><?php esc_html_e('Published excerpt', 'peer-review-system'); ?></label>
							<textarea class="form-control" name="published_excerpt" rows="3"><?php echo esc_textarea($published_excerpt); ?></textarea>
						</div>
						<div class="form-group prs-production-field">
							<label class="sj-label" for="prs_article_doi_input"><?php esc_html_e('DOI (optional)', 'peer-review-system'); ?></label>
							<input type="text" class="form-control" id="prs_article_doi_input" name="prs_article_doi" value="<?php echo esc_attr($article_doi); ?>" placeholder="10.xxxx/xxxxx">
							<p class="prs-field-help"><?php esc_html_e('Enter just the DOI (e.g. 10.1234/jasetui.2026.001) or paste a doi.org link. Google Scholar weights DOIs heavily; this is saved automatically when you save or publish.', 'peer-review-system'); ?></p>
						</div>
						<div class="form-group prs-production-field">
							<label class="sj-label"><?php esc_html_e('Citation details (Google Scholar)', 'peer-review-system'); ?></label>
							<p class="prs-field-help"><?php esc_html_e('Optional. These appear on the website as author, volume, issue, pages, and year. Existing articles keep working if you leave a field blank.', 'peer-review-system'); ?></p>
							<div class="prs-citation-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:12px;">
								<div>
									<label class="sj-label" for="prs_citation_volume"><?php esc_html_e('Volume', 'peer-review-system'); ?></label>
									<input type="text" class="form-control" id="prs_citation_volume" name="prs_citation_volume" value="<?php echo esc_attr($citation_volume); ?>" placeholder="1">
								</div>
								<div>
									<label class="sj-label" for="prs_citation_issue"><?php esc_html_e('Issue', 'peer-review-system'); ?></label>
									<input type="text" class="form-control" id="prs_citation_issue" name="prs_citation_issue" value="<?php echo esc_attr($citation_issue); ?>" placeholder="1">
								</div>
								<div>
									<label class="sj-label" for="prs_citation_pages"><?php esc_html_e('Pages', 'peer-review-system'); ?></label>
									<input type="text" class="form-control" id="prs_citation_pages" name="prs_citation_pages" value="<?php echo esc_attr($citation_pages); ?>" placeholder="12-24">
								</div>
								<div>
									<label class="sj-label" for="prs_citation_year"><?php esc_html_e('Year', 'peer-review-system'); ?></label>
									<input type="text" class="form-control" id="prs_citation_year" name="prs_citation_year" value="<?php echo esc_attr($citation_year); ?>" placeholder="2026">
								</div>
							</div>
						</div>
		<div class="form-group prs-production-field">
							<div class="prs-field-head">
								<label class="sj-label"><?php esc_html_e('Cover image', 'peer-review-system'); ?></label>
								<p class="prs-field-help"><?php esc_html_e('Upload the cover or front-page image for this article.', 'peer-review-system'); ?></p>
							</div>
							<div class="sj-uploadphoto prs-upload-drop" id="prs-publisher-cover">
								<div class="sj-uploaddesc">
									<div id="prs_publisher_cover_processing" class="sj-profile-img">
										<img id="prs-publisher-cover-thumbnail" src="<?php echo esc_url($cover_thumbnail); ?>" alt="<?php esc_attr_e('Cover image', 'peer-review-system'); ?>">
										<input type="hidden" name="cover_attachment" id="prs_cover_attachment_src" value="<?php echo esc_attr($cover_attachment); ?>">
										<input type="hidden" name="cover_attachment_thumbnail" id="prs_cover_attachment_thumb_src" value="<?php echo esc_attr($cover_thumb_src); ?>">
										<input type="hidden" name="cover_attachment_name" id="prs_cover_attachment_name_src" value="">
									</div>
									<h5 id="prs_publisher_cover_droparea"><?php esc_html_e('Drag or ', 'peer-review-system'); ?><input type="file" name="uploadPublisherCover" id="prs_publisher_cover_image" accept="image/*"><label for="prs_publisher_cover_image"><?php esc_html_e('click here', 'peer-review-system'); ?></label> <?php esc_html_e('to upload cover image', 'peer-review-system'); ?></h5>
									<p class="prs-upload-hint"><?php echo esc_html(sprintf(__('JPG, PNG or GIF. Maximum file size %s.', 'peer-review-system'), peer_review_system_max_upload_size_label())); ?></p>
								</div>
								<svg>
									<rect width="100%" height="100%"></rect>
								</svg>
							</div>
						</div>
						<div class="form-group prs-production-field">
							<div class="prs-field-head">
								<label class="sj-label"><?php esc_html_e('Formatted article (PDF)', 'peer-review-system'); ?></label>
								<p class="prs-field-help"><?php esc_html_e('Upload the production-ready PDF after editing offline.', 'peer-review-system'); ?></p>
							</div>
							<div id="prs-publisher-pdf-upload" class="peer-fileuploader sj-uploadarea prs-upload-block">
								<div id="prs-publisher-pdf-droparea" class="prs-upload-drop">
									<div class="sj-uploadphoto">
										<div class="sj-uploaddesc">
											<h5><?php esc_html_e('Drag or ', 'peer-review-system'); ?><input type="file" id="prs_publisher_pdf_input" accept="application/pdf,.pdf"><label for="prs_publisher_pdf_input"><?php esc_html_e('click here', 'peer-review-system'); ?></label> <?php esc_html_e('to upload PDF', 'peer-review-system'); ?></h5>
											<p class="prs-upload-hint"><?php echo esc_html(sprintf(__('PDF only. Maximum file size %s.', 'peer-review-system'), peer_review_system_max_upload_size_label())); ?></p>
										</div>
										<svg>
											<rect width="100%" height="100%"></rect>
										</svg>
									</div>
								</div>
								<ul id="prs-publisher-pdf-processing" class="sj-docupload peer-fileprocessing prs-upload-list">
										<?php if (!empty($production_pdf['attachment_url'])) { ?>
											<li class="article-attachments sj-file-uploaded">
												<div class="sj-doclist_content">
													<img src="<?php echo esc_url($doc_image); ?>" alt="<?php echo esc_attr($production_pdf['attachment_name']); ?>">
													<div class="sj-doclist_title">
														<h6><?php echo esc_html($production_pdf['attachment_name']); ?></h6>
														<input type="hidden" class="attachment_url" name="production_pdf[file]" value="<?php echo esc_url($production_pdf['attachment_url']); ?>">
														<input type="hidden" class="attachment_name" name="production_pdf[fileName]" value="<?php echo esc_attr($production_pdf['attachment_name']); ?>">
														<input type="hidden" class="attachment_size" name="production_pdf[fileSize]" value="<?php echo esc_attr($production_pdf['attachment_size']); ?>">
														<span><?php echo esc_html($production_pdf['attachment_size']); ?></span>
													</div>
													<a href="javascript:void(0);" id="sj_delete_attachment"><i class="icon-trash-2"></i></a>
												</div>
											</li>
										<?php } ?>
								</ul>
							</div>
						</div>
						<div class="form-group prs-production-actions">
							<p class="prs-actions-label"><?php esc_html_e('When your files are ready', 'peer-review-system'); ?></p>
							<div class="prs-production-actions__inner">
								<a href="javascript:void(0);" class="sj-secondery-btn" id="prs-save-production-manuscript"><?php esc_html_e('Save production draft', 'peer-review-system'); ?></a>
								<?php if ($publishing_status === 'awaiting_production') { ?>
									<a href="javascript:void(0);" class="sj-secondery-btn prs-publisher-action" data-action="start_production"><?php esc_html_e('Start production', 'peer-review-system'); ?></a>
								<?php } ?>
								<a href="javascript:void(0);" class="sj-primary-btn prs-publisher-action" data-action="publish_to_website"><?php esc_html_e('Publish to website', 'peer-review-system'); ?></a>
							</div>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
	<script type="text/template" id="tmpl-prs-publisher-pdf-attachment">
		<li id="thumb-{{data.id}}">
			<div class="sj-doclist_content sj-doclist_loader">
				<img src="<?php echo esc_url($doc_image); ?>" alt="<?php esc_attr_e('Document', 'peer-review-system'); ?>">
				<div class="sj-doclist_title">
					<h6>{{data.name}}</h6>
					<input type="hidden" class="attachment_url" name="production_pdf[file]" value="{{data.file}}">
					<input type="hidden" class="attachment_name" name="production_pdf[fileName]" value="{{data.fileName}}">
					<input type="hidden" class="attachment_size" name="production_pdf[fileSize]" value="{{data.fileSize}}">
					<span>{{data.size}}</span>
				</div>
				<a href="javascript:void(0);" id="sj_delete_attachment"><i class="icon-trash-2"></i></a>
			</div>
		</li>
	</script>
	<style>
		.prs-publisher-production .sj-box-two_content > h4 {
			margin-bottom: 12px;
		}
		.prs-publisher-production .prs-production-intro {
			margin: 0 0 32px;
			color: #6b7280;
			font-size: 15px;
			line-height: 1.6;
			max-width: 720px;
		}
		.prs-publisher-production .prs-production-field {
			display: block;
			width: 100%;
			padding: 0;
			margin-bottom: 36px;
		}
		.prs-publisher-production .prs-production-field .form-control {
			margin-top: 4px;
		}
		.prs-publisher-production .prs-field-head {
			display: block;
			margin-bottom: 16px;
		}
		.prs-publisher-production .prs-field-head .sj-label {
			display: block;
			margin: 0 0 8px;
			font-size: 16px;
			line-height: 1.4;
		}
		.prs-publisher-production .prs-field-help,
		.prs-publisher-production .prs-upload-hint {
			display: block;
			margin: 0;
			color: #6b7280;
			font-size: 14px;
			line-height: 1.5;
			font-weight: 400;
		}
		.prs-publisher-production .prs-upload-drop {
			width: 100%;
			margin-top: 4px;
		}
		.prs-publisher-production .prs-upload-drop--done {
			opacity: 0.72;
		}
		.prs-publisher-production .prs-upload-block {
			display: block;
			width: 100%;
		}
		.prs-publisher-production .prs-upload-list {
			margin: 16px 0 0;
			padding: 0;
			width: 100%;
		}
		.prs-publisher-production .prs-upload-list:empty {
			display: none;
		}
		.prs-publisher-production .prs-upload-list .sj-doclist_content {
			margin-bottom: 0;
		}
		.prs-publisher-production .sj-uploadphoto {
			padding: 28px 24px;
		}
		.prs-publisher-production .sj-uploadphoto h5 {
			margin: 12px 0 8px;
			line-height: 1.5;
		}
		.prs-publisher-production .prs-production-actions {
			display: block;
			width: 100%;
			padding: 28px 0 8px;
			margin-top: 8px;
			border-top: 1px solid #e5e7eb;
		}
		.prs-publisher-production .prs-actions-label {
			margin: 0 0 16px;
			font-size: 14px;
			font-weight: 600;
			color: #374151;
		}
		.prs-publisher-production .prs-production-actions__inner {
			display: flex;
			flex-wrap: wrap;
			gap: 14px;
			align-items: center;
		}
		.prs-publisher-production .prs-production-actions__inner .sj-secondery-btn,
		.prs-publisher-production .prs-production-actions__inner .sj-primary-btn {
			margin: 0;
		}
		.prs-publisher-production .prs-production-actions__inner .sj-primary-btn {
			margin-left: auto;
		}
		@media (max-width: 767px) {
			.prs-publisher-production .prs-production-actions__inner .sj-primary-btn {
				margin-left: 0;
				width: 100%;
				text-align: center;
			}
		}
	</style>
<?php } else { ?>
	<div class="sj-box-two">
		<div class="sj-box-two_content">
			<h4><?php esc_html_e('Live on website', 'peer-review-system'); ?></h4>
			<?php if ($pub_state === 'retracted') { ?>
				<p class="prs-state-flag prs-state-flag--retracted"><strong><?php esc_html_e('Status: Retracted', 'peer-review-system'); ?></strong> — <?php esc_html_e('a retraction notice is shown on the public page.', 'peer-review-system'); ?></p>
			<?php } elseif ($pub_state === 'withdrawn') { ?>
				<p class="prs-state-flag prs-state-flag--withdrawn"><strong><?php esc_html_e('Status: Withdrawn', 'peer-review-system'); ?></strong> — <?php esc_html_e('the content is replaced by a withdrawal notice on the public page.', 'peer-review-system'); ?></p>
			<?php } ?>
			<p><a href="<?php echo esc_url(get_permalink($article_id)); ?>" target="_blank" rel="noopener"><?php esc_html_e('View public page', 'peer-review-system'); ?></a></p>

			<form id="prs-article-doi-form" class="sj-themeform" style="margin-top:20px;">
				<input type="hidden" name="prs_article_id" value="<?php echo esc_attr($article_id); ?>">
				<div class="form-group prs-production-field">
					<label class="sj-label" for="prs_article_doi_live"><?php esc_html_e('DOI', 'peer-review-system'); ?></label>
					<input type="text" class="form-control" id="prs_article_doi_live" name="prs_article_doi" value="<?php echo esc_attr($article_doi); ?>" placeholder="10.xxxx/xxxxx">
					<p class="prs-field-help" style="color:#6b7280;font-size:13px;margin-top:6px;"><?php esc_html_e('Add or update the DOI for this published article. It is added to the Google Scholar citation tags and structured data automatically.', 'peer-review-system'); ?></p>
				</div>
				<div class="form-group prs-production-field">
					<label class="sj-label"><?php esc_html_e('Volume, issue, pages, year', 'peer-review-system'); ?></label>
					<div class="prs-citation-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:12px;">
						<div>
							<label class="sj-label" for="prs_citation_volume_live"><?php esc_html_e('Volume', 'peer-review-system'); ?></label>
							<input type="text" class="form-control" id="prs_citation_volume_live" name="prs_citation_volume" value="<?php echo esc_attr($citation_volume); ?>" placeholder="1">
						</div>
						<div>
							<label class="sj-label" for="prs_citation_issue_live"><?php esc_html_e('Issue', 'peer-review-system'); ?></label>
							<input type="text" class="form-control" id="prs_citation_issue_live" name="prs_citation_issue" value="<?php echo esc_attr($citation_issue); ?>" placeholder="1">
						</div>
						<div>
							<label class="sj-label" for="prs_citation_pages_live"><?php esc_html_e('Pages', 'peer-review-system'); ?></label>
							<input type="text" class="form-control" id="prs_citation_pages_live" name="prs_citation_pages" value="<?php echo esc_attr($citation_pages); ?>" placeholder="12-24">
						</div>
						<div>
							<label class="sj-label" for="prs_citation_year_live"><?php esc_html_e('Year', 'peer-review-system'); ?></label>
							<input type="text" class="form-control" id="prs_citation_year_live" name="prs_citation_year" value="<?php echo esc_attr($citation_year); ?>" placeholder="2026">
						</div>
					</div>
				</div>
				<a href="javascript:void(0);" class="sj-secondery-btn" id="prs-save-article-doi"><?php esc_html_e('Save citation details', 'peer-review-system'); ?></a>
			</form>
			<script>
			(function ($) {
				$(document).on('click', '#prs-save-article-doi', function (e) {
					e.preventDefault();
					var $btn = $(this);
					var dataArray = $('#prs-article-doi-form').serialize();
					$('body').append(typeof loader_html !== 'undefined' ? loader_html : '');
					$.ajax({
						type: 'POST',
						url: scripts_vars.ajaxurl,
						data: {
							action: 'peer_review_system_save_article_doi',
							security: scripts_vars.ajax_nonce,
							data: dataArray
						},
						dataType: 'json',
						success: function (response) {
							$('body').find('.sj-preloader').remove();
							if (typeof stickyAlert === 'function') {
								stickyAlert(response.title, response.message, {
									classList: response.type === 'success' ? 'success' : 'error',
									autoclose: 5000
								});
							}
						},
						error: function () {
							$('body').find('.sj-preloader').remove();
						}
					});
				});
			})(jQuery);
			</script>
		</div>
	</div>

	<div class="sj-box-two prs-post-pub">
		<div class="sj-box-two_content">
			<h4><?php esc_html_e('Post-publication management', 'peer-review-system'); ?></h4>
			<p class="prs-post-pub__intro"><?php esc_html_e('Manage this published manuscript in line with international publishing standards. Retraction and withdrawal display a public notice; every action is recorded in the audit trail below.', 'peer-review-system'); ?></p>

			<form id="prs-post-pub-form" class="sj-themeform">
				<input type="hidden" name="prs_article_id" value="<?php echo esc_attr($article_id); ?>">
				<div class="form-group prs-production-field">
					<label class="sj-label" for="prs_pub_reason"><?php esc_html_e('Reason / notice text', 'peer-review-system'); ?></label>
					<textarea class="form-control" id="prs_pub_reason" name="prs_pub_reason" rows="3" placeholder="<?php esc_attr_e('e.g. plagiarism, duplicate publication, data error, copyright infringement, ethical breach…', 'peer-review-system'); ?>"><?php echo esc_textarea($pub_reason); ?></textarea>
					<p class="prs-field-help"><?php esc_html_e('Required for retraction, withdrawal and permanent removal.', 'peer-review-system'); ?></p>
				</div>

				<div class="prs-post-pub__actions" style="display:flex;flex-wrap:wrap;gap:12px;margin-top:12px;">
					<a href="javascript:void(0);" class="sj-secondery-btn prs-post-pub-action" data-action-label="unpublish" data-confirm="<?php esc_attr_e('Temporarily remove this article from the public website?', 'peer-review-system'); ?>" data-action="unpublish"><?php esc_html_e('Unpublish (temporary)', 'peer-review-system'); ?></a>

					<?php if ($pub_state === 'retracted') { ?>
						<a href="javascript:void(0);" class="sj-secondery-btn prs-post-pub-action" data-confirm="<?php esc_attr_e('Remove the retraction notice and restore the article to normal?', 'peer-review-system'); ?>" data-action="clear_retraction"><?php esc_html_e('Clear retraction', 'peer-review-system'); ?></a>
					<?php } else { ?>
						<a href="javascript:void(0);" class="sj-secondery-btn prs-post-pub-action" data-confirm="<?php esc_attr_e('Mark this article as retracted and show a public retraction notice?', 'peer-review-system'); ?>" data-action="retract"><?php esc_html_e('Retract', 'peer-review-system'); ?></a>
					<?php } ?>

					<a href="javascript:void(0);" class="sj-secondery-btn prs-post-pub-action" data-confirm="<?php esc_attr_e('Withdraw this article? The content will be replaced by a withdrawal notice.', 'peer-review-system'); ?>" data-action="withdraw"><?php esc_html_e('Withdraw', 'peer-review-system'); ?></a>

					<a href="javascript:void(0);" class="sj-delete-btn prs-post-pub-action" style="color:#b91c1c;" data-confirm="<?php esc_attr_e('Permanently remove this article from the website? This moves it to trash.', 'peer-review-system'); ?>" data-action="remove"><?php esc_html_e('Remove permanently', 'peer-review-system'); ?></a>
				</div>
			</form>

			<?php if (!empty($pub_log)) { ?>
				<div class="prs-post-pub__log" style="margin-top:24px;">
					<h5><?php esc_html_e('Audit trail', 'peer-review-system'); ?></h5>
					<ul style="margin:8px 0 0;padding-left:18px;">
						<?php
						$state_labels = function_exists('peer_review_system_post_publication_states') ? peer_review_system_post_publication_states() : array();
						foreach (array_reverse($pub_log) as $entry) {
							$action_key   = !empty($entry['action']) ? $entry['action'] : '';
							$action_label = isset($state_labels[$action_key]) ? $state_labels[$action_key] : ucwords(str_replace('_', ' ', $action_key));
							?>
							<li style="margin-bottom:6px;">
								<strong><?php echo esc_html($action_label); ?></strong>
								<?php if (!empty($entry['time'])) { ?>
									<span> — <?php echo esc_html(mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $entry['time'])); ?></span>
								<?php } ?>
								<?php if (!empty($entry['user_name'])) { ?>
									<span> · <?php echo esc_html($entry['user_name']); ?></span>
								<?php } ?>
								<?php if (!empty($entry['reason'])) { ?>
									<div style="font-size:13px;color:#4b5563;"><?php echo esc_html($entry['reason']); ?></div>
								<?php } ?>
							</li>
						<?php } ?>
					</ul>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="sj-box-two prs-correction">
		<div class="sj-box-two_content">
			<h4><?php esc_html_e('Issue a correction', 'peer-review-system'); ?></h4>
			<p class="prs-post-pub__intro"><?php esc_html_e('Publish a correction / erratum without taking the article offline. The article stays live and a dated "Corrected on…" notice is shown publicly above the content. To replace the actual PDF file, unpublish the article, upload the corrected PDF, then republish.', 'peer-review-system'); ?></p>

			<?php if (!empty($correction_note)) { ?>
				<p class="prs-state-flag prs-state-flag--correction" style="background:#eff6ff;border:1px solid #1d4ed8;color:#1e3a8a;padding:10px 12px;border-radius:6px;">
					<strong><?php esc_html_e('Current correction notice:', 'peer-review-system'); ?></strong>
					<?php if (!empty($correction_date)) { ?>
						<span> (<?php echo esc_html(mysql2date(get_option('date_format'), $correction_date)); ?>)</span>
					<?php } ?>
					<br><?php echo esc_html($correction_note); ?>
				</p>
			<?php } ?>

			<form id="prs-correction-form" class="sj-themeform">
				<input type="hidden" name="prs_article_id" value="<?php echo esc_attr($article_id); ?>">
				<div class="form-group prs-production-field">
					<label class="sj-label" for="prs_correction_note"><?php esc_html_e('Correction notice text', 'peer-review-system'); ?></label>
					<textarea class="form-control" id="prs_correction_note" name="prs_pub_reason" rows="3" placeholder="<?php esc_attr_e('e.g. The affiliation of the second author was corrected; Figure 2 was replaced with the correct version.', 'peer-review-system'); ?>"><?php echo esc_textarea($correction_note); ?></textarea>
				</div>
				<div class="prs-post-pub__actions" style="display:flex;flex-wrap:wrap;gap:12px;margin-top:12px;">
					<a href="javascript:void(0);" class="sj-primary-btn prs-post-pub-action" data-form="#prs-correction-form" data-confirm="<?php esc_attr_e('Publish this correction notice on the public page?', 'peer-review-system'); ?>" data-action="correct"><?php echo !empty($correction_note) ? esc_html__('Update correction', 'peer-review-system') : esc_html__('Publish correction', 'peer-review-system'); ?></a>
					<?php if (!empty($correction_note)) { ?>
						<a href="javascript:void(0);" class="sj-secondery-btn prs-post-pub-action" data-form="#prs-correction-form" data-confirm="<?php esc_attr_e('Remove the correction notice from the public page?', 'peer-review-system'); ?>" data-action="clear_correction"><?php esc_html_e('Clear correction', 'peer-review-system'); ?></a>
					<?php } ?>
				</div>
			</form>
		</div>
	</div>
<?php } ?>
