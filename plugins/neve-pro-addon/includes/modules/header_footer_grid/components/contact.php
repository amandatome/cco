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

/**
 * Class Contact
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Contact extends Abstract_Component {
	const COMPONENT_ID  = 'contact';
	const REPEATER_ID   = 'content_setting';
	const ICON_POSITION = 'icon_position';
	const ITEM_SPACING  = 'item_spacing';
	/**
	 * Repeater defaults
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var array
	 */
	private $repeater_default = array(
		array(
			'title'      => 'email@example.com',
			'icon'       => 'envelope',
			'item_type'  => 'email',
			'visibility' => 'yes',
		),
		array(
			'title'      => '202-555-0191',
			'icon'       => 'phone',
			'item_type'  => 'phone',
			'visibility' => 'yes',
		),
		array(
			'title'      => '499 Pirate Island Plaza',
			'icon'       => 'map-marker',
			'item_type'  => 'text',
			'visibility' => 'yes',
		),
	);

	/**
	 * Button constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Contact', 'neve' ) );
		$this->set_property( 'id', self::COMPONENT_ID );
		$this->set_property( 'width', 6 );
		$this->set_property( 'section', 'contact' );
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
				'sanitize_callback' => 'sanitize_text_field',
				'default'           => json_encode( $this->repeater_default ),
				'label'             => __( 'Content', 'neve' ),
				'type'              => 'Neve_Pro\Customizer\Controls\Repeater',
				'options'           => array(
					'type'   => 'neve-repeater',
					'fields' => array(
						'title'     => array(
							'type'  => 'text',
							'label' => __( 'Text', 'neve' ),
						),
						'icon'      => array(
							'type'  => 'icon',
							'label' => __( 'Icon', 'neve' ),
						),
						'item_type' => array(
							'type'    => 'select',
							'label'   => __( 'Type', 'neve' ),
							'choices' => array(
								'text'  => __( 'Text', 'neve' ),
								'email' => __( 'Email', 'neve' ),
								'phone' => __( 'Phone', 'neve' ),
							),
						),
					),
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::ICON_POSITION,
				'group'             => self::COMPONENT_ID,
				'tab'               => SettingsManager::TAB_STYLE,
				'transport'         => 'post' . self::COMPONENT_ID,
				'sanitize_callback' => array( $this, 'sanitize_icon_position' ),
				'default'           => 'left',
				'label'             => __( 'Icon Position', 'neve' ),
				'type'              => 'select',
				'options'           => array(
					'choices' => array(
						'left'  => __( 'Left', 'neve' ),
						'right' => __( 'Right', 'neve' ),
					),
				),
				'section'           => $this->section,
			)
		);

		SettingsManager::get_instance()->add(
			array(
				'id'                => self::ITEM_SPACING,
				'group'             => self::COMPONENT_ID,
				'tab'               => SettingsManager::TAB_STYLE,
				'transport'         => 'post' . self::COMPONENT_ID,
				'sanitize_callback' => 'absint',
				'default'           => 10,
				'label'             => __( 'Item Spacing (px)', 'neve' ),
				'type'              => '\Neve\Customizer\Controls\Range',
				'options'           => array(
					'type'        => 'range-value',
					'media_query' => false,
					'input_attr'  => array(
						'step'    => 1,
						'min'     => 0,
						'max'     => 50,
						'default' => 10,
					),
				),
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
		$this->default_selector = '.builder-item--' . $this->get_id() . ' > .component-wrap > :first-child';

		$icon_spacing = get_theme_mod( $this->id . '_' . self::ITEM_SPACING, 10 );

		$css_array['.nv-contact-list li:not(:first-child)'] = array( 'margin-left' => $icon_spacing . 'px' );

		return parent::add_style( $css_array );
	}

	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'component-contact' );
	}

	/**
	 * Sanitize the icon position value.
	 *
	 * @param string $value icon position value.
	 *
	 * @return string
	 */
	public function sanitize_icon_position( $value ) {
		if ( ! in_array( $value, array( 'left', 'right' ), true ) ) {
			return 'left';
		}

		return $value;
	}
}
