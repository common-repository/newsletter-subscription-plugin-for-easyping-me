<?php
/**
 * Subscribers.
 *
 * Functions used for manege subscribers.
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
 * EPME_Subscribers Class.
 */
class EPME_Subscribers {

	/**
	 * Variable for cache of List of subscribers.
	 *
	 * @var $subscribers
	 */
	protected $subscribers;

	/**
	 * Subscribers Constructor.
	 */
	public function __construct() {
		$this->get();
	}

	/**
	 * Return subscribers.
	 *
	 * @return array
	 */
	public function get() {
		if ( empty( $this->subscribers ) OR !$this->subscribers ) {
			$response = EPME_Connects::get_subscribers();
			if ( $response['type'] !== 'error' ) {
				$this->subscribers = epme_fix_bool_in_arr( $response['respond'] );
			} else {
				return array();
			}
		}

		return $this->subscribers;
	}

	/**
	 * Return name.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_name( $subscriber, $def = '', $class = '' ) {
		if ( isset( $subscriber['name'] ) AND $subscriber['name'] ) {
			$data = $subscriber['name'];
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
	 * Return date.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_date( $subscriber, $def = '', $class = '' ) {
		if ( isset( $subscriber['date'] ) AND $subscriber['date'] ) {
			$data = $subscriber['date'];
		} else {
			$data = $def;
		}
		if ( $data ) {
			$data = gmdate( "m/d/Y G:i:s T", strtotime( $data ) );
			if ( $class ) {
				return "<div class='$class'>$data</div>";
			} else {
				return $data;
			}
		}
		return '';
	}

	/**
	 * Return social type.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_social_type( $subscriber, $def = '', $class = '' ) {
		if ( isset( $subscriber['socialType'] ) AND $subscriber['socialType'] ) {
			$data = $subscriber['socialType'];
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
	 * Return ref url.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_ref_url( $subscriber, $def = '', $class = '' ) {
		if ( !empty( $subscriber['info'] ) AND !empty( $subscriber['info']['source'] ) AND isset( $subscriber['info']['source']['refUrl'] ) AND $subscriber['info']['source']['refUrl'] ) {
			$data = $subscriber['info']['source']['refUrl'];
		} else {
			$data = $def;
		}
		if ( $data ) {
			if ( $class ) {
				return "<div class='$class'>". __( 'Referral URL:', 'easyping.me' ) ." $data</div>";
			} else {
				return $data;
			}
		}
		return '';
	}

	/**
	 * Return url.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_url( $subscriber, $def = '', $class = '' ) {
		if ( !empty( $subscriber['info'] ) AND !empty( $subscriber['info']['source'] ) AND isset( $subscriber['info']['source']['url'] ) AND $subscriber['info']['source']['url'] ) {
			$data = $subscriber['info']['source']['url'];
		} else {
			$data = $def;
		}
		if ( $data ) {
			if ( $class ) {
				return "<div class='$class'>". __( 'URL:', 'easyping.me' ) ." $data</div>";
			} else {
				return $data;
			}
		}
		return '';
	}

	/**
	 * Return lang.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_lang( $subscriber, $def = '', $class = '' ) {
		if ( !empty( $subscriber['info'] ) AND !empty( $subscriber['info']['source'] ) AND isset( $subscriber['info']['source']['lang'] ) AND $subscriber['info']['source']['lang'] ) {
			$data = $subscriber['info']['source']['lang'];
		} else {
			$data = $def;
		}
		if ( $data ) {
			if ( $class ) {
				return "<div class='$class'>". __( 'Browser language:', 'easyping.me' ) ." $data</div>";
			} else {
				return $data;
			}
		}
		return '';
	}

	/**
	 * Return city.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_city( $subscriber, $def = '', $class = '' ) {
		if ( !empty( $subscriber['info'] ) AND !empty( $subscriber['info']['geolocation'] ) AND isset( $subscriber['info']['geolocation']['city'] ) AND $subscriber['info']['geolocation']['city'] ) {
			$data = $subscriber['info']['geolocation']['city'];
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
	 * Return country.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_country( $subscriber, $def = '', $class = '' ) {
		if ( !empty( $subscriber['info'] ) AND !empty( $subscriber['info']['geolocation'] ) AND isset( $subscriber['info']['geolocation']['country'] ) AND $subscriber['info']['geolocation']['country'] ) {
			$data = $subscriber['info']['geolocation']['country'];
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
	 * Return continent.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_continent( $subscriber, $def = '', $class = '' ) {
		if ( !empty( $subscriber['info'] ) AND !empty( $subscriber['info']['geolocation'] ) AND isset( $subscriber['info']['geolocation']['continent'] ) AND $subscriber['info']['geolocation']['continent'] ) {
			$data = $subscriber['info']['geolocation']['continent'];
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
	 * Return device.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_device( $subscriber, $def = '', $class = '' ) {
		if ( !empty( $subscriber['info'] ) AND !empty( $subscriber['info']['technology'] ) AND isset( $subscriber['info']['technology']['device'] ) AND $subscriber['info']['technology']['device'] ) {
			$data = $subscriber['info']['technology']['device'];
		} else {
			$data = $def;
		}
		if ( $data ) {
			if ( $class ) {
				return "<div class='$class'>". __( 'Device:', 'easyping.me' ) ." $data</div>";
			} else {
				return $data;
			}
		}
		return '';
	}

	/**
	 * Return os.
	 *
	 * @param  array $subscriber
	 * @param  string $def
	 * @param  string $class
	 * @return string
	 */
	public function get_os( $subscriber, $def = '', $class = '' ) {
		if ( !empty( $subscriber['info'] ) AND !empty( $subscriber['info']['technology'] ) AND isset( $subscriber['info']['technology']['os'] ) AND $subscriber['info']['technology']['os'] ) {
			$data = $subscriber['info']['technology']['os'];
		} else {
			$data = $def;
		}
		if ( $data ) {
			if ( $class ) {
				return "<div class='$class'>". __( 'OS:', 'easyping.me' ) ." $data</div>";
			} else {
				return $data;
			}
		}
		return '';
	}
}
