<?php
class Ti_White_Label_Data {

	private $inputs = array(
		'author_name'        => '',
		'author_url'         => '',
		'theme_name'         => '',
		'theme_description'  => '',
		'screenshot_url'     => '',
		'plugin_name'        => '',
		'plugin_description' => '',
		'white_label'        => '',
	);

	private function update_field_data() {
		$new_input = get_option( 'ti_white_label_inputs' );
		$new_input = json_decode( $new_input, true );
		if ( empty( $new_input ) ) {
			return;
		}

		$this->inputs = wp_parse_args( $new_input, $this->inputs );
	}

	/**
	 * Return site settings.
	 *
	 * @return array Site settings.
	 */
	public function get_input_fields() {
		$this->update_field_data();
		return $this->inputs;
	}
}
