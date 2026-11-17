<?php
/**
 * Template Name: Inbox
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/Templates
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
*/
global $prs_settings;
if( !is_user_logged_in() ){
	$redirect_url   = get_home_url();    
    wp_redirect( $redirect_url );
    exit;
}

get_header();
?>
<section class="sj-main-section">
	<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<div class="sj-inbox-wrapper">
					<div class="sj-boxsmtitle">
						<h4><?php esc_html_e('Start conversation','peer-review-system');?></h4>
					</div>
					<?php echo do_shortcode('[getGuppyConversation]');?>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
get_footer();
