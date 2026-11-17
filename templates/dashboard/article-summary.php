<?php

/**
 * Artice summary
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/templates/dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!empty($args) && is_array($args)) {
    extract($args);
}

$article_data     = !empty($article_data) ? ($article_data) : array();
if (!empty($article_data)) { ?>
    <div class="sj-wizserviceslist mCustomScrollbar">
        <div class="sj-box">
            <ul class="sj-checkout">              
                <?php
                    if( !empty($article_data['article_title'])){?>
                        <li>
                            <span><?php echo esc_html($article_data['article_title']);?></span>
                            <h6><?php peer_review_system_price_format($article_data['article_price']);?></h6>
                        </li>
                <?php } ?>
            </ul>      
        </div>
    </div>    
<?php }
