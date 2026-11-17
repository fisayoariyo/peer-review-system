<?php

/**
 *
 * Class 'Peer_Review_System_Modal_Popup' defines the bootstrap modal
 *
 * @package     Peer_Review_System
 * @subpackage  Peer_Review_System/includes
 * @author      Amentotech <info@amentotech.com>
 * @link        https://codecanyon.net/user/amentotech/portfolio
 * @version     1.0
 * @since       1.0
 */

/* die if accessed directly */
if (!defined('ABSPATH')) {
    die('No kiddies please!');
}

if (!class_exists('Peer_Review_System_Modal_Popup')) {

    class Peer_Review_System_Modal_Popup
    {

        public function __construct()
        {
            add_action('wp_footer', array($this, 'peer_review_system_prepare_modal_popup'));
        }

        /**
         * Peer Review System add-ons popup
         *
         * @return
         * @throws error
         * @author Amentotech <theamentotech@gmail.com>
         */
        public function peer_review_system_prepare_modal_popup()
        {
            ob_start(); ?>
            <div class="modal hidden fade peer-review-system-profilepopup sj-uploadprofile peer-review-system-popup" tabindex="-1" role="dialog" id="peer-review-system-modal-popup">
                <div class="modal-dialog modal-dialog-centered sj-uplaod-article" role="document">
                    <div class="peer-review-system-modal-content modal-content">
                        <div id="peer-review-system-model-body"></div>
                    </div>
                </div>
            </div>
            <?php
            echo ob_get_clean();
        }
    }
    new Peer_Review_System_Modal_Popup();
}
