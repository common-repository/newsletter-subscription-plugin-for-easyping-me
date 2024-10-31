<?php
/**
 * Admin Channels
 *
 * Functions used for displaying platform page in admin.
 *
 * @author      easyping.me
 * @category    Admin
 * @package     easyping\Admin\Channels
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EPME_Admin_Channels Class.
 */
class EPME_Admin_Channels {

	/**
	 * @var string
	 */
    protected static $oauth = '';

	/**
	 * @var array
	 */
    protected static $channels_texts = array();

	/**
	 * Init channels_texts variable.
     *
     * @return array
	 */
    public static function channels_texts() {
        if ( empty( self::$channels_texts ) ) {
	        self::$channels_texts = array(
		        'fb'      => array(
			        'slug'                => 'fb',
			        'name'                => __( 'Facebook Page', 'easyping.me' ),
			        'short'               => __( 'Facebook', 'easyping.me' ),
			        'description'         => __( 'Connect your Facebook page and your subscribers will get notified from you in Facebook Messenger app.', 'easyping.me' ),
			        'connect-description' => __( 'Your Facebook page is connected', 'easyping.me' ),
			        'add_title'           => __( 'FB Business page setup', 'easyping.me' ),
			        'edit_title'          => __( 'FB Business page setup', 'easyping.me' ),
			        'message'             => __( '', 'easyping.me' ),
		        ),
		        'twitter' => array(
			        'slug'                => 'twitter',
			        'name'                => __( 'Twitter account', 'easyping.me' ),
			        'short'               => __( 'Twitter', 'easyping.me' ),
			        'description'         => __( "Connect your Twitter account and your subscribers will receive notifications from your website as Twitter direct message.", 'easyping.me' ),
			        'connect-description' => __( "Your Twitter account is connected", 'easyping.me' ),
			        'add_title'           => __( 'Twitter account setup', 'easyping.me' ),
			        'edit_title'          => __( 'Twitter account setup', 'easyping.me' ),
			        'message'             => __( '', 'easyping.me' ),
		        ),
		        'viber'   => array(
			        'slug'                => 'viber',
			        'name'                => __( 'Viber Bot account', 'easyping.me' ),
			        'short'               => __( 'Viber', 'easyping.me' ),
			        'description'         => __( 'Connect your Viber Public account and your subscribers will sign up and receive your news updates in their Viber app.', 'easyping.me' ),
			        'connect-description' => __( 'Your Viber bot account is connected', 'easyping.me' ),
			        'add_title'           => __( 'Viber Bot setup', 'easyping.me' ),
			        'edit_title'          => __( 'Viber Bot setup', 'easyping.me' ),
			        'message'             => __( '', 'easyping.me' ),
		        ),
		        'tg'      => array(
			        'slug'                => 'tg',
			        'name'                => __( 'Telegram Bot account', 'easyping.me' ),
			        'short'               => __( 'Telegram', 'easyping.me' ),
			        'description'         => __( 'Connect your Telegram bot here and your subscribers will get comfy notifications and your news updates in their Telegram app.', 'easyping.me' ),
			        'connect-description' => __( 'Your Telegram bot account is connected', 'easyping.me' ),
			        'add_title'           => __( 'Telegram Bot setup', 'easyping.me' ),
			        'edit_title'          => __( 'Telegram Bot setup', 'easyping.me' ),
			        'message'             => __( '', 'easyping.me' ),
		        ),
		        'vk'      => array(
			        'slug'                => 'vk',
			        'name'                => __( 'VKontakte Page', 'easyping.me' ),
			        'short'               => __( 'VKontakte', 'easyping.me' ),
			        'description'         => __( "Connect your VK page and your subscribers will receive notifications from you in their VK app.", 'easyping.me' ),
			        'connect-description' => __( "Your VKontakte page is connected", 'easyping.me' ),
			        'add_title'           => __( 'VKontakte channel setup', 'easyping.me' ),
			        'edit_title'          => __( 'VKontakte channel setup', 'easyping.me' ),
			        'message'             => __( '', 'easyping.me' ),
		        ),
		        'ok'      => array(
			        'slug'                => 'ok',
			        'name'                => __( 'Odnoklassniki Group', 'easyping.me' ),
			        'short'               => __( 'Odnoklassniki', 'easyping.me' ),
			        'description'         => __( "Connect your OK group page and your subscribers will get your notifications in their OK app.", 'easyping.me' ),
			        'connect-description' => __( "Your Odnoklassniki Group is connected", 'easyping.me' ),
			        'add_title'           => __( 'Odnoklassniki Group setup', 'easyping.me' ),
			        'edit_title'          => __( 'Odnoklassniki Group setup', 'easyping.me' ),
			        'message'             => __( '', 'easyping.me' ),
		        ),
		        'wa'      => array(
			        'slug'                => 'wa',
			        'name'                => __( 'Whatsapp Business account', 'easyping.me' ),
			        'short'               => __( 'Whatsapp', 'easyping.me' ),
			        'description'         => __( "This feature is available only for verified Whatsapp Business accounts. If you have one, let us now and we will connect it for you.", 'easyping.me' ),
			        'connect-description' => __( "Your Whatsapp Business account is connected", 'easyping.me' ),
			        'add_title'           => __( 'Whatsapp Business setup', 'easyping.me' ),
			        'edit_title'          => __( 'Whatsapp Business setup', 'easyping.me' ),
			        'is_disable'          => true,
			        'add_class'           => 'epme-card--50',
		        ),
		        'ig'      => array(
			        'slug'                => 'ig',
			        'name'                => __( 'Instagram for Business account', 'easyping.me' ),
			        'short'               => __( 'Instagram', 'easyping.me' ),
			        'description'         => __( "This feature is available only for verified Instagram for Business accounts. If you have one, let us now and we will connect it for you.", 'easyping.me' ),
			        'connect-description' => __( "Your Instagram for Business account is connected", 'easyping.me' ),
			        'add_title'           => __( 'Instagram for Business account setup', 'easyping.me' ),
			        'edit_title'          => __( 'Instagram for Business account setup', 'easyping.me' ),
			        'is_disable'          => true,
			        'add_class'           => 'epme-card--50',
		        ),
	        );
        }
        return self::$channels_texts;
    }

