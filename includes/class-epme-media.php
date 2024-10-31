<?php
/**
 * Media management Class.
 *
 * @package easyping/Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Media Class.
 */
class EPME_Media {

	/**
	 * Register Media.
	 */
	public static function register() {
		wp_register_style( 'epme-subscribe-button-style', plugins_url( '/assets/css/epme-subscribe-button.css', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-subscribe-button', EPME_SERVER . '/button/bundle.js', null, EPME_VERSION );
	}
}
