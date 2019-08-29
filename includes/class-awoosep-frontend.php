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


		add_filter( 'woocommerce_after_output_product_categories', [ $this, 'separation_archives_after' ] );

	}


	/**
	 * Меняем вывод рубрик и товаров
	 *
	 * @return string
	 * @since 1.0.1
	 */
	public function separation_archives_after() {

		return woocommerce_product_loop_end( false ) . $this->loop_start();

	}


	/**
	 * Обертка для подключения файла
	 *
	 * @return false|string
	 * @since 1.0.1
	 */
	public function loop_start() {

		ob_start();

		wc_set_loop_prop( 'loop', 0 );

		wc_get_template( 'loop/loop-start.php' );

		return ob_get_clean();

	}
}

