<?php
/**
 * Admin Subscribers.
 *
 * Functions used for displaying subscribers page in admin.
 *
 * @author      easyping.me
 * @category    Admin
 * @package     easyping/Admin/Subscribers
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EPME_Admin_Subscribers Class.
 */
class EPME_Admin_Subscribers {

	/**
	 * Variable for maneged List of subscribers.
	 *
	 * @var EPME_Subscribers
	 */
	protected static $subscribers;

	/**
	 * Variable for maneged List of testers.
	 *
	 * @var EPME_Testers
	 */
	protected static $testers;

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

			$title = __( 'Subscribers', 'easyping.me' );
			$description = __( 'Subscriber is your website visitor that had subscribed to receive alerts and notifications from your website in a form of private message. You can view profiles of your subscribers and create lists for campaigns.', 'easyping.me' );
			EPME_Admin_Output::layout_start( $title, $description );

			echo '<div class="epme-cont">';
		}

		?>
        <div class="mdl-tabs mdl-js-tabs">
            <div class="mdl-tabs__tab-bar">
                <a href="#epme-subscribers"
                   class="mdl-tabs__tab <?php echo ( !isset( $_GET['testers'] ) ) ? 'is-active' : ''; ?> epme-tab-header epme-tab-header--new-campaign"
                   data-id="campaigns"><span class="mdc-tab__content"><i class="mdc-tab__icon material-icons">face</i> <?php echo __( 'Subscribers', 'easyping.me' ); ?></span></a>
                <a href="#epme-testers"
                   class="mdl-tabs__tab <?php echo ( isset( $_GET['testers'] ) ) ? 'is-active' : ''; ?> epme-tab-header epme-tab-header--testers"
                   data-id="campaigns"><span class="mdc-tab__content"><i class="mdc-tab__icon material-icons">face</i> <?php echo __( 'Testers', 'easyping.me' ); ?></span></a>
<!--                <a href="#epme-subscribers-lists"-->
<!--                   class="mdl-tabs__tab -is-active epme-tab-header epme-tab-header--campaign-history"-->
<!--                   data-id="new-campaign"><span class="mdc-tab__content"><i-->
<!--                            class="mdc-tab__icon material-icons">list</i> --><?php //echo __( 'Filters', 'easyping.me' ); ?><!--</span></a>-->
<!--                <a href="#epme-new-list"-->
<!--                   class="mdl-tabs__tab is-active epme-tab-header epme-tab-header--campaign-history"-->
<!--                   data-id="new-campaign"><span class="mdc-tab__content"><i-->
<!--                            class="mdc-tab__icon material-icons">playlist_add</i> --><?php //echo __( 'Create filter', 'easyping.me' ); ?><!--</span></a>-->
            </div>

            <div class="mdl-tabs__panel mdl-tabs__panel--campaigns <?php echo ( !isset( $_GET['testers'] ) ) ? 'is-active' : ''; ?> epme-cont__subscribers-cont" id="epme-subscribers">
	            <?php echo self::subscribers_table(); ?>
            </div>
            <div class="mdl-tabs__panel mdl-tabs__panel--campaigns <?php echo ( isset( $_GET['testers'] ) ) ? 'is-active' : ''; ?> epme-cont__subscribers-cont" id="epme-testers">
                <div class="epme-testers">
	                <?php echo self::testers(); ?>
                </div>
            </div>
