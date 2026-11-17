<?php
/**
 * Publishing workflow helpers.
 *
 * @package Peer_Review_System
 */

if (!function_exists('peer_review_system_publishing_statuses')) {
	function peer_review_system_publishing_statuses()
	{
		return array(
			'awaiting_production' => esc_html__('Awaiting production', 'peer-review-system'),
			'in_production'       => esc_html__('In production', 'peer-review-system'),
			'published'           => esc_html__('Published on site', 'peer-review-system'),
		);
	}
}

if (!function_exists('peer_review_system_get_publishing_status')) {
	function peer_review_system_get_publishing_status($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return '';
		}

		$status = get_post_meta($article_id, 'prs_publishing_status', true);
		if (!empty($status)) {
			return $status;
		}

		if (get_post_status($article_id) === 'publish') {
			return 'published';
		}

		if (get_post_meta($article_id, 'prs_article_editor_status', true) === 'approved') {
			return 'awaiting_production';
		}

		return '';
	}
}

if (!function_exists('peer_review_system_is_article_publicly_published')) {
	function peer_review_system_is_article_publicly_published($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return false;
		}

		return get_post_status($article_id) === 'publish'
			&& peer_review_system_get_publishing_status($article_id) === 'published';
	}
}

if (!function_exists('peer_review_system_public_article_meta_query')) {
	function peer_review_system_public_article_meta_query()
	{
		return array(
			array(
				'key'     => 'prs_publishing_status',
				'value'   => 'published',
				'compare' => '=',
			),
		);
	}
}

if (!function_exists('peer_review_system_count_publishing_articles')) {
	function peer_review_system_count_publishing_articles($publishing_status = 'awaiting_production')
	{
		$meta_query = array(
			array(
				'key'     => 'prs_article_editor_status',
				'value'   => 'approved',
				'compare' => '=',
			),
		);

		if ($publishing_status === 'published') {
			$meta_query[] = array(
				'key'     => 'prs_publishing_status',
				'value'   => 'published',
				'compare' => '=',
			);
			$post_status = 'publish';
		} elseif ($publishing_status === 'in_production') {
			$meta_query[] = array(
				'key'     => 'prs_publishing_status',
				'value'   => 'in_production',
				'compare' => '=',
			);
			$post_status = 'pending';
		} else {
			$meta_query[] = array(
				'relation' => 'OR',
				array(
					'key'     => 'prs_publishing_status',
					'value'   => 'awaiting_production',
					'compare' => '=',
				),
				array(
					'key'     => 'prs_publishing_status',
					'compare' => 'NOT EXISTS',
				),
			);
			$post_status = 'pending';
		}

		$query = new WP_Query(
			array(
				'post_type'      => 'articles',
				'post_status'    => $post_status,
				'posts_per_page' => 1,
				'fields'         => 'ids',
				'meta_query'     => $meta_query,
			)
		);

		return (int) $query->found_posts;
	}
}

if (!function_exists('peer_review_system_get_production_pdf')) {
	function peer_review_system_get_production_pdf($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return array();
		}

		$production_pdf = get_post_meta($article_id, 'prs_production_pdf', true);
		return !empty($production_pdf) && is_array($production_pdf) ? $production_pdf : array();
	}
}