	/**
	 * Return some column of channel information by slug.
     *
     * @param  string $slug
     * @param  string $field
     * @return string
	 */
    public static function get_channel_info( $slug, $field ) {
        $channels = self::channels_texts();
        return ( isset( $channels[$slug] ) && $channels[$slug][$field] ) ? $channels[$slug][$field] : $slug;
    }

	/**
	 * Handles output of the platform page in admin.
	 *
	 * @param  boolean $only_content
	 * @param  boolean $show
	 * @return string
	 */
	public static function output( $only_content = false, $show = true ) {
		ob_start();
		if ( !$only_content ) {
			EPME_Admin_Output::sources();

			$title = __( 'Channels', 'easyping.me' );
			$description = __( 'Connect and manage your BUSINESS social accounts so website visitors can subscribe and message you with their native apps.', 'easyping.me' );
			EPME_Admin_Output::layout_start( $title, $description );

			echo '<div class="epme-cont">';
		}

		?>
		<div class="mdl-tabs mdl-js-tabs">
			<div class="mdl-tabs__tab-bar">
				<a href="#connected-channels" class="mdl-tabs__tab <?php echo ( !isset( $_GET['new_channel'] ) ) ? 'is-active' : ''; ?>"><span class="mdc-tab__content"><i class="mdc-tab__icon material-icons">check_circle</i> <?php echo __( 'Connected channels', 'easyping.me' ); ?></span></a>
				<a href="#new-channel-tab" class="mdl-tabs__tab <?php echo ( isset( $_GET['new_channel'] ) ) ? 'is-active' : ''; ?>"><span class="mdc-tab__content"><i class="mdc-tab__icon material-icons">add_circle</i> <?php echo __( 'Add new channel', 'easyping.me' ); ?></span></a>
			</div>
			<div class="mdl-tabs__panel <?php echo ( !isset( $_GET['new_channel'] ) ) ? 'is-active' : ''; ?> epme-account" id="connected-channels">
				<div class="epme-cont__tab-cont">
					<p class="epme-cont__tab-descr"><?php echo sprintf( esc_html__( 'Choose the social channel you want to add, activate or deactivate. In case you don\'t have some of them it is quick and easy to create. Just follow instructions from our %1$sFAQ%2$s', 'easyping.me' ), '<a href="https://easyping.me/faq/wordpress/2-plugin-connecting-channels#rec95601682?utm_source=WP-plugin&utm_medium=organic&utm_campaign=faq&utm_content=channels_add_new_instructions" target="_blank">', '</a>' ); ?></p>
                    <table class="mdl-data-table mdl-js-data-table epme-channels-tbl epme-table">
                        <tbody>
                        <?php
                        foreach ( self::$channels_texts as $channel_id => $channel_texts ) {
	                        $channels_items = EPME_Channels::get_channel( $channel_id );

	                        if ( empty( $channels_items ) ) {
		                        echo self::channels_item( $channel_id, 'empty', true, $channel_texts );
                            } else {
		                        foreach ( $channels_items as $channels_item ) {
			                        echo self::channels_item( $channel_id, $channels_item['id'], $channels_item['active'], $channel_texts, $channels_item['name'] );
	                            }
                            }
                        }
                        ?>
                        </tbody>
                    </table>
				</div>
			</div>
			<div class="mdl-tabs__panel <?php echo ( isset( $_GET['new_channel'] ) AND $_GET['new_channel'] ) ? 'is-active' : ''; ?>" id="new-channel-tab">
                <div class="epme-channels__cont">
                    <?php
                    foreach ( self::$channels_texts as $channel_id => $channel_texts ) {
	                    echo self::channel_item_in_newtab( $channel_id, $channel_texts, EPME_ASSETS_URL . "/img/{$channel_id}.svg" );
                    }
                    ?>
                </div>
            </div>
			<?php

			$oauth = get_option( 'EASYPING_OAUTH' );
			if ( isset( $oauth['network'] ) AND $oauth['network'] ) {
				self::$oauth = $oauth['network'];
			}
//			echo "<div style='white-space: pre'>";
//            print_r(EPME_Channels::get_channels());
//			print_r( $oauth );
//			print_r( json_decode( $oauth['data'] ) );
//			echo "</div>";

            switch ( self::$oauth ) {
                case 'fb' :
	                echo self::channels_modal( 'fb', 'empty', EPME_Channels::fb_form( false, true ), 'add', true );
	                break;
                case 'vk' :
	                echo self::channels_modal( 'vk', 'empty', EPME_Channels::vk_form( false, true ), 'add', true );
	                break;
            }

			echo self::channels_modal( 'ok', 'empty', EPME_Channels::ok_form( false ) );
			echo self::channels_modal( 'tg', 'empty', EPME_Channels::tg_form( false ) );
			echo self::channels_modal( 'viber', 'empty', EPME_Channels::viber_form( false ) );
			echo self::channels_modal( 'twitter', 'empty', EPME_Channels::twitter_form( false ) );
			echo self::channels_modal( 'vk', 'empty', EPME_Channels::vk_form( false ) );
			echo self::channels_modal( 'fb', 'empty', EPME_Channels::fb_form( false ) );

			foreach (  EPME_Channels::get_channel( 'ok' ) as $channel ) {
				echo self::channels_modal( 'ok', $channel['id'], EPME_Channels::ok_form( $channel['id'] ) );
			}

			foreach (  EPME_Channels::get_channel( 'tg' ) as $channel ) {
				echo self::channels_modal( 'tg', $channel['id'], EPME_Channels::tg_form( $channel['id'] ) );
			}

			foreach (  EPME_Channels::get_channel( 'viber' ) as $channel ) {
				echo self::channels_modal( 'viber', $channel['id'], EPME_Channels::viber_form( $channel['id'] ) );
			}

			foreach (  EPME_Channels::get_channel( 'twitter' ) as $channel ) {
				echo self::channels_modal( 'twitter', $channel['id'], EPME_Channels::twitter_form( $channel['id'] ) );
			}

			foreach (  EPME_Channels::get_channel( 'vk' ) as $channel ) {
				echo self::channels_modal( 'vk', $channel['id'], EPME_Channels::vk_form( $channel['id'] ) );
			}

			foreach (  EPME_Channels::get_channel( 'fb' ) as $channel ) {
				echo self::channels_modal( 'fb', $channel['id'], EPME_Channels::fb_form( $channel['id'] ) );
			}
			?>
        </div>
		<?php

		if ( !$only_content ) {
			echo '</div>';
			EPME_Admin_Output::layout_end();
		}

		if ( $show ) {
			echo ob_get_clean();
			return '';
		} else {
			return ob_get_clean();
		}
	}

