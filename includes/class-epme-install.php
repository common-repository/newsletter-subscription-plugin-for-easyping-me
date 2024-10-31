<?php
/**
 * Installation related functions and actions.
 *
 * @package easyping/Classes
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Install Class.
 */
class EPME_Install {

	/**
	 * Hook in tabs.
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
		add_action( 'init', array( __CLASS__, 'setup_token' ), 5 );
	}

	/**
	 * Setup easyping.me token if it is declared as a constant
	 */
	public static function setup_token() {
		if ( defined( 'EASYPING_TOKEN' ) AND EASYPING_TOKEN ) {
			update_option( 'EASYPING_TOKEN', EASYPING_TOKEN );
		}
	}

	/**
	 * Check easyping.me version and run the updater is required.
	 *
	 * This check is done on all requests and runs if the versions do not match.
	 */
	public static function check_version() {
		if ( ! defined( 'IFRAME_REQUEST' ) && version_compare( get_option( 'easyping_version' ), EPME_EP()->version, '<' ) ) {
			self::install();
			do_action( 'easyping_updated' );
		}
	}

	/**
	 * Install EP.
	 */
	public static function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( 'yes' === get_transient( 'epme_installing' ) ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'epme_installing', 'yes', MINUTE_IN_SECONDS * 10 );
		epme_maybe_define_constant( 'EPME_INSTALLING', true );

		self::create_taxonomies();
		self::create_post_types();
//		self::create_roles();

		delete_transient( 'epme_installing' );

		do_action( 'easyping_installed' );
	}

	/**
	 * Add the EPME taxonomies.
	 */
	public static function create_taxonomies() {
		/*register_taxonomy(
			'epme-channels',
			apply_filters( 'epme_taxonomy_objects_channels', array( 'epme-channel' ) ),
			apply_filters( 'epme_taxonomy_args_channels', array(
				'labels'       => array(
					'name' => 'EPME channels',
				),
				'hierarchical' => true,
				'show_ui'      => false,
				'query_var'    => true,
				'rewrite'      => false,
			) )
		);*/
	}

	/**
	 * Add the EPME post types.
	 */
	public static function create_post_types() {
		/*$args = array(
			'labels'              => array(
				'name' => 'EPME Channel',
			),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_admin_bar'   => false,
		);

		register_post_type( 'epme-channel', $args );*/
	}
}

EPME_Install::init();
