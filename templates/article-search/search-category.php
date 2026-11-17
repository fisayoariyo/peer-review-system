<?php
/**
 * search by category  
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Article-Search
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
$tax_args = array(
    'taxonomy'      => 'article_categories',
    'hide_empty'    => 0,
);
$article_categories = get_terms($tax_args);

if ($article_categories) { ?>
    <div class="sj-widget">
        <div class="sj-widget_title" role="button" id="widget2" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="true">
            <h2><?php esc_html_e('By category', 'peer-review-system'); ?></h2><i class="icon icon-plus"></i>
        </div>
        <div class="collapse show" id="collapse2">
            <div class="sj-widget_content">
                <ul class="sj-category-filter">
                    <?php foreach ($article_categories as $category_) {
                        $checked    = '';
                        if (!empty($categories) && is_array($categories) && in_array($category_->slug, $categories)) {
                            $checked    = 'checked';
                        }
                    ?>
                    <li>
                        <div class="sj-check">
                            <input type="checkbox" id="category<?php echo intval( $category_->term_id );?>" name="categories[]" value="<?php echo esc_attr( $category_->slug );?>" <?php echo esc_attr($checked);?>>
                            <label for="category<?php echo intval( $category_->term_id );?>"><?php echo esc_html( $category_->name );?><span><?php echo intval( $category_->count );?></span></label>
                        </div>
                    </li>
                    <?php }?>
                </ul>
                <div class="show-more">
                    <a href="javascript:void(0);" class="sj-readmorebtn sj-show_more" data-show_more="<?php esc_attr_e('Show more','peer-review-system');?>" data-show_less="<?php esc_attr_e('Show less','peer-review-system');?>"><?php esc_html_e('Show more','peer-review-system');?></a>
                </div> 
            </div>
        </div>
    </div>
<?php }
