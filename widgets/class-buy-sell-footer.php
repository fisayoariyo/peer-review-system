<?php
/**
 * Sale and Buy widget
 *
 * @since      1.0.0
 *
 * @package    Peer_Review_System
 * @subpackage Peer_Review_System/widgets
 */
 
if (!defined('ABSPATH')) {
    die('Direct access forbidden.');
}

if (!class_exists('Prs_buy_sale_community')) {

    class Prs_buy_sale_community extends WP_Widget {

        /**
         * Register widget with WordPress.
         */
        function __construct() {

            parent::__construct(
                'prs_buy_sale_community' , // Base ID
                esc_html__('Sale & buy | Peer Review System' , 'peer-review-system') , // Name
                array (
                	'classname' 	=> 'sj-footerapp',
					'description' 	=> esc_html__('Sale & buy widget' , 'peer-review-system') ,
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

			$title 		 = (!empty($instance['title']) ) ? ($instance['title']) : '';
            $link 	     = !empty($instance['link']) ? ($instance['link']) : '';
            $button_text = !empty($instance['button_text']) ? ($instance['button_text']) : '';
            $description = !empty($instance['description']) ? ($instance['description']) : '';
            
            $before		= ($args['before_widget']);
			$after	 	= ($args['after_widget']);

            echo do_shortcode($before);?>
            <?php if(!empty($title) ||!empty($button_text) || !empty($description)){ ?>
                <div class="col-lg-12">
                    <div class="sj-join-community">
                        <?php if(!empty($title) || !empty($description)){?>
                            <div class="sj-join-community_title">
                                <?php if(!empty($title)){?>
                                    <h4><?php echo esc_html($title)?></h4>
                                <?php } ?>
                                <?php if(!empty($description)){?>
                                    <div class="sj-footer-desc">
                                        <p><?php echo esc_html($description) ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if(!empty($button_text)){?>
                            <a href="<?php echo esc_url_raw($link)?>" class="sj-secondery-btn-lg"><?php echo esc_html($button_text)?><i class="icon-users"></i></a>
                        <?php  } ?>
                    </div>
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
      
            $title 	     = !empty($instance['title']) ? ($instance['title']) : '';
            $link 	     = !empty($instance['link']) ? ($instance['link']) : ''; 
            $button_text = !empty($instance['button_text']) ? ($instance['button_text']) : ''; 
            $description = !empty($instance['description']) ? ($instance['description']) : ''; ?>
          
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e('Title','peer-review-system'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_html($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('description') ); ?>"><?php esc_html_e('Description','peer-review-system'); ?></label>
                <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id('description') ); ?>" name="<?php echo esc_attr( $this->get_field_name('description') ); ?>"><?php echo esc_html($description); ?></textarea>
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('button_text') ); ?>"><?php esc_html_e('Button text','peer-review-system'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('button_text') ); ?>" type="text" value="<?php echo esc_html($button_text); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id('link') ); ?>"><?php esc_html_e('Button link','peer-review-system'); ?></label>
                <input class="widefat"  name="<?php echo esc_attr( $this->get_field_name('link') ); ?>" type="text" value="<?php echo esc_url($link); ?>">
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
            $instance                   = $old_instance;
            $instance['title']	        = (!empty($new_instance['title']) ) ? sanitize_text_field($new_instance['title']) : '';
            $instance['link']	        = (!empty($new_instance['link']) ) ? sanitize_text_field($new_instance['link']) : '';
            $instance['description']    = (!empty($new_instance['description']) ) ? sanitize_text_field($new_instance['description']) : '';
            $instance['button_text']    = (!empty($new_instance['button_text']) ) ? sanitize_text_field($new_instance['button_text']) : '';
 
            return $instance;
        }
    }
    //register widget
    function prs_register_buy_sale_community_widgets() {
        register_widget( 'Prs_buy_sale_community' );
    }
    add_action( 'widgets_init', 'prs_register_buy_sale_community_widgets' );
}
