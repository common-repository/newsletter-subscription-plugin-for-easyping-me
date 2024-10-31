<?php
/**
 * Campaigns Form.
 *
 * Functions used for displaying campaigns form.
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
 * EPME_Campaigns_Form Class.
 */
class EPME_Campaigns_Form {

	/**
	 * Data of campaign for editing.
	 *
	 * @var EPME_Campaign $campaign
	 */
	protected $campaign;

	/**
	 * Variable maneged for list of subscribers.
	 *
	 * @var EPME_Filter $list
	 */
	protected $list;

	/**
	 * Variable for message blocks for Campaign.
	 *
	 * @var EPME_Blocks $blocks
	 */
	protected $blocks;

	/**
	 * Campaigns Form Constructor.
	 *
	 * @param  int $id
	 */
	public function __construct( $id ) {
		$this->campaign = new EPME_Campaign( $id );
		$this->list = new EPME_Filter( 0 /* $id_list */ );
		$this->blocks = new EPME_Blocks();
	}

	/**
	 * Template of New campaign Steps.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function steps( $id, $name ) {
		switch ( $name ) {
			case 'name':
				return $this->step_name( $id, $name );
			case 'master':
				return $this->step_master( $id, $name );
			case 'fine-tune':
				return $this->step_fine_tune( $id, $name );
			case 'filter':
				return $this->step_filter( $id, $name );
			case 'date':
				return $this->step_date( $id, $name );
			case 'finish':
				return $this->step_finish( $id, $name );
		}
		return '';
	}

	/**
	 * Template of Name step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function step_name( $id, $name ) {
		$c_name = str_replace( '"', "'", $this->campaign->get_field( 'name', '' ) );
		$c_notes = str_replace( '"', "'", $this->campaign->get_field( 'notes', '' ) );
		ob_start();
		?>
		<div class="<?php echo $this->class_for_new_campaign_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
			<div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Let\'s name this campaign and add notes', 'easyping.me' ); ?></div>
			<div class="epme-wide-card__rows">
				<div class="epme-wide-card__left">
					<div class="epme-wide-card__box">
						<input autocomplete="off" name="epme-id" type="hidden" value="<?php echo $this->campaign->get_id(); ?>">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label epme-textfield">
							<input autocomplete="off" name="epme-name" class="mdl-textfield__input epme-textfield__input epme-required epme-new-campaign__name" type="text" id="epme-new-campaign__name" value="<?php echo $this->campaign->get_field( 'name', __( 'My amazing campaign', 'easyping.me' ) ); ?>" <?php echo ( $c_name ) ? "data-prev-val=\"$c_name\"" : ''; ?>>
							<label class="mdl-textfield__label" for="epme-new-campaign__name"><?php echo __( 'Name', 'easyping.me' ); ?></label>
						</div>
						<div class="mdl-textfield mdl-js-textfield epme-campaign-note epme-flex">
							<div class="epme__label"><?php echo __( 'Note', 'easyping.me' ); ?></div>
							<textarea autocomplete="off" name="epme-note" class="mdl-textfield__input epme-textfield__input epme-campaign-note__input" rows="4" id="epme-campaign-note" placeholder='<?php echo __( 'Type your notes here (it is optional)', 'easyping.me' ); ?>' data-prev-val="<?php echo ( $c_notes ) ? $c_notes : ''; ?>"><?php echo $this->campaign->get_field( 'notes', '' ); ?></textarea>
							<label class="mdl-textfield__label" for="epme-campaign-note"></label>
						</div>
					</div>
				</div>
				<div class="epme-wide-card__right">
					<p><?php
						echo __( 'We advise you to name your campaign so that you can quickly recollect what is was about and who was the target audience. So it is easy for you to do analytics later on and the whole messaging is in order nicely :)
', 'easyping.me' );
						?></p>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of  Message Content step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function step_master( $id, $name ) {
		EPME_Admin_Output::add_text( 'mb-title', __( 'Possible data loss!', 'easyping.me' ) );
		EPME_Admin_Output::add_text( 'mb-content', __( 'Custom content per social channel (Fine-tune) will be overwritten if you change General content here', 'easyping.me' ) );
		EPME_Admin_Output::add_text( 'mb-understand', __( 'Add block', 'easyping.me' ) );
		EPME_Admin_Output::add_text( 'mb-close', __( 'Close', 'easyping.me' ) );
		ob_start();
		?>
		<div class="<?php echo $this->class_for_new_campaign_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
			<div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Compose the message for your subscribers', 'easyping.me' ); ?></div>
			<?php echo $this->blocks->campaign_message_template( 'master' ); ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Fine-tune step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function step_fine_tune( $id, $name ) {
		$channels = EPME_Admin_Channels::channels_texts();
		ob_start();
		?>
		<div class="<?php echo $this->class_for_new_campaign_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
			<div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Fine-tune each channel', 'easyping.me' ); ?></div>
			<div class="mdl-tabs mdl-js-tabs epme-tabs epme-tabs--fine-tune">
				<div class="mdl-tabs__tab-bar">
					<?php
					$i = 0;
					foreach ( $channels as $channel_id => $channel_texts ) {
						if ( isset( $channel_texts['is_disable'] ) AND $channel_texts['is_disable'] ) {
							continue;
						}
						++$i;
						?>
						<div
							class="epme-tab <?php echo ( $i == 1 ) ? 'epme-is-active' : ''; ?> epme-tab-header epme-tab-header--<?php echo $channel_id; ?>"
							data-id="<?php echo $channel_id; ?>" data-target=".mdl-tabs__panel--<?php echo $channel_id; ?>"><span class="mdc-tab__content"><i class="epme-social-img epme-social-img--<?php echo $channel_id; ?>"></i> <?php echo $channel_texts['short']; ?></span></div>
						<?php
					}
					?>
				</div>

				<?php
				$i = 0;
				foreach ( $channels as $channel_id => $channel_texts ) {
					if ( isset( $channel_texts['is_disable'] ) AND $channel_texts['is_disable'] ) {
						continue;
					}
					$message = ( isset( $channel_texts['message'] ) ) ? $channel_texts['message'] : '';
					++$i;
					?>
					<div class="mdl-tabs__panel mdl-tabs__panel--<?php echo $channel_id; ?> <?php echo ( $i == 1 ) ? 'epme-is-active' : ''; ?>">
						<?php echo $this->blocks->campaign_message_template( $channel_id, $message ); ?>
					</div>
					<?php
				}
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Filter step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function step_filter( $id, $name ) {
		EPME_Admin_Output::add_text( 'sub-placeholder', __( 'All', 'easyping.me' ) );
		$this->list = new EPME_Filter( $id );
		ob_start();
		?>
        <div class="<?php echo $this->class_for_new_campaign_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
            <div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Add subscribers to receive this Campaign', 'easyping.me' ); ?></div>
            <div class="epme-wide-card__cell">
                <div class="epme-title--caps"><?php echo __( 'Use these Filters to segment your audience:', 'easyping.me' ); ?></div>
                <div class="epme-wide-card__box epme-list-filter">
					<?php
					$filters = $this->list->get_filters();
					$selected_filters = epme_fix_bool_in_arr( $this->campaign->get_field( 'contactFilters' ) );

					foreach ( $filters as $filter => $items ) {
						if ( empty( $items ) ) {
							continue;
						}
						?>
                        <div class="epme-list-filter__select">
                            <div class="epme-select">
                                <div class="epme__label"><?php echo EPME_Filter_Form::get_filter_name( $filter ); ?></div>
                                <select autocomplete="off" class="epme-multi-select epme-filter-select" data-id="<?php echo $filter; ?>" name="filters[]" multiple="multiple" title="">
									<?php
									foreach ( $items as $item ) {
										$name = ( isset( $item['name'] ) && $item['name'] ) ? $item['name'] : $item['id'];
										$selected = '';

										if ( !empty( $selected_filters[$filter] ) ) {
											foreach ( $selected_filters[$filter] as $selected_filter ) {
                                                if ( $selected_filter == $item['id'] ) {
	                                                $selected = 'selected';
                                                }
										    }
                                        }

										if ( isset( $item['socialType'] ) && $item['socialType'] ) {
											$social = EPME_Admin_Channels::get_channel_info( $item['socialType'] , 'short' );
											$name .= " ({$social})";
										}
										echo "<option value=\"{$item['id']}\" $selected>{$name}</option>";
									}
									?>
                                </select>
                            </div>
                        </div>
						<?php
					}
					?>
                </div>
                <div class="epme-filter-footer">
                    <div class="epme-title--caps"><?php echo __( 'Selected Subscribers', 'easyping.me' ); ?></div>
                    <div class="epme-filter-footer__subscribers">
		                <?php echo __( 'Total number of subscribers:', 'easyping.me' ); ?>
                        <div class="epme-filter-footer__total-subscribers"><?php echo $this->campaign->get_field( 'contactCount', 0 ); ?> <i class="epme-small-loader epme-preload"></i></div>
                    </div>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Date step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function step_date( $id, $name ) {
		$utc_date = epme_get_date( array( 'date' => $this->campaign->get_field( 'dateStart', 'tomorrow' ) ), 'tomorrow' );
		ob_start();
		?>
		<div class="<?php echo $this->class_for_new_campaign_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
			<div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Set the time to start sending this campaign', 'easyping.me' ); ?></div>
			<div class="epme-wide-card__rows">
				<div class="epme-wide-card__left">
					<div class="epme-wide-card__box epme-wide-card__center">
						<label for="empe-campaign-date" class=""><?php echo __( 'Start sending from:', 'easyping.me' ); ?> <abbr class="required" title="<?php echo __( 'Required', 'easyping.me' ); ?>">*</abbr></label>
						<input autocomplete="off" class="empe-campaign__date epme-required" name="epme-date" id="empe-campaign-date" value="<?php echo $utc_date; ?>" data-utc="<?php echo $utc_date; ?>" data-masc="isoDate" type="date">
						<input autocomplete="off" class="empe-campaign__time epme-required" name="epme-time" id="empe-campaign-time" value="<?php echo $utc_date; ?>" data-utc="<?php echo $utc_date; ?>" data-masc="HH:MM" type="time" title="<?php echo __( 'Campaign start time', 'easyping.me' ); ?>">
					</div>
				</div>
				<div class="epme-wide-card__right">
					<p><?php
						echo __( 'Easyping platform will start sending messages to your subscribers from the time you set here. Please note all messages are queued first and then sent evenly in different bulks depending on the destination social app. This is done to avoid your social account being blocked for sending too many messages.', 'easyping.me' );
						?></p>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Finish step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function step_finish( $id, $name ) {
		ob_start();
		?>
		<div class="<?php echo $this->class_for_new_campaign_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
			<div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Congratulations! Your Campaign is ready.', 'easyping.me' ); ?></div>
			<div class="epme-wide-card__rows">
				<div class="epme-wide-card__left">
					<div class="epme-wide-card__box epme-wide-card__center">

					</div>
				</div>
				<div class="epme-wide-card__right">
					<p><?php
						echo sprintf( esc_html__( 'Well done! Now you have scheduled your campaign. For the sending process status please check %1$sCampaigns tab%2$s.', 'easyping.me' ), '<a href="'. admin_url( 'admin.php?page=epme-campaigns' ) .'" target="_blank">', '</a>' );
						?></p>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Return value of field.
	 *
	 * @return string
	 */
	public function get_blocks_json() {
		return $this->campaign->get_blocks_json();
	}

	/**
	 * Generate class for new_campaign_step.
	 *
	 * @param  array $classes
	 * @param  string $add
	 * @param  string $base
	 * @return string
	 */
	public function class_for_new_campaign_step( $classes, $add = '', $base = 'epme-wide-card__cont' ) {
		$result = "{$base} ";
		foreach ( $classes as $class ) {
			if ( $class ) {
				$result .= "{$base}--{$class} ";
			}
		}
		return "{$result} {$add}";
	}
}