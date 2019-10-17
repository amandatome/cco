<?php
/**
 * Wish List Component class for Header Footer Grid.
 *
 * @version 1.0.0
 * @package HFG
 */

namespace Neve_Pro\Modules\Header_Footer_Grid\Components;

use HFG\Core\Components\Abstract_Component;
use HFG\Main;
use Neve_Pro\Modules\Woocommerce_Booster\Module;

/**
 * Class Wish_List
 */
class Wish_List extends Abstract_Component {
	const COMPONENT_ID = 'wish_list';

	/**
	 * Wish List constructor.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function init() {
		$this->set_property( 'label', __( 'Wish List', 'neve' ) );
		$this->set_property( 'id', self::COMPONENT_ID );
		$this->set_property( 'width', 1 );
	}

	/**
	 * Method to filter component loading.
	 *
	 * @return bool
	 */
	public function is_active() {
		$woo_booster_instance = new Module();

		return $woo_booster_instance->should_load();
	}

	/**
	 * Called to register component controls.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function add_settings() {
	}


	/**
	 * The render method for the component.
	 *
	 * @since   1.0.0
	 * @access  public
	 */
	public function render_component() {
		Main::get_instance()->load( 'component-wish-list' );
	}

}
