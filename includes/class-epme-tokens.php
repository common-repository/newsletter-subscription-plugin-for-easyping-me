<?php
/**
 * Token management Class.
 *
 * @package easyping/Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Tokens Class.
 */
class EPME_Tokens {

	/**
	 * Return easyping.me system Token
	 *
	 * @return string
	 */
	public static function get_token() {
		switch ( self::is_token_exist() ) {
			case true:
				return get_option( 'EASYPING_TOKEN' );
			default:
				return false;
		}
	}

	/**
	 * Return the existence of a token
	 *
	 * @return integer | boolean
	 */
	public static function is_token_exist() {
		if ( get_option( 'EASYPING_TOKEN' ) ) {
			return true;
		}
		return false;
	}
}
