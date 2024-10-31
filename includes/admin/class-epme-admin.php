<?php
/**
 * easyping.me Admin
 *
 * @class    EPME_Admin
 * @author   easyping.me
 * @category Admin
 * @package  easyping\Admin
 * @version  1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * EPME_Admin class.
 */
class EPME_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'admin_init' ) );
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Admin init.
	 */
	public static function admin_init() {
		self::authorization_check( false );
	}

	/**
	 * Authorization check in easyping.me.
	 *
	 * @param  boolean $show_mess
	 */
	private static function authorization_check( $show_mess = true ) {
		try {
			if ( EPME_Authorization::is_authorization() ) {
				$server_answer = EPME_Connects::handshaking();

				if ( $server_answer['type'] != 'ok' ) {
					$answer = epme_error( $server_answer['cause'] );
					EPME_Authorization::sign_out( true );
				} else {
					EPME_Authorization::update_data( $server_answer['respond'] );
					$answer = epme_ok();
				}
			} else {
				$answer = epme_error( __( 'Not authorization', 'easyping.me' ) );
			}
		} catch (Exception $e) {
			EPME_Authorization::sign_out( true );
			$answer = epme_error( __( "Exception {$e->getMessage()}", 'easyping.me' ) );
		}

		if ( $show_mess ) {
			echo json_encode( $answer );
			die;
		}
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_epme_sign_in', array( 'EPME_Admin_Ajax', 'sign_in_ajax' ), 10 );
		add_action( 'wp_ajax_epme_classic_sign_in', array( 'EPME_Admin_Ajax', 'classic_sign_in' ), 15 );
		add_action( 'wp_ajax_epme_sign_out', array( 'EPME_Admin_Ajax', 'sign_out_ajax' ), 20 );
		add_action( 'wp_ajax_epme_refresh_channels', array( 'EPME_Admin_Ajax', 'refresh_channels' ), 20 );
		add_action( 'wp_ajax_epme_change_name', array( 'EPME_Admin_Ajax', 'change_name' ), 40 );
		add_action( 'wp_ajax_epme_change_country', array( 'EPME_Admin_Ajax', 'change_country' ), 40 );
		add_action( 'wp_ajax_epme_add_redeem', array( 'EPME_Admin_Ajax', 'add_redeem' ), 40 );
		add_action( 'wp_ajax_epme_add_funds', array( 'EPME_Admin_Ajax', 'add_funds_ajax' ), 50 );
		add_action( 'wp_ajax_epme_channel', array( 'EPME_Admin_Ajax', 'save_channel' ), 50 );
		add_action( 'wp_ajax_epme_channel_processed', array( 'EPME_Admin_Ajax', 'save_channel_processed' ), 50 );
		add_action( 'wp_ajax_epme_authorize', array( 'EPME_Admin_Ajax', 'channel_with_authorize' ), 50 );
		add_action( 'wp_ajax_epme_channel_save_with_agree', array( 'EPME_Admin_Ajax', 'save_channel_with_agree' ), 60 );
		add_action( 'wp_ajax_epme_delete_oauth_progress', array( 'EPME_Admin_Ajax', 'delete_oauth_progress' ), 70 );
		add_action( 'wp_ajax_epme_widgets_list', array( 'EPME_Admin_Ajax', 'widgets_list' ), 80 );
		add_action( 'wp_ajax_epme_refresh_widgets', array( 'EPME_Admin_Ajax', 'widgets_template' ), 80 );
		add_action( 'wp_ajax_epme_create_widget', array( 'EPME_Admin_Ajax', 'create_widget' ), 90 );
		add_action( 'wp_ajax_epme_update_widget', array( 'EPME_Admin_Ajax', 'update_widget' ), 90 );
		add_action( 'wp_ajax_epme_delete_widget', array( 'EPME_Admin_Ajax', 'delete_widget' ), 100 );
		add_action( 'wp_ajax_epme_active_widget', array( 'EPME_Admin_Ajax', 'active_widget' ), 100 );
		add_action( 'wp_ajax_epme_create_campaign', array( 'EPME_Admin_Ajax', 'create_campaign' ), 110 );
		add_action( 'wp_ajax_epme_delete_campaign', array( 'EPME_Admin_Ajax', 'delete_campaign' ), 110 );
		add_action( 'wp_ajax_epme_reload_campaigns', array( 'EPME_Admin_Ajax', 'reload_campaigns' ), 120 );
		add_action( 'wp_ajax_epme_reload_campaign_footer', array( 'EPME_Admin_Ajax', 'reload_campaign_footer' ), 120 );
		add_action( 'wp_ajax_epme_filters_done', array( 'EPME_Admin_Ajax', 'filters_done' ), 130 );
		add_action( 'wp_ajax_epme_create_subscriber_list', array( 'EPME_Admin_Ajax', 'create_subscriber_list' ), 140 );
		add_action( 'wp_ajax_epme_delete_tester', array( 'EPME_Admin_Ajax', 'delete_tester' ), 150 );
		add_action( 'wp_ajax_epme_refresh_testers', array( 'EPME_Admin_Ajax', 'refresh_testers' ), 150 );
		add_action( 'wp_ajax_epme_send_to_preview', array( 'EPME_Admin_Ajax', 'send_to_preview' ), 160 );
		add_action( 'wp_ajax_epme_welcome_form_complete', array( 'EPME_Admin_Ajax', 'welcome_form_complete' ), 170 );
	}

	/**
	 * Include any classes we need within admin.
	 */
	private function includes() {
		include_once dirname( __FILE__ ) . '/class-epme-admin-ajax.php';
		include_once dirname( __FILE__ ) . '/class-epme-admin-menus.php';
		include_once dirname( __FILE__ ) . '/class-epme-admin-output.php';
		include_once dirname( __FILE__ ) . '/class-epme-admin-data.php';

		include_once dirname( __FILE__ ) . '/class-epme-admin-error-page.php';
		include_once dirname( __FILE__ ) . '/dashboard/class-epme-admin-dashboard.php';
		include_once dirname( __FILE__ ) . '/platform/class-epme-admin-platform.php';
		include_once dirname( __FILE__ ) . '/channels/class-epme-admin-channels.php';
		include_once dirname( __FILE__ ) . '/channels/epme-class-channels.php';
		include_once dirname( __FILE__ ) . '/widgets/class-epme-admin-widgets.php';
		include_once dirname( __FILE__ ) . '/subscribers/class-epme-admin-subscribers.php';
		include_once dirname( __FILE__ ) . '/subscribers/class-epme-subscribers.php';
		include_once dirname( __FILE__ ) . '/subscribers/class-epme-filter.php';
		include_once dirname( __FILE__ ) . '/subscribers/class-epme-filter-form.php';
		include_once dirname( __FILE__ ) . '/subscribers/class-epme-testers.php';
		include_once dirname( __FILE__ ) . '/campaigns/class-epme-campaign.php';
		include_once dirname( __FILE__ ) . '/campaigns/class-epme-blocks.php';
		include_once dirname( __FILE__ ) . '/campaigns/class-epme-campaign-form.php';
		include_once dirname( __FILE__ ) . '/campaigns/class-epme-admin-campaigns.php';
	}
}

return new EPME_Admin();
