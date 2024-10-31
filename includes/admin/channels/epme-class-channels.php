<?php
/**
 * Channels Class.
 *
 * @package easyping\Classes\Channels
 * @version 1.0.6
 */

defined( 'ABSPATH' ) || exit;

/**
 * EPME_Channels Class.
 */
class EPME_Channels {

	/**
	 * @var array $channels
	 */
	protected static $channels = false;

	/**
	 * Is Channel active?
	 *
	 * @param  string $channel_name
	 * @return boolean
	 */
	public static function is_active( $channel_name ) {
		$is_channel = self::get_channel( $channel_name );
		return empty( $is_channel );
	}

	/**
	 * Return Channel information.
	 *
	 * @param  string $channel_name
	 * @return boolean | array
	 */
	public static function get_channel( $channel_name ) {
        if ( self::$channels === false ) {
            self::get_channels_data();
        }

		if ( !isset( self::$channels[$channel_name] ) OR !is_array( self::$channels[$channel_name] ) OR empty( self::$channels[$channel_name] ) ) {
			return array();
		} else {
		    return self::$channels[$channel_name];
        }
	}

	/**
	 * Return Channels.
     *
	 * @return array
	 */
	public static function get_channels() {
		if ( self::$channels === false ) {
			self::get_channels_data();
		}

		return self::$channels;
	}

	/**
	 * Setup Channels information.
     *
     * @param  object | boolean $list
	 */
	public static function get_channels_data( $list = false ) {
        if ( $list === false OR empty( $list ) ) {
	        $list = self::get_channels_list();
        }
		if ( $list['type'] == 'ok' ) {
            self::$channels = array();
			foreach ( $list['respond'] as $id => $channel ) {
                if ( !isset( $channel->network ) OR !$channel->network ) {
                    continue;
                }
                if ( !isset( self::$channels[$channel->network] ) OR !is_array( self::$channels[$channel->network] ) ) {
	                self::$channels[$channel->network] = array();
                }
				array_push( self::$channels[$channel->network], array(
				    'id' => $id,
				    'name' => $channel->name,
				    'active' => epme_fix_bool_in_arr( $channel->active ),
				    'network' => $channel->network,
				    'uri' => $channel->uri,
				    'beauty-network' => self::get_beauty_network_by_short( $channel->network ),
				    'stats' => array(
					    'day' => $channel->stats->day,
					    'month' => $channel->stats->month,
                    ),
                ) );
            }
		}
	}

	/**
	 * Return long name of channel by short.
     *
     * @param  string $short_name
     * @return string
	 */
	public static function get_long_network_by_short( $short_name ) {
        $arr = array( 'fb' => 'facebook', 'tg' => 'telegram', 'vk' => 'vkontakte', 'twitter' => 'twitter', 'viber' => 'viber', 'ok' => 'odnoklassniki', 'whatsapp' => 'whatsapp', 'instagram' => 'instagram' );

        return ( isset( $arr[$short_name] ) ) ? $arr[$short_name] : $short_name;
	}

	/**
	 * Return beauty name of channel by short.
     *
     * @param  string $short_name
     * @return string
	 */
	public static function get_beauty_network_by_short( $short_name ) {
        $arr = array( 'fb' => 'Facebook', 'tg' => 'Telegram', 'vk' => 'VKontakte', 'twitter' => 'Twitter', 'viber' => 'Viber', 'ok' => 'Odnoklassniki', 'whatsapp' => 'Whatsapp', 'instagram' => 'Instagram' );

        return ( isset( $arr[$short_name] ) ) ? $arr[$short_name] : $short_name;
	}

	/**
	 * Return Channels information from easyping.me.
     *
     * @return object | boolean
	 */
	protected static function get_channels_list() {
		$server_answer = EPME_Connects::channel_read_list();
		if ( $server_answer['type'] == 'ok' ) {
            return $server_answer;
		} else {
		    return false;
        }
	}

	/**
	 * Return Channel information by ID from easyping.me.
     *
	 * @param  integer $id
     * @return object | bool
	 */
	public static function get_channel_by_id( $id ) {
		$server_answer = EPME_Connects::channel_read( $id );
		if ( $server_answer['type'] == 'ok' ) {
            return $server_answer['respond'];
		}
		return false;
	}

