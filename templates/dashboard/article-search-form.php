<?php
/**
 * The template used for article search form
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

if (!empty($args) && is_array($args)) {
    extract($args);
}
$keyword = !empty($keyword) ? $keyword : '';
/* get all cats */
$arguments      =  array(
    'taxonomy'      => 'article_categories',
    'hide_empty'    => false,
);

$tax_query_args     = array();

$article_categories = get_terms($arguments);
$category           = !empty($_GET['category']) ? intval($_GET['category']) : ''; 
$category           = !empty($_GET['category']) ? intval($_GET['category']) : ''; 
$useridentity       = !empty($_GET['useridentity']) ? $_GET['useridentity'] : 0;
$tab                = !empty($_GET['tab']) ? $_GET['tab'] : '';
?>
<form id="article-search-form" action="<?php echo esc_url($current_page_link); ?>">
    <ul class="sj-booking-list">
        <li>
            <div class="sj-inputicon">
                <i class="icon-search"></i>
                <input type="text" placeholder="<?php esc_attr_e('Search keyword', 'peer-review-system'); ?>" name="keyword" id="keyword" value="<?php echo esc_attr($keyword); ?>" class="form-control">
            </div>
        </li>
        <li>
            <div class="sj-select sj-selectv2">
                <a href="#"><i class="icon-trending-up"></i></a>
                <select id="sj-article-categories" class="form-control sj-article-categories" name="category" data-placeholder="<?php esc_attr_e('Article category', 'peer-review-system'); ?>">
                    <option value=""><?php esc_html_e('Select category', 'peer-review-system'); ?></option>
                    <?php foreach ($article_categories as $category_) { ?>
                        <option <?php if (strtolower($category) == strtolower($category_->term_id)) {
                                    echo esc_attr('selected');
                                } ?> value="<?php echo esc_attr($category_->term_id); ?>"><?php echo esc_html($category_->name) ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <input type="hidden" name="useridentity" value="<?php echo esc_html($useridentity); ?>">
        <input type="hidden" name="tab" value="<?php echo esc_html($tab); ?>">
        <li>
            <input type="submit" class="sj-secondery-btn-lg" value="<?php esc_attr_e('Search now', 'peer-review-system'); ?>">
        </li>
    </ul>
</form>