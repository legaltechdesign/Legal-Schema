<?php

class Schema_Markup_Insertion_Admin_Settings {
    
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

    }
    
    /**
	 * This function introduces the theme options into the 'Settings' menu and into a top-level
	 * 'Perfecto Portfolio' menu.
	 */
	public function setup_plugin_options_menu() {
        add_submenu_page(
            'options-general.php',
			'Schema Markup Insertion Settings', 			// The title to be displayed in the browser window for this page.
			'Schema Markup Insertion',					    // The text to be displayed for this menu item
            'manage_options',					            // Which type of users can see this menu item
            'schema_markup_insertion_options',			    // The unique ID - that is, the slug - for this menu item
			array( $this, 'render_settings_page_content')	// The name of the function to call when rendering this menu's page
		);
    }

    /**---------------------------------------------------------------------
     * Default Options
     ---------------------------------------------------------------------*/

    public function default_general_options() {
		$defaults = array(
            'schema_markup_on'	   =>	'',
		);
		return $defaults;
    }
    
    public function default_schema_markup_options() {
		$defaults = array(
            'schema_markup_homepage_onoff'	=>	'',
            'smi_jason_ld'	                =>	'',
			'schema_markup_agree_tested'    =>	'',
		);
		return $defaults;
	}


    /**---------------------------------------------------------------------
     * Settings fields for General Options
     ---------------------------------------------------------------------*/
    
	/**
	 * Initializes the theme's activated options
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_general_options(  ) {         

        if( false == get_option( 'smi_settings_general_options' ) ) {
			$default_array = $this->default_general_options();
			update_option( 'smi_settings_general_options', $default_array );
		} 

        /**
         * Add Section
         */
        add_settings_section(
            'smi_general_section', 
            __( 'General Settings', 'schema-markup-insertion' ), 
            array( $this, 'general_options_callback'),
            'smi_settings_general_options'
        );

        /**
         * Register Section
         */
        register_setting(
			'smi_settings_general_options',
			'smi_settings_general_options',
			array( $this, 'validate_general_options')
        );

    }

    /**
     * The Callback to assist with extra text
     */
    public function general_options_callback() {
		// echo '<p>' . esc_html__( '', 'schema-markup-insertion' ) . '</p>';
    }    

    /**
     * Validator Callback to assist in validation
     */
    public function validate_general_options( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {
				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
			} // end if
		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'validate_general_options', $output, $input );
    }    

    /**---------------------------------------------------------------------
     * Settings fields for Terms Modal
     ---------------------------------------------------------------------*/

    /**
	 * Initializes the theme's activated options
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
    public function initialize_schema_markup_home_options( ){

        // delete_option('smi_settings_json_ld_home_options');

        if( false == get_option( 'smi_settings_json_ld_home_options' ) ) {
			$default_array = $this->default_schema_markup_options();
			update_option( 'smi_settings_json_ld_home_options', $default_array );
        } 
        
        /**
         * Add Section
         */
        add_settings_section(
            'smi_json_ld_home_section', 
            __( 'Home Page Settings', 'schema-markup-insertion' ), 
            array( $this, 'json_ld_options_callback'),
            'smi_settings_json_ld_home_options'
        );
        
        /**
         * Add option to Section
         */    

        add_settings_field( 
            'schema_markup_homepage_onoff', 
            __( 'Schema Markup Insertion for Home Page', 'terms-popup-on-user-login' ), 
            array( $this, 'schema_markup_select_onoff_callback'),
            'smi_settings_json_ld_home_options', 
            'smi_json_ld_home_section' 
        );  

        add_settings_field(
            'smi_jason_ld', // id
            __( 'JSON-LD', 'schema-markup-insertion' ), 
			array( $this, 'schema_markup_smi_jason_ld_callback' ), // callback
			'smi_settings_json_ld_home_options', // page
			'smi_json_ld_home_section' // section
        );
        
        add_settings_field( 
            'schema_markup_agree_tested',
            __( 'Confirm that JSON-LD markup was tested', 'schema-markup-insertion' ), 
            array( $this, 'schema_markup_accept_checkbox_render'),
            'smi_settings_json_ld_home_options', 
            'smi_json_ld_home_section' 
        );

        /**
         * Register Section
         */
        register_setting(
			'smi_settings_json_ld_home_options',
			'smi_settings_json_ld_home_options',
			array( $this, 'validate_json_ld_options')
        );
    }

        /**
     * The Callback to assist with extra text
     */
    public function json_ld_options_callback() {
		echo '<p>' . __( 'Replace Yoast JSON-LD on the home page.', 'schema-markup-insertion' ) . '</p>';
    }

    /**
     * Validator Callback to assist in validation
     */
    public function validate_json_ld_options( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach( $input as $key => $value ) {
			// Check to see if the current option has a value. If so, process it.
			if( isset( $input[$key] ) ) {
				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
			} // end if
		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'validate_json_ld_options', $output, $input );
    }    


    /**---------------------------------------------------------------------
     * Settings fields
     ---------------------------------------------------------------------*/

    /**
	 * Initializes the theme's activated options
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
    public function initialize_reset_users_options( ){
         /**
         * Add Section
         */
        add_settings_section(
            'smi_reset_users_section', 
            __( '', 'schema-markup-insertion' ), 
            array( $this, 'reset_users_options_callback'),
            'smi_settings_reset_users_options'
        );
    }

        /**
     * The Callback to assist with extra text
     */
    public function reset_users_options_callback() {
		echo '';
    }

    /**---------------------------------------------------------------------
     * Render the actual page
     ---------------------------------------------------------------------*/
    
    /**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content( $active_tab = '' ) {

        if (current_user_can('administrator') ) {

            ?>
            <!-- Create a header in the default WordPress 'wrap' container -->
            <div class="wrap">

                <h2><?php esc_html_e( 'Schema Markup Insertion Options', 'schema_markup_insertion' ); ?></h2>

                <?php settings_errors(); ?>

                <?php if( isset( $_GET[ 'tab' ] ) ) {
                    $active_tab = $_GET[ 'tab' ];
                } else if( $active_tab == 'schema_markup_options' ) {
                    $active_tab = 'schema_markup_options';
                } else if( $active_tab == 'reset_users_options' ) {
                    $active_tab = 'reset_users_options';
                } else {
                    // $active_tab = 'general_options';
                    $active_tab = 'schema_markup_options';
                } 

                ?>

                <h2 class="nav-tab-wrapper">
                    <!-- <a href="?page=schema_markup_insertion_options&tab=general_options" class="nav-tab <?php // echo $active_tab == 'general_options' ? 'nav-tab-active' : ''; ?>"><?php // esc_html_e( 'General', 'schema-markup-insertion' ); ?></a> -->
                    <!-- <a href="?page=schema_markup_insertion_options&tab=schema_markup_options" class="nav-tab <?php // echo $active_tab == 'schema_markup_options' ? 'nav-tab-active' : ''; ?>"><?php // esc_html_e( 'Home Page Settings', 'schema-markup-insertion' ); ?></a> -->

                    <a href="?page=schema_markup_insertion_options&tab=schema_markup_options" class="nav-tab nav-tab-active"><?php esc_html_e( 'Schema Markup Insertion', 'schema-markup-insertion' ); ?></a>

                </h2>
                
                <form method="post" action="options.php">
                    <?php

                    if( true && $active_tab == 'schema_markup_options' ) {
                        ?>

                            <div class="tg-outer">						
                                <?php
                                    settings_fields( 'smi_settings_json_ld_home_options' );
                                    do_settings_sections( 'smi_settings_json_ld_home_options' );                            								
                                ?>
                                <div class="schema_markup_insertion_submit_container">
                                    <?php submit_button(); ?>
                                </div>
                            </div>

                        <?php
                    } else {

                        settings_fields( 'smi_settings_general_options' );
                        // do_settings_sections( 'smi_settings_general_options' );
                        // submit_button();
                    } 
                    ?>
                    </form>
                
                </div><!-- /.wrap -->
                <p>Plugin created by Urban Insight on behalf of Legal Services Corporation TIG 18011. Email: <a href="mailto:info@urbaninsight.com">info@urbaninsight.com</a>.</p>
            <?php

        }else{
            esc_html_e( 'You need to have Administrator privileges to see this page.', 'schema_markup_insertion' );
        }
    }

    /**---------------------------------------------------------------------
     * Helper functions to generate the fields for the forms
     ---------------------------------------------------------------------*/

     public function schema_markup_smi_jason_ld_callback() {

        $options = get_option( 'smi_settings_json_ld_home_options' );
		printf(
			'<textarea id="schema_markup_smi_jason_ld" class="large-text" rows="15" name="smi_settings_json_ld_home_options[smi_jason_ld]" id="smi_jason_ld">%s</textarea>',
			isset( $options['smi_jason_ld'] ) ? esc_attr( $options['smi_jason_ld']) : ''
        );
        $html = "<p class='description'>Only add the JSON format <b>DO NOT</b> include the script wrapper! <em>" . esc_html__('<script type="application/ld+json"> ... </script>', 'schema-markup-insertion' ) . '<em>.</p>';

        $html .= "<p class='json_alert hidden' id='invalid_json'>Invalid JSON, verify your JSON at - https://jsonlint.com/ - to debug.</p>";
        $html .= "<p class='json_alert hidden' id='valid_json'>Valid JSON</p>";

        echo $html;

    }
    
    function schema_markup_accept_checkbox_render() { 


        $options = get_option( 'smi_settings_json_ld_home_options' );
        if( !isset( $options['schema_markup_agree_tested'] ) ) $options['schema_markup_agree_tested'] = 0;

        $html = '<input type="checkbox" id="schema_markup_agree_tested" name="smi_settings_json_ld_home_options[schema_markup_agree_tested]" value="1"' . checked( 1, $options['schema_markup_agree_tested'], false ) . '/>';
        $html .= '<label for="schema_markup_agree_tested">' . __( 'I have tested the script I am about to save using Google Structured Data Testing Tool and verified that it is correct.', 'schema-markup-insertion' )  . '</label>';
        $html .= '<br/>';
        $html .= '<br/>';
        $html .= '<p class="description"><a href="https://search.google.com/structured-data/testing-tool">https://search.google.com/structured-data/testing-tool</a></p>';
    
        echo $html;
    }

    public function schema_markup_select_onoff_callback() {

        $options = get_option( 'smi_settings_json_ld_home_options' );
        if( !isset( $options['schema_markup_homepage_onoff'] ) ) $options['schema_markup_homepage_onoff'] = '';

		$html = '<select id="schema_markup_homepage_onoff" name="smi_settings_json_ld_home_options[schema_markup_homepage_onoff]">';
		$html .= '<option value="">' . esc_html__( 'OFF', 'perfecto-portfolio' ) . '</option>';
		$html .= '<option value="1"' . selected( $options['schema_markup_homepage_onoff'], '1', false) . '>' . esc_html__( 'ON', 'perfecto-portfolio' ) . '</option>';
		$html .= '</select>';

		echo $html;

	}

}