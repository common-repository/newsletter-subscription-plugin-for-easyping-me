<?php
/**
 * Main easyping.me Class.
 *
 * @class easyping
 * @version	1.0.6
 */
final class EPME_easyping {

	/**
	 * easyping version.
	 *
	 * @var string
	 */
	public $version = '1.0.6';

	/**
	 * The single instance of the class.
	 *
	 * @var EPME_easyping
	 */
	protected static $_instance = null;

	/**
	 * Main easyping.me Instance.
	 *
	 * Ensures only one instance of easyping is loaded or can be loaded.
	 *
	 * @static
	 * @see EPME_EP()
	 * @return EPME_easyping - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * easyping Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
		$this->init_shortcodes();

		if ( !is_admin() ) {
			EPME_Widgets::show_fixed_sub_button();
		}

		do_action( 'easyping_loaded' );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' ) && ! defined( 'REST_REQUEST' );
		}
	}

	/**
	 * Define easyping.me Constants.
	 */
	private function define_constants() {
		$this->define( 'EPME_ASSETS_DIR', EPME_PLUGIN_DIR . 'assets' );
		$this->define( 'EPME_ASSETS_URL', get_option( 'siteurl' ) . str_replace( str_replace( '\\', '/', ABSPATH ), '/', EPME_ASSETS_DIR ) );
		$this->define( 'EPME_VERSION', $this->version );
		$this->define( 'EPME_SERVER', 'https://pm.chat' );
	}

	/**
	 * Hook into actions and filters.
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_nopriv_easyping_sign_in', array( 'EPME_Requests', 'sign_in_easyping' ), 10 );
		add_action( 'wp_ajax_nopriv_easyping_add_funds', array( 'EPME_Requests', 'add_funds' ), 20 );
		add_action( 'wp_ajax_nopriv_easyping_oauth_url', array( 'EPME_Requests', 'easyping_oauth_url' ), 30 );

		add_action( 'wp_enqueue_scripts', array( 'EPME_Media', 'register' ), 10 );

		add_action( 'admin_enqueue_scripts', array( 'EPME_Media', 'register' ), 10 );
		add_action( 'admin_enqueue_scripts', array( 'EPME_Admin_Output', 'register' ), 20 );
	}

	/**
	 * Hook into shortcodes.
	 */
	private function init_shortcodes() {
		add_shortcode( 'epme_widget', array( 'EPME_Shortcodes', 'widget' ) );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		if ( $this->is_request( 'admin' ) ) {
			include_once EPME_PLUGIN_DIR . 'includes/admin/class-epme-admin.php';
		}

		/**
		 * Core classes.
		 */
		include_once EPME_PLUGIN_DIR . 'includes/epme-core-functions.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-connects.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-tokens.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-nonce.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-authorization.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-ep-account.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-install.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-requests.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-shortcodes.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-media.php';
		include_once EPME_PLUGIN_DIR . 'includes/class-epme-widgets.php';
	}
}