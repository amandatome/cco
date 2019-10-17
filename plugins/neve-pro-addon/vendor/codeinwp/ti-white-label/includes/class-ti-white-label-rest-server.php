<?php
/**
 * White Label Rest Endpoints Handler.
 *
 * @package         ti-white-label
 */
/**
 * Class Themeisle_OB_Rest_Server
 *
 * @package themeisle-onboarding
 */
class Ti_White_Label_Rest_Server {

	/**
	 * Rest api namespace.
	 *
	 * @var string Namespace.
	 */
	private $namespace;

	/**
	 * Initialize the rest functionality.
	 */
	public function __construct() {
		$this->namespace = WHITE_LABEL_NAMESPACE . '/v1';
		add_action( 'rest_api_init', array( $this, 'register_endpoints' ) );
	}

	/**
	 * Register endpoints.
	 */
	public function register_endpoints() {
		register_rest_route(
			$this->namespace,
			'/input_save',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'save_inputs' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	/**
	 * Save inputs.
	 *
	 * @param WP_REST_Request
	 */
	public function save_inputs( WP_REST_Request $request ) {
		$data = $request->get_json_params();
		if ( empty( $data ) ) {
			wp_send_json_error( 'Options were not saved.' );
		}

		$new_input = array();
		foreach ( $data['data'] as $key => $value ) {
			$new_input = array_merge( $new_input, $value );
		}

		$save_data = json_encode( $new_input );
		update_option( 'ti_white_label_inputs', $save_data );
		wp_send_json_success( 'Options saved.' );
	}

}
