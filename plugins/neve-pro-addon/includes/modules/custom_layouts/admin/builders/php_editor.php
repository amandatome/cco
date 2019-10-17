<?php
/**
 * Php Editor to add custom code;
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */

namespace Neve_Pro\Modules\Custom_Layouts\Admin\Builders;

use Neve_Pro\Traits\Core;

/**
 * Class Php_Editor
 *
 * @package Neve_Pro\Modules\Custom_Layouts\Admin\Builders
 */
class Php_Editor extends Abstract_Builders {
	use Core;

	/**
	 * Add class on body to know that the current page is edited with this custom editor
	 *
	 * @param string $classes Body classes.
	 *
	 * @return string
	 */
	public function custom_editor_body_class( $classes ) {
		global $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return $classes;
		}
		global $post;
		if ( $this->get_editor_mode( $post->ID ) === '1' ) {
			return $classes . ' neve-custom-editor-mode';
		}

		return $classes;
	}

	/**
	 * Check if current post is edited with Neve custom editor or not.
	 *
	 * @param int $post_id Post id.
	 *
	 * @return mixed|string
	 */
	private function get_editor_mode( $post_id ) {
		$editor_value = get_post_meta( $post_id, 'neve_editor_mode', true );
		if ( empty( $editor_value ) ) {
			return '0';
		}

		return $editor_value;
	}

	/**
	 * Add templates for switch button and for editor.
	 */
	public function print_admin_js_template() {
		global $pagenow;
		if ( ! in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}
		global $post;
		$value = $this->get_editor_mode( $post->ID );
		echo '<script id="neve-gutenberg-button-switch-mode" type="text/html">';
		echo '<div id="neve-editor-mode">';
		echo '<input id="neve-switch-editor-mode" type="hidden" name="neve-edit-mode" value="' . esc_attr( $value ) . '" />';
		echo '<button id="neve-switch-mode-button" type="button" class="button ' . ( $value === '0' ? 'button-primary' : '' ) . ' button-hero">';
		echo '<span class="neve-switch-mode-on ' . ( $value === '0' ? 'hidden' : '' ) . '">';
		echo __( 'Back to WordPress Editor', 'neve-pro-addon' );
		echo '</span>';
		echo '<span class="neve-switch-mode-off ' . ( $value === '0' ? '' : 'hidden' ) . '">';
		echo __( 'Edit with Neve Custom Code', 'neve-pro-addon' );
		echo '</span>';
		echo '</button>';
		echo '</div>';
		echo '</script>';

		$file_name     = get_post_meta( $post->ID, 'neve_editor_content', true );
		$wp_upload_dir = wp_upload_dir( null, false );
		$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
		$file_path     = $upload_dir . $file_name . '.php';
		global $wp_filesystem;
		WP_Filesystem();
		$value = $wp_filesystem->get_contents( $file_path );

		if ( empty( $value ) ) {
			$value = '<!-- Add your PHP or HTML code here -->&#13;&#10;';
		}
		echo '<script id="neve-gutenberg-panel" type="text/html">';
		echo '<div id="neve-editor">';
		echo '<textarea id="neve-advanced-hook-php-code" name="neve-advanced-hook-php-code" class="wp-editor-area">' . $value . '</textarea>';
		echo '</div>';
		echo '</script>';
	}

	/**
	 * Check if class should load or not.
	 *
	 * @return bool
	 */
	function should_load() {
		return true;
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
			if ( $editor !== 'custom' ) {
				continue;
			}

			add_action(
				$hook,
				function () use ( $post_id ) {
					$file_name = get_post_meta( $post_id, 'neve_editor_content', true );
					if ( empty( $file_name ) ) {
						return;
					}
					$wp_upload_dir = wp_upload_dir( null, false );
					$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
					$file_path     = $upload_dir . $file_name . '.php';
					if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
						include_once( $file_path );
					}
				},
				$priority
			);

		}
	}

	/**
	 * Function that enqueues styles if needed.
	 */
	function add_styles() {
		return;
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
		if ( $editor !== 'custom' ) {
			return;
		}
		remove_all_actions( 'neve_do_header' );
		remove_all_actions( 'neve_do_top_bar' );
		$file_name     = get_post_meta( $post_id, 'neve_editor_content', true );
		$wp_upload_dir = wp_upload_dir( null, false );
		$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
		$file_path     = $upload_dir . $file_name . '.php';
		include_once( $file_path );
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
		if ( $editor !== 'custom' ) {
			return;
		}
		remove_all_actions( 'neve_do_footer' );
		$file_name     = get_post_meta( $post_id, 'neve_editor_content', true );
		$wp_upload_dir = wp_upload_dir( null, false );
		$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
		$file_path     = $upload_dir . $file_name . '.php';
		if ( file_exists( $file_path ) && is_readable( $file_path ) ) {
			include_once( $file_path );
		}
	}

	/**
	 * Remove template files when the post is deleted.
	 *
	 * @param int $post_id Post id.
	 */
	public function clean_template_files( $post_id ) {
		global $post_type;
		global $wp_filesystem;
		if ( $post_type !== 'neve_custom_layouts' ) {
			return;
		}

		$file_name     = get_post_meta( $post_id, 'neve_editor_content', true );
		$wp_upload_dir = wp_upload_dir( null, false );
		$upload_dir    = $wp_upload_dir['basedir'] . '/neve-theme/';
		$file_path     = $upload_dir . $file_name . '.php';

		WP_Filesystem();
		$wp_filesystem->delete( $file_path, false, 'f' );
	}

	/**
	 * Builder id.
	 *
	 * @return string
	 */
	function get_builder_id() {
		return 'custom';
	}

	/**
	 * Init function.
	 */
	protected function run_hooks() {
		if ( ! current_user_can( 'administrator' ) ) {
			return;
		}
		add_action( 'admin_footer', array( $this, 'print_admin_js_template' ) );
		add_filter( 'admin_body_class', array( $this, 'custom_editor_body_class' ), 999 );
		add_action( 'before_delete_post', array( $this, 'clean_template_files' ) );
		parent::run_hooks();
	}


}
