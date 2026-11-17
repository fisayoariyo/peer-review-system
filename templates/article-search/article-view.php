<?php
/**
 * Aricle view
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Article-Search
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$article_id   = isset($post->ID) ? $post->ID : get_the_ID();
$excerpt      = get_the_excerpt();
$author_name  = function_exists('jase_peer_review_get_article_author_name') ? jase_peer_review_get_article_author_name($article_id) : get_the_author_meta('display_name', get_the_author_meta('ID'));
$citation     = function_exists('jase_peer_review_format_article_citation') ? jase_peer_review_format_article_citation($article_id) : '';

$pdf_url = function_exists('jase_peer_review_article_pdf_url') ? jase_peer_review_article_pdf_url($article_id) : '';
if (empty($pdf_url) && function_exists('jase_peer_review_get_article_download_url')) {
    $pdf_url = jase_peer_review_get_article_download_url($article_id);
}
?>
<div class="sj-articles prs-result">
    <h2 class="prs-result__title"><a href="<?php echo esc_url(get_permalink()); ?>"><?php the_title(); ?></a></h2>
    <?php if (!empty($author_name)) { ?>
        <p class="prs-result__authors"><?php echo esc_html($author_name); ?></p>
    <?php } ?>
    <?php if (!empty($citation)) { ?>
        <p class="prs-result__cite"><?php echo esc_html($citation); ?></p>
    <?php } ?>
    <?php if (!empty($excerpt)) { ?>
        <p class="prs-result__excerpt"><?php echo esc_html(wp_trim_words($excerpt, 32, '…')); ?></p>
    <?php } ?>
    <p class="prs-result__links">
        <?php if (!empty($pdf_url)) { ?>
            <a class="prs-result__pdf" href="<?php echo esc_url($pdf_url); ?>" target="_blank" rel="noopener">
                <span class="prs-result__pdf-icon" aria-hidden="true">PDF</span><?php esc_html_e('View PDF', 'peer-review-system'); ?>
            </a>
        <?php } ?>
        <a class="prs-result__view" href="<?php echo esc_url(get_permalink()); ?>"><?php esc_html_e('View details', 'peer-review-system'); ?></a>
    </p>
</div>