if (!function_exists('peer_review_system_save_production_uploads')) {
	function peer_review_system_save_production_uploads($article_id = 0, $data = array())
	{
		$article_id = absint($article_id);
		if (empty($article_id) || empty($data) || !is_array($data)) {
			return;
		}

		$cover_attachment_url = !empty($data['cover_attachment']) ? esc_url_raw($data['cover_attachment']) : '';
		if (!empty($cover_attachment_url)) {
			$response = peer_review_system_temp_upload_to_media($cover_attachment_url, $article_id);
			if (!empty($response['attachment_id'])) {
				update_post_meta($article_id, '_thumbnail_id', (int) $response['attachment_id']);
			}
		}

		if (empty($data['production_pdf']) || !is_array($data['production_pdf'])) {
			return;
		}

		$pdf_file = !empty($data['production_pdf']['file']) ? esc_url_raw($data['production_pdf']['file']) : '';
		if (empty($pdf_file)) {
			return;
		}

		$pdf_name = !empty($data['production_pdf']['fileName']) ? sanitize_text_field($data['production_pdf']['fileName']) : basename(wp_parse_url($pdf_file, PHP_URL_PATH));
		$pdf_size = !empty($data['production_pdf']['fileSize']) ? sanitize_text_field($data['production_pdf']['fileSize']) : '';

		if (strstr($pdf_file, 'peer-review-system-temp')) {
			$params   = array(
				'articleId' => $article_id,
				'file'      => $pdf_name,
			);
			$response = Peer_Review_System_file_permission::moveTempToUploaded($params);

			if (!empty($response['file'])) {
				update_post_meta(
					$article_id,
					'prs_production_pdf',
					array(
						'attachment_url'  => $response['file'],
						'attachment_name' => !empty($response['fileName']) ? $response['fileName'] : $pdf_name,
						'attachment_size' => $pdf_size,
					)
				);
			}

			return;
		}

		update_post_meta(
			$article_id,
			'prs_production_pdf',
			array(
				'attachment_url'  => $pdf_file,
				'attachment_name' => $pdf_name,
				'attachment_size' => $pdf_size,
			)
		);
	}
}

if (!function_exists('peer_review_system_get_public_download_url')) {
	function peer_review_system_get_public_download_url($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return '';
		}

		$production_pdf = peer_review_system_get_production_pdf($article_id);
		if (!empty($production_pdf['attachment_url'])) {
			return esc_url_raw($production_pdf['attachment_url']);
		}

		if (function_exists('jase_peer_review_get_article_attachments')) {
			$attachments = jase_peer_review_get_article_attachments($article_id);
			if (!empty($attachments[0]['attachment_url'])) {
				return esc_url_raw($attachments[0]['attachment_url']);
			}
		} else {
			$attachments = get_post_meta($article_id, 'attachments', true);
			if (!empty($attachments) && is_array($attachments) && !empty($attachments[0]['attachment_url'])) {
				return esc_url_raw($attachments[0]['attachment_url']);
			}
		}

		if ('download' === get_post_meta($article_id, 'sj_paid_article', true)) {
			$external_url = get_post_meta($article_id, 'article_download_url', true);
			if (is_string($external_url) && $external_url) {
				return esc_url_raw($external_url);
			}
		}

		return '';
	}
}

if (!function_exists('peer_review_system_get_public_download_count')) {
	function peer_review_system_get_public_download_count($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return 0;
		}

		$count = 0;
		if (!empty(peer_review_system_get_production_pdf($article_id)['attachment_url'])) {
			++$count;
		}

		if (function_exists('jase_peer_review_get_article_attachments')) {
			$count += count(jase_peer_review_get_article_attachments($article_id));
		} else {
			$attachments = get_post_meta($article_id, 'attachments', true);
			if (is_array($attachments)) {
				$count += count($attachments);
			}
		}

		if ($count > 0) {
			return $count;
		}

		return peer_review_system_get_public_download_url($article_id) ? 1 : 0;
	}
}

if (!function_exists('peer_review_system_get_article_display_content')) {
	function peer_review_system_get_article_display_content($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return '';
		}

		$notice_html = peer_review_system_get_post_publication_notice_html($article_id);
		$pub_state   = peer_review_system_get_post_publication_state($article_id);

		/* A withdrawn article shows only the withdrawal/removal notice (tombstone). */
		if (in_array($pub_state, array('withdrawn', 'removed'), true)) {
			return $notice_html;
		}

		/* A correction / erratum notice is additive: it appears above the content
		   alongside any retraction notice, but never on a withdrawn tombstone. */
		$notice_html = peer_review_system_get_correction_notice_html($article_id) . $notice_html;

		if (peer_review_system_is_article_publicly_published($article_id)) {
			$production_pdf = peer_review_system_get_production_pdf($article_id);
			if (!empty($production_pdf['attachment_url'])) {
				$pdf_url  = esc_url($production_pdf['attachment_url']);
				$pdf_name = !empty($production_pdf['attachment_name']) ? esc_attr($production_pdf['attachment_name']) : esc_attr__('Article PDF', 'peer-review-system');
				$html     = '<div class="prs-article-pdf-viewer">';
				$html    .= '<iframe src="' . $pdf_url . '" title="' . $pdf_name . '" width="100%" height="800" style="border:0;" loading="lazy"></iframe>';
				$html    .= '<p class="prs-pdf-download"><a class="sj-secondery-btn" href="' . $pdf_url . '" download>' . esc_html__('Download PDF', 'peer-review-system') . '<i class="icon-download"></i></a></p>';
				$html    .= '</div>';

				return $notice_html . apply_filters('peer_review_system_article_pdf_content', $html, $article_id, $production_pdf);
			}

			$published_content = get_post_meta($article_id, 'prs_published_content', true);
			if (!empty($published_content)) {
				return $notice_html . apply_filters('the_content', $published_content);
			}
		}

		return $notice_html . apply_filters('the_content', get_post_field('post_content', $article_id));
	}
}

