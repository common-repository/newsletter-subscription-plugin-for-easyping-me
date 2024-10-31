<?php
/**
 * Authorization Class.
 *
 * @package easyping\Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Authorization Class.
 */
class EPME_Authorization {

	/**
	 * Is authorization in the easyping.me?
	 *
	 * @return boolean
	 */
	public static function is_authorization() {
		return self::check_data_integrity();
	}

	/**
	 * Update data from Handshaking
	 *
	 * @param  object $data
	 */
	public static function update_data( $data ) {
		if ( isset( $data->balance ) ) {
			EPME_Authorization::set_balance( addslashes( $data->balance ) );
		}
		if ( isset( $data->country ) ) {
			EPME_Authorization::set_country( addslashes( $data->country ) );
		}
		if ( isset( $data->project ) ) {
			EPME_Authorization::set_project_name( addslashes( $data->project ) );
		}
		if ( isset( $data->plan ) ) {
			EPME_Authorization::set_plan( addslashes( $data->plan ) );
		}
		if ( isset( $data->email ) ) {
			EPME_Authorization::set_email( addslashes( $data->email ) );
		}
	}

	/**
	 * Sign in with data
	 *
	 * @param  object $data
	 * @return array
	 */
	public static function sign_in( $data ) {
		$balance = addslashes( $data->balance );
		$country = addslashes( $data->country );
		$project = addslashes( $data->project );
		$email = addslashes( $data->email );
		$token = addslashes( $data->token );
		$plan = addslashes( $data->plan );
		$cabinet_url = addslashes( $data->cabinetUrl );

		if ( $token ) {
			update_option( 'EASYPING_TOKEN', $token );

			if ( !$balance )
				$balance = 0;
			EPME_Authorization::set_balance( $balance );

			if ( !$country )
				$country = get_locale();
			EPME_Authorization::set_country( $country );

			if ( !$project )
				$project = get_site_url();
			EPME_Authorization::set_project_name( $project );

			if ( !$email )
				$email = '';
			EPME_Authorization::set_email( $email );

			if ( !$plan )
				$plan = 'STANDART';
			EPME_Authorization::set_plan( $plan );

			EPME_Authorization::set_cabinet_url( $cabinet_url );

			$answer = epme_ok();
		} else {
			$answer = epme_error( __( 'token is empty', 'easyping.me' ) );
		}
		return $answer;
	}

	/**
	 * Sign out
	 *
	 * @param  bool $is_soft
	 */
	public static function sign_out( $is_soft = false ) {
		if ( !$is_soft ) {
			delete_option( 'EASYPING_TOKEN' );
		}
		delete_option( 'EASYPING_BALANCE' );
		delete_option( 'EASYPING_COUNTRY' );
		delete_option( 'EASYPING_COMPANY' );
		delete_option( 'EASYPING_EMAIL' );
		delete_option( 'EASYPING_CABINET_URL' );
	}

	/**
	 * Check data integrity
	 *
	 * @return boolean
	 */
	public static function check_data_integrity() {
		if ( EPME_Tokens::get_token() ) {
			if ( self::get_email() AND self::get_country() AND self::get_project_name() ) {
				return true;
			}
			if ( EPME_Tokens::get_token() ) {
				$server_answer = EPME_Connects::handshaking();
				if ( $server_answer['type'] == 'ok' ) {
					EPME_Authorization::update_data( $server_answer['respond'] );
					return true;
				}
			}
			return false;
		} else {
			return false;
		}
	}

	/**
	 * Return authorization email
	 *
	 * @return string
	 */
	public static function get_email() {
		return stripslashes( get_option( 'EASYPING_EMAIL' ) );
	}

	/**
	 * Update the email
	 *
	 * @param string $name
	 */
	public static function set_email( $name ) {
		update_option( 'EASYPING_EMAIL', $name );
	}

	/**
	 * Return cabinet url
	 *
	 * @return string
	 */
	public static function get_cabinet_url() {
		return stripslashes( get_option( 'EASYPING_CABINET_URL' ) );
	}

	/**
	 * Update the cabinet url
	 *
	 * @param string $name
	 */
	public static function set_cabinet_url( $name ) {
		update_option( 'EASYPING_CABINET_URL', $name );
	}

	/**
	 * Return project name
	 *
	 * @return string
	 */
	public static function get_project_name() {
		return stripslashes( get_option( 'EASYPING_COMPANY' ) );
	}

	/**
	 * Update the project name
	 *
	 * @param string $name
	 */
	public static function set_project_name( $name ) {
		update_option( 'EASYPING_COMPANY', $name );
	}

	/**
	 * Return authorization token of project
	 *
	 * @return string
	 */
	public static function get_token() {
		return EPME_Tokens::get_token();
	}

	/**
	 * Return country of project
	 *
	 * @return string
	 */
	public static function get_country() {
		return stripslashes( strtolower( get_option( 'EASYPING_COUNTRY' ) ) );
	}

	/**
	 * Update the country
	 *
	 * @param string $name
	 */
	public static function set_country( $name ) {
		update_option( 'EASYPING_COUNTRY', $name );
	}

	/**
	 * Return plan in the easyping.me system
	 *
	 * @return string
	 */
	public static function get_plan() {
		return stripslashes( get_option( 'EASYPING_PLAN' ) );
	}

	/**
	 * Update the plan.
	 *
	 * @param string $name
	 */
	public static function set_plan( $name ) {
		update_option( 'EASYPING_PLAN', $name );
	}

	/**
	 * Return balance in the easyping.me system
	 *
	 * @return string
	 */
	public static function get_balance() {
		return stripslashes( get_option( 'EASYPING_BALANCE' ) );
	}

	/**
	 * Update the balance
	 *
	 * @param string $name
	 */
	public static function set_balance( $name ) {
		update_option( 'EASYPING_BALANCE', $name );
	}
}