	/**
	 * Template of modal of channel configuration.
	 *
	 * @param  string $prefix
	 * @param  string $id
	 * @param  string $cont
	 * @param  string $title
	 * @param  boolean $is_oauth
	 * @return string
	 */
	public static function channels_modal( $prefix, $id, $cont, $title = '', $is_oauth = false ) {
		ob_start();
		?>
        <div class="epme-modal epme-modal--edit-channel epme-modal--<?php echo "$prefix"; echo ( $is_oauth ) ? '-1' : ''; ?> epme-modal--<?php echo "$id"; ?> <?php echo ( $is_oauth == $prefix ) ? 'epme-modal--processed' : ''; ?>">
            <div class="epme-modal__cont">
                <h4 class="mdl-dialog__title"><?php
                    switch ( $title ) {
                        case 'add' :
                            echo self::$channels_texts[$prefix]['add_title'];
                            break;
                        case 'edit' :
                            echo self::$channels_texts[$prefix]['edit_title'];
                            break;
                        default:
	                        echo ( $title ) ? $title : __( 'Channel configuration', 'easyping.me' );
                    }
                ?></h4>
                <div class="mdl-dialog__content">
                    <?php echo $cont; ?>
                    <div class="epme-links">
                        <a href="https://easyping.me/faq/?utm_source=wp-plugin&utm_medium=organic&utm_campaign=add_new_channel&utm_content=helpcenter" target="_blank" class="epme-link"><?php echo __( 'Helpcenter', 'easyping.me' ); ?></a>
                        <a href="https://pm.chat/easyping/?utm_source=wp-plugin&utm_medium=organic&utm_campaign=add_new_channel&utm_content=livechatrequest" target="_blank" class="epme-link"><?php echo __( 'LIVE SUPPORT', 'easyping.me' ); ?></a>
                    </div>
                </div>
                <div class="mdl-dialog__actions">
                    <button
                        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored epme-channels__save-channel" <?php /*echo ( in_array( $prefix, array( 'fb', 'vk', 'twitter' ) ) AND $id == 'empty' OR $is_oauth ) ? 'disabled' : '';*/ ?> disabled autocomplete="off">
						<?php echo ( $is_oauth == $prefix ) ?
                            __( 'Add new channel', 'easyping.me' ) :
                            __( 'Save', 'easyping.me' ) ;
                        ; ?>
                    </button>
                    <button type="button" class="mdl-button epme-modal__close" onclick="epme_refresh_channels(true);"><?php echo __( 'Close', 'easyping.me' ); ?></button>
                </div>
                <div class="epme-modal__loading"><div class="mdl-spinner mdl-js-spinner is-active"></div></div>
            </div>
	        <?php if ( $is_oauth == $prefix ) { ?>
                <div class="epme-modal__overlay"></div>
            <?php } else { ?>
                <div class="epme-modal__overlay epme-modal__close" onclick="epme_refresh_channels(true);"></div>
            <?php } ?>
        </div>
		<?php
        return ob_get_clean();
	}

