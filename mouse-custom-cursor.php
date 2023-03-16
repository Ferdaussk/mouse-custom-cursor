<?php
/**
 * Plugin Name: Mouse Custom Cursor
 * Description: Mouse Custom Cursor is beautiful responsive customcursor with slider and powerfull customcursor submitter for blogs and sites.
 * Plugin URI:  www.bwdplugins.com/elementor/mouse-custom-cursor
 * Version:     1.0
 * Author:      Best WP Developer
 * Author URI:  www.bestwpdeveloper.com/
 * Text Domain: mouse-custom-cursor
 * Elementor tested up to: 3.0.0
 * Elementor Pro tested up to: 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once ( plugin_dir_path(__FILE__) ) . '/includes/requires-check.php';
final class The_Best_Testimonials{

	const VERSION = '1.0';

	const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

	const MINIMUM_PHP_VERSION = '7.0';

	public function __construct() {
		// Load translation
		add_action( 'mcustomc_init', array( $this, 'mcustomc_loaded_textdomain' ) );
		// mcustomc_init Plugin
		add_action( 'plugins_loaded', array( $this, 'mcustomc_init' ) );
	}

	public function mcustomc_loaded_textdomain() {
		load_plugin_textdomain( 'mouse-custom-cursor' );
	}

	public function mcustomc_init() {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', 'mcustomc_admin_notice_missing_main_plugin');
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'mcustomc_admin_notice_minimum_elementor_version' ) );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'mcustomc_admin_notice_minimum_php_version' ) );
			return;
		}

		// Once we get here, We have passed all validation checks so we can safely include our plugin
		require_once( 'mcustomc_plugin_boots.php' );
	}

	public function mcustomc_admin_notice_minimum_elementor_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mouse-custom-cursor' ),
			'<strong>' . esc_html__( 'Mouse Custom Cursor', 'mouse-custom-cursor' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'mouse-custom-cursor' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>' . esc_html__('%1$s', 'mouse-custom-cursor') . '</p></div>', $message );
	}

	public function mcustomc_admin_notice_minimum_php_version() {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'mouse-custom-cursor' ),
			'<strong>' . esc_html__( 'Mouse Custom Cursor', 'mouse-custom-cursor' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'mouse-custom-cursor' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>' . esc_html__('%1$s', 'mouse-custom-cursor') . '</p></div>', $message );
	}
}

// Instantiate mouse-custom-cursor.
new The_Best_Testimonials();
remove_action( 'shutdown', 'wp_ob_end_flush_all', 1 );