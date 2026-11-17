<?php

/**
 * Article categories
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Article-Loop
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $prs_settings;
$article_terms  = wp_get_post_terms($article_id, 'article_categories');
$categories_arr = array();
$rand_num       = rand(100, 999);
$tpl_search_articles = !empty($prs_settings['tpl_search_articles']) ? get_permalink($prs_settings['tpl_search_articles']) : '';
?>
<?php if (!empty($article_terms) && is_array($article_terms) && count($article_terms) > 0) {
    $show_cats      = 1;
    $counter_cats   = 0; ?>
    <li>
        <span>
        <i class="icon-layers"></i>
        <?php foreach ($article_terms as $article_key => $article_cat) {
            $counter_cats++;
            
            $term_link = get_term_link($article_cat);
            if ($counter_cats <= $show_cats) { ?>
                <?php if (!empty($article_cat->term_id)) { 
                    $category = array();
                    $category[]=  $article_cat->slug;
                    $article_search_url = add_query_arg('categories',$category, $tpl_search_articles); ?>
                    <a href="<?php echo esc_url($article_search_url) ?>"><?php echo esc_html($article_cat->name); ?></a>
            <?php }
            } else {
                $categories_arr[]  =     array(
                    'name' => $article_cat->name,
                    'slug' => $article_cat->slug,
                );
            } 
        } ?>    
        </span>
        <?php if (($counter_cats) > $show_cats && !empty($categories_arr)) { ?>
        <a class="sj-showmore sj-tooltip-tags" id="sj-detail-subjects" href="javascript:void(0);" data-tippy-trigger="click" data-template="sj-industrypro-<?php echo intval($rand_num); ?>" data-tippy-interactive="true" data-tippy-placement="top-start"><span><?php echo sprintf(__('+%s more', 'peer-review-system'), intval($counter_cats) - $show_cats); ?></span></a>
        <div id="sj-industrypro-<?php echo intval($rand_num); ?>" class="sj-tippytooltip d-none">
            <div class="sj-selecttagtippy sj-tooltip">
                <ul class="sj-posttag sj-posttagv2">
                    <?php foreach ($categories_arr as $item) {

                        if (!empty($item['name'] && !empty($item['slug']))) {
                            $category_    = array();
                            $category_[]=  $item['slug'];
                            $article_search_url = add_query_arg('categories', $category_, $tpl_search_articles); ?>
                        <li>
                            <a href="<?php echo esc_url($article_search_url) ?>"><?php echo esc_html($item['name']) ?></a>
                        </li>
                    <?php }
                    } ?>
                </ul>
            </div>
        </div>
    <?php } ?>
    </li>
<?php }
