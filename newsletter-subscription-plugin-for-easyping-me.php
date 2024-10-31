<?php
/**
 * Plugin Name: Newsletter Subscription Plugin for easyping.me
 * Plugin URI: https://easyping.me/
 * Description: Collect more subscribers from your website visitors and then send news updates.
 * Version: 1.0.6
 * Author: Easyping.me
 * Author URI: https://easyping.me
 * Developer: Dmitry Kutalo
 * Text Domain: newsletter-subscription-plugin-for-easyping-me
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define EPME_PLUGIN_DIR.
if ( ! defined( 'EPME_PLUGIN_DIR' ) ) {
	define( 'EPME_PLUGIN_DIR', str_replace( '\\', '/', dirname( __FILE__ ) ) . '/' );
}

// Main easyping.me Class.
if ( ! class_exists( 'easyping' ) ) :
	include_once EPME_PLUGIN_DIR . 'includes/class-epme-easyping.php';
endif;

/**
 * Main instance of easyping.me.
 *
 * Returns the main instance of easyping.me to prevent the need to use globals.
 *
 * @return EPME_easyping
 */
function EPME_EP() {
	return EPME_easyping::instance();
}

// Global for backwards compatibility.
$GLOBALS['easyping'] = EPME_EP();