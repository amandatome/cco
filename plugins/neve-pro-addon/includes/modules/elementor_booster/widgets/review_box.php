<?php
/**
 * Elementor Review Box Widget.
 *
 * @example https://developers.elementor.com/creating-a-new-widget/
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */

namespace Neve_Pro\Modules\Elementor_Booster\Widgets;


/**
 * Class Review_Box
 *
 * @package Neve_Pro\Modules\Elementor_Booster\Widgets
 */
class Review_Box extends \Elementor\Widget_Base {

	/**
	 * Widget slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'neve_review_box';
	}

	/**
	 * Widget Label.
	 *
	 * @return string
	 */
	public function get_title() {
		return 'Review Box';
	}

	/**
	 * Widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'fa fa-star';
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
	 * Render out the control.
	 */
	public function render() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() !== true ) {
			wp_enqueue_style( 'neve-elementor-widgets-styles' );
		}
		$settings      = $this->get_settings();
		$overall_score = 0;
		$score_count   = 0;
		if ( ! empty( $settings['scores_list'] ) ) {
			foreach ( $settings['scores_list'] as $i => $score ) {
				if ( $score['score']['size'] ) {
					$item_score     = ( (int) $score['score']['size'] );
					$overall_score += $item_score;
					$score_count ++;
				}
			}
		}
		$average        = $overall_score / $score_count;
		$score_class    = ' eaw-rated-p' . ( (int) round( $average / 10 ) * 10 );
		$rated          = $this->get_rating_type_class( $average );
		$review_classes = $score_class . ' ' . $rated;
		$display_score  = round( $average / 10, 1 );
		?>
		<div class="eaw-review-box-wrapper">
			<div class="eaw-review-box-top">
				<?php
				if ( ! empty( $settings['title'] ) ) {
					?>
					<h3 class="eaw-review-box-title"><?php echo $settings['title']; ?></h3>
					<?php
				}
				if ( ! empty( $settings['price'] ) ) {
					?>
					<h3 class="eaw-review-box-price"><?php echo $settings['price']; ?></h3>
				<?php } ?>
			</div>
			<div class="eaw-review-box-left">
				<div class="eaw-review-header">
					<?php
					if ( ! empty( $settings['image']['url'] ) ) {
						?>
						<div class="elementor-review-box-image">
							<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $settings ); ?>
						</div>
						<?php
					}
					if ( ! empty( $display_score ) ) {
						?>
						<div class="eaw-rating">
							<div class="eaw-grade-content">
								<div class="eaw-c100 <?php echo esc_attr( $review_classes ); ?>">
									<span><?php echo esc_html( $display_score ); ?></span>
									<div class="eaw-slice">
										<div class="eaw-bar"></div>
										<div class="eaw-fill"></div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
				</div><!-- /.eaw-review-header -->

				<?php if ( ! empty( $settings['scores_list'] ) ) { ?>
					<div class="eaw-review-score-list">
						<?php foreach ( $settings['scores_list'] as $i => $score ) { ?>
							<div class="eaw-score-wrapper">
								<p class="eaw-score-title"><?php echo $score['text']; ?></p>
								<?php
								if ( $score['score']['size'] ) {
									$individual_score = ( (int) $score['score']['size'] / 10 );
									?>
									<strong class="eaw-review-box-score"><?php echo $individual_score; ?></strong>
									<div class="eaw-icon-score-display">
										<div class="eaw-grey">
											<?php
											if ( empty( $score['icon'] ) ) {
												$score['icon'] = 'fa fa-star';
											}
											for ( $i = 0; $i < 10; $i ++ ) {
												?>
												<span class="<?php echo esc_attr( $score['icon'] ); ?>"></span>
											<?php } ?>
										</div>
										<div class="eaw-colored <?php echo $this->get_rating_type_class( $score['score']['size'] ); ?>"
												style="width: <?php echo $individual_score * 10; ?>%">
											<?php
											for ( $i = 0; $i < 10; $i ++ ) {
												?>
												<span class="<?php echo esc_attr( $score['icon'] ); ?>"></span>
											<?php } ?>
										</div>
									</div>
								<?php } ?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				?>
			</div><!-- /.eaw-review-box-left -->
			<div class="eaw-review-box-right">
				<?php
				if ( ! empty( $settings['pros_title'] ) ) {
					?>
					<h4><?php echo esc_html( $settings['pros_title'] ); ?></h4>
					<?php
				}
				if ( ! empty( $settings['pro_list'] ) ) {
					?>
					<ul class="elementor-review-box-pro-list">
						<?php foreach ( $settings['pro_list'] as $i => $pro ) { ?>
							<li class="elementor-repeater-item-<?php echo $pro['_id']; ?>">
								<em><?php echo $pro['text']; ?></em>
							</li>
						<?php } ?>
					</ul>
					<?php
				}

				if ( ! empty( $settings['cons_title'] ) ) {
					?>
					<h4><?php echo esc_html( $settings['cons_title'] ); ?></h4>
					<?php
				}
				if ( ! empty( $settings['cons_list'] ) ) {
					?>
					<ul class="elementor-review-box-con-list">
						<?php foreach ( $settings['cons_list'] as $i => $con ) { ?>
							<li class="elementor-repeater-item-<?php echo $con['_id']; ?>">
								<strong><?php echo $con['text']; ?></strong>
							</li>
						<?php } ?>
					</ul>
				<?php } ?>
			</div><!-- /.eaw-review-box-right -->
		</div><!-- /.eaw-review-box-wrapper -->
		<?php
	}

	/**
	 * Get the type of rating class based on score.
	 *
	 * @param int $score the score that will be passed (between 0-100).
	 *
	 * @return string the class which will be added to items.
	 */
	public function get_rating_type_class( $score ) {
		switch ( true ) {
			case $score <= 25:
				$rated = 'eaw-review-weak';
				break;
			case $score <= 50:
				$rated = 'eaw-review-not-bad';
				break;
			case $score <= 75:
				$rated = 'eaw-review-good';
				break;
			default:
				$rated = 'eaw-review-very-good';
				break;
		}

		return $rated;
	}

	/**
	 * Register Elementor Controls.
	 *
	 * Because this is just a placeholder widget, we need to output this to the Lite users.
	 */
	protected function _register_controls() {
		$this->_register_box_controls();
		$this->_register_pro_controls();
		$this->_register_cons_controls();
		$this->_register_scores_controls();
	}

	/**
	 * Register Box main controls.
	 */
	protected function _register_box_controls() {

		$this->start_controls_section(
			'section_box_settings',
			array(
				'label'      => __( 'Box Settings', 'neve' ),
				'tab'        => \Elementor\Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'neve' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Review Box', 'neve' ),
			)
		);

		$this->add_control(
			'price',
			array(
				'label'   => __( 'Price', 'neve' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '100$',
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => __( 'Choose Image', 'neve' ),
				'type'    => \Elementor\Controls_Manager::MEDIA,
				'default' => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Box Pros controls.
	 */
	protected function _register_pro_controls() {

		$this->start_controls_section(
			'section_pros',
			array(
				'label'      => __( 'Pros', 'neve' ),
				'tab'        => \Elementor\Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'pros_title',
			array(
				'label'   => __( 'Title', 'neve' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Pros', 'neve' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'Label', 'neve' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Pro', 'neve' ),
			)
		);

		$this->add_control(
			'pro_list',
			array(
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'text' => __( 'Pro #1', 'neve' ),
					),
					array(
						'text' => __( 'Pro #2', 'neve' ),
					),
					array(
						'text' => __( 'Pro #3', 'neve' ),
					),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Box Cons controls.
	 */
	protected function _register_cons_controls() {
		$this->start_controls_section(
			'section_cons',
			array(
				'label'      => __( 'Cons', 'neve' ),
				'tab'        => \Elementor\Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$this->add_control(
			'cons_title',
			array(
				'label'   => __( 'Title', 'neve' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Cons', 'neve' ),
			)
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'Text', 'neve' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'List con', 'neve' ),
			)
		);

		$this->add_control(
			'cons_list',
			array(
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'text' => __( 'Con #1', 'neve' ),
						'icon' => 'fa fa-close',
					),
					array(
						'text' => __( 'Con #2', 'neve' ),
						'icon' => 'fa fa-close',
					),
					array(
						'text' => __( 'Con #3', 'neve' ),
						'icon' => 'fa fa-close',
					),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Register Box Scores controls.
	 */
	protected function _register_scores_controls() {
		$this->start_controls_section(
			'section_scores',
			array(
				'label'      => __( 'Scores', 'neve' ),
				'tab'        => \Elementor\Controls_Manager::TAB_CONTENT,
				'show_label' => false,
			)
		);

		$repeater = new \Elementor\Repeater();
		$repeater->add_control(
			'text',
			array(
				'label' => __( 'Score Label', 'neve' ),
				'type'  => \Elementor\Controls_Manager::TEXT,
			)
		);
		$repeater->add_control(
			'score',
			array(
				'label' => __( 'Score', 'neve' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 100,
					),
				),
			)
		);
		$repeater->add_control(
			'icon',
			array(
				'label' => __( 'Icon', 'neve' ),
				'type'  => \Elementor\Controls_Manager::ICON,
			)
		);

		$this->add_control(
			'scores_list',
			array(
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'default'     => array(
					array(
						'text'  => __( 'Our Rating', 'neve' ),
						'score' => array(
							'size' => '95',
							'unit' => 'px',
						),
						'color' => 'fa fa-star',
					),
					array(
						'text'  => __( 'User Rating', 'neve' ),
						'score' => array(
							'size' => '87',
							'unit' => 'px',
						),
						'icon'  => 'fa fa-star',
					),
					array(
						'text'  => __( 'Product Quality', 'neve' ),
						'score' => array(
							'size' => '65',
							'unit' => 'px',
						),
						'icon'  => 'fa fa-star',
					),
				),
				'title_field' => '{{{ text }}}',
			)
		);

		$this->end_controls_section();
	}
}