	/**
	 * Return form for channels with Token.
	 *
	 * @param  string $prefix
	 * @param  integer $id
	 * @param  string $token
	 * @param  string $name
	 * @param  integer $activation
	 * @param  boolean $token_enable
	 * @return string
	 */
	public static function token_form( $prefix, $id, $token, $name, $activation, $token_enable = true ) {
		ob_start();
		?>
		<form class="epme-chl-edit-table__form">
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label epme-chl-edit-table__token">
				<?php if ( !$token_enable ) : ?>
                    <div class="mdl-textfield__input epme-textfield__input"><?php echo $token; ?></div>
				<?php else : ?>
                    <input class="mdl-textfield__input epme-textfield__input epme-textfield__input--required" autocomplete="off" type="text" id="token-<?php echo "$prefix-$id"; ?>" name="token" value="<?php echo $token; ?>">
                    <label class="mdl-textfield__label" for="token-<?php echo "$prefix-$id"; ?>"><?php echo __( 'Token', 'easyping.me' ); ?></label>
				<?php endif; ?>
			</div>
			<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label epme-chl-edit-table__name">
                <input class="mdl-textfield__input epme-textfield__input" autocomplete="off" type="text" id="name-<?php echo "$prefix-$id"; ?>" name="name" value="<?php echo $name; ?>">
                <label class="mdl-textfield__label" for="name-<?php echo "$prefix-$id"; ?>"><?php echo __( 'Channel name', 'easyping.me' ); ?></label>
			</div>
            <div class="epme-channel-activation">
                <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="activation-<?php echo "$prefix-$id"; ?>">
                    <input type="checkbox" autocomplete="off" id="activation-<?php echo "$prefix-$id"; ?>" class="mdl-checkbox__input epme-channel-activation__input" <?php echo ( $activation ) ? 'checked' : ''; ?> name="active">
                    <span class="mdl-checkbox__label">Channel activation</span>
                    <span class="epme-channel-activation__message"><?php echo __( 'Attention! When a channel is deactivated, messages from clients will cease to arrive at the Operator and to the platform robots. Your messages also will not reach the client.', 'easyping.me' ); ?></span>
                </label>
            </div>
			<input type="hidden" name="action" value="epme_channel">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<input type="hidden" name="channel" value="<?php echo $prefix; ?>">
			<?php wp_nonce_field( 'epme_channel', 'epme_channel_field' ); ?>
		</form>
		<?php
		return ob_get_clean();
	}

	/**
	 * Return count of groups for second Oauth stage.
	 *
	 * @param  string $oauth
	 * @return integer
	 */
	public static function count_of_group( $oauth ) {
		$oauth_data = json_decode( $oauth );
		$c = 0;

		foreach ( $oauth_data as $oauth_datum ) {
			if ( isset( $oauth_datum->id ) AND !$oauth_datum->connected ) {
				$c += 1;
			}
		}
		return $c;
	}

	/**
	 * Return form for channels with Token.
	 *
	 * @param  string $prefix
	 * @param  string $oauth
	 * @return string
	 */
	public static function group_select_form( $prefix, $oauth ) {
		ob_start();

		$oauth_data = json_decode( $oauth );
        ?>
        <form class="epme-chl-edit-table__form epme-group-select epme-group-select--<?php echo $prefix; ?>" data-prefix="<?php echo $prefix; ?>">
            <?php
            if ( !EPME_Channels::count_of_group( $oauth ) ) {
                ?>
                <div class="epme-group-select__title"><?php echo __( 'There are no groups to connect.', 'easyping.me' ); ?></div>
                <div class="epme-group-select__text"><?php echo __( 'You need to create a group.', 'easyping.me' ); ?></div>
                <?php
            } else {
                ?>
                <div class="epme-group-select__title"><?php echo __( 'Choose Page', 'easyping.me' ); ?></div>
                <?php
                foreach ( $oauth_data as $oauth_datum ) {
                    if ( isset( $oauth_datum->id ) AND $oauth_datum->id AND isset( $oauth_datum->title ) AND $oauth_datum->title ) {
                        if ( isset( $oauth_datum->id ) AND $oauth_datum->connected ) {
                            continue;
                        }
                        $token = ( isset( $oauth_datum->token ) ) ? $oauth_datum->token : '';
                        $url   = ( isset( $oauth_datum->url ) ) ? $oauth_datum->url : '';
                        ?>
                        <div class="epme-group-select__radio">
                            <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect"
                                   for="epme-group-select--<?php echo $oauth_datum->id; ?>">
                                <input type="radio" id="epme-group-select--<?php echo $oauth_datum->id; ?>"
                                       autocomplete="off"
                                       class="mdl-radio__button" name="epme-group-select"
                                       value="<?php
                                       if ( $prefix == 'vk' ) {
                                           echo $url;
                                       } else {
                                           echo base64_encode( serialize( array(
                                               'id'    => $oauth_datum->id,
                                               'token' => $token,
                                               'url'   => $url,
                                           ) ) );
                                       } ?>">
                                <span class="mdl-radio__label"><?php echo $oauth_datum->title; ?></span>
                            </label>
                        </div>
                        <?php
                    }
                }
                ?>
                <input type="hidden" name="action" value="epme_channel_processed">
                <input type="hidden" name="channel" value="<?php echo $prefix; ?>">
                <?php wp_nonce_field( 'epme_channel', 'epme_channel_field' ); ?>
                <?php
            }
            ?>
        </form>
        <?php
		return ob_get_clean();
	}

