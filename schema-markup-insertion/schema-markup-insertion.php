<?php

/**
 * The plugin
 *
 *
 * @link              www.urbaninsight.com
 * @since             1.0.0
 * @package           Schema_Markup_Insertion
 *
 * @wordpress-plugin
 * Plugin Name:       Legal Aid Schema.org Markup Insertion PlugIn
 * Plugin URI:        www.urbaninsight.com
 * Description:       This plugin allows the insertion of administrator-provided schema.org markup on the home page of a WordPress website with the option of replacing any default Yoast schema.org markup that may be inserted by Yoast. This plugin was funded by the Legal Services Corporation as part of TIG 18011 to help with the standardization and evaluation of schema.org markup for legal aid organizations.
 * Version:           1.0.0
 * Author:            Lehel Matyus, Urban Insight
 * Author URI:        www.urbaninsight.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       schema-markup-insertion
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
define( 'SCHEMA_MARKUP_INSERTION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-schema-markup-insertion-activator.php
 */
function activate_schema_markup_insertion() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-schema-markup-insertion-activator.php';
	Schema_Markup_Insertion_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-schema-markup-insertion-deactivator.php
 */
function deactivate_schema_markup_insertion() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-schema-markup-insertion-deactivator.php';
	Schema_Markup_Insertion_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_schema_markup_insertion' );
register_deactivation_hook( __FILE__, 'deactivate_schema_markup_insertion' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-schema-markup-insertion.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_schema_markup_insertion() {

	$plugin = new Schema_Markup_Insertion();
	$plugin->run();

}
run_schema_markup_insertion();
