<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.urbaninsight.com
 * @since      1.0.0
 *
 * @package    Schema_Markup_Insertion
 * @subpackage Schema_Markup_Insertion/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Schema_Markup_Insertion
 * @subpackage Schema_Markup_Insertion/includes
 * @author     Lehel Matyus, Urban Insight <info@urbaninsight.com>
 */
class Schema_Markup_Insertion_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'schema-markup-insertion',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
