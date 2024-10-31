<?php
/**
 * Connect with easyping.me server Class.
 *
 * @package easyping\Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Install Class.
 */
class EPME_Connects {

	/** @var string */
	private static $server = 'https://k.easyping.me/api/';

	/**
	 * Request to the easyping.me.
	 *
	 * @param  array $data
	 * @return mixed
	 */
	private static function request ( $data ) {
		// easyping.me project token
		$token = base64_encode( EPME_Tokens::get_token() );
		$token = ( $token ) ? $token : 'null';

		// Data to json
		$data = json_encode( $data );

		// Build the header
		$header[] = 'Content-length: ' . strlen( $data );
		$header[] = 'Accept: application/json';
		$header[] = 'Content-type: application/json; charset=utf-8';
		$header[] = 'Token: WP ' . $token;

		// Set up a CURL channel.
		$http_channel = curl_init();

		// Prime the channel
		curl_setopt( $http_channel, CURLOPT_URL, self::$server );
		curl_setopt( $http_channel, CURLOPT_HTTPHEADER, $header );
		curl_setopt( $http_channel, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)' );
		curl_setopt( $http_channel, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $http_channel, CURLOPT_POSTFIELDS, $data );
		curl_setopt( $http_channel, CURLOPT_RETURNTRANSFER, true );

		// This fetches the initial feed result.
		$initialFeed = curl_exec( $http_channel );
		$errmsg      = curl_error( $http_channel );

		if ( $initialFeed === false ) {
			return 'Error curl. '. $errmsg;
		}

		if ( !$initialFeed ) {
			return false;
		}
		return json_decode( $initialFeed );
	}

	/**
	 * Check respond to errors
	 *
	 * @param  mixed $respond
	 * @return array
	 */
	public static function check_error( $respond ) {
		if ( is_bool( $respond ) AND $respond === false ) {
			return array(
				'type' => 'error',
				'cause' => __( 'Connect problems', 'easyping.me' ),
			);
		}
		if ( is_string( $respond ) AND ( strpos( $respond, 'Error curl:' ) === 0 ) ) {
			return array(
				'type' => 'error',
				'cause' => $respond,
			);
		}

		if ( is_object( $respond ) AND is_object( $respond->status ) AND isset( $respond->status->type ) AND $respond->status->type == 'ok' ) {
			return array (
				'type' => 'ok',
				'respond' => $respond,
			);
		} else {
			return array(
				'type' => 'error',
				'cause' => ( isset( $respond->status ) AND isset( $respond->status->cause ) ) ? $respond->status->cause : '',
			);
		}
	}

