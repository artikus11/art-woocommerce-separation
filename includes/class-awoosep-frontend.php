<?php // @codingStandardsIgnoreLine

/**
 * Class AWOOSEP_Front_End
 *
 * @author Artem Abramovich
 * @since  1.0.0
 */
class AWOOSEP_Front_End {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		/**
		 * WooCommerce hooks
		 */ //add_filter( 'woocommerce_product_loop_end', array( $this, 'separation_archives' ), 10, 1 );

		//add_filter( 'woocommerce_before_output_product_categories', array( $this, 'separation_archives_before' ), 10, 1 );

		add_filter( 'woocommerce_after_output_product_categories', [ $this, 'separation_archives_after' ] );

	}



	public function separation_archives_after( ) {

		//error_log( print_r( woocommerce_product_loop_start(false), 1 ) );
		return woocommerce_product_loop_end( false ) . $this->loop_start();

	}


	public function loop_start() {

		ob_start();

		wc_set_loop_prop( 'loop', 0 );

		wc_get_template( 'loop/loop-start.php' );

		return ob_get_clean();

	}
}

