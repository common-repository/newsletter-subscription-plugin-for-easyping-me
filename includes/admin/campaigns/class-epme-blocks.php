<?php
/**
 * Message blocks for Campaign.
 *
 * Class used for displaying message building form for Campaign.
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
 * EPME_Blocks Class.
 */
class EPME_Blocks {

	/**
	 * @var array
	 */
	protected $message_blocks = array();

	/**
	 * @var EPME_Testers
	 */
	protected $testers;

	/**
	 * Campaigns Form Constructor.
	 */
	public function __construct() {
		$this->message_blocks();
		$this->testers = new EPME_Testers();
	}

	/**
	 * Init $message_blocks variable.
	 *
	 * @return array
	 */
	public function message_blocks() {
		if ( empty( $this->message_blocks ) ) {
			$this->message_blocks = array(
				'Image/Photo' => array(
					'name'                => __( 'Image/Photo', 'easyping.me' ),
					'slug'                => 'image',
					'icon'                => 'crop_original',
					'function_content'    => array( 'EPME_Blocks', 'content_image_photo' ),
					'active'              => true,
				),
				'Short Text' => array(
					'name'                => __( 'Short Text', 'easyping.me' ),
					'slug'                => 'text',
					'icon'                => 'text_fields',
					'function_content'    => array( 'EPME_Blocks', 'content_short_text' ),
					'active'              => true,
				),
			);
		}
		return apply_filters( 'epme_message_blocks', $this->message_blocks );
	}

