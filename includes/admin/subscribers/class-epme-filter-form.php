<?php
/**
 * Filter Form.
 *
 * Functions used for displaying form of Subscribers.
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
 * EPME_Filter_Form Class.
 */
class EPME_Filter_Form {

	/**
	 * Variable maneged for list of subscribers.
	 *
	 * @var EPME_Filter $list
	 */
	protected $list;

	/**
	 * List Form Constructor.
	 * 
	 * @param  int $id
	 */
	public function __construct( $id ) {
		$this->list = new EPME_Filter( $id );
	}
	
	/**
	 * Template of Subscribers List Steps.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function steps( $id, $name ) {
		switch ( $name ) {
			case 'name':
				return self::step_name( $id, $name );
			case 'filter':
				return self::step_filter( $id, $name );
			case 'finish':
				return self::step_finish( $id, $name );
		}
		return '';
	}

	/**
	 * Return filter name.
	 *
	 * @param  string $filter
	 * @return string
	 */
	public static function get_filter_name( $filter ) {
		$filters = array(
			'channels' => __( 'Channels', 'easyping.me' ),
			'socialTypes' => __( 'Social types', 'easyping.me' ),
			'subscriptions' => __( 'Subscriptions', 'easyping.me' ),
		);
		return ( isset( $filters[$filter] ) ) ? $filters[$filter] : $filter;
	}

	/**
	 * Template of Name step.
	 *
	 * @param  integer $id
	 * @param  string $name
	 * @return string
	 */
	public function step_name( $id, $name ) {
		$c_name = str_replace( '"', "'", $this->list->get_field( 'name', '' ) );
		$c_notes = str_replace( '"', "'", $this->list->get_field( 'notes', '' ) );
		ob_start();
		?>
		<div class="<?php echo $this->class_for_new_campaign_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
			<div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Type name and note of this campaign', 'easyping.me' ); ?></div>
			<div class="epme-wide-card__rows">
				<div class="epme-wide-card__left">
					<div class="epme-wide-card__box">
						<input autocomplete="off" name="epme-id" type="hidden" value="<?php echo $this->list->get_id(); ?>">
						<div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label epme-textfield">
							<input autocomplete="off" name="epme-name" class="mdl-textfield__input epme-textfield__input epme-required epme-new-campaign__name" type="text" id="epme-new-campaign__name" value="<?php echo $this->list->get_field( 'name', __( 'My amazing subscribers list template', 'easyping.me' ) ); ?>" <?php echo ( $c_name ) ? "data-prev-val=\"$c_name\"" : ''; ?>>
							<label class="mdl-textfield__label" for="epme-new-campaign__name"><?php echo __( 'Name', 'easyping.me' ); ?></label>
						</div>
						<div class="mdl-textfield mdl-js-textfield epme-campaign-note epme-flex">
							<div class="epme__label"><?php echo __( 'Note', 'easyping.me' ); ?></div>
							<textarea autocomplete="off" name="epme-note" class="mdl-textfield__input epme-textfield__input epme-campaign-note__input" rows="4" id="epme-campaign-note" placeholder='<?php echo __( 'Note for campaign (optional)', 'easyping.me' ); ?>' <?php echo ( $c_notes ) ? "data-prev-val=\"$c_notes\"" : ''; ?>><?php echo $this->list->get_field( 'notes', '' ); ?></textarea>
							<label class="mdl-textfield__label" for="epme-campaign-note"></label>
						</div>
					</div>
				</div>
				<div class="epme-wide-card__right">
					<p><?php
						echo __( 'Campaigns are for you to send your news, alerts and special offers in a form of mass-sent message to the visitors of your website that have subscribed. It is like an ordinary email newsletter but in a form of private message that each of your subscribers will get in the app they used when opting in to your subscription.', 'easyping.me' );
						?></p>
				</div>
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
		ob_start();
		?>
		<div class="<?php echo $this->class_for_new_campaign_step( array( $id, $name ) ); ?>" data-id="<?php echo $name; ?>">
			<div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Type name and note of this campaign', 'easyping.me' ); ?></div>
			<div class="epme-wide-card__rows">
                <div class="epme-wide-card__box epme-list-filter">
                    <?php
                    $filters = $this->list->get_filters();
                    foreach ( $filters as $filter => $items ) {
                        if ( empty( $items ) ) {
                            continue;
                        }
                        ?>
                        <div class="epme-list-filter__select">
                            <div class="epme-select">
                                <div class="epme__label"><?php echo self::get_filter_name( $filter ); ?></div>
                                <select autocomplete="off" class="epme-multi-select epme-filter-select" name="filters[]" multiple="multiple" title="">
                                    <?php
                                    foreach ( $items as $item ) {
                                        $name = ( isset( $item['name'] ) && $item['name'] ) ? $item['name'] : $item['id'];
                                        if ( isset( $item['socialType'] ) && $item['socialType'] ) {
                                            $social = EPME_Admin_Channels::get_channel_info( $item['socialType'] , 'short' );
	                                        $name .= " ({$social})";
                                        }
                                        echo "<option value=\"{$item['id']}\">{$name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
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
            <div class="epme-wide-card__title epme__sub-titel--small"><?php echo __( 'Congratulations! Your Subscribers List is ready.', 'easyping.me' ); ?></div>
            <div class="epme-wide-card__rows">
                <div class="epme-wide-card__left">
                    <div class="epme-wide-card__box epme-wide-card__center">
                        <div class="epme-title--caps"><?php
	                        echo __( 'Campaigns are for you to send your news, alerts and special offers in a form of mass-sent message to the visitors of your website that have subscribed. It is like an ordinary email newsletter but in a form of private message that each of your subscribers will get in the app they used when opting in to your subscription.', 'easyping.me' );
	                        ?></div>
                        <p><?php
		                    echo __( 'Campaigns are for you to send your news, alerts and special offers in a form of mass-sent message to the visitors of your website that have subscribed. It is like an ordinary email newsletter but in a form of private message that each of your subscribers will get in the app they used when opting in to your subscription.', 'easyping.me' );
		                    ?></p>
                    </div>
                </div>
                <div class="epme-wide-card__right">
                    <p><?php
						echo __( 'Campaigns are for you to send your news, alerts and special offers in a form of mass-sent message to the visitors of your website that have subscribed. It is like an ordinary email newsletter but in a form of private message that each of your subscribers will get in the app they used when opting in to your subscription.', 'easyping.me' );
						?></p>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
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
