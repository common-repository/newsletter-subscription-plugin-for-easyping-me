<?php
/**
 * Request Class.
 *
 * @package easyping\Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Requests Class.
 */
class EPME_Requests {

	/**
	 * Answer from Easyping.me server on Add funds
	 */
	public static function add_funds() {
		if ( !EPME_Nonce::check_nonce( $_POST['nonce'] ) ) {
			$answer = epme_error( __( 'nonce is incorrect', 'easyping.me' ) );
			echo json_encode( $answer );
			die;
		}

		$data = json_decode( stripslashes ( $_POST['data'] ) );

		if ($data->status->type != 'ok') {
			$answer = epme_error( __( 'bad answer', 'easyping.me' ) );
			echo json_encode( $answer );
			die;
		}

		$balance = ( float )addslashes( $data->changeBalance->balance );

		EPME_Authorization::set_balance( $balance );

		$answer = epme_ok();
		echo json_encode( $answer );
		die;
	}

	/**
	 * Answer from Easyping.me server on Sign in
	 */
	public static function sign_in_easyping() {
		if ( !EPME_Nonce::check_nonce( $_POST['nonce'] ) ) {
			$answer = epme_error( __( 'nonce is incorrect', 'easyping.me' ) );
			echo json_encode( $answer );
			die;
		}

		$data = json_decode( stripslashes ( $_POST['data'] ) );
		if ($data->status->type != 'ok') {
			$answer = epme_error( __( 'bad answer', 'easyping.me' ) );
			echo json_encode( $answer );
			die;
		}

		echo json_encode( EPME_Authorization::sign_in( $data ) );
		die;
	}

	/**
	 * Answer from Easyping.me server on connect FB, VK
	 */
	public static function easyping_oauth_url() {
		if ( !EPME_Nonce::check_nonce( $_POST['nonce'] ) ) {
			$answer = epme_error( __( 'nonce is incorrect', 'easyping.me' ) );
			echo json_encode( $answer );
			die;
		}

		$data_json = stripslashes ( $_POST['data'] );
		$data = json_decode( $data_json );

		if ($data->status->type != 'ok') {
			$answer = epme_error( __( 'bad answer', 'easyping.me' ) );
			echo json_encode( $answer );
			die;
		}

		if ( !empty( $data ) ) {
			$network = '';
			foreach ( $data as $datum ) {
				$network = $datum->network;
				if ( $network ) break;
			}

			if ( $network ) {
				update_option( 'EASYPING_OAUTH', array(
					'data' => $data_json,
					'network' => $network,
				) );
			} else {
				delete_option( 'EASYPING_OAUTH' );
			}

			$answer = epme_ok();
		} else {
			$answer = epme_error( __( '"data" is empty', 'easyping.me' ) );
		}
		echo json_encode( $answer );
		die;
	}
}
