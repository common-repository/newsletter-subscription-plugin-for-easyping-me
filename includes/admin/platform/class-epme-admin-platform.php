<?php
/**
 * Admin Platform
 *
 * Functions used for displaying platform page in admin.
 *
 * @author      easyping.me
 * @category    Admin
 * @package     easyping\Admin\Platform
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EPME_Admin_Platform Class.
 */
class EPME_Admin_Platform {

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

            $title = __( 'Platform', 'easyping.me' );
            $description = sprintf( esc_html__( 'easyping.me widgets and messaging campaigns are powered by %1$sPM.chat%2$s platform.', 'easyping.me' ), '<a href="https://pm.chat/?utm_source=wp-plugin&utm_medium=organic&utm_campaign=platform_main_screen&utm_content=PM_chat_wtf" target="_blank">', '</a>' );
            EPME_Admin_Output::layout_start( $title, $description );

	        echo '<div class="epme-cont">';
        }
		EPME_Admin_Output::add_text( 'pl-empty-error', __( 'Fields login and password should not be empty!', 'easyping.me' ) );

		if ( EPME_Authorization::is_authorization() ) {
	        ?>
            <div class="mdl-tabs mdl-js-tabs">
                <div class="mdl-tabs__panel is-active epme-account" id="my-account">
                    <div class="epme-title"><?php echo __( 'Platform account', 'easyping.me' ); ?></div>
                    <div class="epme-account__mail">
                        <div class="epme-account__email-message">
	                        <?php echo sprintf( esc_html__( 'You are authorized as %1$s'. EPME_Authorization::get_email() .'%2$s.', 'easyping.me' ), '<span class="epme-account__email">', '</span>' ); ?>
                        </div>
                        <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--accent mdc-button epme-account__sign-out">
                            <i class="material-icons mdc-button__icon">exit_to_app</i> <?php echo __( 'Sign out', 'easyping.me' ); ?>
                        </button>
                    </div>
                    <table class="epme-info-table">
                        <tr class="epme-info-table__tr">
                            <td class="epme-info-table__left"><?php echo __( 'Project', 'easyping.me' ); ?>:</td>
                            <td class="epme-info-table__right">
                                <div class="epme-info-table__project">
                                    <div class="epme-edit epme-edit--default epme-edit--project">
                                        <div class="epme-edit__default"><?php echo EPME_Authorization::get_project_name(); ?></div>
                                        <div class="epme-edit__to">
                                            <div class="epme-textfield">
                                                <form class="epme-edit__form">
                                                    <input id="project" type="text" autocomplete="off" class="epme-textfield__input" name="project" title="<?php echo __( 'Project', 'easyping.me' ); ?>" value="<?php echo EPME_Authorization::get_project_name(); ?>">
                                                    <label class="epme-textfield__label" for="project"><?php echo __( 'Project name', 'easyping.me' ); ?></label>
                                                    <input type="hidden" name="action" value="epme_change_name">
	                                                <?php wp_nonce_field( 'epme_change_name', 'epme_change_name_field' ); ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="epme-info-table__token-box">(<?php echo __( 'token ', 'easyping.me' ); ?> <code class="epme-info-table__token">...<?php $token_short = substr( EPME_Authorization::get_token(), -3 ); echo $token_short;
	                                ?></code> <span class="epme__show-token" data-status="show" data-short="...<?php echo $token_short; ?>" data-token="<?php echo EPME_Authorization::get_token(); ?>" data-show="<?php
	                                    echo __( 'show', 'easyping.me' );
	                                    ?>" data-hide="<?php
	                                    echo __( 'hide', 'easyping.me' );?>"><?php echo __( 'show', 'easyping.me' ); ?></span>)</div>
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect epme-edit-btn" data-type="text" data-target="project" data-edit="<?php
                                    echo __( 'Edit', 'easyping.me' );
                                    ?>" data-save="<?php
                                    echo __( 'Save', 'easyping.me' );?>">
                                        <?php echo __( 'Edit', 'easyping.me' ); ?>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="epme-info-table__tr">
                            <td class="epme-info-table__left"><?php echo __( 'Country', 'easyping.me' ); ?>:</td>
                            <td class="epme-info-table__right">
                                <div class="epme-info-table__country">
                                    <div class="epme-edit epme-edit--default epme-edit--country">
                                        <div class="epme-edit__default" data-val="<?php echo EPME_Authorization::get_country(); ?>"><?php echo EPME_Admin_Data::get_country_name_by_slug( EPME_Authorization::get_country() ); ?></div>
                                        <div class="epme-edit__to">
                                            <div class="epme-textfield">
                                                <form class="epme-edit__form">
                                                    <?php
                                                    echo EPME_Admin_Data::get_country_html( 'country', 'country', __( 'Country', 'easyping.me' ), 'epme-textfield__select', EPME_Authorization::get_country() );
                                                    ?>
                                                    <input type="hidden" name="action" value="epme_change_country">
					                                <?php wp_nonce_field( 'epme_change_country', 'epme_change_country_field' ); ?>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect epme-edit-btn" data-type="select" data-target="country" data-edit="<?php
	                                echo __( 'Edit', 'easyping.me' );
	                                ?>" data-save="<?php
	                                echo __( 'Save', 'easyping.me' );?>">
		                                <?php echo __( 'Edit', 'easyping.me' ); ?>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr class="epme-info-table__tr">
                            <td class="epme-info-table__left"><?php echo __( 'You plan', 'easyping.me' ); ?>:</td>
                            <td class="epme-info-table__right">
                                <strong><?php $plan = EPME_Authorization::get_plan(); echo ( $plan ) ? $plan : __( 'default', 'easyping.me' ); ?></strong>
                                <a href="https://easyping.me/pricing" target="_blank" class="mdl-button mdl-js-button mdl-js-ripple-effect mdc-button epme-link-icon">Change plan <i class="mdc-tab__icon material-icons">open_in_new</i></a>
                            </td>
                        </tr>
                    </table>
                    <div class="epme-platform__redeem">
                        <div class="epme-edit epme-edit--default epme-edit--redeem">
                            <div class="epme-edit__default"><i class='mdc-tab__icon material-icons'>redeem</i></div>
                            <div class="epme-edit__to">
                                <div class="epme-textfield">
                                    <form class="epme-edit__form">
                                        <input id="redeem" type="text" autocomplete="off" class="epme-textfield__input epme-required epme-edit__clear" data-default="" name="redeem" title="<?php echo __( 'Redeem special code', 'easyping.me' ); ?>" value="" placeholder="<?php echo __( 'Enter the code', 'easyping.me' ); ?>">
                                        <label class="epme-textfield__label" for="redeem"><?php echo __( 'redeem name', 'easyping.me' ); ?></label>
                                        <input type="hidden" name="action" value="epme_add_redeem">
						                <?php wp_nonce_field( 'epme_add_redeem', 'epme_add_redeem_field' ); ?>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <button class="mdl-button mdl-js-button mdc-button mdl-button--accent mdl-js-ripple-effect epme-edit-btn epme-edit" data-type="text" data-target="redeem" data-edit="<?php
		                echo __( 'Redeem special code', 'easyping.me' );
		                ?>" data-save="<?php
		                echo __( 'Send', 'easyping.me' );?>">
	                        <?php echo __( 'Redeem special code', 'easyping.me' ); ?>
                        </button>
                    </div>
                    <div class="epme-links">
                        <div class="epme-link epme-link--border-hover epme-modal-link" data-link=".epme-modal--refund-policy"><?php echo __( 'Refund Policy', 'easyping.me' ); ?></div>
                        <div class="epme-modal epme-modal--refund-policy">
                            <div class="epme-modal__cont">
                                <h4 class="mdl-dialog__title"><?php echo __( 'Refund Policy', 'easyping.me' ); ?></h4>
                                <div class="mdl-dialog__content">
                                    <p>For Refund policy please refer to our latest Terms of Service document permanently located here: <a href="https://easyping.me/tos" target="_blank">https://easyping.me/tos</a></p>
                                </div>
                                <div class="mdl-dialog__actions">
                                    <button type="button" class="mdl-button epme-modal__close"><?php echo __( 'Close', 'easyping.me' ); ?></button>
                                </div>
                            </div>
                            <div class="epme-modal__overlay epme-modal__close"></div>
                        </div>
                    </div>
                    <div class="epme-balance">
                        <div class="epme-title"><?php echo __( 'Balance', 'easyping.me' ); ?></div>
                        <table class="epme-info-table">
                            <tr class="epme-info-table__tr">
                                <td class="epme-info-table__left"><?php echo __( 'Your balance', 'easyping.me' ); ?>:</td>
                                <td class="epme-info-table__right">
                                    <div class="epme-info-table__balance">
                                        <span class="mdl-button--accent"><?php echo number_format( EPME_Authorization::get_balance(), 2, '.', '' ); ?></span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="epme-balance__cta">
                            <button
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored mdc-button epme-modal-link" data-link=".epme-modal--amount-payment">
                                <?php echo __( 'Add funds', 'easyping.me' ); ?> <i class="material-icons mdc-button__icon mdc-button__icon--left">arrow_right_alt</i>
                            </button>
                            <div class="epme-balance__PayLine"></div>
                            <div class="epme-modal epme-modal--amount-payment epme-modal--narrow">
                                <div class="epme-modal__cont">
                                    <h4 class="mdl-dialog__title"><?php echo __( 'Add funds', 'easyping.me' ); ?></h4>
                                    <div class="mdl-dialog__content amount-payment">
                                        <table>
                                            <tr>
                                                <td><div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                        <input class="mdl-textfield__input epme-textfield__input epme-number-input amount-payment__input" type="text" id="amount-payment" value="10">
                                                        <label class="mdl-textfield__label" for="amount-payment"><?php echo __( 'Amount of payment', 'easyping.me' ); ?></label>
                                                    </div></td>
                                                <td>Euro</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="mdl-dialog__actions">
                                        <button
                                            class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored mdc-button epme-add-funds">
		                                    <?php echo __( 'Go to the payment', 'easyping.me' ); ?> <i class="material-icons mdc-button__icon mdc-button__icon--left">arrow_right_alt</i>
                                        </button>
                                        <button type="button" class="mdl-button epme-modal__close"><?php echo __( 'Close', 'easyping.me' ); ?></button>
                                    </div>
                                    <div class="epme-modal__loading"><div class="mdl-spinner mdl-js-spinner is-active"></div></div>
                                </div>
                                <div class="epme-modal__overlay epme-modal__close"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	        <?php
        } else {
	        ?>
            <div class="epme-title epme-title--border"><?php echo __( 'First step', 'easyping.me' ); ?></div>
            <div class="epme-platform__google">
                <p><?php echo __( 'Press the Google button to create a new or sign-in to your existing PM.chat account. As easy as that. Just one click and you are ready to go!', 'easyping.me' ); ?></p>
                <button
                    class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored mdc-button epme-sign-in">
                    <i class="material-icons mdc-button__icon">input</i> <?php echo __( 'Sign in with Google', 'easyping.me' ); ?>
                </button>
            </div>
            <div class="epme-classic-login">
<!--                <div class="epme-title--caps">--><?php //echo sprintf( esc_html__( 'Already registered with %1$sPM.chat%2$s?', 'easyping.me' ), '<a href="https://pm.chat/?utm_source=wp-plugin&utm_medium=organic&utm_campaign=platform_main_screen&utm_content=already_registered" target="_blank">', '</a>' ); ?><!--</div>-->
                <div class="epme-classic-login__int">
                    <div class="epme-classic-login__link epme-link epme-link--border-hover epme-link--t-t-none"><?php echo __( 'Email & password login option', 'easyping.me' ); ?> <i class="material-icons">keyboard_arrow_down</i>
                    </div>
                </div>
                <div class="epme-classic-login__form-box">
                    <form class="epme-classic-login__form">
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label epme-classic-login__login">
                            <input class="mdl-textfield__input epme-textfield__input" autocomplete="off" type="text" id="epme-login" name="login" value="">
                            <label class="mdl-textfield__label" for="epme-login"><?php echo __( 'Login', 'easyping.me' ); ?></label>
                        </div>
                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label epme-classic-login__password">
                            <input class="mdl-textfield__input epme-textfield__input" autocomplete="off" type="password" id="epme-password" name="password" value="">
                            <label class="mdl-textfield__label" for="epme-password"><?php echo __( 'Password', 'easyping.me' ); ?></label>
                        </div>
                        <button
                            type="submit"
                            class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored mdc-button epme-classic-sign-in">
                            <?php echo __( 'Sign in', 'easyping.me' ); ?>
                        </button>
                    </form>
                </div>
            </div>
            <div class="epme-links">
                <a href="https://easyping.me/faq/?utm_source=wp-plugin&utm_medium=organic&utm_campaign=plugin_side_panel&utm_content=faq" target="_blank" class="epme-link"><?php echo __( 'Help', 'easyping.me' ); ?> <i class="material-icons mdc-button__icon mdc-button__icon--left">launch</i></a>
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
}
