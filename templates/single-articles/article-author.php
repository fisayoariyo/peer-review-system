<?php
/**
 * Aricle authors list
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Articles
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
if (!empty($args) && is_array($args)) {
    extract($args);
}
$article_author = array();
$article_id     = !empty($args) ? intval($article_id) : '' ;
$article_author = get_post_meta($article_id, 'prs_article_authors', true);
$authors_list   = !empty($article_author) && is_array($article_author) ? sizeof($article_author) : 0;
if(!empty($authors_list) &&  $authors_list > 1){ ?>
    <div class="sj-aside-holder">
        <div class="sj-noticeboard">
            <div class="sj-widget_title">
                <h2><?php esc_html_e('Article authors','peer-review-system')?></h2>
            </div>
            <div class="sj-widget_content">
                <ul class="sj-author-list">
                    <?php foreach ($article_author as $key => $value) { 
                        if(!empty($value['name'])){?>
                            <li>
                                <h6><?php echo esc_html($value['name'])?></h6>
                            </li><?php
                        }
                    } ?> 
                </ul>
            </div>
        </div>
    </div>
<?php } ?>