/**
 * Post-publication states (COPE-aligned).
 *
 * @return array<string,string>
 */
if (!function_exists('peer_review_system_post_publication_states')) {
	function peer_review_system_post_publication_states()
	{
		return array(
			'retracted'   => esc_html__('Retracted', 'peer-review-system'),
			'withdrawn'   => esc_html__('Withdrawn', 'peer-review-system'),
			'unpublished' => esc_html__('Unpublished (temporarily offline)', 'peer-review-system'),
			'removed'     => esc_html__('Removed', 'peer-review-system'),
		);
	}
}

/**
 * Current post-publication state of an article ('' when none).
 */
if (!function_exists('peer_review_system_get_post_publication_state')) {
	function peer_review_system_get_post_publication_state($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return '';
		}
		$state = get_post_meta($article_id, 'prs_post_pub_state', true);
		return is_string($state) ? $state : '';
	}
}

/**
 * Append an entry to an article's post-publication audit trail.
 */
if (!function_exists('peer_review_system_log_post_publication_action')) {
	function peer_review_system_log_post_publication_action($article_id, $action, $reason = '', $user_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return;
		}

		$log = get_post_meta($article_id, 'prs_post_pub_log', true);
		$log = is_array($log) ? $log : array();

		$user_id   = $user_id ? absint($user_id) : get_current_user_id();
		$user_data = $user_id ? get_userdata($user_id) : false;

		$log[] = array(
			'action'    => sanitize_key($action),
			'reason'    => sanitize_textarea_field($reason),
			'user_id'   => $user_id,
			'user_name' => $user_data ? $user_data->display_name : '',
			'time'      => current_time('mysql'),
		);

		update_post_meta($article_id, 'prs_post_pub_log', $log);
	}
}

/**
 * The reason / date stored for the latest retraction or withdrawal.
 */
if (!function_exists('peer_review_system_get_post_publication_notice_data')) {
	function peer_review_system_get_post_publication_notice_data($article_id = 0)
	{
		$article_id = absint($article_id);
		$state      = peer_review_system_get_post_publication_state($article_id);
		if (empty($state)) {
			return array();
		}

		return array(
			'state'  => $state,
			'reason' => get_post_meta($article_id, 'prs_post_pub_reason', true),
			'date'   => get_post_meta($article_id, 'prs_post_pub_date', true),
		);
	}
}

/**
 * Public-facing retraction / withdrawal notice HTML.
 */
if (!function_exists('peer_review_system_get_post_publication_notice_html')) {
	function peer_review_system_get_post_publication_notice_html($article_id = 0)
	{
		$data = peer_review_system_get_post_publication_notice_data($article_id);
		if (empty($data['state']) || !in_array($data['state'], array('retracted', 'withdrawn', 'removed'), true)) {
			return '';
		}

		if ('retracted' === $data['state']) {
			$label = esc_html__('RETRACTED ARTICLE', 'peer-review-system');
			$intro = esc_html__('This article has been retracted by the journal. The content below is retained for the scholarly record.', 'peer-review-system');
		} else {
			$label = esc_html__('WITHDRAWN ARTICLE', 'peer-review-system');
			$intro = esc_html__('This article has been withdrawn by the journal and is no longer available.', 'peer-review-system');
		}

		$html  = '<div class="prs-pubnotice prs-pubnotice--' . esc_attr($data['state']) . '" role="alert" style="border:2px solid #b91c1c;background:#fef2f2;color:#7f1d1d;padding:18px 20px;border-radius:8px;margin:0 0 24px;">';
		$html .= '<strong style="display:block;font-size:14px;letter-spacing:.06em;margin-bottom:6px;">' . $label . '</strong>';
		$html .= '<p style="margin:0 0 6px;">' . $intro . '</p>';
		if (!empty($data['date'])) {
			$html .= '<p style="margin:0 0 6px;font-size:13px;"><em>' . esc_html(sprintf(__('Notice date: %s', 'peer-review-system'), mysql2date(get_option('date_format'), $data['date']))) . '</em></p>';
		}
		if (!empty($data['reason'])) {
			$html .= '<p style="margin:0;"><strong>' . esc_html__('Reason:', 'peer-review-system') . '</strong> ' . esc_html($data['reason']) . '</p>';
		}
		$html .= '</div>';

		return apply_filters('peer_review_system_post_publication_notice_html', $html, $article_id, $data);
	}
}

