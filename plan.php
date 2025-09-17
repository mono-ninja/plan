<?php
/**
 * Plugin Name: Plan
 * Plugin URI: https://wordpress.org/
 * Description: Create table Plans
 * Version: 1.0
 * Author: lobov
 * Author URI: https://wordpress.org/
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: plan
 * PHP version: 7.4
 */

namespace Plan;


use Plan\Core\Core;
use Plan\CPT\CPT;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Plan' ) ) :

	final class Plan {


		// Plugin Shortcode
		public const SHORTCODE = 'plans';

		public const CACHEKEY = 'plans_query_cache';

		public const CACHE_TTL  = 0;

		private static $instance;

		private Autoloader $autoloader;

		public static function instance(): Plan {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
				self::$instance = new self;

				self::$instance->includes();

				self::$instance->autoloader = new Autoloader( 'Plan' );

				add_action( 'plugins_loaded', [ self::$instance, 'loaded' ] );
			}


			return self::$instance;
		}

		// Plugin Root File.
		public static function file(): string {
			return __FILE__;
		}

		// Plugin Base Name.
		public static function basename(): string {
			return plugin_basename( __FILE__ );
		}

		// Plugin Folder Path.
		public static function dir(): string {
			return plugin_dir_path( __FILE__ );
		}

		// Plugin Folder URL.
		public static function url(): string {
			return plugin_dir_url( __FILE__ );
		}

		// Get Plugin Info
		public static function info( $show = '' ): string {
			$data        = [
				'name'    => 'Plugin Name',
				'version' => 'Version',
			];
			$plugin_data = get_file_data( __FILE__, $data, false );

			return $plugin_data[ $show ] ?? '';
		}

		/**
		 * Include required files.
		 *
		 * @access private
		 * @since  1.0
		 */
		private function includes(): void {
			require_once self::dir() . 'classes/Autoloader.php';
		}

		/**
		 * Throw error on object clone.
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @return void
		 * @access protected
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?', 'plan' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @return void
		 * @access protected
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden.
			_doing_it_wrong( __FUNCTION__, esc_attr__( 'Cheatin&#8217; huh?', 'plan' ), '1.0' );
		}


		/**
		 * Download the folder with languages.
		 *
		 * @access Publisher
		 * @return void
		 */
		public function loaded(): void {

			new Core();
			new CPT();

			$languages_folder = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			load_plugin_textdomain( 'plan', false, $languages_folder );

		}
	}

endif;

function wp_plugin_run(): Plan {
	return Plan::instance();
}

wp_plugin_run();
