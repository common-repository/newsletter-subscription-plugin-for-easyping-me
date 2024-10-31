<?php
/**
 * Admin Error Page
 *
 * Functions used for displaying error page in admin.
 *
 * @author      easyping.me
 * @category    Admin
 * @package     easyping\Admin\Error
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'EPME_Admin_Error_Page', false ) ) {
	return;
}

/**
 * EPME_Admin_Dashboard Class.
 */
class EPME_Admin_Error_Page {

	/**
	 * Handles output of the dashboard page in admin.
	 */
	public static function output() {
		EPME_Admin_Output::sources();

		$title = __( 'Not allowed this page', 'easyping.me' );
		$description = __( '' );
		EPME_Admin_Output::layout_start( $title, $description );
		?>
		<div class="epme-cont">
			<p><?php echo sprintf( esc_html__( 'Sorry, seems you have to sign-in to our %1$sPlatform%2$s first to see this page.', 'easyping.me' ), '<a href="'. admin_url( 'admin.php?page=epme-platform' ) .'">', '</a>' ); ?></p>
			<p><a href="javascript:history.back()">← <?php echo __( 'Back', 'easyping.me' ) ?></a></p>
		</div>
		<?php
		EPME_Admin_Output::layout_end();
	}
}
