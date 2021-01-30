<?php
/**
 * Visitor Login Notice
 *
 * Displays a notice to non-logged in users reminding them to login/register to your website.
 *
 * @since             1.0.0
 * @package           Visitor_Login_Notice
 *
 * @wordpress-plugin
 * Plugin Name:       Visitor Login Notice
 * Description:       Display a notice to non-logged in users reminding them to login/register to your website.
 * Version:           1.0.0
 * Author:            Maria Akritidou <maria@2squared.io>
 * Author URI:        https://github.com/mariaak
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       login-notice
 * Domain Path:       /languages
 */

/**
 * Main file, contains the plugin metadata and activation processes
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

if ( ! defined( 'TS_VLN_VERSION' ) ) {
	/**
	 * The version of the plugin.
	 */
	define( 'TS_VLN_VERSION', '1.0.0' );
}

if ( ! defined( 'TS_VLN_PATH' ) ) {
	/**
	 *  The server file system path to the plugin directory.
	 */
	define( 'TS_VLN_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'TS_VLN_URL' ) ) {
	/**
	 * The url to the plugin directory.
	 */
	define( 'TS_VLN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'TS_VLN_BASE_NAME' ) ) {
	/**
	 * The base name of the plugin.
	 */
	define( 'TS_VLN_BASE_NAME', plugin_basename( dirname ( __FILE__ ) ) );
}

/**
 * Include files.
 */
function vln_include_plugin_files() {

	// Include application files
	$files = array(
        'app/class-login-notice-options',
	    'app/class-login-notice',
        'app/admin/class-login-notice-admin'
	);

	// Include libraries
	$includes = array(
        'includes/helpers'
	);

	// Merge arrays
	$files = array_merge( $files, $includes );

	foreach ( $files as $file ) {
		require TS_VLN_PATH . $file . '.php';
	}
}

add_action( 'plugins_loaded', 'vln_include_plugin_files' );
