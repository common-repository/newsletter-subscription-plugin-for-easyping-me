<?php
/**
 * Personal Account easyping.me Class.
 *
 * @package easyping/Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Account Class.
 */
class EPME_Account {

	/**
	 * Token for auth into cabinet EP (ls.chat).
	 *
	 * @var $token
	 */
	protected static $token;

	/**
	 * Return nonce.
	 *
	 * @return  string
	 */
	public static function get_token() {
		if ( empty( self::$token ) OR !self::$token ) {
			$response = EPME_Connects::get_cabinet_login_token();
			if ( $response['type'] !== 'error' AND isset( $response['respond'] ) AND isset( $response['respond']->cabinetToken ) ) {
				self::$token = $response['respond']->cabinetToken;
			} else {
				return '';
			}
		}
		return self::$token;
	}

	/**
	 * Return link by name.
	 *
	 * @param  string $name
	 * @return string
	 */
	public static function get_link( $name ) {
		$path = '';
		switch ( $name ) {
			case 'testers' :
			case 'test-account' :
				$path = '/settings/test-account';
				break;
		}
		return self::get_link_with_path( $path );
	}

	/**
	 * Return link by path.
	 *
	 * @param  string $path
	 * @return string
	 */
	public static function get_link_with_path( $path ) {
		$link = EPME_Authorization::get_cabinet_url();
		$link .= "$path?token=" . self::get_token();
		return $link;
	}
}
