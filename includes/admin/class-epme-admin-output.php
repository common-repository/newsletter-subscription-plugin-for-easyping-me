<?php
/**
 * Output Class
 *
 * Auxiliary functions for display in the admin panel
 *
 * @author      easyping.me
 * @category    Admin\Classes
 * @package     easyping
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'EPME_Admin_Output', false ) ) {
	return;
}

/**
 * EPME_Admin_Dashboard Class.
 */
class EPME_Admin_Output {

	/**
	 * Storage of the texts for the frontend.
	 *
	 * @var $texts
	 */
	protected static $texts = array();

	/**
	 * Register styles and scripts for admin.
	 */
	public static function register() {
		wp_register_style( 'epme-material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );
		wp_register_style( 'epme-material-styles', plugins_url( '/assets/css/epme-material.deep_orange-blue.min.css', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_style( 'epme-materialcolorpicker', plugins_url( '/assets/css/epme-materialcolorpicker.css', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_style( 'epme-jqueryui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css' );
		wp_register_style( 'epme-emojipicker', plugins_url( '/assets/css/epme-emojipicker.css', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_style( 'epme-emojipicker-g', plugins_url( '/assets/css/epme-emojipicker.g.css', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_style( 'epme-select2', plugins_url( '/assets/css/epme-select2.min.css', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_style( 'epme-admin-styles', plugins_url( '/assets/css/epme-admin-styles.css', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_style( 'epme-admin-media', plugins_url( '/assets/css/epme-admin-media.css', EPME_ASSETS_DIR ), null, EPME_VERSION );

		wp_register_script( 'epme-material-js', plugins_url( '/assets/js/epme-material.min.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-materialcolorpicker-js', plugins_url( '/assets/js/epme-materialcolorpicker.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-select2-js', plugins_url( '/assets/js/epme-select2.min.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-tabs', plugins_url( '/assets/js/epme-tabs.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-date-format', plugins_url( '/assets/js/epme-date-format.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-js', plugins_url( '/assets/js/epme-admin-js.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-editors', plugins_url( '/assets/js/epme-admin-editors.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-platform', plugins_url( '/assets/js/epme-admin-platform.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-channels', plugins_url( '/assets/js/epme-admin-channels.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-widgets', plugins_url( '/assets/js/admin-widgets/epme-admin-widgets.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-widgets-preview', plugins_url( '/assets/js/admin-widgets/epme-admin-widgets-preview.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-widgets-server', plugins_url( '/assets/js/admin-widgets/epme-admin-widgets-server.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-emojipicker', plugins_url( '/assets/js/epme-emojipicker.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-emojis', plugins_url( '/assets/js/epme-emojis.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-iframe-resizer', plugins_url( '/assets/js/epme-iframe-resizer.min.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-campaigns', plugins_url( '/assets/js/admin-campaigns/epme-admin-campaigns.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-modal-image', plugins_url( '/assets/js/epme-admin-modal-image.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-campaigns-server', plugins_url( '/assets/js/admin-campaigns/epme-admin-campaigns-server.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-campaigns-media-uploader', plugins_url( '/assets/js/admin-campaigns/epme-admin-campaigns-media-uploader.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-message-blocks', plugins_url( '/assets/js/admin-campaigns/epme-admin-message-blocks.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-campaigns-main', plugins_url( '/assets/js/admin-campaigns/epme-admin-campaigns-main.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-list-create', plugins_url( '/assets/js/admin-subscribers/epme-admin-list-create.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-subscribers-server', plugins_url( '/assets/js/admin-subscribers/epme-admin-subscribers-server.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-testers', plugins_url( '/assets/js/admin-subscribers/epme-admin-testers.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-subscribers-main', plugins_url( '/assets/js/admin-subscribers/epme-admin-subscribers-main.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-welcome-form', plugins_url( '/assets/js/epme-admin-welcome-form.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
		wp_register_script( 'epme-admin-dev', plugins_url( '/assets/js/epme-admin-dev.js', EPME_ASSETS_DIR ), null, EPME_VERSION );
	}

	/**
	 * Handles output of the styles and scripts in admin.
	 */
	public static function sources() {
		wp_enqueue_style( 'epme-material-icons' );
		wp_enqueue_style( 'epme-material-styles' );

		wp_enqueue_style( 'epme-subscribe-button-style' );
		wp_enqueue_style( 'epme-materialcolorpicker' );

		if ( EPME_Admin_Menus::get_admin_page_name() === 'campaigns' ) {
			wp_enqueue_style( 'epme-jqueryui' );
			wp_enqueue_style( 'epme-emojipicker' );
			wp_enqueue_style( 'epme-emojipicker-g' );
			wp_enqueue_style( 'epme-select2' );

			wp_enqueue_script( 'epme-emojipicker' );
			wp_enqueue_script( 'epme-emojis' );
		}

		if ( EPME_Admin_Menus::get_admin_page_name() === 'subscribers' ) {
			wp_enqueue_style( 'epme-select2' );
		}

		wp_enqueue_style( 'epme-admin-styles' );
		wp_enqueue_style( 'epme-admin-media' );

		wp_enqueue_script( 'epme-material-js' );
		wp_enqueue_script( 'epme-materialcolorpicker-js' );
		wp_enqueue_script( 'epme-subscribe-button' );
		wp_enqueue_script( 'epme-date-format' );
		wp_enqueue_script( 'epme-iframe-resizer' );
		wp_enqueue_script( 'epme-tabs' );
		wp_enqueue_script( 'epme-admin-js' );
		wp_enqueue_script( 'epme-admin-editors' );
		wp_enqueue_script( 'epme-admin-platform' );
		wp_enqueue_script( 'epme-admin-channels' );
		wp_enqueue_script( 'epme-admin-welcome-form' );
		wp_enqueue_script( 'epme-admin-dev' );

		if ( EPME_Admin_Menus::get_admin_page_name() === 'widgets' ) {
			wp_enqueue_script( 'epme-admin-widgets' );
			wp_enqueue_script( 'epme-admin-widgets-preview' );
			wp_enqueue_script( 'epme-admin-widgets-server' );
		}

		if ( EPME_Admin_Menus::get_admin_page_name() === 'campaigns' ) {
			wp_enqueue_media();
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'epme-select2-js' );
			wp_enqueue_script( 'epme-admin-modal-image' );
			wp_enqueue_script( 'epme-admin-campaigns' );
			wp_enqueue_script( 'epme-admin-campaigns-media-uploader' );
			wp_enqueue_script( 'epme-admin-message-blocks' );
			wp_enqueue_script( 'epme-admin-campaigns-server' );
			wp_enqueue_script( 'epme-admin-campaigns-main' );
		}

		if ( EPME_Admin_Menus::get_admin_page_name() === 'subscribers' ) {
			wp_enqueue_script( 'epme-select2-js' );
			wp_enqueue_script( 'epme-admin-campaigns' );
			wp_enqueue_script( 'epme-admin-list-create' );
			wp_enqueue_script( 'epme-admin-testers' );
			wp_enqueue_script( 'epme-admin-subscribers-server' );
			wp_enqueue_script( 'epme-admin-subscribers-main' );
		}

		wp_localize_script( 'epme-material-js', 'epme_ajaxurl',
			array(
				'url' => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'easyping.me' )
			) );
	}

	/**
	 * Handles output of start of the layout in admin.
	 *
	 * @param  string $title.
	 * @param  string $description.
	 * @param  boolean $is_top.
	 * @param  boolean $is_header.
	 */
	public static function layout_start( $title = '', $description = '', $is_top = true, $is_header = true ) {
		?>
		<div class="mdl-grid epme">
			<?php if ( $is_top ) self::top(); ?>
			<div class="mdl-cell mdl-cell--9-col epme__cont">
		<?php if ( $is_header ) self::header( $title, $description ); ?>
		<?php
	}

	/**
	 * Handles output of end of the layout in admin.
	 */
	public static function layout_end() {
		?>
			</div>
			<?php self::sidebar(); ?>
			<?php self::welcome_form(); ?>
            <div class="epme-modal epme-modal--dialog epme-modal--narrow">
                <div class="epme-modal__cont">
                    <h4 class="mdl-dialog__title"></h4>
                    <div class="mdl-dialog__content"></div>
                    <div class="mdl-dialog__actions">
                        <button
                            class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored epme-modal__save">
                            <?php echo __( 'Save', 'easyping.me' ); ?>
                        </button>
                        <button type="button" class="mdl-button epme-modal__close"><?php echo __( 'Close', 'easyping.me' ); ?></button>
                    </div>
                    <div class="epme-modal__loading"><div class="mdl-spinner mdl-js-spinner is-active"></div></div>
                </div>
                <div class="epme-modal__overlay epme-modal__close"></div>
            </div>
		</div>
        <div id="error-box" class="mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text"></div>
            <button class="mdl-snackbar__action" type="button"></button>
        </div>
        <div id="message-box" class="mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text"></div>
            <button class="mdl-snackbar__action" type="button"></button>
        </div>
        <?php
        $y1 = "https://mc.yandex.ru/metrika/tag.js";
        $y2 = "https://mc.yandex.ru/watch/53943658";
        ?>
        <script type="application/json" id="epme-texts"><?php echo json_encode( self::$texts ); ?></script>
        <!--<script type="text/javascript" >
            (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
            (window, document, "script", '<?php echo $y1; ?>', "ym");
            ym(53943658, "init", {
                clickmap:true,
                trackLinks:true,
                accurateTrackBounce:true,
                webvisor:true
            });
        </script>-->
<!--        <noscript><div><img src="--><?php //echo $y2; ?><!--" style="position:absolute; left:-9999px;" alt="" /></div></noscript>-->
		<?php
	}

	/**
	 * Handles output of sidebar layout in admin.
	 */
	public static function sidebar() {
		?>
        <div class="mdl-cell mdl-cell--3-col epme-sidebar">
            <div class="epme-card epme-card-sidebar epme-card--radius mdl-card mdl-shadow--2dp">
                <div class="epme-card__cont epme-card-sidebar__cont">
                    <div class="mdl-card__title epme-card__title--sidebar epme-card__title--pad-6">
                        <div class="epme__sub-titel--small"><?php echo __( 'If you like our plugin please support us with your 5-star vote. It means a world for us!', 'easyping.me' ); ?></div>
                    </div>
                    <div class="mdl-card__supporting-text"><?php echo __( 'The plugin will remain free forever and we will deliver new super-tools for you to get more interaction with customers.', 'easyping.me' ); ?>
                    </div>
                    <div class="mdl-card__actions mdl-card--border epme-card__actions">
                        <a href="https://wordpress.org/plugins/newsletter-subscription-plugin-for-easyping-me/" class="epme-card__btn" target="_blank">
	                        <?php echo __( 'VOTE 5-STAR 😊', 'easyping.me' ); ?>
                        </a>
                    </div>
                    <div class="mdl-card__menu mdl-card__menu--down">
                        <a href="https://wordpress.org/plugins/newsletter-subscription-plugin-for-easyping-me/" class="" target="_blank"><button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect  mdc-button--grey">
                            <i class="material-icons">favorite_border</i>
                            </button></a>
                    </div>
                </div>
            </div>
            <div class="epme-card epme-card-sidebar epme-card--radius mdl-card mdl-shadow--2dp">
                <div class="epme-card__header epme-card__header--support"></div>
                <div class="epme-card__cont epme-card__cont--sidebar">
                    <div class="mdl-card__title epme-card__title--sidebar">
                        <div class="epme__sub-titel--small"><?php echo __( 'Support center', 'easyping.me' ); ?></div>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <ul>
                            <li><?php echo sprintf( esc_html__( 'Do you have questions about easyping.me service? Find answers %1$shere%2$s!', 'easyping.me' ), '<a href="https://easyping.me/faq/?utm_source=wp-plugin&utm_medium=organic&utm_campaign=plugin_side_panel&utm_content=faq" target="_blank">', '</a>' ); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
<!--            <div class="epme-card epme-card-sidebar mdl-card mdl-shadow--2dp">-->
<!--                <div class="epme-card__header epme-card__header--sidebar"></div>-->
<!--                <div class="epme-card__cont epme-card__cont--sidebar">-->
<!--                    <div class="mdl-card__title epme-card__title--sidebar">-->
<!--                        <div class="epme__sub-titel--small">--><?php //echo __( 'Premium plan', 'easyping.me' ); ?><!--</div>-->
<!--                    </div>-->
<!--                    <div class="mdl-card__supporting-text mdl-card__supporting-text--bold">--><?php //echo __( "It's awesome:)", 'easyping.me' ); ?><!--</div>-->
<!--                    <div class="mdl-card__supporting-text">--><?php //echo __( "Need more features or cheaper outbound messages? Premium plan save you a lot!", 'easyping.me' ); ?><!--</div>-->
<!--                    <div class="mdl-card__actions mdl-card--border epme-card__actions">-->
<!--                        <a class="epme-card__btn">--><?php //echo __( "Read", 'easyping.me' ); ?><!--</a>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
		<?php
	}

	/**
	 * Header top for admin pages.
	 */
	public static function top() {
		?>
		<div class="mdl-cell"><img src="<?php echo EPME_ASSETS_URL . '/img/easyping-logo-full.png' ?>" alt="easyping.me logo" width="300"></div>
		<?php
	}

	/**
	 * Header for admin pages.
	 *
	 * @param  string $title.
	 * @param  string $description.
	 */
	public static function header( $title, $description ) {
        if ( $title ) {
	        ?>
            <div class="mdl-progress mdl-js-progress mdl-progress__indeterminate epme-loading"></div>
            <div class="epme-overlay"></div>
            <h3 class="mdl-typography--headline"><?php echo $title; ?></h3>
	        <?php
        }
        if ( $description ) {
            ?>
            <p><?php echo $description; ?></p>
            <?php
        }
        ?>
		<?php
	}

	/**
	 * Storage of the texts for the frontend.
	 *
	 * @param  string $name.
	 * @param  string $value.
	 */
	public static function add_text( $name, $value ) {
        if ( $name ) {
            self::$texts[$name] = $value;
        }
	}

	/**
	 * Display welcome form.
	 */
	public static function welcome_form() {
        if ( !get_option( 'epme-welcome-form--' . EPME_Authorization::get_email() ) AND EPME_Authorization::is_authorization() ) {
	        ?>
            <div class="epme-modal epme-modal--welcome-form epme-welcome-form" data-login="<?php echo EPME_Authorization::get_email(); ?>">
                <div class="epme-modal__cont">
                    <div class="mdl-dialog__content">
                        <iframe class="epme-welcome-form__iframe"
                                src="https://easyping.me/internal/wp-welcome-form/?utm_source=wp_plugin&utm_medium=welcome_splash&utm_campaign=onboarding&utm_content=<?php echo EPME_Authorization::get_email(); ?>&utm_term=<?php echo get_site_url(); ?>"
                                frameborder="0"></iframe>
                    </div>
                    <div class="mdl-dialog__actions">
                        <button type="button"
                                class="mdl-button epme-modal__close"><?php echo __( 'Close', 'easyping.me' ); ?></button>
                    </div>
                </div>
                <div class="epme-modal__overlay epme-modal__close"></div>
            </div>
	        <?php
        }
	}
}
