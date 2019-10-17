<?php
/**
 * Replace header, footer or hooks with the default editor.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Traits\Core;

/**
 * Class Default_Editor
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Default_Editor extends Abstract_Builders {
	use Core;

	/**
	 * Otter plugin instance.
	 *
	 * @var $otter_instance \ThemeIsle\GutenbergBlocks Otter instance.
	 */
	private $otter_instance;

	/**
	 * Default_Editor constructor.
	 */
	public function __construct() {
		if ( class_exists( '\ThemeIsle\GutenbergBlocks' ) ) {
			$this->otter_instance = new \ThemeIsle\GutenbergBlocks( '' );
		}
	}

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	public function should_load() {
		return true;
	}

	/**
	 * Function that enqueues styles if needed.
	 */
	function add_styles() {
	}

	/**
	 * Header markup on front end.
	 */
	function load_markup_header() {
		$posts = $this->get_post_at( 'header' );
		if ( $posts === false ) {
			return;
		}
		reset( $posts );
		$post_id = key( $posts );
		if ( ! $this->check_conditions( $post_id ) ) {
			return;
		}
		$editor = $this->get_post_builder( $post_id );
		if ( $editor !== 'default' ) {
			return;
		}
		remove_all_actions( 'neve_do_header' );
		remove_all_actions( 'neve_do_top_bar' );
		$content_post = get_post( $post_id );
		$content      = apply_filters( 'neve_post_content', $content_post->post_content );

		if ( defined( 'THEMEISLE_GUTENBERG_BLOCKS_VERSION' ) && version_compare( THEMEISLE_GUTENBERG_BLOCKS_VERSION, '1.2.2' ) >= 0 ) {
			$this->otter_instance->render_server_side_css( $post_id );
			$this->render_otter_fa( $post_id );

		}
		echo wp_kses_post( $content );
	}

	/**
	 * Footer markup on front end.
	 */
	function load_markup_footer() {
		$posts = $this->get_post_at( 'footer' );
		if ( $posts === false ) {
			return;
		}
		reset( $posts );
		$post_id = key( $posts );
		if ( ! $this->check_conditions( $post_id ) ) {
			return;
		}
		$editor = $this->get_post_builder( $post_id );
		if ( $editor !== 'default' ) {
			return;
		}
		remove_all_actions( 'neve_do_footer' );
		$post = get_post( $post_id );
		if ( defined( 'THEMEISLE_GUTENBERG_BLOCKS_VERSION' ) && version_compare( THEMEISLE_GUTENBERG_BLOCKS_VERSION, '1.2.2' ) >= 0 ) {
			$this->otter_instance->render_server_side_css( $post_id );
			$this->render_otter_fa( $post_id );
		}
		echo apply_filters( 'neve_post_content', $post->post_content );
	}

	/**
	 * Hooks markup on front end.
	 *
	 * @param String $hook Where to add markup.
	 */
	function load_markup_hook( $hook ) {
		if ( is_singular( 'neve_custom_layouts' ) ) {
			return;
		}
		$posts = $this->get_post_at( 'hooks', $hook );
		if ( $posts === false ) {
			return;
		}
		foreach ( $posts as $post_id => $priority ) {
			if ( ! $this->check_conditions( $post_id ) ) {
				continue;
			}

			$editor = $this->get_post_builder( $post_id );
			if ( $editor !== 'default' ) {
				continue;
			}
			add_action(
				$hook,
				function () use ( $post_id ) {
					$post = get_post( $post_id );
					if ( defined( 'THEMEISLE_GUTENBERG_BLOCKS_VERSION' ) && version_compare( THEMEISLE_GUTENBERG_BLOCKS_VERSION, '1.2.2' ) >= 0 ) {
						$this->otter_instance->render_server_side_css( $post_id );
						$this->render_otter_fa( $post_id );
					}
					echo apply_filters( 'neve_post_content', $post->post_content );
				},
				$priority
			);
		}
	}

	/**
	 * Builder id.
	 *
	 * @return string
	 */
	function get_builder_id() {
		return 'default';
	}

	/**
	 * Render Font Awesome from otter in case one of the blocks that use fa is in any custom field.
	 *
	 * @param int $post_id Post id.
	 */
	private function render_otter_fa( $post_id ) {
		if ( has_block( 'themeisle-blocks/button-group', $post_id ) ||
			has_block( 'themeisle-blocks/font-awesome-icons', $post_id ) ||
			has_block( 'themeisle-blocks/sharing-icons', $post_id ) ||
			has_block( 'themeisle-blocks/plugin-cards', $post_id ) ||
			has_block( 'block', $post_id ) ) {
			wp_enqueue_style( 'font-awesome-5', WP_PLUGIN_URL . '/otter-blocks/assets/fontawesome/css/all.min.css' );
			wp_enqueue_style( 'font-awesome-4-shims', WP_PLUGIN_URL . '/otter-blocks/assets/fontawesome/css/v4-shims.min.css' );
		}
	}
}
