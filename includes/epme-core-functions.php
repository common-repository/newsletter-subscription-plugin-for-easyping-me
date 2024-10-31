<?php
/**
 * easyping.me Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package easyping\Functions
 * @version 1.0.6
 */

/**
 * Define a constant if it is not already defined.
 *
 * @param string $name  Constant name.
 * @param mixed  $value Value.
 */
function epme_maybe_define_constant( $name, $value ) {
	if ( ! defined( $name ) ) {
		define( $name, $value );
	}
}

/**
 * Return OK array for respond
 *
 * @return array
 */
function epme_ok() {
	return array(
		'status' => array(
			'type' => 'ok',
			'cause' => '',
		)
	);
}

/**
 * Return ERROR array for respond.
 *
 * @param  string $cause.
 * @param  mixed $code.
 * @return array
 */
function epme_error( $cause, $code = '' ) {
	return array(
		'status' => array(
			'type' => 'error',
			'code' => $code,
			'cause' => $cause,
		)
	);
}

/**
 * Fix bool value in the array.
 *
 * @param  array $data.
 * @return mixed
 */
function epme_fix_bool_in_arr( $data ) {
	$res = array();
	if ( is_array( $data ) OR is_object( $data ) ) {
		foreach ( $data as $key => $datum ) {
			$res[$key] = epme_fix_bool_in_arr( $datum );
		}
	} else {
		switch ( true ) {
			case ( $data === true OR $data === 'true' ):
				return true;
			case ( $data === false OR $data === 'false' ):
				return false;
			case ( $data === null OR $data === 'NULL' ):
				return false;
			case ( preg_match( '/(^\d*$)/i', $data, $matches ) ):
				return intval( $data );
			case ( is_string( $data ) ):
				return stripslashes( $data );
			default:
				return $data;
		}
	}
	return $res;
}

/**
 * Return date.
 *
 * @param  array $data
 * @param  string $def
 * @param  string $class
 * @param  string $key
 * @param  string $format
 * @return string
 */
function epme_get_date( $data, $def = '', $class = '', $key = 'date', $format = 'm/d/Y G:i:s T' ) {
	if ( is_array( $data ) ) {
		if ( isset( $data[$key] ) AND $data[$key] ) {
			$data = $data[$key];
		} else {
			if ( !$def ) {
				return '—';
			}
			$data = $def;
		}
	} else {
		if ( !$data ) {
			if ( !$def ) {
				return '—';
			}
			$data = $def;
		}
	}

	if ( $data ) {
		$data = gmdate( $format, strtotime( $data ) );
		if ( $class ) {
			return "<div class='$class'>$data</div>";
		} else {
			return $data;
		}
	}
	return '';
}

/**
 * Return value of field.
 *
 * @param  array $data
 * @param  string $key
 * @param  string $def
 * @param  string $class
 * @return string
 */
function epme_get_field( $data, $key, $def = '', $class = '' ) {
	if ( isset( $data[$key] ) AND $data[$key] ) {
		$data = $data[$key];
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