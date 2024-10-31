<?php
/**
 * List of Testers.
 *
 * Functions used for manege list of testers.
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
 * EPME_Testers Class.
 */
class EPME_Testers {

	/**
	 * Variable for cache of List of testers.
	 *
	 * @var $subscribers
	 */
	protected $testers;

	/**
	 * Link into cabinet EP.
	 *
	 * @var $link
	 */
	protected $link;

	/**
	 * Testers Constructor.
	 */
	public function __construct() {
		$this->get();
	}

	/**
	 * Return testers.
	 *
	 * @return array
	 */
	public function get() {
		if ( empty( $this->testers ) OR !$this->testers ) {
			$response = EPME_Connects::get_testers();
			if ( $response['type'] !== 'error' ) {
				$this->testers = epme_fix_bool_in_arr( $response['respond'] );
			} else {
				return array();
			}
		}
		return $this->testers;
	}

	/**
	 * Return tester's link.
	 *
	 * @return string
	 */
	public function get_link() {
		return EPME_Account::get_link( 'test-account' );
	}

	/**
	 * Return Contacts by network type.
	 *
	 * @param  string $type
	 * @return array
	 */
	public function get_contacts_by_type( $type ) {
		$testers = array();
		if ( !empty( $this->testers ) ) {
			foreach ( $this->testers as $tester ) {
				if ( isset( $tester['network'] ) AND $tester['network'] == $type ) {
					$testers[] = $tester;
				}
			}
		}
		return $testers;
	}

	/**
	 * Return list of channels of testers.
	 *
	 * @return array
	 */
	public function get_channels() {
		$channels = array();
		if ( !empty( $this->testers ) ) {
			foreach ( $this->testers as $tester ) {
				if ( isset( $tester['network'] ) AND ! in_array( $tester['network'], $channels ) AND $tester['network'] ) {
					$channels[] = $tester['network'];
				}
			}
		}
		return $channels;
	}

	/**
	 * Return value of field.
	 *
	 * @param  integer $i
	 * @param  string $key
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_field( $i, $key, $def = '', $class = '' ) {
		if ( isset( $this->testers[$i][$key] ) AND $this->testers[$i][$key] ) {
			$data = $this->testers[$i][$key];
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