	/**
	 * Template of channels item tr.
	 *
	 * @param  string $prefix
	 * @param  integer $id
	 * @param  boolean $active
	 * @param  array $texts
	 * @param  string $name
	 * @return string
	 */
	public static function channels_item( $prefix, $id, $active, $texts, $name = '' ) {
		ob_start();
		?>
        <tr class="epme-channels-tbl__tr epme-channels-tbl__tr--<?php echo $prefix; ?> epme-channels-tbl__tr--<?php echo $id; ?> <?php echo ( $id !== 'empty' ) ? 'epme-channels-tbl__tr--enable' : ''; ?> <?php echo ( !$active ) ? 'epme-channels-tbl__tr--not-active' : ''; ?> <?php echo ( isset( $texts['is_disable'] ) AND $texts['is_disable'] ) ? 'epme-channels-tbl__tr--disable' : ''; ?>">
            <td class="epme-channels-tbl__status"></td>
            <td class="epme-channels-tbl__img-coll"><div class="epme-social-img epme-social-img--<?php echo $prefix; ?>"></div></td>
            <td class="epme-channels-tbl__title"><p title="<?php echo $texts['name']; ?>" class="epme-channels-tbl--text text--bold"><?php echo ( $name ) ? $name : $texts['name']; ?></p></td>
            <td class="epme-channels-tbl__desc"><p><?php if ( $id === 'empty' ) {
                echo $texts['description'];
            } else {
			    echo $texts['connect-description'];
			    if ( !$active ) {
			        echo " and DEACTIVATED";
                }
            }
            ?></p></td>
            <td class="epme-channels-tbl__actions">
                <?php
                if ( isset( $texts['is_disable'] ) AND $texts['is_disable'] ) {
	                ?>
                    <a href="https://pm.chat/easyping/?utm_source=wp-plugin&utm_medium=organic&utm_campaign=add_new_channel&utm_content=<?php echo $texts['slug']; ?>" target="_blank"
                        title="<?php echo __( 'Message us', 'easyping.me' ); ?>"><button
                        class="epme-channels__btn epme-channels__btn--goto mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored epme-button--green" title="<?php echo __( 'Message us', 'easyping.me' ); ?>">
                        <i class="material-icons">message</i>
                    </button></a>
                    <a href="mailto:want-whatsapp@easyping.me?subject=WhatsApp/Instagram account registration&body=<br><br><br>WordPress version: <?php global $wp_version; echo $wp_version; ?>. Plugin version: <?php echo EPME_VERSION; ?>. Account email: <?php echo EPME_Authorization::get_email(); ?>." target="_blank"
                        title="<?php echo __( 'Mail us', 'easyping.me' ); ?>"><button
                        class="epme-channels__btn epme-channels__btn--goto mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-button--secondary" title="<?php echo __( 'Mail us', 'easyping.me' ); ?>">
                        <i class="material-icons">mail_outline</i>
                    </button></a>
                    <?php
                } else {
	                ?>
                    <button
                        class="epme-channels__btn epme-channels__btn--edit mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary mdc-button__small-btn epme-modal-link"
                        data-link=".epme-modal--<?php echo "$prefix"; ?>.epme-modal--<?php echo "$id"; ?>"
                        data-title="<?php echo $texts['edit_title']; ?>"
                        title="<?php echo __( 'Edit', 'easyping.me' ); ?>">
                        <i class="material-icons">edit</i>
                    </button>
                    <button
                        class="epme-channels__btn epme-channels__btn--add mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-button--primary epme-modal-link"
                        data-link=".epme-modal--<?php echo "$prefix"; ?>.epme-modal--<?php echo "$id"; ?>"
                        data-title="<?php echo $texts['add_title']; ?>"
                        title="<?php echo __( 'Create Channel', 'easyping.me' ); ?>" <?php echo ( isset( $texts['is_disable'] ) AND $texts['is_disable'] ) ? 'disabled' : ''; ?> <?php /*echo ( !$id OR $id == 'empty' ) ? "onclick=\"epme_channel_with_button_authorize('{$prefix}')\"" : '';*/ ?>>
                        <i class="material-icons">add</i>
                    </button>
	                <?php
                }
                ?>
            </td>
        </tr>
		<?php
        return ob_get_clean();
	}

