<?php

/**
 * Plugin Name: Bookgrail
 * Plugin URI: 
 * Description: Add Bookgrail buy buttons to your site
 * Version: 1.0.0
 * Author: Bookgrail Ltd
 * Author URI: http://www.bookgrail.com
 * Requires at least: 3.5
 * Tested up to: 4.5.2
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Bookgrail' ) ) {

/**
 * Main Bookgrail Class
 *
 * Contains the main functions for Bookgrail, stores variables, and handles error messages
 *
 */
class Bookgrail {

    /**
     * @var string
     */
    public $version = '1.0.0';
    /**
     * @var string
     */
    public $plugin_url;

    /**
     * @var string
     */
    public $plugin_path;
    
    protected static $options_default = array(
        "referrer_id" => "",
        "theme_color" => "#5f6f7e"
    );
    
    public $options;

    /**
     * Bookgrail Constructor.
     *
     * @access public
     * @return void
     */
    public function __construct() {

        // Define version constant
        define( 'BOOKGRAIL_VERSION', $this->version );

        $this->options = Bookgrail::get_options();
        
        // Installation
        register_activation_hook( __FILE__, array( $this, 'activate' ) );

        // Updates
        add_action( 'admin_init', array( $this, 'update' ), 5 );
        
        // Admin Page & Menu
        add_action('admin_init', array($this, 'admin_page_init'));
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
         
        // Include required files
        $this->includes();

        add_action( 'init', array( $this, 'init' ), 0 );

        // Loaded action
        do_action( 'bookgrail_loaded' );
    }
 
    static function get_options()
    {
        return array_merge(self::$options_default, get_option('bg_settings', array()));
    }
    
    
    /**
     * Include required core files used in admin and on the frontend.
     *
     * @access public
     * @return void
     */
    function includes() {

        if ( is_admin() )
               $this->admin_includes();
        if ( ! is_admin() || defined('DOING_AJAX') )
                $this->frontend_includes();

    }

    /**
     * Include required frontend files.
     *
     * @access public
     * @return void
     */
    public function admin_includes() {
    }
    
    /**
     * Include required frontend files.
     *
     * @access public
     * @return void
     */
    public function frontend_includes() {
        
        // Classes
        include_once( 'classes/class-bookgrail-shortcodes.php' );			// Shortcodes class
        
    }
    
    /**
     * setup the admin menu
     *
     * @access public
     * @return void
     */
    public function admin_menu() {
        add_options_page( 'Bookgrail Settings', 'Bookgrail', 'manage_options', 'bookgrail-settings', array($this, 'admin_page') );
    }
    
    public function admin_page() {

        if ( !current_user_can( 'manage_options' ) )  {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }
        ?>

        <div class="wrap">
            <form method="post" action="options.php">
            <?php
            settings_fields('bg_settings_group');
            do_settings_sections('bg-options');
            submit_button();
            ?>
            </form>
            <div>
                <h3>Shortcodes</h3>
                <p>Display any Bookgrail price and buy button using this plugin's Shortcodes</p>
                
                <h4>bg_price</h4>
                <p>Returns a book's price identified by its ISBN</p>
                <p>E.g. [bg_price isbn="9781781852514"]</p>
                
                <h4>bg_buy_button</h4>
                <p>Displays a button which when clicked adds a book to the Bookgrail shopping cart and displays the cart as a popup on screen.</p>
                <p>E.g. [bg_buy_button isbn="9781781852514" classes="btn blue"]</p>
                <p>The <strong>classes</strong> shortcode parameter value is added to the class value of the button control</p>
            </div>
        </div>                

        <?php
    }

    public function admin_page_init()
    {
        register_setting('bg_settings_group', 'bg_settings', array($this, 'sanitize'));
        add_settings_section('general', 'Bookgrail Settings', null, 'bg-options');
        add_settings_field('referrer_id', 'Referrer Key', array($this, 'render_referrer_field'), 'bg-options', 'general');
        add_settings_field('theme_color', 'Theme Color', array($this, 'render_theme_color_field'), 'bg-options', 'general');
    }

    public function sanitize($input)
    {
        return $input;
    }
    
    public function render_referrer_field()
    {
        ?>
        <input type="text" id="referrer_id" name="bg_settings[referrer_id]" class="regular-text" value="<?php echo $this->options['referrer_id']; ?>"/>
        <br/>
        <small>You can find your Key by logging into your Bookgrail account and opening the <strong>Recommends</strong> screen from the menu on the top right of your screen.</small>
        <?php
        
    }
            
    public function render_theme_color_field()
    {
        ?>
        <input type="text" id="theme_color" name="bg_settings[theme_color]" class="regular-text" value="<?php echo $this->options['theme_color']; ?>"/>
        <br/>
        <small>Sets the theme color scheme to apply to the Bookgrail cart popup.</small>
        <?php
        
    }
            
    
    
    /**
     * Register/queue scripts.
     *
     * @access public
     * @return void
     */
    public function load_scripts() {
        
        $script_path 	= $this->plugin_url() . '/assets/scripts/';
        wp_register_script( 'bookgrail-init', $script_path . 'bg.min.js', array( 'jquery' ), $this->version, true );        
        wp_localize_script( 'bookgrail-init', 'bookgrail_params', $this->script_variables() );        
        
    }
    
    public function script_variables() {
        
        $referrer_id = $this->options['referrer_id'];
        
        // Variables for JS scripts
        $bookgrail_params = array(
                'referrer_id' => $referrer_id
        );        
        
        return $bookgrail_params;        
        
    }
    
    
    
    /** Helper functions ******************************************************/

    /**
     * Get the plugin url.
     *
     * @access public
     * @return string
     */
    public function plugin_url() {
        if ( $this->plugin_url ) return $this->plugin_url;
        return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
    }


    /**
     * Get the plugin path.
     *
     * @access public
     * @return string
     */
    public function plugin_path() {
            if ( $this->plugin_path ) return $this->plugin_path;

            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }


    /**
     * Get Ajax URL.
     *
     * @access public
     * @return string
     */
    public function ajax_url() {
            return admin_url( 'admin-ajax.php', 'relative' );
    }

    
    
    /**
     * Init Bookgrail when WordPress Initialises.
     *
     * @access public
     * @return void
     */
    public function init() {
        
        //Before init action
        do_action( 'before_bookgrail_init' );

        // Set up localisation
        //$this->load_plugin_textdomain();

        // Variables
        
        // Classes/actions loaded for the frontend and for ajax requests
        if ( ! is_admin() || defined('DOING_AJAX') ) {

            // Class instances
            $this->shortcodes		= new BG_Shortcodes();	// Shortcodes class, controls all frontend shortcodes

            // Hooks
            add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
        }

        // Init action
        do_action( 'bookgrail_init' );
    }
   
    /**
     * activate function.
     *
     * @access public
     * @return void
     */
    public function activate() {
            $this->install();
    }
    
    /**
    * update function.
    *
    * @access public
    * @return void
    */
    public function update() {
           if ( ! defined( 'IFRAME_REQUEST' ) && ( get_option( 'bookgrail_version' ) != $this->version ) )
                   $this->install();
    }

    /**
    * upgrade function.
    *
    * @access public
    * @return void
    */
    function install() {
           //do_install_bookgrail();
    }
           
}

/**
 * Init bookgrail class
 */
$GLOBALS['bookgrail'] = new Bookgrail();

} // class_exists check