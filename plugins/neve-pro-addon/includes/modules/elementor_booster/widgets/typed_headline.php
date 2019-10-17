<?php
/**
 * Elementor Typed Headline Widget.
 *
 * @example https://developers.elementor.com/creating-a-new-widget/
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;


/**
 * Class Typed_Headline
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */
class Typed_Headline extends \Elementor\Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_typed_headline';
	}

	/**
	 * Widget Label.
	 *
	 * @return string
	 */
	public function get_title() {
		return 'Typed Headline';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-h-square';
	}

	/**
	 * Set the category of the widget.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'neve-elementor-widgets' );
	}

	/**
	 * The render function.
	 */
	public function render() {
		$settings = $this->get_settings();
		$tag      = $settings['tag'];

		wp_enqueue_script( 'eaw-pro-scripts' );

		$this->add_render_attribute( 'typed', 'class', 'eaw-typed-text' );
		$this->add_render_attribute( 'speed', 'class', 'eaw-speed' );

		?>
		<<?php echo $tag . ' '; ?><?php echo $this->get_render_attribute_string( 'typed' ); ?>>
		<?php if ( ! empty( $settings['before_text'] ) ) : ?>
			<span class="eaw-typed-text-plain eaw-typed-text-wrapper"><?php echo $settings['before_text']; ?></span>
		<?php endif; ?>

		<?php if ( ! empty( $settings['typed_text'] ) ) : ?>
			<span class="eaw-typed-text-placeholder"></span>
		<?php endif; ?>

		<?php if ( ! empty( $settings['after_text'] ) ) : ?>
			<span class="eaw-typed-text-plain eaw-typed-text-wrapper"><?php echo $settings['after_text']; ?></span>
		<?php endif; ?>
		</<?php echo $tag; ?>>
		<?php
	}

	/**
	 * Register Elementor Controls.
	 *
	 * Because this is just a placeholder widget, we need to output this to the Lite users.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_title',
			array(
				'label' => esc_html__( 'Settings', 'neve' ),
			)
		);

		$this->add_control(
			'before_text',
			array(
				'label'       => __( 'Before', 'neve' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'This is an', 'neve' ),
				'placeholder' => __( 'Before Typed Text', 'neve' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'typed_text',
			array(
				'label'              => __( 'Typed Text', 'neve' ),
				'type'               => \Elementor\Controls_Manager::TEXTAREA,
				'placeholder'        => __( 'Enter each word in a separate line', 'neve' ),
				'default'            => "Awesome\nEngaging\n",
				'rows'               => 5,
				'frontend_available' => true,
			)
		);

		$this->add_control(
			'after_text',
			array(
				'label'       => __( 'After', 'neve' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => __( 'After Typed Text', 'neve' ),
				'default'     => __( 'Typed Text', 'neve' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'   => __( 'HTML Tag', 'neve' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				),
				'default' => 'h3',
			)
		);

		$this->end_controls_section(); // end section-title

		$this->start_controls_section(
			'section_style',
			array(
				'label' => __( 'Headline', 'neve' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'Color', 'neve' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .eaw-typed-text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .eaw-typed-text',
				'scheme'   => \Elementor\Scheme_Typography::TYPOGRAPHY_1,
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'       => __( 'Alignment', 'neve' ),
				'type'        => \Elementor\Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'neve' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'neve' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'neve' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'     => 'center',
				'separator'   => 'before',
				'selectors'   => array(
					'{{WRAPPER}} .eaw-typed-text' => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'              => __( 'Typing Duration', 'neve' ),
				'type'               => \Elementor\Controls_Manager::SLIDER,
				'range'              => array(
					'px' => array(
						'min'  => 10,
						'max'  => 500,
						'step' => 10,
					),
				),
				'default'            => array(
					'size' => 110,
				),
				'frontend_available' => true,
			)
		);

		$this->end_controls_section();
	}
}
