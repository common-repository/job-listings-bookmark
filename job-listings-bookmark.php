<?php

/**
 * Plugin Name: Job Listings Bookmark
 * Plugin URI:        https://nootheme.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.1.0
 * Author:            NooTheme
 * Author URI:        https://nootheme.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       job-listings-bookmark
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Job_Listings_Bookmark' ) ):

	class Job_Listings_Bookmark {
		protected static $instance;

		/**
		 * Job_Listings_Bookmark constructor.
		 */
		public function __construct() {

			define( 'JLT_BOOKMARK_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			define( 'JLT_BOOKMARK_PLUGIN_TEMPLATE_DIR', JLT_BOOKMARK_PLUGIN_DIR . 'templates/' );

			// Includes
			$this->includes();

			add_action( 'init', array( $this, 'load_plugin_textdomain' ), 0 );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		public static function init() {
			is_null( self::$instance ) AND self::$instance = new self;

			return self::$instance;
		}

		public function includes() {
			require JLT_BOOKMARK_PLUGIN_DIR . 'includes/loader.php';
		}

		public function load_plugin_textdomain() {
			
			$locale = apply_filters( 'plugin_locale', get_locale(), 'job-listings-bookmark' );

			load_textdomain( 'job-listings-bookmark', WP_LANG_DIR . "/job-listings-bookmark/job-listings-bookmark-$locale.mo" );
			load_plugin_textdomain( 'job-listings-bookmark', false, plugin_basename( dirname( __FILE__ ) . "/languages" ) );
		}

		public function enqueue_scripts() {
			wp_enqueue_script( 'jlt-job-bookmark', plugin_dir_url( __FILE__ ) . 'assets/frontend/js/job-bookmark.js', array( 'jquery' ), '1.0', true );
		}

		public function enqueue_style() {
			wp_enqueue_style( 'jlt-job-bookmark', plugin_dir_url( __FILE__ ) . 'assets/frontend/css/job-bookmark.css', array(), '1.0.0', 'all' );
		}

		public function admin_enqueue_scripts() {
		}
	}

endif;

if ( ! function_exists( 'run_job_listings_bookmark' ) ) :

	function run_job_listings_bookmark() {

		return Job_Listings_Bookmark::init();
	}

	add_action( 'job_listings_loaded', 'run_job_listings_bookmark' );
endif;
