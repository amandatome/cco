<?php
/**
 * Abstract class for builders compatibility.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Traits\Core;

/**
 * Class Abstract_Builders
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
abstract class Abstract_Builders {
	use Core;

	/**
	 * Id of the current builder
	 *
	 * @var string
	 */
	protected $builder_id;
	/**
	 * The static rules array.
	 *
	 * @var array
	 */
	private $static_rules = array();

	/**
	 * Initialize function.
	 * Check if class should load and then run hooks.
	 */
	public function init() {
		if ( ! $this->should_load() ) {
			return;
		}
		$this->initialize_class_settings();
	}

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	abstract function should_load();

	/**
	 * Initialize class settings and run hooks.
	 */
	private function initialize_class_settings() {
		$this->builder_id = $this->get_builder_id();
		if ( empty( $this->builder_id ) ) {
			return;
		}
		$this->run_hooks();
	}

	/**
	 * Get builder id.
	 *
	 * @return string
	 */
	abstract function get_builder_id();

	/**
	 * Add actions to hooks.
	 */
	protected function run_hooks() {
		add_filter( 'neve_post_content', 'do_blocks' );
		add_filter( 'neve_post_content', 'wptexturize' );
		add_filter( 'neve_post_content', 'convert_smilies' );
		add_filter( 'neve_post_content', 'convert_chars' );
		add_filter( 'neve_post_content', 'wpautop' );
		add_filter( 'neve_post_content', 'shortcode_unautop' );
		add_filter( 'neve_post_content', 'do_shortcode' );

		add_action( 'wp', array( $this, 'hooks_template' ), 1 );
		add_action( 'wp_enqueue_scripts', array( $this, 'add_styles' ), 9 );
		add_action( 'neve_do_header', array( $this, 'load_markup_header' ), 1 );
		add_action( 'neve_do_footer', array( $this, 'load_markup_footer' ), 1 );
		add_action( 'wp', array( $this, 'hook_wrapper' ), 1 );

	}

	/**
	 * This function handles the display on Custom Layouts preview, the single of Custom Layouts custom post type.
	 */
	public function hooks_template() {
		if ( ! is_singular( 'neve_custom_layouts' ) ) {
			return;
		}

		$post_id = get_the_id();
		$layout  = get_post_meta( $post_id, 'custom-layout-options-layout', true );

		if ( empty( $layout ) ) {
			remove_all_actions( 'neve_custom_layouts_template_content' );
			add_action( 'neve_custom_layouts_template_content', array( $this, 'custom_hook_markup' ) );

			return;
		}

		if ( $layout === 'header' ) {
			remove_all_actions( 'neve_do_header' );
			remove_all_actions( 'neve_do_top_bar' );
			add_action( 'neve_do_header', array( $this, 'custom_header_markup' ) );
		}

		if ( $layout === 'footer' ) {
			remove_all_actions( 'neve_do_footer' );
			add_action( 'neve_do_footer', array( $this, 'custom_footer_markup' ) );
		}

		if ( $layout === 'hooks' ) {
			$hook = get_post_meta( $post_id, 'custom-layout-options-hook', true );
			if ( ! empty( $hook ) ) {
				if ( ! has_action( $hook, array( $this, 'custom_hook_markup' ) ) ) {
					add_action( $hook, array( $this, 'custom_hook_markup' ) );
				}
			}
		}
	}

	/**
	 * Header markup on Custom Layouts preview.
	 */
	public function custom_header_markup() {
		echo '<header class="nv-custom-header" itemscope="itemscope" itemtype="https://schema.org/WPHeader">';
		$this->get_layout_content();
		echo '</header>';
	}

	/**
	 * Get the layout content.
	 */
	private function get_layout_content() {
		while ( have_posts() ) {
			the_post();
			$post_id = get_the_ID();
			$builder = $this->get_post_builder( $post_id );
			if ( $builder === 'custom' ) {
				$file_name = get_post_meta( $post_id, 'neve_editor_content', true );
				if ( empty( $file_name ) ) {
					continue;
				}
				$wp_upload_dir = wp_upload_dir( null, false );
				$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
				$file_path     = $upload_dir . $file_name . '.php';
				if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
					include_once( $file_path );
				}
			} else {
				the_content();
			}
		}
	}

	/**
	 * Get the builder that you used to edit a post.
	 *
	 * @param int $post_id Post id.
	 *
	 * @return string
	 */
	protected function get_post_builder( $post_id ) {
		if ( get_post_meta( $post_id, 'neve_editor_mode', true ) === '1' ) {
			return 'custom';
		}

		if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
			return 'elementor';
		}

		if ( class_exists( 'FLBuilderModel' ) && get_post_meta( $post_id, '_fl_builder_enabled', true ) ) {
			return 'beaver';
		}

		if ( class_exists( 'Brizy_Editor_Post' ) ) {
			try {
				$post = \Brizy_Editor_Post::get( $post_id );
				if ( $post->uses_editor() ) {
					return 'brizy';
				}
			} catch ( \Exception $exception ) {
				// The post type is not supported by Brizy hence Brizy should not be used render the post.
			}
		}

		return 'default';
	}

	/**
	 * Footer markup on Custom Layouts preview.
	 */
	public function custom_footer_markup() {
		echo '<footer class="nv-custom-footer" itemscope="itemscope" itemtype="https://schema.org/WPFooter">';
		$this->get_layout_content();
		echo '</footer>';
	}

	/**
	 * Hooks markup on Custom Layouts preview.
	 */
	public function custom_hook_markup() {
		$this->get_layout_content();
	}

	/**
	 * Wrapper function for load_markup_hook. Executes after the theme is loaded to be able to get hooks from the theme.
	 */
	public function hook_wrapper() {
		$hooks = neve_hooks();
		foreach ( $hooks as $cat ) {
			foreach ( $cat as $hook ) {
				$this->load_markup_hook( $hook );
			}
		}
	}

	/**
	 * Hooks markup on front end.
	 *
	 * @param String $hook Where to add markup.
	 */
	abstract function load_markup_hook( $hook );

	/**
	 * Function that enqueues styles if needed.
	 */
	abstract function add_styles();

	/**
	 * Header markup on front end.
	 */
	abstract function load_markup_header();

	/**
	 * Footer markup on front end.
	 */
	abstract function load_markup_footer();

	/**
	 * Check the display conditions.
	 *
	 * @param int $custom_layout_id the custom layout post ID.
	 *
	 * @return bool
	 */
	protected function check_conditions( $custom_layout_id ) {
		$this->setup_static_rules();
		$condition_groups = json_decode( get_post_meta( $custom_layout_id, 'custom-layout-conditional-logic', true ), true );

		if ( ! is_array( $condition_groups ) || empty( $condition_groups ) ) {
			return true;
		}
		$evaluated_groups = array();
		foreach ( $condition_groups as $index => $conditions ) {
			$individual_rules = array();
			foreach ( $conditions as $condition ) {
				$individual_rules[ $index ][] = $this->evaluate_condition( $condition );
			}
			$evaluated_groups[ $index ] = ! in_array( false, $individual_rules[ $index ], true );
		}

		return in_array( true, $evaluated_groups, true );
	}

	/**
	 * Setup static rules.
	 */
	private function setup_static_rules() {
		$this->static_rules = array(
			'page_type'   => array(
				'front_page' => get_option( 'show_on_front' ) === 'page' && is_front_page(),
				'not_found'  => is_404(),
				'posts_page' => is_home(),
			),
			'user_status' => array(
				'logged_in'  => is_user_logged_in(),
				'logged_out' => ! is_user_logged_in(),
			),
		);

		$this->static_rules['archive_type'] = array(
			'date'   => is_date(),
			'author' => is_author(),
			'search' => is_search(),
		);

		$post_types = get_post_types( array( 'public' => true ) );

		foreach ( $post_types as $post_type ) {
			if ( $post_type === 'post' ) {
				$this->static_rules['archive_type'][ $post_type ] = is_home();
				continue;
			}
			$this->static_rules['archive_type'][ $post_type ] = is_post_type_archive( $post_type );
		}
	}

	/**
	 * Evaluate single condition
	 *
	 * @param array $condition condition.
	 *
	 * @return bool
	 */
	private function evaluate_condition( $condition ) {
		$post_id = null;
		global $post;
		if ( isset( $post->ID ) ) {
			$post_id = (string) $post->ID;
		}
		if ( ! is_array( $condition ) || empty( $condition ) ) {
			return true;
		}
		$evaluated = false;
		switch ( $condition['root'] ) {
			case 'post_type':
				$evaluated = is_singular( $condition['end'] );
				break;
			case 'post':
				$evaluated = is_single() && $post_id === $condition['end'];
				break;
			case 'page':
				$evaluated = is_page() && $post_id === $condition['end'];
				break;
			case 'page_template':
				$evaluated = get_page_template_slug() === $condition['end'];
				break;
			case 'page_type':
				$evaluated = $this->static_rules['page_type'][ $condition['end'] ];
				break;
			case 'post_taxonomy':
				$parts = preg_split( '/\|/', $condition['end'] );
				if ( is_array( $parts ) && sizeof( $parts ) === 2 ) {
					$evaluated = is_singular() && has_term( $parts[1], $parts[0], get_the_ID() );
				}
				break;
			case 'archive_term':
				$parts  = preg_split( '/\|/', $condition['end'] );
				$object = get_queried_object();
				if ( is_array( $parts ) && sizeof( $parts ) === 2 && $object instanceof \WP_Term && isset( $object->slug ) ) {
					$evaluated = $object->slug === $parts[1] && $object->taxonomy === $parts[0];
				}
				break;
			case 'archive_taxonomy':
				$object = get_queried_object();
				if ( $object instanceof \WP_Term && isset( $object->taxonomy ) ) {
					$evaluated = $object->taxonomy === $condition['end'];
				}
				break;
			case 'archive_type':
				$evaluated = $this->static_rules['archive_type'][ $condition['end'] ];
				break;
			case 'user':
				$evaluated = (string) get_current_user_id() === $condition['end'];
				break;
			case 'post_author':
				$evaluated = is_singular() && (string) $post->post_author === $condition['end'];
				break;
			case 'archive_author':
				$evaluated = is_author( $condition['end'] );
				break;
			case 'user_status':
				$evaluated = $this->static_rules['user_status'][ $condition['end'] ];
				break;
			case 'user_role':
				$user      = wp_get_current_user();
				$evaluated = in_array( $condition['end'], $user->roles, true );
				break;
		}
		if ( $condition['condition'] === '===' ) {
			return $evaluated;
		}

		return ! $evaluated;
	}
}
