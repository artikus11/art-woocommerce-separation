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

		add_action( 'after_setup_theme', [ $this, 'setup_theme' ] );
		add_action( 'woocommerce_before_shop_loop', [ $this, 'out_subcategories' ], 40 );

	}


	/**
	 * Откключение дефолтного вывода категорий
	 *
	 * @since 1.1.0
	 */
	public function setup_theme() {

		remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

	}


	/**
	 * Кастомный вывод категорий
	 * @since 1.1.0
	 */
	public function out_subcategories() {

		if ( is_search() ) {
			return;
		}

		if ( is_paged() ) {
			return;
		}

		echo $this->loop_start();
		echo woocommerce_maybe_show_product_subcategories();
		echo $this->loop_end();
	}


	/**
	 * Обертка для подключения файла
	 *
	 * @since 1.0.1
	 */
	public function loop_start() {

		ob_start();

		wc_get_template( 'loop/loop-start.php' );

		return ob_get_clean();

	}


	/**
	 * Обертка для подключения файла
	 *
	 * @since 1.0.1
	 */
	public function loop_end() {

		ob_start();

		wc_get_template( 'loop/loop-end.php' );

		return ob_get_clean();

	}


	/**
	 * Меняем вывод рубрик и товаров
	 *
	 * @return string
	 * @since 1.0.1
	 */
	public function separation_archives_after() {

		return $this->loop_end() . $this->loop_start();

	}
}

