<?php
/**
 * User profile avatar
 *
 * @package     Peer_Review_System
 * @subpackage   Peer_Review_System/Templates/Dashboard
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */
global $current_user;
?>
<script type="text/template" id="tmpl-load-profile-avatar">
    <div class="sj-popuptitle">
        <h4><?php esc_html_e('Upload profile photo', 'peer-review-system'); ?></h4>
        <a href="javascript:void(0);" class="close"><i class="icon icon-x" data-bs-dismiss="modal"></i></a>
    </div>
    <div class="modal-body">
            <form class="sj-dhb-orders-listing">
                <div id="crop_img_area"></div>
            </form>
        <div class="sj-popupfooter">
            <em> <?php esc_html_e('Click “Save” to update profile photo', 'peer-review-system'); ?></em>
            <a href="javascript:void(0);" class="sj-secondery-btn" id="save-profile-img"><?php esc_html_e('Save', 'peer-review-system'); ?></a>
        </div>
    </div>
</script>
<script type="text/template" id="tmpl-load-default-image">
	<figure id="thumb-{{data.id}}" >
		<img class="attachment_url" alt="<?php esc_attr_e('Profile avatar', 'peer-review-system' ); ?>">
		<div class="progress sj-upload-progressbar"><div style="width:{{data.percentage}}%" class="progress-bar"></div></div>
	</figure>
</script>