<?php
/**
 * Admin Widgets.
 *
 * Functions used for displaying widgets page in admin.
 *
 * @author      easyping.me
 * @category    Admin
 * @package     easyping/Admin/Widgets
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EPME_Admin_Widgets Class.
 */
class EPME_Admin_Widgets {

	/**
     * Variable for json settings of Widget.
     *
	 * @var $widget
	 */
    protected static $widget;

	/**
     * Variable for cache of List of Channels.
     *
	 * @var $channels
	 */
    protected static $channels;

	/**
     * Array of checked Channel.
     *
	 * @var $checked_channel
	 */
    protected static $checked_channel = array();

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

			$title = __( 'Widgets', 'easyping.me' );
			$description = __( 'Widget is a block of buttons or a form or an interactive widget that you place on your website and engage visitors to subscribe for your news via social messaging apps. You can also generate special deep-link script-code and implement within any elements of your website.', 'easyping.me' );
			EPME_Admin_Output::layout_start( $title, $description );

			echo '<div class="epme-cont">';
		}

		if ( isset( $_GET['id-widget'] ) AND $_GET['id-widget'] ) {
			self::get_widget();
			echo self::widget_form();
			echo self::print_script_widget();
        } else {
			?>
            <div class="mdl-tabs mdl-js-tabs">
                <div class="mdl-tabs__tab-bar">
                    <a href="#epme-widgets"
                       class="mdl-tabs__tab is-active epme-tab-header epme-tab-header--widget-tab epme-tab-header--widgets"
                       data-id="widgets"><span class="mdc-tab__content"><i class="mdc-tab__icon material-icons">account_circle</i> <?php echo __( 'Widgets', 'easyping.me' ); ?></span></a>
                    <a href="#epme-new-widget"
                       class="mdl-tabs__tab -is-active epme-tab-header epme-tab-header--widget-tab epme-tab-header--new-widget"
                       data-id="new-widget"><span class="mdc-tab__content"><i
                                class="mdc-tab__icon material-icons">star</i> <?php echo __( 'Create new widget', 'easyping.me' ); ?></span></a>
                </div>

                <div class="mdl-tabs__panel mdl-tabs__panel--widgets is-active" id="epme-widgets">
	                <?php echo self::widgets_template(); ?>
                </div>
                <div class="mdl-tabs__panel mdl-tabs__panel--new-widget -is-active" id="epme-new-widget">
					<?php echo self::widget_form(); ?>
                </div>
            </div>
			<?php
		}
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
	 * Template of Widgets.
	 *
	 * @return string
	 */
	public static function widgets_template() {
		ob_start();
		?>
        <div class="epme-cont__widgets-cont">
            <div class="epme-widget mdl-card epme-widget--add-new">
                <div class="epme-widget__header">
                    <div class="epme-widget__pict epme-widget__pict--new"></div>
                </div>
                <div class="epme-widget__desc">
                    <div
                        class="mdl-card__title epme-widget__title epme__sub-titel--small"><?php echo __( 'Create new widget', 'easyping.me' ); ?></div>
                    <div
                        class="mdl-card__supporting-text epme-widget__texts"><?php echo __( 'Create a subscription form, block of buttons or a widget', 'easyping.me' ); ?></div>
                    <div class="epme-widget__actions ">
                        <button
                            class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary epme-widget__new-button"><?php echo __( 'Create', 'easyping.me' ); ?></button>
                    </div>
                </div>
            </div>
			<?php
			$response = EPME_Connects::get_widgets();
			if ( $response['type'] !== 'error' AND !empty( $response['respond'] ) ) {
				$widget_list = array_reverse( epme_fix_bool_in_arr( $response['respond'] ) );
				foreach ( $widget_list as $widget_item ) {
                    if ( !isset( $widget_item['id'] ) OR !$widget_item['id'] ) {
                        continue;
                    }
					?>
                    <div
                        class="epme-widget mdl-card epme-widget--shadow epme-widget--<?php echo $widget_item['id']; ?> epme-widget--<?php echo ( $widget_item['active'] ) ? 'active' : 'not-active'; ?>"
                        data-id="<?php echo $widget_item['id']; ?>">
                        <div class="epme-widget__header">
                            <div class="epme-widget__pict epme-widget__pict--<?php echo ( $widget_item['position'] == 2 ) ? 'relative' : 'fixed'; ?>" title="<?php
                            echo ( $widget_item['position'] == 2 ) ? __( 'Any place on any page with snippet (div)', 'easyping.me' ) : __( 'In lower corner as a floating widget on every page on my website', 'easyping.me' );
                            ?>"></div>
                        </div>
                        <div class="epme-widget__desc">
                            <div
                                class="mdl-card__title epme-widget__title epme__sub-titel--small"><?php echo $widget_item['name']; ?></div>
                            <div class="mdl-card__supporting-text epme-widget__texts">
                                <table class="epme-widget__texts-table">
                                    <tr class="epme-widget-info epme-widget-info--buttonText">
                                        <td class="epme-widget-info__title" title="<?php echo __( 'Button text', 'easyping.me' ); ?>"><?php echo __( 'Button', 'easyping.me' ); ?></td>
                                        <td class="epme-widget-info__cont"><?php
	                                        echo $widget_item['buttonText'];
	                                        ?></td>
                                    </tr>
	                                <?php
	                                $humanly_channels = self::get_humanly_channels( $widget_item['channels'] );
	                                if ( $widget_item['withEmail'] ) {
		                                if ( strlen( $humanly_channels ) > 1 ) {
			                                $humanly_channels = "Email, $humanly_channels";
		                                } else {
			                                $humanly_channels = "Email";
		                                }
	                                }
	                                if ( strlen( $humanly_channels ) > 1 ) {
		                                ?>
                                        <tr class="epme-widget-info epme-widget-info--channels">
                                            <td class="epme-widget-info__title"><?php echo __( 'Channels', 'easyping.me' ); ?></td>
                                            <td class="epme-widget-info__cont"><?php
				                                if ( strlen( $humanly_channels ) > 1 ) {
					                                echo "$humanly_channels";
				                                }
				                                ?></td>
                                        </tr>
		                                <?php
	                                }
	                                ?>
                                    <tr class="epme-widget-info epme-widget-info--status">
                                        <td class="epme-widget-info__title"><?php echo __( 'Status', 'easyping.me' ); ?></td>
                                        <td class="epme-widget-info__cont"><?php
	                                        echo ( $widget_item['active'] ) ? __( 'is active', 'easyping.me' ) : __( 'deactivated', 'easyping.me' );
	                                        ?></td>
                                    </tr>
                                    <tr class="epme-widget-info epme-widget-info--position">
                                        <td class="epme-widget-info__title"><?php echo __( 'Position', 'easyping.me' ); ?></td>
                                        <td class="epme-widget-info__cont"><?php
	                                        echo ( $widget_item['position'] == 2 ) ? __( 'Any place on any page with snippet (div)', 'easyping.me' ) : __( 'In lower corner as a "floating" widget on every page on my website', 'easyping.me' );
	                                        ?></td>
                                    </tr>
	                                <?php
	                                if ( $widget_item['position'] == 2 ) {
		                                ?>
                                        <tr class="epme-widget-info epme-widget-info--shortcode">
                                        <tr>
                                            <td class="epme-widget-info__title"><?php echo __( 'Shortcode', 'easyping.me' ); ?></td>
                                            <td class="epme-widget-info__cont"><?php
				                                echo "[epme_widget id='{$widget_item['id']}']";
				                                ?></td>
                                        </tr>
		                                <?php
	                                }
	                                ?>
                                </table>
                            </div>
                            <div class="epme-widget__actions ">
                                <a href="<?php echo admin_url( 'admin.php?page=epme-widgets&id-widget=' . $widget_item['id'] ); ?>"
                                   class="mdl-button mdl-js-button mdl-button--raised <?php echo ( $widget_item['active'] ) ? 'mdl-button--colored mdl-button--primary' : ''; ?>"><?php echo __( 'Modify', 'easyping.me' ); ?></a>
                                <button
                                    class="mdl-button mdl-js-button mdl-button--raised <?php echo ( $widget_item['active'] ) ? 'epme-widget__deactivate' : 'mdl-button--colored mdl-button--primary epme-widget__activate'; ?>" onclick="<?php
                                    if ( $widget_item['active'] ) :
                                        ?>epme_widget_id = <?php echo $widget_item['id']; ?>; epme_open_dialog_modal('<?php
                                        echo __( 'Are you sure you want to do this?', 'easyping.me' );
                                        ?>', '<?php
                                        echo __( 'Deactivate the widget', 'easyping.me' );
                                        ?>', 'Deactivate', 'Cancel', 'epme-modal--deactivate-widget'); <?php
                                    else :
                                        ?>
                                        epme_active_widget({'id' : <?php echo $widget_item['id']; ?>, 'active' : 1});
                                        <?php endif;
                                        ?>"><?php
                                    if ( $widget_item['active'] ) {
	                                    echo __( 'Deactivate', 'easyping.me' );
                                    } else {
	                                    echo __( 'Activate', 'easyping.me' );
                                    }
                                    ?></button>
                                <button class="mdl-button mdl-js-button mdc-button__small-btn epme-widget__delete" title="<?php echo __( 'Delete Widget', 'easyping.me' ); ?>" data-id="<?php echo $widget_item['id']; ?>" onclick="epme_widget_id = <?php echo $widget_item['id']; ?>; epme_open_dialog_modal('<?php
                                echo __( 'Are you sure you want to do this?', 'easyping.me' );
                                ?>', '<?php
                                echo __( 'Delete the widget', 'easyping.me' );
                                ?>', 'Delete', 'Cancel', 'epme-modal--remove-widget');">
                                    <i class="material-icons">delete</i>
                                </button>
                            </div>
                        </div>
                    </div>
					<?php
				}
			}
			?>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Widget Form.
	 *
	 * @return string
	 */
	public static function widget_form() {
		ob_start();
        $initial_step = 1;
		?>
        <div class="epme-cont__tab-cont">
            <div class="epme-new-widget epme-new-widget--<?php echo $initial_step; ?> epme-new-widget--d-<?php echo self::$widget['position']; ?> mdl-card epme-card--big-shadow" data-step="<?php echo $initial_step; ?>">
                <div class="epme-new-widget__progress"></div>
                <div class="epme-new-widget__body">
                    <div class="epme-new-widget__wizard">
						<?php echo self::new_widget_step( 1, 'channels' ); ?>
						<?php echo self::new_widget_step( 2, 'cta_phrase' ); ?>
						<?php echo self::new_widget_step( 3, 'design' ); ?>
						<?php echo self::new_widget_step( 4, 'color' ); ?>
						<?php echo self::new_widget_step( 5, 'helping_text' ); ?>
						<?php echo self::new_widget_step( 6, 'button_position' ); ?>
						<?php echo self::new_widget_step( 7, 'finish' ); ?>
                    </div>
                    <div class="epme-new-widget__prev epme-prev">
                        <div class="epme-prev__box">
                            <div class="epme-prev__cont epme-prev__cont--text"></div>
                            <div class="epme-prev__cont epme-prev__cont--color-picker">
								<?php
								$text_pr = __( 'Preview area background (just for preview purposes)', 'easyping.me' );
								?>
                                <label for="epme-color-picker--preview" class="epme-prev__color-label"><?php echo $text_pr; ?></label>
                                <input id="epme-color-picker--preview" class="epme-prev__color-picker epme-color-picker epme-color-picker--preview" autocomplete="off" alt="icon-color" title="<?php echo $text_pr; ?>" value="#000000">
                            </div>
                            <div class="epme-prev__cont epme-prev__cont--button"><div></div></div>
                        </div>
                    </div>
                </div>
                <div class="epme-new-widget__footer">
                    <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect epme-new-widget__nav epme-new-widget__nav--back" data-nav="back">Back</button>
                    <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored epme-new-widget__nav epme-new-widget__nav--next" data-nav="next">Next</button>
                    <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored epme-new-widget__nav epme-new-widget__nav--save" data-nav="next">Save</button>
                    <a href="<?php echo admin_url( 'admin.php?page=epme-widgets' ); ?>" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored epme-new-widget__nav epme-new-widget__nav--finish epme-new-widget__nav--my-b">View my buttons</a>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of New Widget Steps.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public static function new_widget_step( $id, $name ) {
		switch ( $name ) {
            case 'channels':
                return self::stepme_channels( $id, $name );
            case 'cta_phrase':
                return self::stepme_cta_phrase( $id, $name );
            case 'design':
                return self::stepme_design( $id, $name );
            case 'color':
                return self::stepme_color( $id, $name );
            case 'helping_text':
                return self::stepme_helping_text( $id, $name );
            case 'button_position':
                return self::stepme_button_position( $id, $name );
            case 'finish':
                return self::stepme_finish( $id, $name );
        }
        return '';
	}

	/**
	 * Template of Channels inputs list
	 *
	 * @return string
	 */
	public static function channels_list_template() {
		ob_start();
		self::get_widget();
		$channels = self::get_channels();
		echo self::label_channels_list( 'mail', 'Email', 'mail', self::email( true ) );
		if ( is_array( $channels ) ) {
			echo self::list_of_channels( $channels );
		}
		return ob_get_clean();
	}

	/**
	 * Template of Channels step
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public static function stepme_channels( $id, $name ) {
		ob_start();
		?>
        <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
            <div class="epme-new-widget__title epme__sub-titel--small"><?php echo __( 'Сhoose channels available for visitors to subscribe', 'easyping.me' ); ?></div>
            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label epme-textfield">
                <input autocomplete="off" class="mdl-textfield__input epme-textfield__input epme-required epme-new-widget__name" type="text" id="epme-new-widget__name" value="<?php echo self::name( __( 'My new Widget', 'easyping.me' ) ); ?>">
                <label class="mdl-textfield__label" name="name" for="epme-new-widget__name"><?php echo __( 'Widget name (required)', 'easyping.me' ); ?></label>
            </div>
            <div class="epme-channels-list">
                <div class="epme-channels-list__cont">
                    <?php
                    echo self::channels_list_template();
                    ?>
                </div>
                <div class="epme-margin-block-p">
                    <button type="button" class="mdl-button epme-new-widget__refresh" data-id-widget="<?php echo ( isset( $_GET['id-widget'] ) ) ? $_GET['id-widget'] : ''; ?>">Refresh</button>
                    <a href="<?php echo admin_url( 'admin.php?page=epme-channels&new_channel=1' ); ?>" target="_blank" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored epme-new-widget__new-channel">Add new channel</a>
                </div>
            </div>
            <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ), '', 'epme-preview-info' ); ?>"><?php
                if ( self::email( true ) ) {
	                self::$checked_channel[] = 'mail';
                }
                $prev = array(
                    'type' => 'text',
                    'text' => htmlspecialchars( __( 'Just one more step and you will see me, your precious subscription button (funny bw button)', 'easyping.me' ) ),
                    'channels' => self::$checked_channel,
                    'widget' => self::get_widget(),
                );
                echo json_encode( $prev );
            ?></div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of 'Call-To-Action phrase' step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public static function stepme_cta_phrase( $id, $name ) {
		ob_start();
		?>
        <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
            <div class="epme-new-widget__title epme__sub-titel--small"><?php echo __( 'Set a catchy text for your button here', 'easyping.me' ); ?></div>
            <div class="mdl-textfield mdl-js-textfield epme-cta-phrase epme-flex">
                <textarea autocomplete="off" class="mdl-textfield__input epme-cta-phrase__input epme-required epme-live-refresh-widget--keyup" rows="4" id="epme-cta-phrase" placeholder='<?php echo __( 'Type your CTA here like "Sign-up to our super news" or "Subscribe now!"', 'easyping.me' ); ?>'><?php echo ( isset( self::$widget['guid'] ) AND self::$widget['guid'] ) ? self::get_cta_phrase( '' ) : ''; ?></textarea>
                <label class="mdl-textfield__label" for="epme-cta-phrase"></label>
            </div>
            <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ), '', 'epme-preview-info' ); ?>"><?php
		        $prev = array(
			        'type' => 'button',
			        'text' => htmlspecialchars( __( 'Almost done...', 'easyping.me' ) ),
			        'widget' => self::get_widget(),
		        );
		        echo json_encode( $prev );
            ?></div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Square|Round design step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public static function stepme_design( $id, $name ) {
		ob_start();
		?>
        <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
            <div class="epme-new-widget__title epme__sub-titel--small"><?php echo __( 'Choose button design to fit your website', 'easyping.me' ); ?></div>
            <div class="epme-margin-block epme-flex epme-toggle epme-toggle--design">
                <div class="epme-toggle__cont">
                    <div data-for="epme-design-toggle" data-val="0" class="epme-toggle__text epme-toggle__text--active epme-toggle__text--left">
                        <div class="epme-toggle__icon epme-toggle__icon--square"></div>
                        <div class="epme-toggle__value">Square</div>
                    </div>
                    <label class="epme-switch" for="epme-design-toggle">
                        <input autocomplete="off" type="checkbox" id="epme-design-toggle" class="epme-design-toggle epme-live-refresh-widget--change" value="1" <?php echo ( self::is_design_checked() ) ? 'checked' : ''; ?>>
                        <span class="epme-slider epme-round"></span>
                    </label>
                    <div data-for="epme-design-toggle" data-val="1" class="epme-toggle__text epme-toggle__text--right">
                        <div class="epme-toggle__icon epme-toggle__icon--round"></div>
                        <div class="epme-toggle__value">Round</div>
                    </div>
                </div>
            </div>
            <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ), '', 'epme-preview-info' ); ?>"><?php
		        $prev = array(
			        'type' => 'button',
			        'text' => htmlspecialchars( __( 'I will look something like that...', 'easyping.me' ) ),
			        'widget' => self::get_widget(),
		        );
		        echo json_encode( $prev );
            ?></div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of choose color step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public static function stepme_color( $id, $name ) {
		ob_start();
		$text_bg = __( 'Background color', 'easyping.me' );
		$text_t = __( 'Text color', 'easyping.me' );
		?>
        <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
            <div class="epme-new-widget__title epme__sub-titel--small"><?php echo __( 'Now select color for button background and text', 'easyping.me' ); ?></div>
            <div class="epme-margin-block epme-flex epme-color-select">
                <div class="epme-color-select__cont">
                    <div class="epme-color-select__item epme-color-select__item--background">
                        <div class="epme-color-select__title"><?php echo $text_bg; ?></div>
                        <div class="epme-color-select__picker">
                            <input class="epme-color-picker--back epme-color-picker epme-live-refresh-widget--change" autocomplete="off" alt="icon-color" title="<?php echo $text_bg; ?>" value="<?php echo self::color_back( '#000000' ); ?>">
                        </div>
                    </div>
                    <div class="epme-color-select__item epme-color-select__item--text">
                        <div class="epme-color-select__title"><?php echo $text_t; ?></div>
                        <div class="epme-color-select__picker">
                            <input class="epme-color-picker--text epme-color-picker epme-live-refresh-widget--change" autocomplete="off" alt="icon-color" title="<?php echo $text_t; ?>" value="<?php echo self::color_text( '#eb7a1e' ); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ), '', 'epme-preview-info' ); ?>"><?php
		        $prev = array(
			        'type' => 'color-button',
			        'text' => htmlspecialchars( __( 'Try and press me :)', 'easyping.me' ) ),
			        'color_picker' => array(
                        'color' => '#f1f1f1',
                        'label' => htmlspecialchars( __( 'Preview area background (just for preview purposes)', 'easyping.me' ) ),
			        ),
			        'widget' => self::get_widget(),
		        );
		        echo json_encode( $prev );
		        ?></div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of 'Helping Text' step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public static function stepme_helping_text( $id, $name ) {
		ob_start();
		$def = __( 'Choose a suitable way to receive our newsletters', 'easyping.me' );
		?>
        <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
            <div class="epme-new-widget__title epme__sub-titel--small"><?php echo __( 'Change the default helper text above social icons when the button is clicked out. It helps website visitors subscribe with the most suitable social app', 'easyping.me' ); ?></div>
            <div class="mdl-textfield mdl-js-textfield epme-helping-text epme-flex">
                <textarea autocomplete="off" class="mdl-textfield__input epme-helping-text__input epme-textarea epme-live-refresh-widget--keyup" rows="4" id="epme-helping-text" placeholder='<?php echo $def; ?>'><?php echo self::helping_text( $def ); ?></textarea>
                <label class="mdl-textfield__label" for="epme-helping-text"></label>
            </div>
            <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ), '', 'epme-preview-info' ); ?>"><?php
		        $prev = array(
			        'type' => 'button',
			        'widget' => self::get_widget(),
		        );
		        echo json_encode( $prev );
            ?></div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of 'Button Position' step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public static function stepme_button_position( $id, $name ) {
		ob_start();
		?>
        <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
            <div class="epme-new-widget__title epme__sub-titel--small"><?php echo __( 'Now how do you want to place the button?', 'easyping.me' ); ?></div>
            <div class="epme-button-position epme-flex">
                <div class="epme-margin-auto">
                    <div class="epme-button-position__inputs">
                        <div class="epme-title--caps"><?php echo __( 'Button Position', 'easyping.me' ); ?></div>
                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect epme-button-position__rel" for="epme-button-position--rel">
                            <input autocomplete="off" type="radio" id="epme-button-position--rel" class="mdl-radio__button epme-button-position__input epme-button-position__input--2 epme-live-refresh-widget--change" name="epme-button-position__input" value="2" <?php echo ( self::position( 2, true ) ) ? 'checked' : ''; ?>>
                            <span class="mdl-radio__label"><?php echo __( 'Any place on any page with snippet (div)', 'easyping.me' ); ?></span>
                        </label>
                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="epme-button-position--fixed">
                            <input autocomplete="off" type="radio" id="epme-button-position--fixed" class="mdl-radio__button epme-button-position__input epme-button-position__input--1 epme-live-refresh-widget--change" name="epme-button-position__input" value="1" <?php echo ( self::position( 1, false ) ) ? 'checked' : ''; ?>>
                            <span class="mdl-radio__label"><?php echo __( 'In lower corner as a "floating" widget on every page on my website', 'easyping.me' ); ?></span>
                        </label>
                    </div>
                    <div class="epme-button-position__aligns epme-text-align">
                        <div class="epme-title--caps"><?php echo __( 'Button Align', 'easyping.me' ); ?></div>
                        <input type="radio" class="epme-text-align__input epme-text-align__input--left epme-live-refresh-widget--change" name="epme-text-align" value="left" title="left" <?php echo ( self::textAlign( 'left', true ) ) ? 'checked' : ''; ?>>
                        <input type="radio" class="epme-text-align__input epme-text-align__input--center epme-live-refresh-widget--change" name="epme-text-align" value="center" title="center" <?php echo ( self::textAlign( 'center', false ) ) ? 'checked' : ''; ?>>
                        <input type="radio" class="epme-text-align__input epme-text-align__input--right epme-live-refresh-widget--change" name="epme-text-align" value="right" title="right" <?php echo ( self::textAlign( 'right', false ) ) ? 'checked' : ''; ?>>
                        <button class="epme-text-align__button mdl-button mdl-js-button mdc-button__small-btn <?php echo ( self::textAlign( 'left', true ) ) ? 'mdl-button--raised mdl-button--primary mdl-button--colored' : ''; ?>" data-id="left" title="<?php echo __( 'Align Left', 'easyping.me' ); ?>">
                            <i class="material-icons">format_align_left</i>
                        </button>
                        <button class="epme-text-align__button mdl-button mdl-js-button mdc-button__small-btn <?php echo ( self::textAlign( 'center', false ) ) ? 'mdl-button--raised mdl-button--primary mdl-button--colored' : ''; ?>" data-id="center" title="<?php echo __( 'Align Center', 'easyping.me' ); ?>">
                            <i class="material-icons">format_align_center</i>
                        </button>
                        <button class="epme-text-align__button mdl-button mdl-js-button mdc-button__small-btn <?php echo ( self::textAlign( 'right', false ) ) ? 'mdl-button--raised mdl-button--primary mdl-button--colored' : ''; ?>" data-id="right" title="<?php echo __( 'Align Right', 'easyping.me' ); ?>">
                            <i class="material-icons">format_align_right</i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ), '', 'epme-preview-info' ); ?>"><?php
		        $prev = array(
			        'type' => 'button',
			        'widget' => self::get_widget(),
		        );
		        echo json_encode( $prev );
            ?></div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of finish step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public static function stepme_finish( $id, $name ) {
		ob_start();
		?>
        <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
            <div class="epme-new-widget__title epme__sub-titel--small"><?php echo __( 'Congratulations! Your widget is ready.', 'easyping.me' ); ?></div>
            <div class="mdl-textfield epme-widget-finish epme-flex">
                <div class="epme-margin-auto--y epme-new-widget__finish epme-new-widget__finish--1">
                    <div class="epme-margin-block">
                        <div class="epme-widget-finish__title"><?php echo sprintf( esc_html__( 'Use shortcodes below to place your button into desired place on any page. For help about this check our %1$sFAQ%2$s.', 'easyping.me' ), '<a href="https://easyping.me/faq/wordpress/3-plugin-create-widget-place-on-wordpress-website" target="_blank">', '</a>' ); ?></div>
                        <code class="epme-widget-finish__code epme-widget-finish__code--1">[epme_widget id='123']</code>
                    </div>
                </div>
                <div class="epme-margin-auto--y epme-new-widget__finish epme-new-widget__finish--2">
                    <p><?php echo __( 'You chose it to be "floating" so it is already on your page in a lower part of the screen. Check it yourself.', 'easyping.me' ); ?></p>
                    <p><?php echo sprintf( esc_html__( 'If you wanted to place it otherwise please check our %1$sFAQ article%2$s.', 'easyping.me' ), '<a href="https://easyping.me/faq/wordpress/3-plugin-create-widget-place-on-wordpress-website" target="_blank">', '</a>' ); ?></p>
                </div>
            </div>
            <div class="<?php echo self::class_for_new_widget_step( array( $id, $name ), '', 'epme-preview-info' ); ?>"><?php
		        $prev = array(
			        'type' => 'button',
			        'widget' => self::get_widget(),
		        );
		        echo json_encode( $prev );
            ?></div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate HTML label for channels_list.
	 *
	 * @param  string $id
	 * @param  string $name
	 * @param  string $prefix
	 * @param  boolean $is_checked
	 * @param  array $info
	 * @return string
	 */
	public static function label_channels_list( $id, $name, $prefix, $is_checked = false, $info = array() ) {
		ob_start();
		?>
        <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect epme-channels-list__item epme-channels-list__item--<?php echo $id; ?> epme-channels-list__item--<?php echo $prefix; ?>" for="epme-channel-checkbox--<?php echo $id; ?>">
            <input type="checkbox" id="epme-channel-checkbox--<?php echo $id; ?>" value="<?php echo $id; ?>" data-name="<?php echo $name; ?>" data-network="<?php echo $info['beauty-network']; ?>" data-uri="<?php echo $info['uri']; ?>" autocomplete="off" class="mdl-checkbox__input epme-channel-checkbox epme-channel-checkbox--<?php echo $id; ?>" <?php echo ( $is_checked ) ? 'checked' : ''; ?>>
            <span class="mdl-checkbox__label epme-channels-list__name"><span class="epme-social-img epme-social-img--<?php echo $prefix; ?>"></span> <?php echo $name; ?></span>
        </label>
		<?php
		return ob_get_clean();
	}

	/**
	 * Generate class for new_widget_step.
	 *
	 * @param  array $classes
	 * @param  string $add
	 * @param  string $base
	 * @return string
	 */
	public static function class_for_new_widget_step( $classes, $add = '', $base = 'epme-new-widget__cont' ) {
        $result = "{$base} ";
        foreach ( $classes as $class ) {
            if ( $class ) {
	            $result .= "{$base}--{$class} ";
            }
        }
		return "{$result} {$add}";
	}

	/**
	 * Template of Channels List.
	 *
	 * @param  array | boolean $channels
	 * @return string
	 */
	public static function list_of_channels( $channels = false ) {
        if ( $channels === false OR empty( $channels ) ) {
	        $channels = self::get_channels();
        }
        $html = '';
		foreach ( $channels as $prefix => $channel ) {
			foreach ( $channel as $item ) {
				if ( !$item['active'] ) {
					continue;
				}
				$html .= self::label_channels_list( $item['id'], $item['name'], $prefix, self::is_checked_channel( $item['id'] ), $item );
			}
		}
		return $html;
	}

	/**
	 * Set variable for json settings of Widget.
	 *
	 * @param  array | boolean $settings
	 */
	public static function set_widget( $settings = false ) {
        if ( $settings === false OR empty( $settings ) OR !isset( $settings['guid'] ) OR !$settings['guid'] ) {
	        self::$widget = array(
		        'withEmail' => '1',
		        'active' => '0',
		        'buttonDesign' => '0',
		        'buttonColor' => '#000000',
		        'textColor' => '#eb7a1e',
		        'position' => '2',
		        'textAlign' => 'center',
		        'buttonText' => __( 'Subscribe now!', 'easyping.me' ),
		        'saleText' => __( 'Choose a suitable way to receive our newsletters', 'easyping.me' ),
	        );
        } else {
	        self::$widget = $settings;
        }
	}

	/**
	 * Return variable for json settings of Widget.
	 *
     * @return array
	 */
	public static function get_widget() {
        if ( empty( self::$widget ) OR !isset( self::$widget['guid'] ) OR !self::$widget['guid'] ) {
            if ( isset( $_GET['id-widget'] ) AND $_GET['id-widget'] OR isset( $_POST['id-widget'] ) AND $_POST['id-widget'] ) {
                if ( isset( $_GET['id-widget'] ) AND $_GET['id-widget'] ) {
                    $id_widget = intval( $_GET['id-widget'] );
                }
                if ( isset( $_POST['id-widget'] ) AND $_POST['id-widget'] ) {
                    $id_widget = intval( $_POST['id-widget'] );
                }
	            $response = EPME_Connects::get_widget_by_id( $id_widget );
	            self::set_widget( epme_fix_bool_in_arr( $response['respond'] ) );
            } else {
	            self::set_widget();
            }
        }

        return self::$widget;
	}

	/**
	 * Print script widget.
	 *
     * @return string
	 */
	public static function print_script_widget() {
        if ( !empty( self::$widget ) AND isset( self::$widget['guid'] ) AND self::$widget['guid'] ) {
		    ob_start();
            ?>
            <script>
                window.preview_info = {};
                window.preview_info.guid = '<?php echo self::$widget['guid']; ?>';
                window.preview_info.selector = '#brnnrn-subscription-button-<?php echo self::$widget['guid']; ?>';
                window.preview_info.id = '<?php echo self::$widget['id']; ?>';
                window.preview_info.widget = JSON.parse('<?php echo json_encode( self::get_widget() ); ?>');
            </script>
            <?php
	        return ob_get_clean();
        }
        return '';
	}

	/**
	 * Return true if actual Channel.
	 *
     * @param  integer $id
     * @return boolean
	 */
	public static function is_checked_channel( $id ) {
		self::get_widget();
        if ( !empty( self::$widget ) AND !empty( self::$widget['channels'] ) ) {
	        foreach ( self::$widget['channels'] as $channel ) {
                if ( $channel == $id ) {
                    self::$checked_channel[] = $id;
                    return true;
                }
            }
        }
        return false;
	}

	/**
	 * Return buttonText (cta_phrase).
	 *
     * @param  string $default
     * @return string
	 */
	public static function get_cta_phrase( $default ) {
        return ( !empty( self::$widget ) AND isset( self::$widget['buttonText'] ) AND self::$widget['buttonText'] ) ? self::$widget['buttonText'] : $default;
	}

	/**
	 * Return design.
	 *
	 * @return boolean
	 */
	public static function is_design_checked() {
		if ( !empty( self::$widget ) AND isset( self::$widget['buttonDesign'] ) AND self::$widget['buttonDesign'] ) {
			return (self::$widget['buttonDesign'] == 1);
		}
		return false;
	}

	/**
	 * Return buttonColor.
	 *
	 * @param  string $default
	 * @return string
	 */
	public static function color_back( $default ) {
		return ( !empty( self::$widget ) AND isset( self::$widget['buttonColor'] ) AND self::$widget['buttonColor'] ) ? self::$widget['buttonColor'] : $default;
	}

	/**
	 * Return textColor.
	 *
	 * @param  string $default
	 * @return string
	 */
	public static function color_text( $default ) {
		return ( !empty( self::$widget ) AND isset( self::$widget['textColor'] ) AND self::$widget['textColor'] ) ? self::$widget['textColor'] : $default;
	}

	/**
	 * Return saleText.
	 *
	 * @param  string $default
	 * @return string
	 */
	public static function helping_text( $default ) {
		return ( !empty( self::$widget ) AND isset( self::$widget['saleText'] ) AND self::$widget['saleText'] ) ? self::$widget['saleText'] : $default;
	}

	/**
	 * Return name.
	 *
	 * @param  string $default
	 * @return string
	 */
	public static function name( $default ) {
		return ( !empty( self::$widget ) AND isset( self::$widget['name'] ) AND self::$widget['name'] ) ? self::$widget['name'] : $default;
	}

	/**
	 * Return email.
	 *
	 * @param  string $default
	 * @return string
	 */
	public static function email( $default ) {
		return ( !empty( self::$widget ) AND isset( self::$widget['withEmail'] ) ) ? self::$widget['withEmail'] : $default;
	}

	/**
	 * Return true if position equal.
	 *
	 * @param  integer $val
	 * @param  integer $def
	 * @return boolean
	 */
	public static function position( $val, $def ) {
		if ( !empty( self::$widget ) AND isset( self::$widget['position'] ) AND self::$widget['position'] ) {
			return ( self::$widget['position'] == $val );
		}
		return $def;
	}

	/**
	 * Return true if textAlign equal.
	 *
	 * @param  integer $val
	 * @param  integer $def
	 * @return boolean
	 */
	public static function textAlign( $val, $def ) {
		if ( !empty( self::$widget ) AND isset( self::$widget['textAlign'] ) AND self::$widget['textAlign'] ) {
			return ( self::$widget['textAlign'] == $val );
		}
		return $def;
	}

	/**
	 * Return humanly names list of Channels.
	 *
     * @param  array $ids
	 * @return string
	 */
	public static function get_humanly_channels( $ids ) {
		$channels = self::get_channels();
		$list = '';

        if ( !empty( $ids ) ) {
	        foreach ( $ids as $id ) {
		        foreach ( $channels as $prefix => $channel ) {
			        foreach ( $channel as $item_channel ) {
			            if ( $id == $item_channel['id'] ) {
				            $list .= "{$item_channel['beauty-network']}, ";
                        }
			        }
                }
	        }
        }

        return ( strlen( $list ) > 3 ) ? substr( $list, 0, -2 ) : $list;
	}

	/**
	 * Return Channels.
	 *
	 * @return array
	 */
	public static function get_channels() {
		if ( empty( self::$channels ) OR !self::$channels ) {
			self::$channels = EPME_Channels::get_channels();
		}
        return self::$channels;
	}
}
