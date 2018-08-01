<?php
/**
 * Plugin Name:     Staff
 * Plugin URI:      https://github.com/INN/staff
 * Description:     Widget to list site staff, and associated helper functions
 * Author:          INN Labs
 * Author URI:      https://labs.inn.org
 * Text Domain:     inn-staff
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Staff
 */

namespace INN\Staff;

$includes = array(
	'/includes/class-largo-staff-widget.php',
	'/includes/roster-shortcode.php',
	'/includes/users.php',
);

foreach ( $includes as $include ) {
	if ( 0 === validate_file( dirname( __FILE__ ) . $include ) ) {
		require_once( dirname( __FILE__ ) . $include );
	}
}
