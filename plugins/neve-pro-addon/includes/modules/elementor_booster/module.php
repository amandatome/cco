<?php
/**
 * Elementor Booster Module main file.
 *
 * @package Neve_Pro\Modules\Elementor_Booster
 */

namespace Neve_Pro\Modules\Elementor_Booster;

use Neve_Pro\Core\Abstract_Module;

/**
 * Class Module
 *
 * @package Neve_Pro\Modules\Elementor_Booster
 */
class Module extends Abstract_Module {

	/**
	 * Holds the base module namespace
	 * Used to load submodules.
	 *
	 * @var string $module_namespace
	 */
	private $module_namespace = 'Neve_Pro\Modules\Elementor_Booster';

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 * @property string  $this->slug              The slug of the module.
	 * @property string  $this->name              The pretty name of the module.
	 * @property string  $this->description       The description of the module.
	 * @property string  $this->order             Optional. The order of display for the module. Default 0.
	 * @property boolean $this->active            Optional. Default `false`. The state of the module by default.
	 * @property boolean $this->dependent_plugins Optional. Dependent plugin for this module.
	 * @property boolean $this->documentation     Optional. Module documentation.
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug              = 'elementor_booster';
		$this->name              = __( 'Elementor Booster', 'neve' );
		$this->description       = __( 'Leverage the true flexibility of Elementor with powerful addons and templates that you can import with just one click.', 'neve' );
		$this->order             = 7;
		$this->dependent_plugins = array(
			'elementor' => array(
				'path' => 'elementor/elementor.php',
				'name' => 'Elementor',
			),
		);
		$this->documentation     = array(
			'url'   => 'https://docs.themeisle.com/article/1063-elementor-booster-module-documentation',
			'label' => __( 'Learn more', 'neve' ),
		);
	}

	/**
	 * Check if module should be loaded.
	 *
	 * @return bool
	 */
	function should_load() {
		return ( $this->settings->is_module_active( $this->slug ) && defined( 'ELEMENTOR_VERSION' ) );
	}

	/**
	 * Run Elementor Booster Module
	 */
	function run_module() {
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'register_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	/**
	 * Register Elementor Widgets.
	 */
	public function register_widgets() {
		$widgets = array(
			$this->module_namespace . '\Widgets\Flip_Card',
			$this->module_namespace . '\Widgets\Review_Box',
			$this->module_namespace . '\Widgets\Share_Buttons',
			$this->module_namespace . '\Widgets\Typed_Headline',
		);

		foreach ( $widgets as $widget ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $widget() );
		}
	}

	/**
	 * Add a new category of widgets.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elementor elements manager.
	 */
	public function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			'neve-elementor-widgets',
			array(
				'title' => esc_html__( 'Neve Pro Addon Widgets', 'neve' ),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	/**
	 * Register styles and maybe load them on the editor side when needed.
	 */
	function register_styles() {
		// While rendering in fronted, the enqueue will be triggered inside the widget. But on the edit side, we'll enqueue globally
		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			$this->rtl_enqueue_style( 'neve-elementor-widgets-styles', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/css/public.min.css', array(), NEVE_PRO_VERSION );
		} else {
			wp_register_style( 'neve-elementor-widgets-styles', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/css/public.min.css', array(), NEVE_PRO_VERSION );
		}
	}

	/**
	 * Register scripts.
	 */
	public function register_scripts() {
		wp_enqueue_script( 'typed-animation', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/typed.min.js', array( 'jquery' ), NEVE_PRO_VERSION );
		wp_enqueue_script( 'eaw-pro-scripts', NEVE_PRO_INCLUDES_URL . 'modules/elementor_booster/assets/js/public.js', array( 'typed-animation' ), NEVE_PRO_VERSION );
	}
}
