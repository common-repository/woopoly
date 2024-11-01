<?php

/*
 * Plugin Name: WooPoly
 * Plugin URI: https://github.com/decarvalhoaa/woopoly/
 * Description: Integrates WooCommerce and Polylang
 * Author: Antonio de Carvalho
 * Author URI: https://github.com/decarvalhoaa
 * Text Domain: woopoly
 * Domain Path: /languages
 * GitHub Plugin URI: decarvalhoaa/woopoly
 * License: MIT License
 * Version: 1.0.1
 */

/**
 * This file is part of the decarvalhoaa/woopoly plugin.
 * (c) Antonio de Carvalho <decarvalhoaa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Returns the main instance of the plugin to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object woopoly
 */
function WooPoly() {
	return WooPoly::instance();
} // woopoly()

WooPoly();

/**
 * Main WooPoly Class
 *
 * @class WooPoly
 * @version 1.0.0
 * @since 1.0.0
 * @package WooPoly
 * @author Antonio de Carvalho
 */
final class WooPoly {

    /**
     * The single instance of WooPoly.
     * @var	object
     * @access	private
     * @since	1.0.0
     */
    private static $_instance = null;

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $token;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $version;

    /**
    * Constructor function.
    *
    * @access  public
    * @since   1.0.0
    * @return  void
    */
    public function __construct() {
		$this->token 		= 'woopoly';
		$this->plugin_file	= __FILE__;
		$this->plugin_url 	= plugin_dir_url( __FILE__ );
		$this->plugin_path 	= plugin_dir_path( __FILE__ );
		$this->version 		= '1.0.0';

		register_activation_hook( __FILE__, array( $this, 'activation' ) );

		add_action( 'plugins_loaded', array( $this, 'setup' ) );
    } // End __construct()

    /**
     * Main WooPoly Instance
     *
     * Ensures only one instance of WooPoly is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Main WooPoly instance
     */
    public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
    } // End instance()

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    } // End __clone()

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
    } // End __wakeup()

    /**
     * Runs on activation.
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function activation() {
		$this->check_dependencies();
		$this->_log_version_number();
	} // End activation()

    /**
     * Log the plugin version number.
     *
     * @access  private
     * @since   1.0.0
     * @return  void
     */
    private function _log_version_number() {
		update_option( $this->token . '-version', $this->version );
    } // End _log_version_number()

	/**
     * Checks plugin dependencies.
     *
     * @access  public
     * @since   1.0.0
     * @return  void
     */
    public function check_dependencies() {
		$conflicts		= array( 'woo-poly-integration/__init__.php' );
		$required		= array( 'polylang/polylang.php', 'woocommerce/woocommerce.php' );
		$active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

		foreach ( $conflicts as $plugin ) {
			if ( in_array( $plugin, $active_plugins ) ) {
				trigger_error( 'WooPoly Plugin conflicts with Hyyan WooCommerce Polylang Integration Plugin. Please deactivate Hyyan WooCommerce Integration before activating this plugin.', E_USER_ERROR );
			}
		}

		foreach ( $required as $plugin ) {
			if ( ! in_array( $plugin, $active_plugins ) ) {
				trigger_error( 'WooPoly Plugin requires WooCommerce and Polylang. Please install and activate both plugins before activating this plugin.', E_USER_ERROR );
			}
		}
    } // End check_dependencies()

    /**
     * Setup all the things
     *
     * @access  public
     * @since   1.0.0
     * @return	void
     */
    public function setup() {
		// Define constants
		define( 'WOOPOLY_DIR', $this->plugin_path );
		define( 'WOOPOLY_FILE', $this->plugin_file );
		define( 'WOOPOLY_URL', $this->plugin_url );  // Not being used?
		define( 'WOOPOLY_TOKEN', $this->token );

		// Load 3rd party classes
		require_once ABSPATH . 'wp-admin/includes/plugin.php'; // Is it needed?
		require_once __DIR__ . '/vendor/class.settings-api.php';

		// Register the autoloader
		require_once __DIR__ . '/src/Hyyan/WPI/Autoloader.php';
		new Hyyan\WPI\Autoloader(__DIR__ . '/src/');

		// Bootstrap the plugin
		new Hyyan\WPI\Plugin();
    } // End setup()

}
