<?php
/**
 * Custom Layouts Main Class
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Custom_Layouts;

use Neve_Pro\Core\Abstract_Module;

/**
 * Class Module
 *
 * @package Neve_Pro\Modules\Custom_Layouts
 */
class Module extends Abstract_Module {

	/**
	 * Holds the base module namespace
	 * Used to load submodules.
	 *
	 * @var string $module_namespace
	 */
	private $module_namespace = 'Neve_Pro\Modules\Custom_Layouts';

	/**
	 * Define module properties.
	 *
	 * @access  public
	 * @return void
	 * @property string  $this->slug        The slug of the module.
	 * @property string  $this->name        The pretty name of the module.
	 * @property string  $this->description The description of the module.
	 * @property string  $this->order       Optional. The order of display for the module. Default 0.
	 * @property boolean $this->active      Optional. Default `false`. The state of the module by default.
	 *
	 * @version 1.0.0
	 */
	public function define_module_properties() {
		$this->slug          = 'custom_layouts';
		$this->name          = __( 'Custom Layouts', 'neve' );
		$this->description   = __( 'Easily create custom headers and footers as well as adding your own custom code or content in any of the hooks locations.', 'neve' );
		$this->documentation = array(
			'url'   => 'https://docs.themeisle.com/article/1062-custom-layouts-module',
			'label' => __( 'Learn more', 'neve' ),
		);
		$this->order         = 6;
	}

	/**
	 * Check if module should load.
	 *
	 * @return bool
	 */
	function should_load() {
		return $this->settings->is_module_active( 'custom_layouts' );
	}

	/**
	 * Run Custom Layouts module.
	 */
	function run_module() {
		$this->register_custom_post_type();
		$this->run_hooks();

		$submodules = array(
			$this->module_namespace . '\Admin\Layouts_Metabox',
			$this->module_namespace . '\Rest\Server',
			$this->module_namespace . '\Admin\View_Hooks',
			$this->module_namespace . '\Admin\Builders\Default_Editor',
			$this->module_namespace . '\Admin\Builders\Elementor',
			$this->module_namespace . '\Admin\Builders\Beaver',
			$this->module_namespace . '\Admin\Builders\Brizy',
			$this->module_namespace . '\Admin\Builders\Php_Editor',
		);

		foreach ( $submodules as $index => $mod ) {
			if ( class_exists( $mod ) ) {
				$mod_{$index} = new $mod;
				$mod_{$index}->init();
			}
		}
	}

	/**
	 * Register Custom Layouts post type.
	 */
	private function register_custom_post_type() {

		$labels = array(
			'name'          => esc_html_x( 'Custom Layouts', 'advanced-hooks general name', 'neve-pro-addon' ),
			'singular_name' => esc_html_x( 'Custom Layout', 'advanced-hooks singular name', 'neve-pro-addon' ),
			'search_items'  => esc_html__( 'Search Custom Layouts', 'neve-pro-addon' ),
			'all_items'     => esc_html__( 'Custom Layouts', 'neve-pro-addon' ),
			'edit_item'     => esc_html__( 'Edit Custom Layout', 'neve-pro-addon' ),
			'view_item'     => esc_html__( 'View Custom Layout', 'neve-pro-addon' ),
			'add_new'       => esc_html__( 'Add New', 'neve-pro-addon' ),
			'update_item'   => esc_html__( 'Update Custom Layout', 'neve-pro-addon' ),
			'add_new_item'  => esc_html__( 'Add New', 'neve-pro-addon' ),
			'new_item_name' => esc_html__( 'New Custom Layout Name', 'neve-pro-addon' ),
		);

		$args = array(
			'labels'              => $labels,
			'show_in_menu'        => 'themes.php',
			'public'              => true,
			'show_ui'             => true,
			'query_var'           => true,
			'can_export'          => true,
			'show_in_admin_bar'   => true,
			'exclude_from_search' => true,
			'show_in_rest'        => true,
			'supports'            => array( 'title', 'editor', 'elementor' ),
		);

		register_post_type( 'neve_custom_layouts', apply_filters( 'neve_custom_layouts_post_type_args', $args ) );
	}

	/**
	 * Add hooks and filters.
	 */
	private function run_hooks() {
		add_filter( 'fl_builder_post_types', array( $this, 'beaver_compatibility' ), 10, 1 );
		add_filter( 'single_template', array( $this, 'custom_layouts_single_template' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Add Beaver Builder Compatibility
	 *
	 * @param array $value Post types.
	 *
	 * @return array
	 */
	public function beaver_compatibility( $value ) {
		$value[] = 'neve_custom_layouts';

		return $value;
	}

	/**
	 * Set path to neve_custom_layouts template.
	 *
	 * @param string $single Path to single.php .
	 *
	 * @return string
	 */
	public function custom_layouts_single_template( $single ) {
		global $post;
		if ( $post->post_type === 'neve_custom_layouts' && file_exists( plugin_dir_path( __FILE__ ) . 'admin/template.php' ) ) {
			return plugin_dir_path( __FILE__ ) . 'admin/template.php';
		}

		return $single;
	}

	/**
	 * Enqueue scripts.
	 */
	public function admin_enqueue_scripts() {
		global $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		global $post;
		if ( $post !== null && $post->post_type !== 'neve_custom_layouts' ) {
			return;
		}

		if ( ! function_exists( 'wp_enqueue_code_editor' ) ) {
			return;
		}

		wp_enqueue_code_editor(
			array(
				'type'       => 'application/x-httpd-php',
				'codemirror' => array(
					'indentUnit' => 2,
					'tabSize'    => 2,
				),
			)
		);
		wp_enqueue_script( 'neve-pro-addon-custom-layout', NEVE_PRO_INCLUDES_URL . 'modules/custom_layouts/assets/js/script.js', array(), NEVE_PRO_VERSION );
		wp_localize_script(
			'neve-pro-addon-custom-layout',
			'neveCustomLayouts',
			array(
				'customEditorEndpoint' => rest_url( '/wp/v2/neve_custom_layouts/' . $post->ID ),
				'nonce'                => wp_create_nonce( 'wp_rest' ),
				'phpError'             => esc_html__( 'There are some errors in your PHP code. Please fix them before saving the code.', 'neve-pro-addon' ),

			)
		);

		$this->rtl_enqueue_style( 'neve-pro-addon-custom-layouts', NEVE_PRO_INCLUDES_URL . 'modules/custom_layouts/assets/admin_style.min.css', array(), NEVE_PRO_VERSION );

	}
}