	/**
	 * Template of Campaign Message section.
	 *
	 * @param  string $type
	 * @param  string $message
	 * @return string
	 */
	public function campaign_message_template( $type, $message = '' ) {
		ob_start();
		?>
		<div class="epme-wide-card__rows empe-campaign-message empe-campaign-message--sections empe-campaign-message--<?php echo $type; ?>">
			<div class="epme-wide-card__left empe-campaign-message__left">
				<div class="epme-wide-card__box">
					<div class="epme-title--caps"><?php echo __( 'Choose the block', 'easyping.me' ); ?></div>
					<div class="epme-source-message-blocks">
						<?php echo $this->source_message_blocks( $type ); ?>
					</div>
				</div>
			</div>
			<div class="epme-wide-card__right empe-campaign-message__right">
				<div class="epme-title--caps"><?php echo __( 'Your message', 'easyping.me' ); ?></div>
				<div class="epme-message-build">
					<div class="epme-message-build__body epme-message-build__body--0" data-count="0">
						<div class="epme-info epme-info--empty"><?php echo __( 'Build your message from the blocks located on your left', 'easyping.me' ); ?></div>
						<div class="epme-info epme-info--1">
							<div class="epme-info__title"><?php
								echo __( 'Attention! Custom message contents for individual social channel changed', 'easyping.me' );
								?></div><?php
							echo __( 'Please note: any changes of the General message here replaces custom content you might have set for each individual channel', 'easyping.me' );
							?> <span class="epme-message-build__changed-channels"></span>.
						</div>
						<div class="epme-message-blocks empy-sortable"></div>
						<?php if ( $message ) { ?>
							<div class="epme-info epme-info--2"><?php echo $message; ?></div>
						<?php } ?>
					</div>
					<div class="epme-message-build__footer">
                        <?php echo $this->campaign_footer_template( $type ) ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Campaign Footer section.
	 *
	 * @param  string $type
	 * @return string
	 */
	public function campaign_footer_template( $type ) {
		ob_start();
		$testers = $this->testers->get_contacts_by_type( $type );
		if ( $type == 'master' ) {
            $channels = $this->testers->get_channels();
			$testers = $channels;
        }
		?>
            <div class="epme-message-build__testers epme-message-build__testers--<?php echo count( $testers ); ?>"><?php
                $message_empty_testers = sprintf( esc_html__( 'You don\'t have testers. Click the %1$slink%2$s to create the tests.', 'easyping.me' ), "<a href='". admin_url( 'admin.php?page=epme-subscribers&testers' ) ."' target='blank'>", '</a>' );
                $btn_refresh_tetsters = "<div class='epme-link epme-link--border-hover epme-message-build__testers-refresh' data-type='$type'>". __( 'Refresh testers', 'easyping.me' ) ." <i class=\"mdc-tab__icon material-icons\">refresh</i></div>";
	            if ( $type == 'master' ) {
		            if ( empty( $channels ) ) {
			            echo $message_empty_testers;
			            echo $btn_refresh_tetsters;
                    } else {
                        echo "<div class='epme-message-build__testers-title'>";
			            echo __( 'Social apps of your testers for message preview', 'easyping.me' );
			            echo "</div>";

			            foreach ( $channels as $channel ) {
                            echo " <div class='epme-social-img epme-social-img--$channel'></div>";
			            }
                    }
	            } else {
		            if ( !empty( $testers ) ) {
			            $c_testers = count( $testers );
			            echo sprintf( esc_html__( 'You have %1$s testers.', 'easyping.me' ), "<b>$c_testers</b>" );
		            } else {
			            echo $message_empty_testers;
			            echo $btn_refresh_tetsters;
		            }
                }
                ?></div>
            <button
                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored mdc-button <?php echo ( !empty( $testers ) ) ? 'epme-message-build__send-prev' : ''; ?>" <?php echo ( empty( $testers ) ) ? 'disabled' : ''; ?> data-content="<?php echo $type; ?>">
                <?php echo __( 'Send preview to testers', 'easyping.me' ); ?> <i class="material-icons mdc-button__icon mdc-button__icon--left">arrow_right</i>
            </button>
		<?php
		return ob_get_clean();
	}

	/**
	 * Return Message Block by type.
	 *
	 * @param  string $type
	 * @param  string $extra_type
	 * @param  integer $i
	 * @param  string $extra_class
	 * @return string
	 */
	public function message_block( $type, $extra_type, $i, $extra_class = '' ) {
		ob_start();
		?>
		<div class="epme-message-block epme-message-block--<?php echo $this->message_blocks[$type]['slug']; ?> <?php echo $extra_class; ?>">
			<div class="epme-message-block__drag-and-drop">
				<i class="material-icons">drag_handle</i>
			</div>
			<div class="epme-message-block__body">
				<div class="epme-message-block__box">
					<div class="mdl-card mdl-shadow--2dp">
						<div class="epme-message-block__header">
							<div class="epme-message-block__header-title">
								<i class="material-icons"><?php echo $this->message_blocks[$type]['icon']; ?></i>
								<span><?php echo $this->message_blocks[$type]['name']; ?></span>
							</div>
							<button id="epme-message-block-menu--<?php echo $this->message_blocks[$type]['slug'] . "-$extra_type-$i"; ?>"
							        class="epme-message-block__menu-button mdl-button mdl-js-button mdl-button--icon">
								<i class="material-icons">more_vert</i>
							</button>

							<ul class="epme-message-block__menu mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect"
							    for="epme-message-block-menu--<?php echo $this->message_blocks[$type]['slug'] . "-$extra_type-$i"; ?>">
								<li class="mdl-menu__item epme-message-block__delete"><?php echo __( 'Delete', 'easyping.me' ); ?></li>
							</ul>
						</div>
						<div class="mdl-card__actions mdl-card--border epme-message-block__content">
							<?php
							if ( is_array( $this->message_blocks[$type]['function_content'] ) ) {
								if ( method_exists( $this->message_blocks[$type]['function_content'][0], $this->message_blocks[$type]['function_content'][1] ) ) {
									echo call_user_func( $this->message_blocks[$type]['function_content'] );
								}
							} else {
								if ( function_exists( $this->message_blocks[$type]['function_content'] ) ) {
									echo call_user_func( $this->message_blocks[$type]['function_content'] );
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Content of Image/Photo block.
	 *
	 * @return string
	 */
	public function content_image_photo() {
		ob_start();
		?>
		<div class="epme-media">
			<div class="epme-media__upload-box">
				<div class="epme-media__images" data-src="">
					<div class="epme-media__single-image">
						<div class="epme-media__upload-image" data-empty="<?php echo $empty_src = EPME_ASSETS_URL . "/img/empty.png"; ?>" style="background-image: url(<?php echo $empty_src; ?>)"></div>
					</div>
				</div>
				<div class="epme-media__upload-buttons">
					<input type="hidden" class="epme-media__media-upload epme-required epme-block-input epme-block-input--change" id="<?php echo 0; ?>">
					<div class="epme__upload_image_button button">Upload image</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Content of Short Text block.
	 *
	 * @return string
	 */
	public function content_short_text() {
		ob_start();
		?>
		<div class="mdl-textfield mdl-js-textfield epme-flex">
			<textarea autocomplete="off" class="mdl-textfield__input epme-required epme-textarea-emoji epme-textfield__input empe-campaign-message__textarea epme-block-input epme-block-input--change" rows="4" id="epme-new-campaign__message"></textarea>
			<label class="mdl-textfield__label" for="epme-new-campaign__message"><?php echo __( 'Content of your message', 'easyping.me' ); ?></label>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Template of Source Message Blocks.
	 *
	 * @param  string $type
	 * @return string
	 */
	public function source_message_blocks( $type ) {
		ob_start();
		foreach ( $this->message_blocks as $block_id => $block ) {
			if ( !isset( $block['active'] ) OR !$block['active'] ) {
				continue;
			}
			?>
			<div class="epme-source-message-block epme-source-message-block--<?php echo $block['slug']; ?>">
				<div class="epme-source-message-block__icon">
					<i class="material-icons"><?php echo $block['icon']; ?></i>
				</div>
				<div class="epme-source-message-block__name"><?php echo $block['name']; ?></div>
				<div class="epme-source-message-block__action">
					<button
						class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored mdc-button epme-source-message-block__add epme-source-message-block__add--<?php echo $block['slug']; ?>" data-id="<?php echo $block['slug']; ?>" data-content-type="<?php echo $type; ?>">
						<?php echo __( 'Add', 'easyping.me' ); ?> <i class="material-icons mdc-button__icon mdc-button__icon--left">arrow_right_alt</i>
					</button>
					<div class="epme-source-message-block__template">
						<script type="html" id="epme-source-message-block__content">
						<?php echo $this->message_block( $block_id, $type, '%', 'epme-message-block--new' ); ?>
                        </script>
					</div>
				</div>
			</div>
			<?php
		}
		return ob_get_clean();
	}
}