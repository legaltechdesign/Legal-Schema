<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.urbaninsight.com
 * @since      1.0.0
 *
 * @package    Schema_Markup_Insertion
 * @subpackage Schema_Markup_Insertion/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Schema_Markup_Insertion
 * @subpackage Schema_Markup_Insertion/includes
 * @author     Lehel Matyus, Urban Insight <info@urbaninsight.com>
 */
class Schema_Markup_Insertion {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Schema_Markup_Insertion_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		if ( defined( 'SCHEMA_MARKUP_INSERTION_VERSION' ) ) {
			$this->version = SCHEMA_MARKUP_INSERTION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'schema-markup-insertion';


		$get_home_options = get_option( 'smi_settings_json_ld_home_options' );
		if( false ==  $get_home_options) {
			$this->generals_options = $this->default_smi_settings_json_ld_home_options();
		}else{
			$this->generals_options = $get_home_options;
		}

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}


	/**
	 * Provides default values Settings
	 *
	 * @return array
	 */
	public function default_smi_settings_json_ld_home_options() {
		$defaults = array(
			'schema_markup_homepage_onoff'	=>	'',
            'smi_jason_ld'	                =>	'',
			'schema_markup_agree_tested'    =>	'',
		);
		return $defaults;
	}


	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Schema_Markup_Insertion_Loader. Orchestrates the hooks of the plugin.
	 * - Schema_Markup_Insertion_i18n. Defines internationalization functionality.
	 * - Schema_Markup_Insertion_Admin. Defines all hooks for the admin area.
	 * - Schema_Markup_Insertion_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-schema-markup-insertion-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-schema-markup-insertion-i18n.php';
		
		/**
		 * The class responsible for plugin settings
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-schema-markup-insertion-plugin-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-schema-markup-insertion-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-schema-markup-insertion-ld-inserter.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-schema-markup-insertion-public.php';

		$this->loader = new Schema_Markup_Insertion_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Schema_Markup_Insertion_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Schema_Markup_Insertion_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Schema_Markup_Insertion_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$plugin_settings = new Schema_Markup_Insertion_Admin_Settings( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_menu', $plugin_settings, 'setup_plugin_options_menu' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_general_options' );
		$this->loader->add_action( 'admin_init', $plugin_settings, 'initialize_schema_markup_home_options' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Schema_Markup_Insertion_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$plugin_public_inserter = new Schema_Markup_Insertion_LD_Inserter( $this->get_plugin_name(), $this->get_version() );


		/**
		 * Remove Yoast if Turned ON
		 * Add CUSTOM if Turned ON
		 */

		if (
			(!empty($this->generals_options['schema_markup_homepage_onoff']))
			&&
			(!empty($this->generals_options['schema_markup_agree_tested']))
			)
		{
			// Turn OFF Yoast
			$this->loader->add_filter( 'wpseo_json_ld_output', $plugin_public_inserter, 'homepage_unhook_yoast' );

			// Turn ON Custom
			$this->loader->add_action( 'wp_head', $plugin_public_inserter, 'homepage_add_jsonld_head' );
		}

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Schema_Markup_Insertion_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
