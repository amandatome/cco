<?php
/**
 * The customizer addons loader class.
 *
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2018-12-03
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Customizer;


use Neve\Core\Factory;
use Neve_Pro\Core\Settings;
use Neve_Pro\Traits\Core;

/**
 * Class Loader
 *
 * @since   0.0.1
 * @package Neve Pro Addon
 */
class Loader {
	use Core;

	/**
	 * Customizer modules.
	 *
	 * @access private
	 * @since  0.0.1
	 * @var array
	 */
	private $modules = array();

	/**
	 * Loader constructor.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function __construct() {
		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_customizer_controls' ) );
	}

	/**
	 * Initialize the customizer functionality
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function init() {
		global $wp_customize;

		if ( ! isset( $wp_customize ) ) {
			return;
		}

		$this->define_modules();
		$this->load_modules();
	}

	/**
	 * Define the modules that will be loaded.
	 *
	 * @access private
	 * @since  0.0.1
	 */
	private function define_modules() {
		$this->modules = apply_filters(
			'neve_pro_filter_customizer_modules',
			array(
				'Customizer\Options\Main',
			)
		);
	}

	/**
	 * Enqueue customizer controls script.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function enqueue_customizer_controls() {
		wp_enqueue_script( 'neve-pro-controls', NEVE_PRO_INCLUDES_URL . 'customizer/controls/js/bundle.js', array(), NEVE_PRO_VERSION );

		$this->rtl_enqueue_style( 'neve-pro-controls', NEVE_PRO_INCLUDES_URL . 'customizer/controls/css/customizer-controls.min.css', array(), NEVE_PRO_VERSION );
	}

	/**
	 * Enqueue customizer preview script.
	 *
	 * @access public
	 * @since  0.0.1
	 */
	public function enqueue_customizer_preview() {
	}

	/**
	 * Load the customizer modules.
	 *
	 * @access private
	 * @since  0.0.1
	 * @return void
	 */
	private function load_modules() {
		$factory = new Factory( $this->modules, '\\Neve_Pro\\' );
		$factory->load_modules();
	}
}
