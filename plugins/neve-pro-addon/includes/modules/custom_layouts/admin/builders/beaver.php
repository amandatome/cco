<?php
/**
 * Replace header, footer or hooks for Beaver Builder page builder.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Traits\Core;

/**
 * Class Beaver
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Beaver extends Abstract_Builders {
	use Core;

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	function should_load() {
		return class_exists( 'FLBuilderModel' );
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
		if ( $editor !== 'beaver' ) {
			return;
		}

		remove_all_actions( 'neve_do_header' );
		remove_all_actions( 'neve_do_top_bar' );
		echo \FLBuilderShortcodes::insert_layout(
			array(
				'id' => $post_id,
			)
		);
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
		if ( $editor !== 'beaver' ) {
			return;
		}
		remove_all_actions( 'neve_do_footer' );
		echo \FLBuilderShortcodes::insert_layout(
			array(
				'id' => $post_id,
			)
		);
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
			if ( $editor !== 'beaver' ) {
				continue;
			}
			add_action(
				$hook,
				function () use ( $post_id ) {
					echo \FLBuilderShortcodes::insert_layout(
						array(
							'id' => $post_id,
						)
					);
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
		return 'beaver';
	}
}
