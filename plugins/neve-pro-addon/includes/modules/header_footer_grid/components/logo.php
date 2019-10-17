<?php
/**
 * Logo Component Wrapper class extends Header Footer Grid Component.
 *
 * Name:    Header Footer Grid
 * Author:  Bogdan Preda <bogdan.preda@themeisle.com>
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Logo as CoreLogo;

/**
 * Class Logo
 *
 * @package Neve_Pro\Modules\Header_Footer_Grid\Components
 */
class Logo extends CoreLogo {
	/**
	 * Holds the instance count.
	 * Starts at 1 since the base component is not altered.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected static $instance_count = 1;
	/**
	 * Holds the current instance count.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected $instance_number;
	/**
	 * The maximum allowed instances of this class.
	 * This refers to the global scope, across all builders.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var int
	 */
	protected $max_instance = 2;

	/**
	 * Logo constructor.
	 *
	 * @param string $panel Builder panel.
	 */
	public function __construct( $panel ) {
		self::$instance_count += 1;
		$this->instance_number = self::$instance_count;
		parent::__construct( $panel );
	}

	/**
	 * Utility method to return the component ID.
	 *
	 * @return string
	 * @since   1.0.0
	 * @access  public
	 */
	public function get_id() {
		return parent::get_id() . '-' . $this->instance_number;
	}

	/**
	 * Don't copy abstract settings for this component.
	 *
	 * So we have a reference to original component settings.
	 *
	 * @since   1.0.1
	 * @access  public
	 */
	public function define_settings() {}

	/**
	 * Method to filter component loading if needed.
	 *
	 * @since   1.0.1
	 * @access public
	 * @return bool
	 */
	public function is_active() {
		if ( $this->max_instance < $this->instance_number ) {
			return false;
		}
		return parent::is_active();
	}
}
