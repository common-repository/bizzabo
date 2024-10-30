<?php
/**
 * Bizzabo Output
 * @version 0.1.3
 * @package Bizzabo
 */

class Bizzabo_Output {
	/**
	 * Parent plugin class
	 *
	 * @var class
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since 0.1.0
	 * @return  null
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Output an Event Template File
	 *
	 * @param $template
	 * @param $event_id Event ID to use for the template
	 * @return mixed|string
	 */
	public function template( $template, $event_id ){

		if( empty( $event_id ) ) return __( 'Error: Unable to determine Event ID.  Please contact the Website Administrator for assistance.' );
		// Start output buffering
		ob_start();
		$file = Bizzabo::dir( 'templates/'. $template .'.php' );
		if ( file_exists( $file ) ) {
			include( $file );
		}
		return ob_get_clean();
	}

	/**
	 * Output an Event Template File
	 *
	 * @param $template
	 * @param $unique_name Unique Name to use for the template
	 * @return mixed|string
	 */
	public function template_unique_name( $template, $unique_name ){

		if( empty( $unique_name ) ) return __( 'Error: Unable to determine Unique Name.  Please contact the Website Administrator for assistance.' );
		// Start output buffering
		ob_start();
		$file = Bizzabo::dir( 'templates/'. $template .'.php' );
		if ( file_exists( $file ) ) {
			include( $file );
		}
		return ob_get_clean();
	}

	/**
	 * Output an Event Template File
	 *
	 * @param $template
	 * @param $unique_name Unique Name to use for the template
	 * @param $tab_id Tab ID to use for the template
	 * @return mixed|string
	 */
	public function template_unique_name_and_tab_id( $template, $unique_name, $tab_id ){

		if( empty( $unique_name ) ) return __( 'Error: Unable to determine Unique Name.  Please contact the Website Administrator for assistance.' );
		if( empty( $tab_id ) ) return __( 'Error: Unable to determine Tab ID.  Please contact the Website Administrator for assistance.' );
		// Start output buffering
		ob_start();
		$file = Bizzabo::dir( 'templates/'. $template .'.php' );
		if ( file_exists( $file ) ) {
			include( $file );
		}
		return ob_get_clean();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since 0.1.0
	 * @return  null
	 */
	public function hooks() {
	}
}