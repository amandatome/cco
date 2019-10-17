<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-02-11
 *
 * @package Neve Pro Addon
 */

namespace Neve_Pro\Modules\Woocommerce_Booster\Views;

use Neve\Views\Base_View;

/**
 * Class Single_Product
 *
 * @package Neve_Pro\Modules\Woocommerce_Booster\Views
 */
class Single_Product extends Base_View {

	/**
	 * Content ordering mapping with priority and hooked function.
	 *
	 * @var array
	 */
	protected $mapping = array(
		'title'       => array(
			'initial' => 5,
			'method'  => 'woocommerce_template_single_title',
		),
		'reviews'     => array(
			'initial' => 10,
			'method'  => 'woocommerce_template_single_rating',
		),
		'price'       => array(
			'initial' => 10,
			'method'  => 'woocommerce_template_single_price',
		),
		'description' => array(
			'initial' => 20,
			'method'  => 'woocommerce_template_single_excerpt',
		),
		'add_to_cart' => array(
			'initial' => 30,
			'method'  => 'woocommerce_template_single_add_to_cart',
		),
		'meta'        => array(
			'initial' => 40,
			'method'  => 'woocommerce_template_single_meta',
		),
	);

	/**
	 * Default content ordering.
	 *
	 * @var array
	 */
	protected $default_order = array(
		'title',
		'price',
		'description',
		'add_to_cart',
		'meta',
	);

	/**
	 * Check if submodule should be loaded.
	 *
	 * @return bool
	 */
	private function should_load() {
		if ( ! class_exists( 'Woocommerce' ) ) {
			return false;
		}

		$post_id = get_the_ID();
		if ( ! $post_id || ! in_array( get_post_type( $post_id ), array( 'product', 'product_variation' ), true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Initialize the module.
	 */
	public function init() {
		add_action( 'wp', array( $this, 'run' ), 10 );
	}

	/**
	 * Register submodule hooks
	 */
	public function register_hooks() {
		$this->init();
	}

	/**
	 * Run the module.
	 */
	public function run() {
		if ( ! $this->should_load() ) {
			return;
		}

		$this->remove_elements();
		$this->reorder_elements();
		$this->image_zoom_effect();
		$this->breadcrumbs();
		$this->tabs();
		$this->related_products();
		$this->upsells();
		$this->recently_viewed();
		$this->add_gallery_classes();
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_number' ), 20 );
	}

	/**
	 * Add the recently viewed products box.
	 */
	private function recently_viewed() {
		$status = get_theme_mod( 'neve_enable_related_viewed', false );

		if ( $status !== true || neve_is_amp() ) {
			return;
		}
		add_action(
			'woocommerce_after_single_product_summary',
			function () {
				the_widget(
					'WC_Widget_Recently_Viewed',
					array(
						'number' => 6,
						'title'  => __( 'Recently Viewed', 'neve' ),
					),
					array(
						'before_widget' => '<div class="nv-recently-viewed expanded">',
						'before_title'  => '<span class="close"></span><h5 class="title">',
						'after_widget'  => '</div>',
						'after_title'   => '</h5>',
						'widget_id'     => 'neve_related_widget_sp',
					)
				);
			}
		);
	}

	/**
	 * Add gallery classes
	 */
	public function add_gallery_classes() {
		$gallery_layout = get_theme_mod( 'neve_single_product_gallery_layout', 'normal' );
		$gallery_slider = get_theme_mod( 'neve_enable_product_gallery_thumbnails_slider', false );
		$new_classes    = array();
		if ( $gallery_layout === 'left' ) {
			$new_classes[] = 'nv-left-gallery';
		}
		if ( $gallery_slider === true ) {
			$new_classes[] = 'nv-slider-gallery';
		}

		add_filter(
			'body_class',
			function ( $classes ) use ( $new_classes ) {
				return array_merge( $classes, $new_classes );
			}
		);
	}

	/**
	 * Remove the elements from single product.
	 */
	private function remove_elements() {
		array_walk(
			$this->mapping,
			function ( $args ) {
				remove_action( 'woocommerce_single_product_summary', $args['method'], $args['initial'] );
			}
		);
	}

	/**
	 * Reorder the elements on single product.
	 */
	private function reorder_elements() {
		$order = get_theme_mod( 'neve_single_product_elements_order', json_encode( $this->default_order ) );

		$order = json_decode( $order );

		if ( ! is_array( $order ) ) {
			$order = $this->default_order;
		}

		array_walk(
			$order,
			function ( $value, $index ) {
				add_action( 'woocommerce_single_product_summary', $this->mapping[ $value ]['method'], $index );
			}
		);
	}

	/**
	 * Toggle breadcrumbs
	 */
	private function breadcrumbs() {
		$enable_crumbs = get_theme_mod( 'neve_enable_product_breadcrumbs', true );

		if ( $enable_crumbs === true ) {
			return;
		}

		remove_all_actions( 'neve_before_shop_loop_content' );
	}

	/**
	 * Toggle tabs
	 */
	private function tabs() {
		$enable_tabs = get_theme_mod( 'neve_enable_product_tabs', true );

		if ( $enable_tabs === true ) {
			return;
		}

		add_filter( 'woocommerce_product_tabs', '__return_empty_array', PHP_INT_MAX );
		add_filter(
			'post_class',
			function ( $classes, $class = '', $post_id = 0 ) {
				$classes[] = 'nv-tabless-product';

				return $classes;
			},
			20,
			3
		);
		add_action(
			'woocommerce_after_single_product_summary',
			function () {
				echo '<div class="nv-related-clearfix"></div>';
			},
			5
		);
	}

	/**
	 * Toggle image zoom effect
	 */
	private function image_zoom_effect() {
		$enable_zoom = get_theme_mod( 'neve_enable_product_image_zoom_effect', true );

		if ( $enable_zoom === true ) {
			return;
		}

		remove_theme_support( 'wc-product-gallery-zoom' );
	}

	/**
	 * Toggle related products
	 */
	private function related_products() {
		$enable_related_prods = get_theme_mod( 'neve_enable_product_related', true );

		if ( $enable_related_prods === true ) {
			return;
		}
		add_filter( 'woocommerce_related_products', '__return_empty_array' );
	}

	/**
	 * Toggle upsells.
	 */
	private function upsells() {
		$enable_upsells = get_theme_mod( 'neve_enable_product_upsells', true );

		if ( $enable_upsells === true ) {
			return;
		}
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	}

	/**
	 * Change related products number.
	 *
	 * @param array $args query parameters for related products.
	 *
	 * @return mixed
	 */
	public function related_products_number( $args ) {
		$related_count = get_theme_mod( 'neve_single_product_related_count', 4 );

		if ( empty( $related_count ) ) {
			return $args;
		}

		$args['posts_per_page'] = absint( $related_count );

		return $args;
	}
}
