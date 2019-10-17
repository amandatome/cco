<?php
/**
 * Class that handle the show/hide hooks.
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin;

/**
 * Class View_Hooks
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin
 */
class View_Hooks {

	/**
	 * Initialize function.
	 */
	public function init() {
		$this->run_hooks();
	}

	/**
	 * Add actions to hooks.
	 */
	private function run_hooks() {
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 99 );
		add_action( 'wp', array( $this, 'render_hook_placeholder' ) );
	}

	/**
	 * Admin Bar Menu
	 *
	 * @param array $wp_admin_bar Admin bar menus.
	 */
	function admin_bar_menu( $wp_admin_bar = array() ) {
		if ( is_admin() ) {
			return;
		}
		$title = __( 'Show Hooks', 'neve' );

		$href = add_query_arg( 'neve_preview_hook', 'show' );
		if ( isset( $_GET['neve_preview_hook'] ) && 'show' === $_GET['neve_preview_hook'] ) {
			$title = __( 'Hide Hooks', 'neve' );
			$href  = remove_query_arg( 'neve_preview_hook' );
		}

		$wp_admin_bar->add_menu(
			array(
				'title'  => $title,
				'id'     => 'neve_preview_hook',
				'parent' => false,
				'href'   => $href,
			)
		);
	}

	/**
	 * Render hook placeholder.
	 */
	public function render_hook_placeholder() {
		if ( ! isset( $_GET['neve_preview_hook'] ) || 'show' !== $_GET['neve_preview_hook'] ) {
			return;
		}
		$hooks = neve_hooks();
		foreach ( $hooks as $hook_category => $hooks_in_category ) {
			foreach ( $hooks_in_category as $hook_value ) {
				$hook_label = $hook_category . '/' . $hook_value;
				add_action(
					$hook_value,
					function () use ( $hook_label ) {
						$css_style = 'width: 98%; margin: 10px auto; border: 2px dashed #e22222; font-size: 14px; padding: 6px 10px; display: block; color: #404248';
						echo '<div class="nv-hook-placeholder" style="' . esc_attr( $css_style ) . '">';
						echo $hook_label;
						echo '</div>';
					}
				);
			}
		}
	}


}
