<?php
/**
 * Aricle notice  
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Article-Search
 * @author      Amentotech <info@amentotech.com>
 * @link       https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $post;
$args = array(
    'post_type'     => 'articles',
    'post_status'   => 'publish',
    'orderby'       => 'date',
    'posts_per_page' => 5
);
$recent_articles = get_posts($args);

if(!empty($recent_articles)){?>
    <div class="sj-noticeboard">
        <div class="sj-widget_title">
            <h2><?php esc_html_e('Notice board','peer-review-system')?></h2>
        </div>
        <div class="sj-widget_content">
            <ul class="sj-notice-list">
            <?php
            foreach($recent_articles as $article){
                    $article_id     = $article->ID;
                    $article_title  = get_the_title($article_id);
                    $permalink      = get_permalink($article_id);
                    $Image          = '';

                    if(!empty($article_title) || !empty($Image)){?>
                        <li>
                            <?php if(!empty($Image)){?>
                            <figure>
                                <img src="<?php echo esc_url($Image); ?>" alt="<?php echo esc_attr($article_title); ?>">
                            </figure>
                            <?php } ?>
                            <?php if(!empty($article_title)){?>
                                <h6><a href="<?php echo esc_url($permalink)?>"><?php echo esc_html($article_title)?></a></h6>
                            <?php } ?>
                        </li>
                    <?php
                    }  
                } ?>
            </ul>
        </div>
    </div>
<?php }