	/**
	 * Request for payment link
	 *
	 * @param  string $amount
	 * @return mixed
	 */
	public static function add_funds( $amount = '10' ) {
		$request_data = array(
			'invoiceCreate',
			array(
				'amount' => $amount,
				'nonce' => EPME_Nonce::get_nonce(),
				'action' => 'easyping_add_funds',
				'answerUrl' => admin_url( 'admin-ajax.php' ),
				'redirectUrl' => admin_url( 'admin.php?page=epme-platform' ),
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Classic Sign in.
	 *
	 * @param  string $login
	 * @param  string $password
	 * @return mixed
	 */
	public static function classic_sign_in( $login, $password ) {
		global $wp_version;

		$request_data = array(
			'login',
			array(
				'email' => $login,
				'password' => $password,
				'another' => array(
					'wpVersion' => $wp_version,
					'pluginVersion' => EPME_VERSION,
				),
			),
		);


		update_option( 'EASYPING_sign_in', $request_data );
		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * First connect with Sign in.
	 *
	 * @return mixed
	 */
	public static function sign_in() {
		global $wp_version;

		$request_data = array(
			'googleAuth',
			array(
				'network' => 'google',
				'country' => substr( get_locale(), 0, 2 ),
				'company' => get_site_url(),
				'promocode' => '',
				'another' => array(
					'wpVersion' => $wp_version,
					'pluginVersion' => EPME_VERSION,
				),
				'nonce' => EPME_Nonce::get_nonce(),
				'action' => 'easyping_sign_in',
				'answerUrl' => admin_url( 'admin-ajax.php' ),
				'redirectUrl' => admin_url( 'admin.php?page=epme-platform' ),
			),
		);


		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Change project name
	 *
	 * @param  string $project_name
	 * @return mixed
	 */
	public static function change_name( $project_name ) {
		$request_data = array(
			'changeProject',
			array(
				'token' => EPME_Authorization::get_token(),
				'project' => $project_name,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Add Redeem special code
	 *
	 * @param  string $redeem
	 * @return mixed
	 */
	public static function add_redeem( $redeem ) {
		$request_data = array(
			'addPromocode',
			array(
				'token' => EPME_Authorization::get_token(),
				'promocode' => $redeem,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Change country
	 *
	 * @param  string $country
	 * @return mixed
	 */
	public static function change_country( $country ) {
		$request_data = array(
			'changeCountry',
			array(
				'token' => EPME_Authorization::get_token(),
				'country' => $country,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Handshaking with easyping.me
	 *
	 * @return mixed
	 */
	public static function handshaking() {
		global $wp_version;

		$request_data = array(
			'handshaking',
			array(
				'token' => EPME_Authorization::get_token(),
				'another' => array(
					'wpVersion' => $wp_version,
					'pluginVersion' => EPME_VERSION,
				),
				'nonce' => EPME_Nonce::get_nonce(),
				'action' => 'easyping_sign_in',
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Request to Edit channel
	 *
	 * @param  string $channel
	 * @param  string $token
	 * @param  string $name
	 * @param  string $id
	 * @param  string $active
	 * @return mixed
	 */
	public static function edit_channel( $channel, $token, $name, $id, $active ) {
		$request_data = array(
			'channelUpdate',
			array(
				'id' => "$id",
				'name' => $name,
				'networkToken' => "$token",
				'network' => "$channel",
				'active' => $active,
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Request to Create channel (first stage)
	 *
	 * @param  string $channel
	 * @param  string $token
	 * @param  string $name
	 * @return mixed
	 */
	public static function create_channel( $channel, $token, $name ) {
		$request_data = array(
			'channelCreate',
			array(
				'networkToken' => "$token",
				'name' => "$name",
				'network' => "$channel",
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Request to Create channel (second stage)
	 *
	 * @param  string $channel
	 * @param  string $id
	 * @param  string $token
	 * @param  array $changeBalance
	 * @return mixed
	 */
	public static function create_channel_processed( $channel, $id, $token, $changeBalance = array() ) {
		if ( empty( $changeBalance ) ) {
			$request_data = array(
				'networkPageAdd',
				array(
					'id' => "$id",
					'network' => $channel,
					'networkToken' => "$token",
					'token' => EPME_Authorization::get_token(),
				),
			);
		} else {
			$request_data = array(
				'networkPageAdd',
				$changeBalance,
			);
		}

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * List of Channels.
	 *
	 * @return mixed
	 */
	public static function channel_read_list() {
		$request_data = array(
			'channelReadList',
			array(
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Channel by id.
	 *
	 * @param  integer $id
	 * @return mixed
	 */
	public static function channel_read( $id ) {
		$request_data = array(
			'channelRead',
			array(
				'id' => $id,
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Request to Create channel (second stage with AGREE)
	 *
	 * @param  array $respond
	 * @return mixed
	 */
	public static function create_channel_with_agree( $respond = array() ) {
		$request_data = array(
			'channelCreate',
			$respond,
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * First connect with Sign in
	 *
	 * @param  string $prefix
	 * @return mixed
	 */
	public static function network_oauth_url( $prefix ) {
		$request_data = array(
			'networkOauthUrl',
			array(
				'network' => $prefix,
				'nonce' => EPME_Nonce::get_nonce(),
				'action' => 'easyping_oauth_url',
				'answerUrl' => admin_url( 'admin-ajax.php' ),
				'redirectUrl' => admin_url( 'admin.php?page=epme-channels' ),
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Create new Widget.
	 *
	 * @param  array $respond
	 * @return mixed
	 */
	public static function create_widget( $respond = array() ) {
		if ( empty( $respond ) ) {
			$request_data = array(
				'subscriptionButtonCreate',
				array(
					'token' => EPME_Authorization::get_token(),
					'name' => 'My new Widget',
					'withEmail' => '1',
					'active' => false,
					'buttonColor' => '#000000',
					'textColor' => '#eb7a1e',
				),
			);
		} else {
			$request_data = array(
				'subscriptionButtonCreate',
				$respond,
			);
		}

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Update Widget.
	 *
	 * @param  array $data
	 * @return mixed
	 */
	public static function update_widget( $data ) {
		$request_data = array(
			'subscriptionButtonUpdate',
			$data,
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Delete Widget.
	 *
	 * @param  integer $id
	 * @return mixed
	 */
	public static function delete_widget( $id ) {
		$request_data = array(
			'subscriptionButtonDelete',
			array(
				'token' => EPME_Authorization::get_token(),
				'id' => $id,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Active Widget.
	 *
	 * @param  array $data
	 * @return mixed
	 */
	public static function active_widget( $data ) {
		$request_data = array(
			'subscriptionButtonUpdate',
			$data,
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * List of Widgets.
	 *
	 * @return mixed
	 */
	public static function get_widgets() {
		$request_data = array(
			'subscriptionButtonReadList',
			array(
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * List of Widgets.
	 *
	 * @param  integer $id
	 * @return mixed
	 */
	public static function get_widget_by_id( $id ) {
		$request_data = array(
			'subscriptionButtonRead',
			array(
				'token' => EPME_Authorization::get_token(),
				'id' => (int)$id,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * List of Widgets.
	 *
	 * @return mixed
	 */
	public static function get_subscribers() {
		$request_data = array(
			'subscriberReadList',
			array(
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * List of Campaigns.
	 *
	 * @return mixed
	 */
	public static function get_campaigns() {
		$request_data = array(
			'campaignReadList',
			array(
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Create Campaign.
	 *
	 * @param  string $name
	 * @param  string $note
	 * @return mixed
	 */
	public static function create_campaign( $name, $note = '' ) {
		$request_data = array(
			'campaignCreate',
			array(
				'token' => EPME_Authorization::get_token(),
				'name' => $name,
				'notes' => $note,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Update Campaign.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @param  string $note
	 * @param  string $date
	 * @return mixed
	 */
	public static function update_campaign( $id, $name, $note = '', $date = '' ) {
		$request_data = array(
			'campaignUpdate',
			array(
				'token' => EPME_Authorization::get_token(),
				'id' => "$id",
				'name' => $name,
				'notes' => $note,
				'dateStart' => $date,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Update Campaign.
	 *
	 * @param  integer $id
	 * @param  array $masterMessageBlock
	 * @param  array $messageBlocks_result
	 * @return mixed
	 */
	public static function message_block_create( $id, $masterMessageBlock, $messageBlocks_result ) {
		$request_data = array(
			'batchMessageBlockCreate',
			array(
				'token' => EPME_Authorization::get_token(),
				'campaignId' => "$id",
				'masterMessageBlock' => $masterMessageBlock,
				'messageBlocks' => $messageBlocks_result,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Update Campaign.
	 *
	 * @param  integer $id
	 * @return mixed
	 */
	public static function delete_campaign( $id ) {
		$request_data = array(
			'campaignDelete',
			array(
				'token' => EPME_Authorization::get_token(),
				'id' => "$id",
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Return Campaign by id.
	 *
	 * @param  integer $id
	 * @return mixed
	 */
	public static function get_campaign( $id ) {
		$request_data = array(
			'campaignRead',
			array(
				'token' => EPME_Authorization::get_token(),
				'id' => "$id",
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Return filters for subscriptions list.
	 *
	 * @return mixed
	 */
	public static function get_filters() {
		$request_data = array(
			'contactFilterReadList',
			array(
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Create subscriber's 'list.
	 *
	 * @param  string $name
	 * @param  string $filters
	 * @param  string $note
	 * @return mixed
	 */
	public static function create_subscriber_list( $name, $filters, $note = '' ) {
		$request_data = array(
			'contactFilterTemplateCreate',
			array(
				'token' => EPME_Authorization::get_token(),
				'filters' => $filters,
				'name' => $name,
				'notes' => $note,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Create subscriber's 'list.
	 *
	 * @param  array $data
	 * @return mixed
	 */
	public static function filters_done( $data ) {
		$request_data = array(
			'contactFilter',
			$data,
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * List of Testers.
	 *
	 * @return mixed
	 */
	public static function get_testers() {
		$request_data = array(
			'authorisedContactReadList',
			array(
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Return token for link into cabinet EP.
	 *
	 * @return mixed
	 */
	public static function get_cabinet_login_token() {
		$request_data = array(
			'cabinetLoginToken',
			array(
				'token' => EPME_Authorization::get_token(),
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Delete the Tester.
	 *
	 * @param  integer $id
	 * @return mixed
	 */
	public static function delete_tester( $id ) {
		$request_data = array(
			'authorisedContactDelete',
			array(
				'token' => EPME_Authorization::get_token(),
				'id' => "$id",
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}

	/**
	 * Delete the Tester.
	 *
	 * @param  string $content_type
	 * @param  array $data
	 * @return mixed
	 */
	public static function send_to_preview( $content_type, $data ) {
		$request_data = array(
			'sendPreviewMessage',
			array(
				'token' => EPME_Authorization::get_token(),
				'socialNetworkType' => "$content_type",
				'messageBlocks' => $data,
			),
		);

		$respond = self::check_error( self::request( $request_data ) );

		return $respond;
	}
}
