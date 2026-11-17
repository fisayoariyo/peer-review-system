<?php
/**
 * Article detail term info
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates/Single-Edition
 * @author      Amentotech <info@amentotech.com>
 * @link        https://themeforest.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 * 
 */
if (!empty($args) && is_array($args)) {
    extract($args);
}
global $prs_settings,$post,$current_user;
$edition_search_url = !empty($prs_settings['tpl_search_editions']) ? get_permalink($prs_settings['tpl_search_editions']) : '';
$edition_price      = get_post_meta( $edition_id, 'edition_price',true ); 
$edition_price      = !empty($edition_price) ? esc_html($edition_price): '0';
$author_id          = !empty($post) ? intval($post->post_author) : '';  
$user_identity      = !empty($current_user)? intval($current_user->ID) : '0'; ?>
<div class="container"> 
    <div class="row">
        <div class="col-xl-12">
            <div class="sj-boxwrapper sj-edition">
                <ul class="sj-article-list sj-editions-list">
                    <li>
                        <div class="sj-article-list_content">
                            <?php if (has_post_thumbnail( $edition_id ) ): ?>
                                <figure>
                                    <img src="<?php echo get_the_post_thumbnail_url($edition_id, 'sj_edition_thumbnail'); ?>" alt="<?php echo esc_attr(get_the_title());?>">
                                </figure>
                            <?php endif;?>
                            <div class="sj-article-list-info">
                                <h5><?php echo esc_html(get_the_title())?></h5>
                                <?php if(!empty($author_name)|| !empty($edition_date)){?>
                                    <ul class="sj-publish-content">
                                        <?php if(!empty($edition_date)){?>
                                        <li>
                                            <i class="icon-calendar"></i>
                                            <span><?php echo date_i18n(get_option('date_format'), strtotime($edition_date))?></span>
                                        </li>
                                        <?php } ?>
                                        <?php if(!empty($author_name)) {?>
                                            <li>
                                                <i class="icon-users"></i>
                                                <span><?php echo esc_html($author_name)?></span>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="sj-editions_head_option">
                            <a href="<?php echo esc_url($edition_search_url)?>" data-task_key="<?php echo do_shortcode($package_key); ?>" class="sj-actionbtn"><span><?php esc_html_e('View all editions','peer-review-system')?></span></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>