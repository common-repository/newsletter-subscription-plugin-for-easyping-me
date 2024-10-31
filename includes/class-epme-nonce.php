<?php
/**
 * Nonce management Class.
 *
 * @package easyping/Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Nonce Class.
 */
class EPME_Nonce {

	/**
	 * Return nonce.
	 *
	 * @return  string
	 */
	public static function get_nonce() {
		$nonce_array = get_option( 'epme-nonce' );
		if ( !self::is_nonce_correct( '', $nonce_array ) ) {
			$nonce = self::create_nonce();
		} else {
			$nonce = $nonce_array['nonce'];
		}

		return $nonce;
	}

	/**
	 * Create nonce.
	 *
	 * @return string
	 */
	public static function create_nonce() {
		$nonce = substr( wp_hash( time(), 'nonce' ), 0, 10 );
		update_option( 'epme-nonce', array(
			'nonce' => $nonce,
			'time' => time(),
			'is_used' => '0',
		) );
		return $nonce;
	}

	/**
	 * Check nonce.
	 *
	 * @param  string $nonce
	 * @param  array $nonce_array
	 * @param  integer $time_limit
	 * @return boolean
	 */
	public static function is_nonce_correct( $nonce = '', $nonce_array = array(), $time_limit = 3600 ) {
		if ( empty( $nonce_array ) ) {
			$nonce_array = get_option( 'epme-nonce' );
		}
		if ( $nonce_array['is_used'] ) {
			return false;
		}
		if ( $nonce AND $nonce_array['nonce'] != $nonce ) {
			return false;
		}
		$time = time();
		if ( $time > $nonce_array['time'] AND $time <= ( $nonce_array['time'] + $time_limit ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check [and mark as using] nonce.
	 *
	 * @param  string $nonce
	 * @param  integer $mark_as_used
	 * @return boolean
	 */
	public static function check_nonce( $nonce, $mark_as_used = 1 ) {
		$nonce_array = get_option( 'epme-nonce' );
		if ( self::is_nonce_correct( $nonce, $nonce_array ) ) {
			update_option( 'epme-nonce', array(
				'nonce' => $nonce,
				'time' => $nonce_array['time'],
				'is_used' => $mark_as_used,
			) );
			return true;
		}
		return false;
	}
}