	/**
	 * Return form for channels with One Button.
	 *
	 * @param  string $prefix
	 * @return string
	 */
	public static function button_form( $prefix ) {
		ob_start();
		?>
        <div class="epme-button-form">
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary epme-button-form__button" data-prefix="<?php echo $prefix; ?>"><?php echo __( 'Authorize', 'easyping.me' ); ?></button>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Return form for Token type channels
	 *
	 * @param  string $prefix
	 * @param  integer | boolean $channel_id
	 * @return string
	 */
	public static function get_token_form( $prefix, $channel_id ) {
		ob_start();
        if ( !$channel_id ) {
	        echo self::token_form( $prefix, 'empty', '', '', 1 );
        } else {
	        $channel = self::get_channel_by_id( $channel_id );

	        $token = $channel->networkToken;
	        $name = $channel->name;
	        $id = $channel->id;
	        $activate = $channel->active;
	        echo self::token_form( $prefix, $id, $token, $name, $activate, true );
        }
		return ob_get_clean();
	}

	/**
	 * Return form for One button type channels
	 *
	 * @param  string $prefix
	 * @param  integer | boolean $channel_id
	 * @param  boolean $is_oauth
	 * @return string
	 */
	public static function get_button_form( $prefix, $channel_id, $is_oauth ) {
		ob_start();
        if ( !$channel_id ) {
            if ( $is_oauth ) {
	            $oauth = get_option( 'EASYPING_OAUTH' );
	            if ( isset( $oauth['network'] ) AND $prefix AND $oauth['network'] == $prefix ) {
		            echo self::group_select_form( $prefix, $oauth['data'] );
	            } else {
		            echo self::button_form( $prefix );
	            }
            } else {
	            echo self::button_form( $prefix );
            }
        } else {
	        $channel = self::get_channel_by_id( $channel_id );

	        $token = $channel->networkToken;
	        $name = $channel->name;
	        $id = $channel->id;
	        $activate = $channel->active;
	        echo self::token_form( $prefix, $id, $token, $name, $activate, false );
        }
		return ob_get_clean();
	}

	/**
	 * Return Telegram form
	 *
	 * @param  integer | boolean $channel_id
	 * @return string
	 */
	public static function tg_form( $channel_id ) {
		$prefix = 'tg';
		return self::get_token_form( $prefix, $channel_id );
	}

	/**
	 * Return Odnoklassniki form
	 *
	 * @param  integer | boolean $channel_id
	 * @return string
	 */
	public static function ok_form( $channel_id ) {
		$prefix = 'ok';
		return self::get_token_form( $prefix, $channel_id );
	}

	/**
	 * Return Viber form
	 *
	 * @param  integer | boolean $channel_id
	 * @return string
	 */
	public static function viber_form( $channel_id ) {
		$prefix = 'viber';
		return self::get_token_form( $prefix, $channel_id );
	}

	/**
	 * Return Twitter form
	 *
	 * @param  integer | boolean $channel_id
	 * @return string
	 */
	public static function twitter_form( $channel_id ) {
		$prefix = 'twitter';
		return self::get_button_form( $prefix, $channel_id, false );
	}

	/**
	 * Return VK form
	 *
	 * @param  integer | boolean $channel_id
	 * @param  boolean $is_oauth
	 * @return string
	 */
	public static function vk_form( $channel_id, $is_oauth = false ) {
		$prefix = 'vk';
		return self::get_button_form( $prefix, $channel_id, $is_oauth );
	}

	/**
	 * Return VK form
	 *
	 * @param  integer | boolean $channel_id
	 * @param  boolean $is_oauth
	 * @return string
	 */
	public static function fb_form( $channel_id, $is_oauth = false ) {
		$prefix = 'fb';
		return self::get_button_form( $prefix, $channel_id, $is_oauth );
	}
}
