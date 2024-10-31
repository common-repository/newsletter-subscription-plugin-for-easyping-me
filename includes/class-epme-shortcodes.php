<?php
/**
 * Shortcodes Class.
 *
 * @package easyping\Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Shortcodes Class.
 */
class EPME_Shortcodes {

	/**
	 * Show button Subscribe button.
	 *
	 * @param  array $attributes
	 * @return string
	 */
	public static function widget( $attributes ) {
		$atts = shortcode_atts( array(
		    'id' => false,
        ), $attributes );

		$atts['id'] = intval( $atts['id'] );
		if ( !isset( $atts['id'] ) OR !$atts['id'] ) {
		    return '';
        }

		ob_start();

		$response = EPME_Connects::get_widget_by_id( $atts['id'] );
		if ( isset( $response['respond'] ) ) {
			$widget = epme_fix_bool_in_arr( $response['respond'] );

			EPME_Widgets::resources();

			if ( isset( $widget['guid'] ) AND $widget['guid'] ) {
				echo "<div id=\"brnnrn-subscription-button-{$widget['guid']}\"></div>";
				echo "<script>window.addEventListener('load',function(){BRNNRNSubscriptionButton.create({guid:'{$widget['guid']}',selector:'#brnnrn-subscription-button-{$widget['guid']}'});});</script>";
			}
		}

		return ob_get_clean();
	}
}
