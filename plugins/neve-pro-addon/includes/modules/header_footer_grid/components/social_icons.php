<?php
/**
 * Button Component class for Header Footer Grid.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Abstract_Component;
use HFG\Core\Settings\Manager as SettingsManager;
use HFG\Main;
use Neve_Pro\Traits\Core;

/**
 * Class Social_Icons
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Social_Icons extends Abstract_Component {

	use Core;

	const COMPONENT_ID  = 'social_icons';
	const REPEATER_ID   = 'content_setting';
	const NEW_TAB       = 'new_tab';
	const ICON_SIZE     = 'icon_size';
	const ICON_SPACING  = 'icon_spacing';
	const ICON_PADDING  = 'icon_padding';
	const BORDER_RADIUS = 'border_radius';
	/**
	 * Repeater defaults
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var array
	 */
	private $repeater_default = array(
		array(
			'title'            => 'Facebook',
			'url'              => '#',
			'icon'             => 'facebook',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#3b5998',
		),
		array(
			'title'            => 'Twitter',
			'url'              => '#',
			'icon'             => 'twitter',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#1da1f2',
		),
		array(
			'title'            => 'Youtube',
			'url'              => '#',
			'icon'             => 'youtube-play',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#cd201f',
		),
		array(
			'title'            => 'Instagram',
			'url'              => '#',
			'icon'             => 'instagram',
			'visibility'       => 'yes',
			'icon_color'       => '#fff',
			'background_color' => '#e1306c',
		),
	);

	/**
	 * Button constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Social Icons', 'neve' ) );
		$this->set_property( 'id', self::COMPONENT_ID );
		$this->set_property( 'width', 4 );
		$this->set_property( 'section', 'social_icons' );
	}

	/**
	 * Called to register component controls.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_settings() {
		SettingsManager::get_instance()->add(
			array(
				'id'                => self::REPEATER_ID,
				'group'             => self::COMPONENT_ID,
				'tab'               => SettingsManager::TAB_GENERAL,
				'transport'         => 'post' . self::COMPONENT_ID,
				'sanitize_callback' => array( $this, 'sanitize_social_icons_repeater' ),
				'default'           => json_encode( $this->repeater_default ),
				'label'             => __( 'Social Icons', 'neve' ),
				'type'              => 'Neve_Pro\Customizer\Controls\Repeater',
				'options'           => array(
					'type'   => 'neve-repeater',
					'fields' => array(
						'title'            => array(
							'type'  => 'text',
							'label' => 'Title',
						),
						'icon'             => array(
							'type'  => 'icon',
							'label' => 'Icon',
						),
						'url'              => array(
							'type'  => 'text',
							'label' => 'Link',
						),
						'icon_color'       => array(
							'type'  => 'color',
							'label' => 'Icon Color',
						),
						'background_color' => array(
							'type'  => 'color',
							'label' => 'Background Color',
						),
					),
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::NEW_TAB,
				'group'             => $this->get_id(),
				'tab'               => SettingsManager::TAB_GENERAL,
				'transport'         => 'post' . $this->get_id(),
				'sanitize_callback' => 'absint',
				'default'           => 0,
				'label'             => __( 'Open in new tab', 'neve' ),
				'type'              => '\Neve\Customizer\Controls\Checkbox',
				'options'           => array(
					'type' => 'checkbox-toggle',
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::ICON_SIZE,
				'group'             => self::COMPONENT_ID,
				'tab'               => SettingsManager::TAB_STYLE,
				'transport'         => 'post' . self::COMPONENT_ID,
				'sanitize_callback' => 'absint',
				'default'           => 18,
				'label'             => __( 'Icon Size', 'neve' ),
				'type'              => '\Neve\Customizer\Controls\Range',
				'options'           => array(
					'type'        => 'range-value',
					'media_query' => false,
					'input_attr'  => array(
						'step'    => 1,
						'min'     => 10,
						'max'     => 40,
						'default' => 18,
					),
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::ICON_SPACING,
				'group'             => self::COMPONENT_ID,
				'tab'               => SettingsManager::TAB_STYLE,
				'transport'         => 'post' . self::COMPONENT_ID,
				'sanitize_callback' => 'absint',
				'default'           => 10,
				'label'             => __( 'Icon Spacing', 'neve' ),
				'type'              => '\Neve\Customizer\Controls\Range',
				'options'           => array(
					'type'        => 'range-value',
					'media_query' => false,
					'input_attr'  => array(
						'step'    => 1,
						'min'     => 0,
						'max'     => 100,
						'default' => 10,
					),
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::BORDER_RADIUS,
				'group'             => self::COMPONENT_ID,
				'tab'               => SettingsManager::TAB_STYLE,
				'transport'         => 'post' . self::COMPONENT_ID,
				'sanitize_callback' => 'absint',
				'default'           => 5,
				'label'             => __( 'Border Radius (px)', 'neve' ),
				'type'              => '\Neve\Customizer\Controls\Range',
				'options'           => array(
					'type'        => 'range-value',
					'media_query' => false,
					'input_attr'  => array(
						'step'    => 1,
						'min'     => 0,
						'max'     => 50,
						'default' => 5,
					),
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::ICON_PADDING,
				'group'             => $this->get_id(),
				'tab'               => SettingsManager::TAB_STYLE,
				'transport'         => 'post' . $this->get_id(),
				'sanitize_callback' => array( $this, 'sanitize_spacing_array' ),
				'default'           => array(
					'desktop'      => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'tablet'       => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'mobile'       => array(
						'top'    => '',
						'right'  => '',
						'bottom' => '',
						'left'   => '',
					),
					'desktop-unit' => 'px',
					'tablet-unit'  => 'px',
					'mobile-unit'  => 'px',
				),
				'label'             => __( 'Icon Padding', 'neve' ),
				'type'              => class_exists( '\Neve\Customizer\Controls\React\Spacing', false ) ? '\Neve\Customizer\Controls\React\Spacing' : 'neve_spacing',
				'section'           => $this->section,
			)
		);
	}

	/**
	 * Method to add Component css styles.
	 *
	 * @param array $css_array An array containing css rules.
	 *
	 * @return array
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_style( array $css_array = array() ) {
		$this->default_selector = '.builder-item--' . $this->get_id() . ' .nv-social-icons-list';

		$icon_spacing  = get_theme_mod( $this->id . '_' . self::ICON_SPACING, 10 );
		$border_radius = get_theme_mod( $this->id . '_' . self::BORDER_RADIUS, 5 );
		$icon_padding  = get_theme_mod( $this->id . '_' . self::ICON_PADDING, '' );
		$icon_selector = '.nv-social-icons-list li a';

		$css_array['ul.nv-social-icons-list > li:not(:first-child)'] = array( 'margin-left' => $icon_spacing . 'px' );
		$css_array[ $icon_selector ]                                 = array( 'border-radius' => $border_radius . 'px' );

		if ( isset( $icon_padding['mobile'] ) ) {
			$css_array[' @media (max-width: 576px)'][ $icon_selector ]['padding'] =
				$icon_padding['mobile']['top'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['right'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['bottom'] . $icon_padding['mobile-unit'] . ' ' .
				$icon_padding['mobile']['left'] . $icon_padding['mobile-unit'];
		}
		if ( isset( $icon_padding['tablet'] ) ) {
			$css_array[' @media (min-width: 576px)'][ $icon_selector ]['padding'] =
				$icon_padding['tablet']['top'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['right'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['bottom'] . $icon_padding['tablet-unit'] . ' ' .
				$icon_padding['tablet']['left'] . $icon_padding['tablet-unit'];
		}
		if ( isset( $icon_padding['desktop'] ) ) {
			$css_array[' @media (min-width: 961px)'][ $icon_selector ]['padding'] =
				$icon_padding['desktop']['top'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['right'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['bottom'] . $icon_padding['desktop-unit'] . ' ' .
				$icon_padding['desktop']['left'] . $icon_padding['desktop-unit'];
		}

		return parent::add_style( $css_array );
	}

	/**
	 * Sanitize repeater values.
	 *
	 * @param string $value repeater json value.
	 *
	 * @return string
	 */
	public function sanitize_social_icons_repeater( $value ) {
		$fields = array(
			'title',
			'url',
			'icon',
			'visibility',
			'icon_color',
			'background_color',
		);
		$valid  = $this->sanitize_repeater_json( $value, $fields );

		if ( $valid === false ) {
			return json_encode( $this->repeater_default );
		}

		return $value;
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'component-social-icons' );
	}
}
