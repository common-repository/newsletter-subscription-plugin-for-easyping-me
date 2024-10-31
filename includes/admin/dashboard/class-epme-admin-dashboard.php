<?php
/**
 * Admin Dashboard
 *
 * Functions used for displaying dashboard page in admin.
 *
 * @author      easyping.me
 * @category    Admin
 * @package     easyping\Admin\Dashboard
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'EPME_Admin_Dashboard', false ) ) {
	return;
}

/**
 * EPME_Admin_Dashboard Class.
 */
class EPME_Admin_Dashboard {

	/**
	 * Handles output of the dashboard page in admin.
	 */
	public static function output() {
		EPME_Admin_Output::sources();

		$title = __( 'Dashboard', 'easyping.me' );
		$description = __( '' );
		EPME_Admin_Output::layout_start( $title, $description );
		?>
        <div class="epme-card epme-card-sidebar mdl-card mdl-shadow--2dp epme-card--dashboard epme-card--wide epme-card--left">
            <div class="epme-card__header epme-card__header--dashboard"></div>
            <div class="epme-card__cont epme-card__cont--sidebar">
                <div class="epme-cont-block">
                    <div class="mdl-card__title epme-card__title--sidebar">
                        <div class="epme__sub-titel--small"><?php echo __( 'Dear friend!', 'easyping.me' ); ?></div>
                    </div>
                    <div class="mdl-card__supporting-text"><?php echo __( "You are one of the first who will use this plugin. We truly believe that together we can achieve good results. You will get more customers and conversion and we will get your love and support. With next versions we will replace this page with a Dashboard. However, we want to know from you which indicators and statistics you will find really useful to see here.", 'easyping.me' ); ?></div>

                    <div class="mdl-card__supporting-text mdl-card__supporting-text--bold"><?php echo __( "Let's get the results with subscription done right!", 'easyping.me' ); ?></div>
                </div>

                <div class="mdl-card__title epme-card__title--sidebar">
                    <div class="epme__sub-titel--small"><?php echo __( "There are 5 easy steps you need to complete. Don't hesitate to ask us - we are here to help!", 'easyping.me' ); ?></div>
                </div>
                <ol>
                    <li>
                        <?php echo sprintf( esc_html__( 'As WordPress is just a CMS and has no functionality to work with social messaging you have to %1$ssign up to our Platform%2$s. It is 1 tap action with your Google account.', 'easyping.me' ), '<a href="'. admin_url( 'admin.php?page=epme-platform' ) .'">', '</a>' ); ?>
                        <br>
	                    <?php echo __( "All messaging and subscribing is processed on our platform secured according to strict requirements of EU GDPR.", 'easyping.me' ); ?>
                    </li>
                    <li>
	                    <?php echo sprintf( esc_html__( '%1$sConnect accounts%2$s of your social networks and messengers which your visitors will subscribe to you with.', 'easyping.me' ), '<a href="'. admin_url( 'admin.php?page=epme-channels' ) .'">', '</a>' ); ?>
                        <br>
	                    <?php echo __( "We do not store any passwords - just tokens, so your social accounts are secure.", 'easyping.me' ); ?>
                    </li>
                    <li>
	                    <?php echo sprintf( esc_html__( '%1$sCreate subscription buttons%2$s for all the pages you plan to gain subscribers at.', 'easyping.me' ), '<a href="'. admin_url( 'admin.php?page=epme-widgets' ) .'">', '</a>' ); ?>
                        <br>
	                    <?php echo __( "We recommend to make as many buttons as you have places to insert the button. So you will have information which buttons are performing better and what was the initial interest of subscriber. Later on you can use this information for better segmentation of you subscribers.", 'easyping.me' ); ?>
                    </li>
                    <li>
	                    <?php echo __( "Place the buttons on your pages. This is how you usually add or edit your pages in WordPress.", 'easyping.me' ); ?>
                    </li>
                    <li>
		                <?php echo sprintf( esc_html__( 'Start %1$sgaining subscribers%2$s with the buttons. Watch how your base grows.', 'easyping.me' ), '<a href="'. admin_url( 'admin.php?page=epme-subscribers' ) .'">', '</a>' ); ?>
                    </li>
                    <li>
	                    <?php echo sprintf( esc_html__( 'Easily %1$screate outbound campaings and send newsletters%2$s, deals and updates.', 'easyping.me' ), '<a href="'. admin_url( 'admin.php?page=epme-campaigns' ) .'">', '</a>' ); ?>
                        <br>
		                <?php echo __( "Create lists, personalised messaging campaigns and distribute your message with a speed of light directly to the subscriber's Facebook Messenger or WhatsApp or Viber or other social messaging app. Open rate is at least 4 times higher!", 'easyping.me' ); ?>
                    </li>
                </ol>
            </div>
        </div>
		<?php
		EPME_Admin_Output::layout_end();
	}
}
