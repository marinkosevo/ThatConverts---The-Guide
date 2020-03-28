<?php

/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/marinkosevo/
 * @since             1.0.0
 * @package           Thatconverts_theguide
 *
 * @wordpress-plugin
 * Plugin Name:       ThatConverts - The Guide
 * Plugin URI:        https://github.com/marinkosevo/ThatConverts---The-Guide
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Marinko Sevo
 * Author URI:        https://github.com/marinkosevo/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       thatconverts_theguide
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'THATCONVERTS_THEGUIDE_VERSION', '1.0.0' );

//Including All Relevant Files
include('admin/pages.php');



//End of including files
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/activator.php
 */
function activate_thatconverts_theguide() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/activator.php';
	activate();

}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/deactivator.php
 */
function deactivate_thatconverts_theguide() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/deactivator.php';
	deactivate();
}
register_activation_hook( __FILE__, 'activate_thatconverts_theguide' );
register_deactivation_hook( __FILE__, 'deactivate_thatconverts_theguide' );

