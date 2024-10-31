<?php
/**
 * Output Class
 *
 * Auxiliary functions for display in the admin panel
 *
 * @author      easyping.me
 * @category    easyping\Admin
 * @package     easyping
 * @version     1.0.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'EPME_Admin_Data', false ) ) {
	return;
}

/**
 * EPME_Admin_Dashboard Class.
 */
class EPME_Admin_Data {

	/**
	 * Return array of countries.
	 *
	 * @return array
	 */
	public static function get_country() {
		$file_default = EPME_PLUGIN_DIR . "includes/data/iso3166.php";
		$file_locale = EPME_PLUGIN_DIR . "includes/data/iso3166-". get_locale() .".php";
		if ( file_exists( $file_locale ) ) {
			return include $file_locale;
		} else {
			return include $file_default;
		}
	}

	/**
	 * Return HTML select of countries.
	 *
	 * @param  string $select_name
	 * @param  string $id
	 * @param  string $title
	 * @param  string $class
	 * @param  string $value
	 * @return string
	 */
	public static function get_country_html( $select_name, $id, $title, $class = '', $value = '' ) {
		$countries = self::get_country();
		ob_start();
		?>
		<select name="<?php echo $select_name; ?>" class="<?php echo $class; ?>" id="<?php echo $id; ?>" title="<?php echo $title; ?>">
		<?php
		$selected = '';
		foreach ( $countries as $country ) {
			$alpha_2 = strtolower( $country['alpha_2'] );
			if ( $value AND $value == $alpha_2 ) {
				$selected = 'selected';
			}
			echo "<option value=\"{$alpha_2}\" $selected>{$country['name']}</option>";
			$selected = '';
		}
		?>
		</select>
		<?php
		return ob_get_clean();
	}

	/**
	 * Return country name by slug.
	 *
	 * @param  string $country_slug
	 * @return string
	 */
	public static function get_country_name_by_slug( $country_slug ) {
		$countries = self::get_country();
		foreach ( $countries as $country ) {
			if ( $country_slug AND $country_slug == strtolower( $country['alpha_2'] ) ) {
				return $country['name'];
			}
		}
		return '';
	}
}
