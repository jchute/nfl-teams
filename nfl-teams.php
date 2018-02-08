<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://jereross.com
 * @since             1.0.0
 * @package           Nfl_Teams
 *
 * @wordpress-plugin
 * Plugin Name:       NFL Teams
 * Plugin URI:        http://www.example.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jeremy Ross
 * Author URI:        http://jereross.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nfl-teams
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
define( 'NFL_TEAMS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nfl-teams-activator.php
 */
function activate_nfl_teams() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nfl-teams-activator.php';
	Nfl_Teams_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nfl-teams-deactivator.php
 */
function deactivate_nfl_teams() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nfl-teams-deactivator.php';
	Nfl_Teams_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nfl_teams' );
register_deactivation_hook( __FILE__, 'deactivate_nfl_teams' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nfl-teams.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nfl_teams() {

	$plugin = new Nfl_Teams();
	$plugin->run();

}
run_nfl_teams();
