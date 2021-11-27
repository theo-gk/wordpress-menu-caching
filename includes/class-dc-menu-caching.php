<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.dicha.gr
 * @since      1.0.0
 *
 * @package    Dc_Menu_Caching
 * @subpackage Dc_Menu_Caching/includes
 */

class Dc_Menu_Caching {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Dc_Menu_Caching_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'DC_MENU_CACHING_VERSION' ) ) {
			$this->version = DC_MENU_CACHING_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'dc-menu-caching';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
//		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Dc_Menu_Caching_Loader. Orchestrates the hooks of the plugin.
	 * - Dc_Menu_Caching_i18n. Defines internationalization functionality.
	 * - Dc_Menu_Caching_Admin. Defines all hooks for the admin area.
	 * - Dc_Menu_Caching_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dc-menu-caching-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dc-menu-caching-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dc-menu-caching-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dc-menu-caching-public.php';

		$this->loader = new Dc_Menu_Caching_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Dc_Menu_Caching_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Dc_Menu_Caching_i18n();

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

		$plugin_admin = new Dc_Menu_Caching_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'dc_menu_caching_create_menu' );

        //Plugin actions on plugin list
        $this->loader->add_filter( 'plugin_action_links', $plugin_admin, 'dc_action_links', 10, 2 );

        $this->loader->add_filter( 'wp_nav_menu', $plugin_admin, 'dc_save_menu_html', PHP_INT_MAX, 2 );
        $this->loader->add_filter( 'pre_wp_nav_menu', $plugin_admin, 'dc_show_cached_menu_html', PHP_INT_MAX, 2 );
        $this->loader->add_action( 'wp_update_nav_menu', $plugin_admin, 'dc_purge_updated_menu_transient', PHP_INT_MAX );
        $this->loader->add_action( 'after_rocket_clean_domain', $plugin_admin, 'dc_purge_all_menu_html_transients' );
        $this->loader->add_action( 'wp_ajax_dc_menu_caching_purge_all', $plugin_admin, 'dc_purge_all_menus_settings_button' );
        $this->loader->add_action( 'wp_ajax_dc_save_nocache_menus', $plugin_admin, 'dc_save_nocache_menus' );

        // enqueue styles-scripts
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    }


	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Dc_Menu_Caching_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
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
	 * @return    Dc_Menu_Caching_Loader    Orchestrates the hooks of the plugin.
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