/**
 * Public-facing correction / erratum notice HTML (additive, shown above content).
 */
if (!function_exists('peer_review_system_get_correction_notice_html')) {
	function peer_review_system_get_correction_notice_html($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return '';
		}

		$note = get_post_meta($article_id, 'prs_post_correction_note', true);
		if (empty($note)) {
			return '';
		}

		$date  = get_post_meta($article_id, 'prs_post_correction_date', true);
		$html  = '<div class="prs-pubnotice prs-pubnotice--correction" role="note" style="border:1px solid #1d4ed8;background:#eff6ff;color:#1e3a8a;padding:16px 18px;border-radius:8px;margin:0 0 24px;">';
		$html .= '<strong style="display:block;font-size:14px;letter-spacing:.06em;margin-bottom:6px;">' . esc_html__('CORRECTION', 'peer-review-system') . '</strong>';
		if (!empty($date)) {
			$html .= '<p style="margin:0 0 6px;font-size:13px;"><em>' . esc_html(sprintf(__('Corrected on %s', 'peer-review-system'), mysql2date(get_option('date_format'), $date))) . '</em></p>';
		}
		$html .= '<p style="margin:0;">' . esc_html($note) . '</p>';
		$html .= '</div>';

		return apply_filters('peer_review_system_correction_notice_html', $html, $article_id);
	}
}

if (!function_exists('peer_review_system_migrate_publishing_meta')) {
	function peer_review_system_migrate_publishing_meta()
	{
		if (get_option('prs_publishing_meta_migrated')) {
			return;
		}

		$articles = get_posts(
			array(
				'post_type'      => 'articles',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => 'prs_article_editor_status',
						'value'   => 'approved',
						'compare' => '=',
					),
				),
			)
		);

		foreach ($articles as $article_id) {
			if (!get_post_meta($article_id, 'prs_publishing_status', true)) {
				update_post_meta($article_id, 'prs_publishing_status', 'published');
			}
		}

		update_option('prs_publishing_meta_migrated', 1);
	}
	add_action('init', 'peer_review_system_migrate_publishing_meta', 20);
}

if (!function_exists('peer_review_system_notify_publishers_awaiting_production')) {
	function peer_review_system_notify_publishers_awaiting_production($article_id = 0)
	{
		$article_id = absint($article_id);
		if (empty($article_id)) {
			return;
		}

		$publishers = get_users(array('role' => 'article_publisher'));
		if (empty($publishers)) {
			return;
		}

		$article_title = get_the_title($article_id);
		$dashboard_url = peer_review_system_dashboard_page_uri('dashboard');
		if (!empty($dashboard_url)) {
			$dashboard_url = add_query_arg(
				array(
					'tab'        => 'awaiting-production',
					'article_id' => $article_id,
				),
				$dashboard_url
			);
		}

		foreach ($publishers as $publisher) {
			wp_mail(
				$publisher->user_email,
				sprintf(
					/* translators: %s: article title */
					esc_html__('Manuscript ready for production: %s', 'peer-review-system'),
					$article_title
				),
				sprintf(
					/* translators: 1: publisher name, 2: article title, 3: dashboard link */
					esc_html__("Hello %1\$s,\n\nThe manuscript \"%2\$s\" has been approved by the editor and is ready for production.\n\nOpen it here: %3\$s", 'peer-review-system'),
					$publisher->display_name,
					$article_title,
					$dashboard_url
				)
			);
		}
	}
}
