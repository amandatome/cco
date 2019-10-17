<?php
/**
 * Replace header, footer or hooks for Elementor page builder.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Traits\Core;

/**
 * Class Elementor
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Elementor extends Abstract_Builders {
	use Core;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	public function should_load() {
		return class_exists( '\Elementor\Plugin' );
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
		$is_elementor = false;
		foreach ( $query->posts as $post_id ) {
			if ( $this->get_post_builder( $post_id ) === 'elementor' ) {
				$is_elementor = true;
				if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
					$css_file = new \Elementor\Core\Files\CSS\Post( $post_id );
					$css_file->enqueue();
				}
			}
		}

		if ( $is_elementor === true ) {
			if ( class_exists( '\Elementor\Plugin' ) ) {
				$elementor = \Elementor\Plugin::instance();
				$elementor->frontend->enqueue_styles();
			}
			if ( class_exists( '\ElementorPro\Plugin' ) ) {
				$elementor = \Elementor\Plugin::instance();
				$elementor->frontend->enqueue_styles();
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
		if ( $editor !== 'elementor' ) {
			return;
		}
		remove_all_actions( 'neve_do_header' );
		remove_all_actions( 'neve_do_top_bar' );
		$elementor = \Elementor\Plugin::instance();
		echo $elementor->frontend->get_builder_content_for_display( $post_id, true );
	}

	/**
	 * Footer markup on front end.
	 */
	public function load_markup_footer() {
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
		if ( $editor !== 'elementor' ) {
			return;
		}
		remove_all_actions( 'neve_do_footer' );
		$elementor = \Elementor\Plugin::instance();
		echo $elementor->frontend->get_builder_content_for_display( $post_id, true );
	}

	/**
	 * Hooks markup on front end.
	 *
	 * @param String $hook Where to add markup.
	 */
	public function load_markup_hook( $hook ) {
		$posts = $this->get_post_at( 'hooks', $hook );
		if ( $posts === false ) {
			return;
		}
		foreach ( $posts as $post_id => $priority ) {
			if ( ! $this->check_conditions( $post_id ) ) {
				continue;
			}
			$editor = $this->get_post_builder( $post_id );
			if ( $editor !== 'elementor' ) {
				continue;
			}
			add_action(
				$hook,
				function () use ( $post_id ) {
					$elementor = \Elementor\Plugin::instance();
					echo $elementor->frontend->get_builder_content_for_display( $post_id, true );
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
		return 'elementor';
	}
}
