<?php
/**
 * Campaign.
 *
 * Functions used for manege campaign.
 *
 * @author      easyping.me
 * @category    Admin
 * @package     easyping/Admin/Campaign
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * EPME_Campaign Class.
 */
class EPME_Campaign {

	/**
	 * Data of campaign.
	 *
	 * @var $campaign
	 */
	protected $campaign;

	/**
	 * Data of Message Block.
	 *
	 * @var $masterMessageBlock
	 */
	protected $masterMessageBlock;

	/**
	 * Data of Blocks of channels.
	 *
	 * @var $messageBlocks
	 */
	protected $messageBlocks;

	/**
	 * Campaign Constructor.
	 *
	 * @param  integer $id
	 */
	public function __construct( $id ) {
		if ( !$id ) {
			$this->campaign = array();
		} else {
			$server_answer = EPME_Connects::get_campaign( $id );
			if ( isset( $server_answer['type'] ) AND $server_answer['type'] == 'ok' AND isset( $server_answer['respond'] ) ) {
				if ( isset( $server_answer['respond']->campaign ) ) {
					$this->campaign = epme_fix_bool_in_arr( $server_answer['respond']->campaign );
				}
				if ( isset( $server_answer['respond']->masterMessageBlock ) ) {
					$this->masterMessageBlock = epme_fix_bool_in_arr( $server_answer['respond']->masterMessageBlock );
				}
				if ( isset( $server_answer['respond']->messageBlocks ) ) {
					$this->messageBlocks = epme_fix_bool_in_arr( $server_answer['respond']->messageBlocks );
				}
			} else {
				$this->campaign = array();
			}
		}
	}

	/**
	 * Return Campaign id.
	 *
	 * @param  string $def
	 * @param  string $class
	 * @return integer
	 */
	public function get_id( $def = '', $class = '' ) {
		return $this->get_field( 'id', $def, $class );
	}

	/**
	 * Return value of field.
	 *
	 * @param  string $key
	 * @param  string $def
	 * @param  string $class
	 * @return mixed
	 */
	public function get_field( $key, $def = '', $class = '' ) {
		if ( isset( $this->campaign[$key] ) AND $this->campaign[$key] ) {
			$data = $this->campaign[$key];
		} else {
			$data = $def;
		}
		if ( $data OR $def === 0 ) {
			if ( $class ) {
				return "<div class='$class'>$data</div>";
			} else {
				return $data;
			}
		}
		return '';
	}

	/**
	 * Return value of master block.
	 *
	 * @param  mixed $def
	 * @return array
	 */
	public function get_master_block( $def = '' ) {
		if ( isset( $this->masterMessageBlock ) AND $this->masterMessageBlock ) {
			$data = $this->masterMessageBlock;
		} else {
			$data = $def;
		}
		if ( $data ) {
            return $data;
		}
		return array();
	}

	/**
	 * Return value of message blocks.
	 *
	 * @param  mixed $def
	 * @return array
	 */
	public function get_message_blocks( $def = '' ) {
		if ( isset( $this->messageBlocks ) AND $this->messageBlocks ) {
			$data = $this->messageBlocks;
		} else {
			$data = $def;
		}
		if ( $data ) {
            return $data;
		}
		return array();
	}

	/**
	 * Return value of field.
	 *
	 * @return string
	 */
	public function get_blocks_json() {
		ob_start();
		?>
		<script type="application/json" id="epme-master-blocks"><?php echo json_encode( $this->get_master_block() ); ?></script>
		<script type="application/json" id="epme-fine-tune-blocks"><?php echo json_encode( $this->get_message_blocks() ); ?></script>
<!--		<script type="application/json" id="epme-master-blocks">[{"blockType":"text","content":"one!","order":1,"extra_content":""}]</script>-->
<!--		<script type="application/json" id="epme-master-blocks">[{"blockType":"text","content":"","order":1,"extra_content":""},{"blockType":"short-text","content":"2","order":2,"extra_content":""},{"blockType":"short-text","content":"3","order":3,"_old_content":"","extra_content":""},{"blockType":"short-text","content":"4","order":4,"_old_content":"","extra_content":""},{"blockType":"short-text","content":"5","order":5,"_old_content":"","extra_content":""}]</script>-->
<!--		<script type="application/json" id="epme-fine-tune-blocks">[{"socialNetworkType":"fb","blockType":"image","content":"http://pm.ld/wp-content/uploads/2019/04/2_spalnya_s_garderobom.png","order":1},{"socialNetworkType":"fb","blockType":"image-photo","content":"","order":2},{"socialNetworkType":"fb","blockType":"text","content":"3","order":3},{"socialNetworkType":"vk","blockType":"short-text","content":"1","order":1}]</script>-->
		<?php
		return ob_get_clean();
	}
}