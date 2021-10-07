<?php

class Schema_Markup_Insertion_LD_Inserter {
    
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $generals_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$get_home_options = get_option( 'smi_settings_json_ld_home_options' );
		if( false ==  $get_home_options) {
			$this->generals_options = $this->default_smi_settings_json_ld_home_options();
		}else{
			$this->generals_options = $get_home_options;
		}

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

	
	public function homepage_unhook_yoast() {
		/**
		 * Remove Yoast if HOMEPAGE
		 */
		if ( is_home() || is_front_page() ) {
			$data = array();
			return $data;
		}
	}

	public function homepage_add_jsonld_head() {
		
		if ( is_home() || is_front_page() ) {
			if (!empty($this->generals_options['smi_jason_ld'])){
				echo '<script type="application/ld+json">';
				echo $this->generals_options['smi_jason_ld'];
				echo '</script>';
			}
		}

	}

	public function remove_footer_admin () {
		return '<p>Plugin created by Urban Insight on behalf of Legal Services Corporation TIG 18011. Email: <a href="mailto:info@urbaninsight.com">info@urbaninsight.com</a>.</p>';
	}

}