<?php
/**
 * Author:          Andrei Baicus <andrei@themeisle.com>
 * Created on:      2019-06-04
 *
 * @package starter_sites.php
 */

namespace Neve_Pro\Admin;


use Neve_Pro\Traits\Core;

/**
 * Class Starter_Sites
 *
 * @package Neve_Pro\Admin
 */
class Starter_Sites {
	use Core;

	/**
	 * Initialize the starter sites class.
	 */
	public function init() {
		add_filter( 'neve_filter_onboarding_data', array( $this, 'add_starter_sites' ) );
	}

	/**
	 * Add the starter sites.
	 *
	 * @param array $starter_sites starter sites array.
	 *
	 * @return mixed
	 */
	public function add_starter_sites( $starter_sites ) {
		if ( $this->get_license_type() < 2 ) {
			return $starter_sites;
		}

		unset( $starter_sites['upsell'] );

		if ( ! isset( $starter_sites['remote'] ) ) {
			$starter_sites['remote'] = array();
		}

		$remote_starter_sites = array(
			'beaver builder' => array(
				'neve-beaver-cafe'          => array(
					'url'         => 'https://demo.themeisle.com/neve-cafe-bb/',
					'remote_json' => 'https://s20206.pcdn.co/neve-cafe-bb/',
					'screenshot'  => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/06/neve-caffe-new-ss.jpg',
					'title'       => 'Coffee Shop',
				),
				'neve-beaver-constructions' => array(
					'url'         => 'https://demo.themeisle.com/neve-constructions-bb/',
					'remote_json' => 'https://s20206.pcdn.co/neve-constructions-bb/',
					'screenshot'  => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/06/neve-construction-new-screenshot.jpg',
					'title'       => 'Constructions Company',
				),
				'neve-beaver-fashion'       => array(
					'url'         => 'https://demo.themeisle.com/neve-bb-fashion/',
					'remote_json' => 'https://s20206.pcdn.co/neve-bb-fashion/',
					'screenshot'  => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/07/neve-fashion-demo-screenshot.png',
					'title'       => 'Fashion',
				),
				'neve-beaver-consultants'   => array(
					'url'         => 'https://demo.themeisle.com/neve-consultants-bb/',
					'remote_json' => 'https://s20206.pcdn.co/neve-consultants-bb/',
					'screenshot'  => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/09/neve-business-consulting.jpg',
					'title'       => 'Business Consulting',
				),
			),
			'elementor'      => array(
				'neve-cafe'            => array(
					'url'         => 'https://demo.themeisle.com/neve-cafe/',
					'remote_json' => 'https://s20206.pcdn.co/neve-cafe/',
					'screenshot'  => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/06/neve-caffe-new-ss.jpg',
					'title'       => 'Coffe Shop',
				),
				'neve-constructions'   => array(
					'url'              => 'https://demo.themeisle.com/neve-constructions/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-constructions/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/06/neve-construction-new-screenshot.jpg',
					'title'            => 'Constructions Company',
					'unsplash_gallery' => 'https://unsplash.com/collections/4847783/construction',
				),
				'neve-fashion'         => array(
					'url'              => 'https://demo.themeisle.com/neve-fashion/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-fashion/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/07/neve-fashion-demo-screenshot.png',
					'title'            => 'Fashion',
					'unsplash_gallery' => 'https://unsplash.com/collections/4926520/fashion',
				),
				'neve-showcase'        => array(
					'url'              => 'https://demo.themeisle.com/neve-showcase/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-showcase/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/06/neve-showcase-demo-screenshot-big.png',
					'title'            => 'Showcase',
					'unsplash_gallery' => 'https://unsplash.com/collections/4587690/showcase/ed70b13c3a6d1219334f193873aaad61',
				),
				'neve-consultants'     => array(
					'url'              => 'https://demo.themeisle.com/neve-consultants/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-consultants/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/09/neve-business-consulting.jpg',
					'title'            => 'Business Consulting',
					'unsplash_gallery' => 'https://unsplash.com/collections/8366058/consultants',
				),
				'neve-job-listings'    => array(
					'url'              => 'https://demo.themeisle.com/neve-job-listings/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-job-listings/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/09/neve-job-listings.jpg',
					'title'            => 'Job Listing',
					'unsplash_gallery' => 'https://unsplash.com/collections/8327271/job-listing',
				),
				'neve-barber-shop'     => array(
					'url'              => 'https://demo.themeisle.com/neve-barber-shop/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-barber-shop/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/09/neve-barber-shop.jpg',
					'title'            => 'Barber Shop',
					'unsplash_gallery' => 'https://unsplash.com/collections/8357212/barber-shop',
				),
				'neve-personal-traner' => array(
					'url'              => 'https://demo.themeisle.com/neve-personal-trainer/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-personal-trainer/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/09/neve-personal-trainer.jpg',
					'title'            => 'Personal Trainer',
					'unsplash_gallery' => 'https://unsplash.com/collections/8351250/personal-trainer/4f469c0cb610352567f5233410c61742',
				),
				'neve-real-estate'     => array(
					'url'              => 'https://demo.themeisle.com/neve-real-estate/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-real-estate/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/09/neve-real-estate.jpg',
					'title'            => 'Real Estate',
					'unsplash_gallery' => 'https://unsplash.com/collections/8280384/real-estate',
				),
				'neve-events'          => array(
					'url'              => 'https://demo.themeisle.com/neve-events/',
					'remote_json'      => 'https://s20206.pcdn.co/neve-events/',
					'screenshot'       => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/08/neve-events-demo.jpg',
					'title'            => 'Events',
					'unsplash_gallery' => 'https://unsplash.com/collections/8311870/music-events',
				),
			),
			'brizy'          => array(
				'neve-brizy-cafe'    => array(
					'url'         => 'https://demo.themeisle.com/neve-cafe-brizy/',
					'remote_json' => 'https://s20206.pcdn.co/neve-cafe-brizy/',
					'screenshot'  => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/06/neve-caffe-new-ss.jpg',
					'title'       => 'Coffee Shop',
				),
				'neve-constructions' => array(
					'url'         => 'https://demo.themeisle.com/neve-constructions-brizy/',
					'remote_json' => 'https://s20206.pcdn.co/neve-constructions-brizy/',
					'screenshot'  => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/06/neve-construction-new-screenshot.jpg',
					'title'       => 'Constructions Company',
				),
				'neve-fashion'       => array(
					'url'         => 'https://demo.themeisle.com/neve-fashion-brizy/',
					'remote_json' => 'https://s20206.pcdn.co/neve-fashion-brizy/',
					'screenshot'  => 'https://demo.themeisle.com/hestia-pro-demo-content/wp-content/uploads/sites/105/2019/07/neve-fashion-demo-screenshot.png',
					'title'       => 'Fashion',
				),
			),
		);
		$addon_starter_sites  = array_merge_recursive( $starter_sites['remote'], $remote_starter_sites );

		$starter_sites['remote'] = $addon_starter_sites;

		return $starter_sites;
	}
}
