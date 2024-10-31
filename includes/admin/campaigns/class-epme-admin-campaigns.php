<?php
/**
 * Admin Campaigns.
 *
 * Functions used for displaying campaigns page in admin.
 *
 * @author      easyping.me
 * @category    Admin
 * @package     easyping/Admin/Campaigns
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EPME_Admin_Campaigns Class.
 */
class EPME_Admin_Campaigns {

	/**
	 * Variable for cache of List of Campaigns.
	 *
	 * @var $campaigns
	 */
	protected static $campaigns;

	/**
	 * Variable for cache of List of Campaigns.
	 *
	 * @var EPME_Campaigns_Form $campaigns
	 */
	protected static $campaign_form;

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
			$title = __( 'Campaigns', 'easyping.me' );
			$description = "";
			EPME_Admin_Output::layout_start( $title, $description );
			echo '<div class="epme-cont">';
		}

		if ( isset( $_GET['id-campaign'] ) AND $_GET['id-campaign'] ) {
			echo self::new_campaign_template( ( int )$_GET['id-campaign'] );
		} else {
			?>
            <div class="mdl-tabs mdl-js-tabs">
                <div class="mdl-tabs__tab-bar">
                    <a href="#epme-new-campaign"
                       class="mdl-tabs__tab -is-active epme-tab-header epme-tab-header--new-campaign"
                       data-id="campaigns"><span class="mdc-tab__content"><i class="mdc-tab__icon material-icons">assignment_ind</i> <?php echo __( 'Create new campaign', 'easyping.me' ); ?></span></a>
                    <a href="#epme-campaign-history"
                       class="mdl-tabs__tab is-active epme-tab-header epme-tab-header--campaign-history"
                       data-id="new-campaign"><span class="mdc-tab__content"><i
                                class="mdc-tab__icon material-icons">assignment</i> <?php echo __( 'Campaign history', 'easyping.me' ); ?></span></a>
                </div>

                <div class="mdl-tabs__panel mdl-tabs__panel--campaigns -is-active" id="epme-new-campaign">
					<?php echo self::new_campaign_template( 0 ); ?>
                </div>
                <div class="mdl-tabs__panel mdl-tabs__panel--new-campaign is-active" id="epme-campaign-history">
					<?php echo self::campaigns_template(); ?>
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
	 * Template of Campaign list.
	 *
	 * @return string
	 */
	public static function campaigns_template() {
		ob_start();
		?>
		<div class="epme-cont__tab-cont">
			<?php echo self::campaigns_table_template(); ?>
<!--			<div class="epme-campaigns epme-wide-card mdl-card epme-card--big-shadow">-->
<!--				<div class="epme-wide-card__body epme-campaigns__refresh">-->
<!--				</div>-->
<!--			</div>-->
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of table with Campaign.
	 *
	 * @return string
	 */
	public static function campaigns_table_template() {
		$campaigns = self::get_campaigns();
		ob_start();
        if ( !empty( $campaigns ) ) :
		?>
            <table class="mdl-data-table epme-campaigns epme-table">
            <thead>
                <tr class="epme-campaigns__header epme-campaigns__item ">
                    <th class="epme-campaigns__state"><?php echo __( 'Status', 'easyping.me' ); ?></th>
                    <th class="epme-campaigns__name"><?php echo __( 'Campaign name', 'easyping.me' ); ?></th>
    <!--                            <th class="epme-campaigns__date-created">--><?php //echo __( 'Campaign Created', 'easyping.me' ); ?><!--</th>-->
                    <th class="epme-campaigns__date"><?php echo __( 'Date started', 'easyping.me' ); ?></th>
                    <th class="epme-campaigns__recipients"><?php echo __( 'Recipients', 'easyping.me' ); ?></th>
                    <th class="epme-campaigns__delivered"><?php echo __( 'Delivered', 'easyping.me' ); ?></th>
                    <th class="epme-campaigns__clicked-trough"><?php echo __( 'Clicked trough', 'easyping.me' ); ?></th>
                    <th class="epme-campaigns__actions"><?php echo __( 'Details and Actions', 'easyping.me' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 0;
                $campaigns = array_reverse( $campaigns );

                $modal_html = '';
                foreach ( $campaigns as $campaign ) {
                    if ( !isset( $campaign['id'] ) OR !$campaign['id'] ) {
                        continue;
                    }
                    $i += 1;
                    ?>
                    <tr class="epme-campaigns__item epme-campaigns__item--<?php echo $campaign['id']; ?>">
                        <td class="epme-campaigns__state"><?php echo self::get_state( $campaign ); ?></td>
                        <td class="epme-campaigns__name"><?php echo epme_get_field( $campaign, 'name' ); ?></td>
    <!--                                <td class="epme-campaigns__date-created epme-date--gmt-to-local">--><?php //echo epme_get_date( $campaign, '', '', 'created' ); ?><!--</td>-->
                        <td class="epme-campaigns__date epme-date--gmt-to-local"><?php echo epme_get_date( $campaign, '2019-04-'. rand(1,30) .'T17:10:09+3', '', 'dateStart' ); ?></td>
                        <td class="epme-campaigns__recipients"><?php echo epme_get_field( $campaign, 'contactCount', '—' ); ?></td>
                        <td class="epme-campaigns__delivered"><?php echo epme_get_field( $campaign, 'delivered', '—' ); ?></td>
                        <td class="epme-campaigns__clicked-trough"><?php echo epme_get_field( $campaign, 'clicked-trough', '—' ); ?></td>
                        <td class="epme-campaigns__actions">
                            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-button--primary epme-campaigns__btn-more epme-modal-link" data-link=".epme-modal--s-<?php echo $campaign['id']; ?>" data-title="<?php echo epme_get_field( $campaign, 'name' ); ?>"><?php echo __( 'Details', 'easyping.me' ); ?></button>
                            <?php ob_start(); ?>
                            <div class="epme-modal epme-modal--s-<?php echo $campaign['id']; ?>">
                                <div class="epme-modal__cont epme-modal__cont--700">
                                    <h4 class="mdl-dialog__title epme-campaign__title"><?php echo epme_get_field( $campaign, 'name' ); ?></h4>
                                    <div class="mdl-dialog__content">
                                        <div class="epme-campaign__info epme-diagram-items">
                                            <div class="epme-diagram-item epme-diagram-item--">
                                                <div class="epme-diagram-item__graph">
                                                    <svg class="epme-diagram"><circle class="epme-diagram__circle epme-diagram__circle--1" r="65" cx="-56" cy="80"></circle><circle class="epme-diagram__circle epme-diagram__circle--2" r="65" cx="-56" cy="80" style="stroke-dasharray: <?php echo 419*100/100 ?>px;"></circle></svg>
                                                    <div class="epme-diagram-item__value"><?php echo self::get_state( $campaign ); ?></div>
                                                </div>
                                                <div class="epme-diagram-item__title"><?php echo __( 'Status', 'easyping.me' ); ?></div>
                                                <div class="epme-diagram-item__status">of the campaign</div>
                                            </div>
                                            <div class="epme-diagram-item epme-diagram-item--contactCount">
                                                <div class="epme-diagram-item__graph">
                                                    <svg class="epme-diagram"><circle class="epme-diagram__circle epme-diagram__circle--1" r="65" cx="-56" cy="80"></circle><circle class="epme-diagram__circle epme-diagram__circle--2" r="65" cx="-56" cy="80" style="stroke-dasharray: <?php echo 419*100/100 ?>px;"></circle></svg>
                                                    <div class="epme-diagram-item__value"><?php echo epme_get_field( $campaign, 'contactCount', '—' ); ?></div>
                                                </div>
                                                <div class="epme-diagram-item__title"><?php echo __( 'Total messages', 'easyping.me' ); ?></div>
                                                <div class="epme-diagram-item__status"><?php echo __( 'in campaign', 'easyping.me' ); ?></div>
                                            </div>
                                            <div class="epme-diagram-item epme-diagram-item--">
                                                <div class="epme-diagram-item__graph">
                                                    <svg class="epme-diagram"><circle class="epme-diagram__circle epme-diagram__circle--1" r="65" cx="-56" cy="80"></circle><circle class="epme-diagram__circle epme-diagram__circle--2" r="65" cx="-56" cy="80" style="stroke-dasharray: <?php echo 419*5/100 ?>px;"></circle></svg>
                                                    <div class="epme-diagram-item__value">—</div>
                                                </div>
                                                <div class="epme-diagram-item__title"><?php echo __( 'Messages delivered', 'easyping.me' ); ?></div>
                                                <div class="epme-diagram-item__status"><?php echo __( 'to subscribers', 'easyping.me' ); ?></div>
                                            </div>
                                            <div class="epme-diagram-item epme-diagram-item--">
                                                <div class="epme-diagram-item__graph">
                                                    <svg class="epme-diagram"><circle class="epme-diagram__circle epme-diagram__circle--1" r="65" cx="-56" cy="80"></circle><circle class="epme-diagram__circle epme-diagram__circle--2" r="65" cx="-56" cy="80" style="stroke-dasharray: <?php echo 419*5/100 ?>px;"></circle></svg>
                                                    <div class="epme-diagram-item__value">—</div>
                                                </div>
                                                <div class="epme-diagram-item__title"><?php echo __( 'Conversion rate', 'easyping.me' ); ?></div>
                                                <div class="epme-diagram-item__status"><?php echo __( 'clicked link in message', 'easyping.me' ); ?></div>
                                            </div>
                                        </div>
                                        <?php
                                        if ( $campaign['notes'] ) {
                                            ?>
                                            <div class="mdl-dialog__block">
                                                <div class="epme__sub-titel--small"><?php echo __( 'Notes', 'easyping.me' ); ?></div>
                                                <p><?php echo trim( epme_get_field( $campaign, 'notes', '—' ) ); ?></p>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        <div class="mdl-dialog__block">
                                            <div class="epme__sub-titel--small"><?php echo __( 'Actions', 'easyping.me' ); ?></div>
                                            <div class="epme-campaigns__modal-actions">
                                                <a href="<?php echo admin_url( 'admin.php?page=epme-campaigns&id-campaign=' . $campaign['id'] ); ?>"
                                                   class="mdl-button mdl-js-button mdl-button--raised <?php echo ( !in_array( $campaign['state'], array( 'submitted', 'in_process', 'paused' ) ) ) ? 'mdl-button--colored mdl-button--primary' : ''; ?>"><?php echo __( 'Modify', 'easyping.me' ); ?></a>
                                                <button class="epme-campaigns__btn--archive mdl-button mdl-button--raised" title="<?php echo __( 'Delete the Campaign', 'easyping.me' ); ?>" data-id="<?php echo $campaign['id']; ?>" onclick="epme_campaign_id = <?php echo $campaign['id']; ?>; epme_open_dialog_modal('<?php
                                                echo __( 'Are you sure you want to do this?', 'easyping.me' );
                                                ?>', '<?php
                                                echo __( 'Archive campaign', 'easyping.me' );
                                                ?>', 'Archive', 'Cancel', 'epme-modal--archive-campaign');">
                                                    <?php echo __( 'Archive', 'easyping.me' ); ?>
                                                </button>
                                                <button class="epme-campaigns__btn--delete mdl-button mdl-js-button mdc-button__small-btn" title="<?php echo __( 'Delete the Campaign', 'easyping.me' ); ?>" data-id="<?php echo $campaign['id']; ?>" onclick="epme_campaign_id = <?php echo $campaign['id']; ?>; epme_open_dialog_modal('<?php
                                                echo __( 'Are you sure you want to do this?', 'easyping.me' );
                                                ?>', '<?php
                                                echo __( 'Delete the Campaign', 'easyping.me' );
                                                ?>', 'Delete', 'Cancel', 'epme-modal--delete-campaign');">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-dialog__actions">
                                        <button type="button" class="mdl-button epme-modal__close"><?php echo __( 'Close', 'easyping.me' ); ?></button>
                                    </div>
                                    <div class="epme-modal__loading"><div class="mdl-spinner mdl-js-spinner is-active"></div></div>
                                </div>
                                <div class="epme-modal__overlay epme-modal__close"></div>
                            </div>
                            <?php $modal_html .= ob_get_clean(); ?>
                        </td>
                    </tr>
                    <?php
                }
        else :
            ?>
            <div class="epme-campaigns epme-wide-card mdl-card mdl-card--empty">
                <div class="epme-wide-card__body">
                    <?php echo __( 'So far empty :(', 'easyping.me' ); ?>
                </div>
            </div>
            <?php
        endif;
		if ( !empty( $campaigns ) ) :
        ?>
            </tbody>
            </table>
		<?php
		endif;
        echo ( isset( $modal_html ) AND $modal_html ) ? $modal_html : '';

		return ob_get_clean();
	}

	/**
	 * Template of New Campaign.
	 *
     * @param  int $id
	 * @return string
	 */
	public static function new_campaign_template( $id = 0 ) {
		$initial_step = 2;
		self::$campaign_form = new EPME_Campaigns_Form( $id );
		echo self::$campaign_form->get_blocks_json();
		ob_start();
		?>
		<div class="epme-cont__tab-cont">
			<p><?php
				echo __( 'Unleash the power of social messaging by creating a mass-campaign. Ping subscribers with a news update, special offer or a coupon code with the super deal!', 'easyping.me' );
				?></p>
			<p><?php
				echo sprintf( esc_html__( 'Messages give 90%% open rate (emails give max 20%%). Sharp concise message text and a catchy photo can rocket your conversion of 70%% and more! Use this powerful tool for sales and customer communications! Please %1$sread our article%2$s in FAQ on how to create good campaign messages.', 'easyping.me' ), '<a href="https://PM.chat" target="_blank">', '</a>' );
				?></p>

			<div class="epme-new-campaign epme-wide-card epme-wide-card--<?php echo $initial_step; ?> mdl-card epme-card--big-shadow" data-step="<?php echo $initial_step; ?>">
				<div class="epme-wide-card__body">
                    <?php echo self::$campaign_form->steps( 1, 'name' ); ?>
                    <?php echo self::$campaign_form->steps( 3, 'fine-tune' ); ?>
                    <?php echo self::$campaign_form->steps( 2, 'master' ); ?>
                    <?php echo self::$campaign_form->steps( 4, 'filter' ); ?>
                    <?php echo self::$campaign_form->steps( 5, 'date' ); ?>
                    <?php echo self::$campaign_form->steps( 6, 'finish' ); ?>
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

	/**
	 * Return State.
	 *
	 * @param  array $campaign
	 * @param  string $def
	 * @param  string $class
	 * @param  string $key
	 * @return string
	 */
	public static function get_state( $campaign, $def = '', $class = '', $key = 'state' ) {
        $states = array(
	        'new' => __( 'New', 'easyping.me' ),
	        'submitted' => __( 'submitted', 'easyping.me' ),
	        'in_process' => __( 'in process', 'easyping.me' ),
	        'paused' => __( 'paused', 'easyping.me' ),
	        'completed' => __( 'completed', 'easyping.me' ),
	        'finished' => __( 'finished', 'easyping.me' ),
	        'archived' => __( 'archived', 'easyping.me' ),
	        'deleted' => __( 'deleted', 'easyping.me' ),
        );

		if ( isset( $campaign[$key] ) AND $campaign[$key] ) {
            if ( isset( $states[$campaign[$key]] ) ) {
	            $data = $states[$campaign[$key]];
            } else {
	            $data = $campaign[$key];
            }
		} else {
			$data = $def;
		}
		if ( $data ) {
			if ( $class ) {
				return "<div class='$class'>$data</div>";
			} else {
				return $data;
			}
		}
		return '';
	}

	/**
	 * Return subscribers.
	 *
	 * @return array
	 */
	public static function get_campaigns() {
		if ( empty( self::$campaigns ) OR !self::$campaigns ) {
			$response = EPME_Connects::get_campaigns();
			if ( $response['type'] !== 'error' ) {
				self::$campaigns = epme_fix_bool_in_arr( $response['respond'] );
			} else {
				return array();
			}
		}
		return self::$campaigns;
	}
}