	/**
	 * Template of channels item in New Channel Tab.
	 *
	 * @param  string $prefix
	 * @param  array $texts
	 * @param  string $img
	 * @return string
	 */
	public static function channel_item_in_newtab( $prefix, $texts, $img ) {
		ob_start();
		?>
        <div class="epme-card epme-card--channels <?php echo $texts['add_class']; ?> epme-card--<?php echo $prefix; ?> mdl-card mdl-card">
            <div class="epme-card__header epme-card__header--channels">
                <img src="<?php echo $img; ?>" class="epme-card__img epme-card__img--<?php echo $prefix; ?>" alt="">
            </div>
            <div class="epme-card__cont epme-card__cont--channels">
                <div class="mdl-card__title epme-card__title--channels epme-card__title--pad-6">
                    <div class="epme__sub-titel--small"><?php echo $texts['name']; ?></div>
                </div>
                <div class="mdl-card__supporting-text epme-card__text--channels"><?php echo $texts['description']; ?>
                </div>
                <div class="mdl-card__actions epme-card__actions epme-card__actions--channels">
                    <?php
                    if ( isset( $texts['is_disable'] ) AND $texts['is_disable'] ) {
	                    ?>
                        <a href="https://pm.chat/easyping/?utm_source=wp-plugin&utm_medium=organic&utm_campaign=add_new_channel&utm_content=<?php echo $texts['slug']; ?>" target="_blank"
                           title="<?php echo __( 'Message us', 'easyping.me' ); ?>"><button
                                class="epme-channels__btn epme-channels__btn--goto mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored epme-button--green" title="<?php echo __( 'Message us', 'easyping.me' ); ?>">
                                <i class="material-icons">message</i>
                            </button></a>
                        <a href="mailto:want-whatsapp@easyping.me?subject=WhatsApp/Instagram account registration&body=<br><br><br>WordPress version: <?php global $wp_version; echo $wp_version; ?>. Plugin version: <?php echo EPME_VERSION; ?>. Account email: <?php echo EPME_Authorization::get_email(); ?>." target="_blank"
                           title="<?php echo __( 'Mail us', 'easyping.me' ); ?>"><button
                                class="epme-channels__btn epme-channels__btn--goto mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-button--secondary" title="<?php echo __( 'Mail us', 'easyping.me' ); ?>">
                                <i class="material-icons">mail_outline</i>
                            </button></a>
	                    <?php
                    } else {
	                    ?>
                        <button
                            class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary epme-modal-link"
                            data-link=".epme-modal--<?php echo $prefix; ?>.epme-modal--empty"
                            data-title="<?php echo $texts['add_title']; ?>" <?php echo ( isset( $texts['is_disable'] ) AND $texts['is_disable'] ) ? 'disabled' : ''; ?>><?php echo __( 'Connect', 'easyping.me' ); ?>
                        </button>
	                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
		<?php
        return ob_get_clean();
	}
}

EPME_Admin_Channels::channels_texts();