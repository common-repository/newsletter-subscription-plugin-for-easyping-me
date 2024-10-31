<?php
/**
 * List of Subscribers.
 *
 * Functions used for manege list of subscribers.
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
 * EPME_Filter Class.
 */
class EPME_Filter {

	/**
	 * Variable for cache of List of subscribers.
	 *
	 * @var $subscribers
	 */
	protected $list;

	/**
	 * Subscribers Constructor.
	 */
	public function __construct( $id ) {
		$this->get( $id );
	}

	/**
	 * Return subscribers.
	 *
	 * @param  int $id
	 * @return array
	 */
	public function get( $id ) {
		if ( !$id ) {
			$this->list = array();
		}
		if ( empty( $this->list ) OR !$this->list ) {
			$response = EPME_Connects::get_subscribers();
			if ( $response['type'] !== 'error' ) {
				$this->list = epme_fix_bool_in_arr( $response['respond'] );
			} else {
				return array();
			}
		}
		return $this->list;
	}

	/**
	 * Return value of field.
	 *
	 * @param  string $key
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_field( $key, $def = '', $class = '' ) {
		if ( isset( $this->list[$key] ) AND $this->list[$key] ) {
			$data = $this->list[$key];
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
	 * Return filters for subscriptions list.
	 *
	 * @return array
	 */
	public static function get_filters() {
		$response = EPME_Connects::get_filters();
		if ( $response['type'] !== 'error' ) {
			$filters = epme_fix_bool_in_arr( $response['respond'] );
			$filters = $filters['data'];
		} else {
			return array();
		}
		return $filters;
	}
}
