<?php
/**
 * Replace header, footer or hooks for Brizy page builder.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Traits\Core;

/**
 * Class Brizy
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Brizy extends Abstract_Builders {
	use Core;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	function should_load() {
		return class_exists( 'Brizy_Editor_Post' );
	}

	/**
	 * Function that enqueues styles if needed.
	 */
	public function add_styles() {
		$args  = array(
			'post_type'      => 'neve_custom_layouts',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		);
		$query = new \WP_Query( $args );
		if ( empty( $query->posts ) ) {
			return;
		}
		foreach ( $query->posts as $post_id ) {
			try {
				$post = \Brizy_Editor_Post::get( $post_id );
				if ( ! $post ) {
					continue;
				}

				$main = new \Brizy_Public_Main( $post );
				add_filter( 'body_class', array( $main, 'body_class_frontend' ) );
				add_action( 'wp_enqueue_scripts', array( $main, '_action_enqueue_preview_assets' ), 9999 );
				add_action(
					'wp_head',
					function () use ( $post ) {
						$html = new \Brizy_Editor_CompiledHtml( $post->get_compiled_html() );
						echo $html->get_head();
					}
				);
			} catch ( \Exception $exception ) {
				// The post type is not supported by Brizy hence Brizy should not be used render the post.
			}
		}
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
		if ( $editor !== 'brizy' ) {
			return;
		}

		try {
			$post = \Brizy_Editor_Post::get( $post_id );
			if ( $post ) {
				remove_all_actions( 'neve_do_header' );
				remove_all_actions( 'neve_do_top_bar' );
				$html = new \Brizy_Editor_CompiledHtml( $post->get_compiled_html() );
				echo $html->get_body();
			}
		} catch ( \Exception $exception ) {
			// The post type is not supported by Brizy hence Brizy should not be used render the post.
		}
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
		if ( $editor !== 'brizy' ) {
			return;
		}

		try {
			$post = \Brizy_Editor_Post::get( $post_id );
			if ( $post ) {
				remove_all_actions( 'neve_do_footer' );
				$html = new \Brizy_Editor_CompiledHtml( $post->get_compiled_html() );
				echo $html->get_body();
			}
		} catch ( \Exception $exception ) {
			// The post type is not supported by Brizy hence Brizy should not be used render the post.
		}
	}

	/**
	 * Hooks markup on front end.
	 *
	 * @param String $hook Where to add markup.
	 */
	function load_markup_hook( $hook ) {
		$posts = $this->get_post_at( 'hooks', $hook );
		if ( $posts === false ) {
			return;
		}
		foreach ( $posts as $post_id => $priority ) {
			if ( ! $this->check_conditions( $post_id ) ) {
				continue;
			}
			$editor = $this->get_post_builder( $post_id );
			if ( $editor !== 'brizy' ) {
				continue;
			}
			try {
				$post = \Brizy_Editor_Post::get( $post_id );
				if ( $post ) {
					add_action(
						$hook,
						function () use ( $post ) {
							$html = new \Brizy_Editor_CompiledHtml( $post->get_compiled_html() );
							echo $html->get_body();
						},
						$priority
					);

				}
			} catch ( \Exception $exception ) {
				// The post type is not supported by Brizy hence Brizy should not be used render the post.
			}
		}
	}

	/**
	 * Builder id.
	 *
	 * @return string
	 */
	function get_builder_id() {
		return 'brizy';
	}
}
