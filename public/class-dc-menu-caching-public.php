<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.dicha.gr
 * @since      1.0.0
 *
 * @package    Dc_Menu_Caching
 * @subpackage Dc_Menu_Caching/public
 */

class Dc_Menu_Caching_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

    
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
//		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dc-menu-caching-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
//		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/dc-menu-caching-public.js', array( 'jquery' ), $this->version, false );
	}

}