<!--            <div class="mdl-tabs__panel mdl-tabs__panel--new-campaign -is-active" id="epme-subscribers-lists">-->
<!--                <div class="epme-cont__widgets-cont">-->
<!--	                --><?php //echo self::subscribers_lists(); ?>
<!--                </div>-->
<!--            </div>-->
<!--            <div class="mdl-tabs__panel mdl-tabs__panel--new-campaign is-active epme-cont__subscribers-cont" id="epme-new-list">-->
<!--				--><?php //echo self::subscribers_list(); ?>
<!--            </div>-->
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
	 * Template of Subscribers table.
	 *
	 * @return string
	 */
	public static function subscribers_table() {
		ob_start();
		self::$subscribers = new EPME_Subscribers();
		$subscribers = self::$subscribers->get();
		?>
        <table class="mdl-data-table epme-subscribers epme-table">
            <thead>
                <tr class="epme-subscribers__header epme-subscribers__item ">
                    <th class="epme-subscribers__channel"><?php echo __( 'Channel', 'easyping.me' ); ?></th>
                    <th class="epme-subscribers__name"><?php echo __( 'Subscriber name', 'easyping.me' ); ?></th>
                    <th class="epme-subscribers__country"><?php echo __( 'Country', 'easyping.me' ); ?></th>
                    <th class="epme-subscribers__date"><?php echo __( 'Date subscribed', 'easyping.me' ); ?></th>
                    <th class="epme-subscribers__more"><?php echo __( 'View more', 'easyping.me' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                $subscribers = array_reverse( $subscribers );
                foreach ( $subscribers as $subscriber ) {
                    if ( !isset( $subscriber['id'] ) OR !$subscriber['id'] ) {
                        continue;
                    }
                    $i += 1;
                    ?>
                    <tr class="epme-subscribers__item epme-subscribers__item--<?php echo $subscriber['id']; ?> epme-subscribers__item--<?php echo $subscriber['socialType']; ?>">
                        <td class="epme-subscribers__channel"><div class="epme-social-img epme-social-img--<?php echo self::$subscribers->get_social_type( $subscriber ); ?>"></div></td>
                        <td class="epme-subscribers__name"><?php echo self::$subscribers->get_name( $subscriber ); ?></td>
                        <td class="epme-subscribers__country"><?php echo self::$subscribers->get_country( $subscriber ); ?></td>
                        <td class="epme-subscribers__date epme-date--gmt-to-local"><?php echo self::$subscribers->get_date( $subscriber ); ?></td>
                        <td class="epme-subscribers__more">
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary epme-subscribers__btn-more epme-modal-link" data-link=".epme-modal--s-<?php echo $subscriber['id']; ?>" data-title="<?php echo self::$subscribers->get_name( $subscriber ); ?>"><?php echo __( 'View more', 'easyping.me' ); ?></button>
                            <div class="epme-modal epme-modal--s-<?php echo $subscriber['id']; ?>">
                                <div class="epme-modal__cont epme-modal__cont--600">
                                    <h4 class="mdl-dialog__title epme-subscriber__title"><?php echo self::$subscribers->get_name( $subscriber ); ?></h4>
                                    <div class="mdl-dialog__content epme-subscriber__info">
                                        <div class="epme-subscriber__s-data epme-subscriber__prop">
                                            <div class="epme-subscriber__icon epme-subscriber__icon--s-data"></div>
                                            <div class="epme-subscriber__descr">
                                                <div class="epme-subscriber__caption epme__sub-titel--small"><?php echo __( 'Social Data', 'easyping.me' ); ?></div>
                                                <div class="epme-subscriber__text">
                                                    <div class="epme-subscriber__social-name"><div class="epme-social-img epme-social-img--<?php echo self::$subscribers->get_social_type( $subscriber ); ?> epme-social-img--small"></div> <?php echo self::$subscribers->get_name( $subscriber ); ?></div>
                                                    <?php echo self::$subscribers->get_date( $subscriber, '—', 'epme-subscriber__date-subscribed epme-date--gmt-to-local' ); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="epme-subscriber__source epme-subscriber__prop">
                                            <div class="epme-subscriber__icon epme-subscriber__icon--source"></div>
                                            <div class="epme-subscriber__descr">
                                                <div class="epme-subscriber__caption epme__sub-titel--small"><?php echo __( 'Source', 'easyping.me' ); ?></div>
                                                <div class="epme-subscriber__text">
                                                    <?php echo self::$subscribers->get_ref_url( $subscriber, '', 'epme-subscriber__ref-url' ); ?>
                                                    <?php echo self::$subscribers->get_url( $subscriber, '—', 'epme-subscriber__url' ); ?>
                                                    <?php echo self::$subscribers->get_lang( $subscriber, '', 'epme-subscriber__lang' ); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="epme-subscriber__geo epme-subscriber__prop">
                                            <div class="epme-subscriber__icon epme-subscriber__icon--geo"></div>
                                            <div class="epme-subscriber__descr">
                                                <div class="epme-subscriber__caption epme__sub-titel--small"><?php echo __( 'Geolocation', 'easyping.me' ); ?></div>
                                                <div class="epme-subscriber__text">
                                                    <?php echo self::$subscribers->get_city( $subscriber, '', 'epme-subscriber__city' ); ?>
                                                    <?php echo self::$subscribers->get_country( $subscriber, '—', 'epme-subscriber__country' ); ?>
                                                    <?php echo self::$subscribers->get_continent( $subscriber, '', 'epme-subscriber__continent' ); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="epme-subscriber__tech epme-subscriber__prop">
                                            <div class="epme-subscriber__icon epme-subscriber__icon--tech"></div>
                                            <div class="epme-subscriber__descr">
                                                <div class="epme-subscriber__caption epme__sub-titel--small"><?php echo __( 'Technology', 'easyping.me' ); ?></div>
                                                <div class="epme-subscriber__text">
                                                    <?php echo self::$subscribers->get_device( $subscriber, '', 'epme-subscriber__device' ); ?>
                                                    <?php echo self::$subscribers->get_os( $subscriber, '—', 'epme-subscriber__os' ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-dialog__actions">
                                        <button type="button" class="mdl-button epme-modal__close"><?php echo __( 'Close', 'easyping.me' ); ?></button>
                                    </div>
                                </div>
                                <div class="epme-modal__overlay epme-modal__close"></div>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
                if ( !$i ) {
                    ?>
                    <tr class="epme-subscribers__item epme-subscribers__item--empty">
                        <td colspan="5"><?php echo __( 'So far empty :(', 'easyping.me' ); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Testers table.
	 *
	 * @return string
	 */
	public static function testers() {
		ob_start();
		self::$testers = new EPME_Testers();
		$testers = self::$testers->get();
		$testers_link = self::$testers->get_link();

		if ( $testers_link ) {
			?>
            <div class="epme-testers__add-new">
                <a href="<?php echo $testers_link; ?>"
                   class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary"
                   target="_blank">Add new tester <i
                        class="material-icons mdc-button__icon mdc-button__icon--left">open_in_new</i></a>
            </div>
			<?php
		}
        ?>
        <table class="mdl-data-table epme-table">
            <thead>
                <tr class="epme-testers__header epme-testers__item ">
                    <th class="epme-testers__network"><?php echo __( 'Network', 'easyping.me' ); ?></th>
                    <th class="epme-testers__name"><?php echo __( 'Name', 'easyping.me' ); ?></th>
                    <th class="epme-testers__role"><?php echo __( 'Role', 'easyping.me' ); ?></th>
                    <th class="epme-testers__date"><?php echo __( 'Date of auth', 'easyping.me' ); ?></th>
                    <th class="epme-testers__more"><?php echo __( 'Action', 'easyping.me' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $c = 0;
                $testers = array_reverse( $testers );

                foreach ( $testers as $tester ) {
                    if ( !isset( $tester['id'] ) OR !$tester['id'] ) {
                        continue;
                    }
                    $c += 1;
                    ?>
                    <tr class="epme-testers__item epme-testers__item--<?php echo $tester['id']; ?> epme-testers__item--<?php echo $tester['network']; ?>">
                        <td class="epme-testers__network"><div class="epme-social-img epme-social-img--<?php echo $tester['network']; ?>"></div></td>
                        <td class="epme-testers__name"><?php echo $tester['name']; ?></td>
                        <td class="epme-testers__role"><?php echo $tester['role']; ?></td>
                        <td class="epme-testers__date epme-date--gmt-to-local"><?php echo epme_get_date( $tester['authDate'] ); ?></td>

                        <td class="epme-testers__more">
                            <button class="epme-campaigns__btn--delete mdl-button mdl-js-button mdc-button__small-btn" title="<?php echo __( 'Delete the Tester', 'easyping.me' ); ?>" data-id="<?php echo $tester['id']; ?>" onclick="epme_tester_id = <?php echo $tester['id']; ?>; epme_open_dialog_modal('<?php
                            echo __( 'Are you sure you want to do this?', 'easyping.me' );
                            ?>', '<?php
                            echo __( 'Delete the Tester', 'easyping.me' );
                            ?>', 'Delete', 'Cancel', 'epme-modal--delete-tester');">
                                <i class="material-icons">delete</i>
                            </button>
                        </td>
                    </tr>
                    <?php
                }
                if ( !$c ) {
                    ?>
                    <tr class="epme-testers__item epme-testers__item--empty">
                        <td colspan="5"><?php echo __( 'So far empty :(', 'easyping.me' ); ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Subscribers Lists.
	 *
	 * @return string
	 */
	public static function subscribers_lists() {
		ob_start();
//		self::$subscribers = new EPME_Subscribers();
//		$subscribers = self::$subscribers->get();
		?>
        <div class="epme-widget mdl-card epme-widget--add-new">
            <div class="epme-widget__header">
                <div class="epme-widget__pict epme-widget__pict--new"></div>
            </div>
            <div class="epme-widget__desc">
                <div
                    class="mdl-card__title epme-widget__title epme__sub-titel--small"><?php echo __( 'Create new List', 'easyping.me' ); ?></div>
                <div
                    class="mdl-card__supporting-text epme-widget__text"><?php echo __( 'Create a preset list of subscribers to easily and quickly create your new messaging campaigns.', 'easyping.me' ); ?></div>
                <div class="epme-widget__actions ">
                    <button
                        class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary epme-widget__new-button"><?php echo __( 'Create', 'easyping.me' ); ?></button>
                </div>
            </div>
        </div>
        <div class="epme-widget mdl-card epme-widget--shadow epme-widget--1">
            <div class="epme-widget__header">
                <div class="epme-widget__pict epme-widget__pict--list"></div>
            </div>
            <div class="epme-widget__desc">
                <div
                    class="mdl-card__title epme-widget__title epme__sub-titel--small"><?php echo 'iPhone users'; ?></div>
                <div
                    class="mdl-card__supporting-text epme-widget__text"><?php echo __( 'Note of List.', 'easyping.me' ); ?></div>
                <div class="epme-widget__actions ">
                    <button
                        class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary epme-widget__new-button"><?php echo __( 'Modify', 'easyping.me' ); ?></button>
                    <button class="mdl-button mdl-js-button mdc-button__small-btn epme-widget__delete" title="<?php echo __( 'Delete Widget', 'easyping.me' ); ?>" data-id="<?php echo 2; ?>" onclick="epme_widget_id = <?php echo 2; ?>; epme_open_dialog_modal('<?php
	                echo __( 'Are you sure you want to do this?', 'easyping.me' );
	                ?>', '<?php
	                echo __( 'Delete the widget', 'easyping.me' );
	                ?>', 'Delete', 'Cancel', 'epme-modal--remove-widget');">
                        <i class="material-icons">delete</i>
                    </button>
                </div>
            </div>
        </div>
        <div class="epme-widget mdl-card epme-widget--shadow epme-widget--2">
            <div class="epme-widget__header">
                <div class="epme-widget__pict epme-widget__pict--list"></div>
            </div>
            <div class="epme-widget__desc">
                <div
                    class="mdl-card__title epme-widget__title epme__sub-titel--small"><?php echo 'Subscribers before 2018-03-15'; ?></div>
                <div
                    class="mdl-card__supporting-text epme-widget__text"><?php echo __( 'Custom Note of List', 'easyping.me' ); ?></div>
                <div class="epme-widget__actions ">
                    <button
                        class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary epme-widget__new-button"><?php echo __( 'Modify', 'easyping.me' ); ?></button>
                    <button class="mdl-button mdl-js-button mdc-button__small-btn epme-widget__delete" title="<?php echo __( 'Delete Widget', 'easyping.me' ); ?>" data-id="<?php echo 2; ?>" onclick="epme_widget_id = <?php echo 2; ?>; epme_open_dialog_modal('<?php
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
		return ob_get_clean();
	}

	/**
	 * Template of Subscribers List.
	 *
	 * @param  integer $id
	 * @return string
	 */
	public static function subscribers_list( $id = 0 ) {
		$initial_step = 2;
		$list_form = new EPME_Filter_Form( ( int )$id );
		ob_start();
		?>
        <div class="epme-cont__tab-cont">
            <div class="epme-new-campaign epme-wide-card epme-wide-card--<?php echo $initial_step; ?> mdl-card epme-card--big-shadow" data-step="<?php echo $initial_step; ?>">
                <div class="epme-wide-card__body">
					<?php echo $list_form->steps( 1, 'name' ); ?>
					<?php echo $list_form->steps( 2, 'filter' ); ?>
					<?php echo $list_form->steps( 3, 'finish' ); ?>
                </div>
                <div class="epme-wide-card__footer">
                    <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect epme-wide-card__nav epme-wide-card__nav--back" data-nav="back">Back</button>
                    <button type="button" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored epme-wide-card__nav epme-wide-card__nav--next" data-nav="next">Next</button>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}
}
