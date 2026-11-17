<?php
/**
 * search by keyword  
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Article-Search
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
?>
<div class="sj-widget">
    <div class="sj-widget_title" role="button" id="widget1" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true">
        <h2><?php esc_html_e('Search', 'peer-review-system'); ?></h2><i class="icon icon-plus"></i>
    </div>
    <div class="collapse show" id="collapse1">
        <div class="sj-widget_content">
            <div class="sj-searchfield">
                <input type="text" class="form-control" name="keyword" value="<?php echo esc_attr($keyword); ?>" placeholder="<?php esc_attr_e('Search with keyword', 'peer-review-system'); ?>">
                <i class="icon icon-search"></i>
            </div>
        </div>
    </div>
</div>