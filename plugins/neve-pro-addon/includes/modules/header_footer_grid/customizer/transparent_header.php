<?php
/**
 * Customizer Class for Header Footer Grid.
 *
 * Name:    Header Footer Grid Addon
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Customizer;

use Neve\Customizer\Base_Customizer;
use Neve\Customizer\Types\Control;

/**
 * Class Header_Footer_Grid
 *
 * @package Neve_Pro\Customizer\Options
 */
class Transparent_Header extends Base_Customizer {

	/**
	 * Function that should be extended to add customizer controls.
	 *
	 * @return void
	 */
	public function add_controls() {
		add_filter( 'hfg_header_neve_panel_settings', array( $this, 'render_setting' ) );
		$this->transparent_header_options();
	}

	/**
	 * Render toggle inside panel.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param string $panel_settings The content for the panel setting area.
	 *
	 * @return string
	 */
	public function render_setting( $panel_settings ) {
		$checked = '';
		if ( get_theme_mod( 'neve_transparent_header', false ) ) {
			$checked = 'checked';
		}
		$panel_settings .= '
		<script type="application/javascript">
			( function( $, wpcustomize) {
				wpcustomize.bind(\'ready\', function(){
				    $("#neve_transparent_header").on( \'change\', function() {
				    	var curent_val = wpcustomize.control(\'neve_transparent_header\').setting.get()
				      	wpcustomize.control(\'neve_transparent_header\').setting.set( ! curent_val );
				    } )
				});
			})( jQuery, wp.customize);
		</script>
		<span id="customize-control-neve_transparent_header_custom" class="customize-control-checkbox-toggle">
			<div class="checkbox-toggle-wrap">
				<span>Enable Transparent Header</span>
				<input data-customize-setting-link="neve_transparent_header" type="checkbox" id="neve_transparent_header" ' . $checked . '><label for="neve_transparent_header"></label>
			</div>
		</span>';
		return $panel_settings;
	}

	/**
	 * Add the transparent header control.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function transparent_header_options() {
		$this->add_control(
			new Control(
				'neve_transparent_header',
				array(
					'transport'         => 'refresh',
					'sanitize_callback' => 'neve_sanitize_checkbox',
					'default'           => false,
				),
				array(
					'label'    => esc_html__( 'Enable Transparent Header', 'neve' ),
					'section'  => 'hfg_header_layout_section',
					'type'     => 'checkbox-toggle',
					'priority' => 10,
				),
				'Neve\Customizer\Controls\Checkbox'
			)
		);
	}

	/**
	 * Add classes to header wrapper.
	 *
	 * @since   1.0.0
	 * @access  public
	 * @param string $classes The classes for the wrapper.
	 *
	 * @return string
	 */
	public function add_class_to_header_wrapper( $classes ) {

		if ( is_front_page() && get_option( 'show_on_front' ) === 'page' && // check that page is front page but not blog
			get_theme_mod( 'neve_transparent_header', false )
		) {
			$classes .= ' neve-transparent-header';
		}
		return $classes;
	}
}
