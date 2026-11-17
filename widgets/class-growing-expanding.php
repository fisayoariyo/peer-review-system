<?php
/**
 * Growing and expanding widget
 *
 * @since      1.0.0
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/Widgets
 */
 
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('Prs_growing_expanding')) {

    class Prs_growing_expanding extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                'prs_growing_expanding' , // Base ID
                esc_html__('Growning & Expaning | Peer Review System' , 'peer-review-system') , // Name
                array (
                	'classname' 	=> 'sj-footerapp',
					'description' 	=> esc_html__('Peer review system info' , 'peer-review-system') ,
				) // Args
            );
        }

        /**
         * Outputs the content of the widget
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args , $instance) {
            // outputs the content of the widget
			global $post;
            extract($instance);
			$title 		                = (!empty($instance['title']) ) ? ($instance['title']) : '';
            $support_title 	            = !empty($instance['support_title']) ? ($instance['support_title']) : '';
            $verified_ads_title 	    = !empty($instance['verified_ads_title']) ? ($instance['verified_ads_title']) : '';
            $verified_user_title 	    = !empty($instance['verified_user_title']) ? ($instance['verified_user_title']) : '';
            $support_descripton         = !empty($instance['support_descripton']) ? ($instance['support_descripton']) : '';
            $verified_ads_description   = !empty($instance['verified_ads_description']) ? ($instance['verified_ads_description']) : '';
            $verified_user_description  = !empty($instance['verified_user_description']) ? ($instance['verified_user_description']) : '';
            $before		= ($args['before_widget']);
			$after	 	= ($args['after_widget']);
            echo do_shortcode($before);?>
            <?php if(!empty($support_title) || !empty($verified_ads_title) ||!empty($verified_user_title) || !empty($support_descripton) || !empty($verified_ads_description) || !empty($verified_user_description) || !empty($title)){ ?>
                <div class="col-lg-12">
                    <?php  if(!empty($title)){?>
                        <div class="sj-footer-title">
                            <h3><?php echo esc_html($title) ?></h3>
                        </div>
                    <?php }?>
                    <?php if(!empty($support_title) || !empty($verified_ads_title) ||!empty($verified_user_title) || !empty($support_descripton) ||
                        !empty($verified_ads_description) || !empty($verified_user_description)) {?>
                        <ul class="sj-feedback">
                            <?php if(!empty($support_title) || !empty($support_descripton)){?>
                                <li>
                                    <?php if(!empty($support_title)){?>
                                        <div class="sj-feedback_title">
                                            <span class="sj-lightblue-bg"><i class="icon-headphones"></i></span>
                                            <h4><?php echo esc_html($support_title)?></h4>
                                        </div>
                                    <?php } ?>
                                    <?php if(!empty($support_descripton)){?>
                                        <div class="sj-footer-desc">
                                            <p><?php echo esc_html($support_descripton)?></p>
                                        </div>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                            <?php if(!empty($verified_ads_title) || !empty($verified_ads_description)) {?>
                                <li>
                                    <?php if(!empty($verified_ads_title)){?>
                                        <div class="sj-feedback_title">
                                            <span class="sj-lightyellow-bg"><i class="icon-check-circle"></i></span>
                                            <h4><?php echo esc_html($verified_ads_title)?></h4>
                                        </div>
                                    <?php }?>
                                    <?php if(!empty($verified_ads_description)){?>
                                        <div class="sj-footer-desc">
                                            <p><?php echo esc_html($verified_ads_description)?></p>
                                        </div>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                            <?php if(!empty($verified_user_title) || !empty($verified_user_description)) {?>
                                <li>
                                    <?php if(!empty($verified_user_title)){?>
                                        <div class="sj-feedback_title">
                                            <span class="sj-lightgreen-bg"><i class="icon-user-check"></i></span>
                                            <h4><?php echo esc_html($verified_user_title)?></h4>
                                        </div>
                                    <?php } ?>
                                    <?php if(!empty($verified_user_description)){?>
                                        <div class="sj-footer-desc">
                                            <p><?php echo esc_html($verified_user_description)?></p>
                                        </div>
                                    <?php } ?>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </div> 
            <?php  }
			echo do_shortcode( $after );
        }

        /**
         * Outputs the options form on admin
         *
         * @param array $instance The widget options
         */
        public function form($instance) {
            // outputs the options form on admin      
            $title 	                    = !empty($instance['title']) ? ($instance['title']) : '';
            $support_title 	            = !empty($instance['support_title']) ? ($instance['support_title']) : ''; 
            $support_descripton         = !empty($instance['support_descripton']) ? ($instance['support_descripton']) : '';
            $verified_ads_title         = !empty($instance['verified_ads_title']) ? ($instance['verified_ads_title']) : ''; 
            $verified_ads_description   = !empty($instance['verified_ads_description']) ? ($instance['verified_ads_description']) : ''; 
            $verified_user_title        = !empty($instance['verified_user_title']) ? ($instance['verified_user_title']) : ''; 
            $verified_user_description  = !empty($instance['verified_user_description']) ? ($instance['verified_user_description']) : ''; ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title','peer-review-system'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_html($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('support_title') ); ?>"><?php esc_html_e('Support section title','peer-review-system'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('support_title') ); ?>" type="text" value="<?php echo esc_html($support_title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('support_descripton') ); ?>"><?php esc_html_e('Support section description','peer-review-system'); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id('support_descripton') ); ?>" name="<?php echo esc_attr( $this->get_field_name('support_descripton') ); ?>"><?php echo esc_html($support_descripton); ?></textarea>

            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('verified_ads_title') ); ?>"><?php esc_html_e('Adds section title','peer-review-system'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('verified_ads_title') ); ?>" type="text" value="<?php echo esc_html($verified_ads_title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('verified_ads_description') ); ?>"><?php esc_html_e('Adds section description','peer-review-system'); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id('verified_ads_description') ); ?>" name="<?php echo esc_attr( $this->get_field_name('verified_ads_description') ); ?>"><?php echo esc_html($verified_ads_description); ?></textarea>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('verified_user_title') ); ?>"><?php esc_html_e('User section title','peer-review-system'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('verified_user_title') ); ?>" type="text" value="<?php echo esc_html($verified_user_title); ?>">
            </p>

            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('verified_user_description') ); ?>"><?php esc_html_e('User section description','peer-review-system'); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id('verified_user_description') ); ?>" name="<?php echo esc_attr( $this->get_field_name('verified_user_description') ); ?>"><?php echo esc_html($verified_user_description); ?></textarea>
            </p>
          
            <?php
        }

        /**
         * Processing widget options on save
         *
         * @param array $new_instance The new options
         * @param array $old_instance The previous options
         */
        public function update($new_instance , $old_instance) {
            // processes widget options to be saved
            $instance                               = $old_instance;
            $instance['title']	                    = (!empty($new_instance['title']) ) ? sanitize_text_field($new_instance['title']) : '';
            $instance['support_title']	            = (!empty($new_instance['support_title']) ) ? sanitize_text_field($new_instance['support_title']) : '';
            $instance['verified_ads_title']	        = (!empty($new_instance['verified_ads_title']) ) ? sanitize_text_field($new_instance['verified_ads_title']) : '';
            $instance['verified_user_title']        = (!empty($new_instance['verified_user_title']) ) ? sanitize_text_field($new_instance['verified_user_title']) : '';
            $instance['support_descripton']	        = (!empty($new_instance['support_descripton']) ) ? sanitize_textarea_field($new_instance['support_descripton']) : '';
            $instance['verified_ads_description']	= (!empty($new_instance['verified_ads_description']) ) ? sanitize_textarea_field($new_instance['verified_ads_description']) : '';
            $instance['verified_user_description']  = (!empty($new_instance['verified_user_description']) ) ? sanitize_textarea_field($new_instance['verified_user_description']) : '';
            return $instance;
        }
    }
}

//register widget
function prs_register_growing_expanding_widgets() {
	register_widget( 'Prs_growing_expanding' );
}

add_action( 'widgets_init', 'prs_register_growing_expanding_widgets' );
