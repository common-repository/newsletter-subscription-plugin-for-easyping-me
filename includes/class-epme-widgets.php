<?php
/**
 * Widgets Class.
 *
 * @package easyping\Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Widgets Class.
 */
class EPME_Widgets {

	/*
	 * HTML variable
	 *
	 * @var $html
	 */
	protected static $html;

	/**
	 * Show fixed button Subscribe button on the all pages.
	 */
	public static function show_fixed_sub_button() {
		$widget = self::get_first_fixed_sub_button();

		if ( !empty( $widget ) AND $widget['id'] ) {
			self::$html = "<div id=\"brnnrn-subscription-button-{$widget['guid']}\" data-id='{$widget['id']}'></div>";
			self::$html .= "<script>window.addEventListener('load',function(){BRNNRNSubscriptionButton.create({guid:'{$widget['guid']}',selector:'#brnnrn-subscription-button-{$widget['guid']}'});});</script>";

			add_action( 'wp_enqueue_scripts', array( 'EPME_Widgets', 'resources' ), 30 );
			add_action( 'wp_footer', array( 'EPME_Widgets', 'footer_html' ), 40 );
		}
	}

	/**
	 * Resources.
	 */
	public static function resources() {
		wp_enqueue_script('epme-subscribe-button');
		wp_enqueue_style('epme-subscribe-button-style');
	}

	/**
	 * HTML into footer.
	 */
	public static function footer_html() {
		echo self::$html;
	}

	/**
	 * Show fixed button Subscribe button on the all pages.
	 *
	 * @return array
	 */
	protected static function get_first_fixed_sub_button() {
		$response = EPME_Connects::get_widgets();

		if ( $response['type'] !== 'error' AND !empty( $response['respond'] ) ) {
			$widget_list = array_reverse( epme_fix_bool_in_arr( $response['respond'] ) );
			foreach ( $widget_list as $widget_item ) {
				if ( !isset( $widget_item['id'] ) OR !$widget_item['id'] OR !isset( $widget_item['buttonText'] ) OR !$widget_item['buttonText'] ) {
					continue;
				}

				if ( $widget_item['position'] == 1 AND $widget_item['active'] ) {
					return $widget_item;
				}
			}
			return array();
		} else {
			return array();
		}
	}
}
