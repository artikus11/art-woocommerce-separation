<?php // @codingStandardsIgnoreLine

/**
 * Class Art_Woo_Sep
 *
 * Main AWOOSEP class, initialized the plugin
 *
 * @class       Art_Woo_Sep
 * @version     1.0.0
 * @author      Artem Abramovich
 */
class Art_Woo_Sep {

	/**
	 * Instance of Art_Woo_Sep.
	 *
	 * @since  1.8.0
	 * @access private
	 * @var object $instance The instance of Art_Woo_Sep.
	 */
	private static $instance;

	/**
	 * Added AWOOSEP_Front_End.
	 *
	 * @since 1.0.0
	 * @var object AWOOSEP_Front_End $front_end
	 */
	public $front_end;

	/**
	 * @since 1.0.0
	 * @var array Required plugins.
	 */
	protected $required_plugins = array();


	/**
	 * Construct.
	 *
	 * @since 1.0.0
	 *
	 * @see   https://github.com/kagg-design/woof-by-category
	 *
	 */
	public function __construct() {

		$this->required_plugins = array(
			array(
				'plugin'  => 'woocommerce/woocommerce.php',
				'name'    => 'WooCommerce',
				'slug'    => 'woocommerce',
				'class'   => 'WooCommerce',
				'version' => '3.0',
				'active'  => false,
			),
		);

		$this->load_dependencies();

		$this->init();

		$this->load_textdomain();
	}


	/**
	 *
	 * Load plugin parts.
	 *
	 *
	 * @since 2.0.0
	 */
	private function load_dependencies() {

		/**
		 * Front end
		 */
		require AWOOSEP_PLUGIN_DIR . '/includes/class-awoosep-frontend.php';
		$this->front_end = new AWOOSEP_Front_End();

	}


	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 *
	 * @since 1.0.0
	 */
	public function init() {

		add_action( 'admin_init', array( $this, 'check_requirements' ) );
		add_action( 'admin_init', array( $this, 'check_php_version' ) );

		foreach ( $this->required_plugins as $required_plugin ) {
			if ( ! class_exists( $required_plugin['class'] ) ) {
				return;
			}
		}
	}


	/**
	 * Textdomain.
	 *
	 * Load the textdomain based on WP language.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {

		load_plugin_textdomain(
			'art-woocommerce-separation',
			false,
			dirname( AWOOSEP_PLUGIN_FILE ) . '/languages/'
		);

	}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @return object Instance of the class.
	 * @since 1.0.0
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) :
			self::$instance = new self();
		endif;

		return self::$instance;

	}


	/**
	 * Display PHP 5.6 required notice.
	 *
	 * Display a notice when the required PHP version is not met.
	 *
	 * @since 1.8.0
	 */
	public function php_version_notice() {

		$message = sprintf(
		/* translators: 1: Name plugins, 2:PHP version */ esc_html__(
			                                                  '%1$s requires PHP version 5.6 or higher. Your current PHP version is %2$s. Please upgrade PHP version to run this plugin.',
			                                                  'art-woocommerce-separation'
		                                                  ),
		                                                  esc_html( AWOOSEP_PLUGIN_NAME ),
		                                                  PHP_VERSION
		);

		$this->admin_notice( $message, 'notice notice-error is-dismissible' );

	}


	/**
	 * Show admin notice.
	 *
	 * @param string $message Message to show.
	 * @param string $class   Message class: notice notice-success notice-error notice-warning notice-info is-dismissible
	 *
	 * @since 1.0.0
	 *
	 */
	private function admin_notice( $message, $class ) {

		?>
		<div class="<?php echo esc_attr( $class ); ?>">
			<p>
				<span>
				<?php echo wp_kses_post( $message ); ?>
				</span>
			</p>
		</div>
		<?php

	}


	/**
	 * Check plugin PHP version. If not met, show message and deactivate plugin.
	 *
	 * @since 1.0.0
	 */
	public function check_php_version() {

		if ( version_compare( PHP_VERSION, '5.6', 'lt' ) ) {

			deactivate_plugins( plugin_basename( AWOOSEP_PLUGIN_FILE ) );

			add_action( 'admin_notices', array( $this, 'php_version_notice' ) );
			add_action( 'admin_notices', array( $this, 'show_deactivate_notice' ) );
		}

	}


	/**
	 * Check plugin requirements. If not met, show message and deactivate plugin.
	 *
	 * @since 1.0.0
	 */
	public function check_requirements() {

		if ( false === $this->requirements() ) {
			$this->deactivation_plugin();
		}
	}


	/**
	 * Check if plugin requirements.
	 *
	 * @return bool
	 * @since 2.0.0
	 *
	 */
	private function requirements() {

		$all_active = true;
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		foreach ( $this->required_plugins as $key => $required_plugin ) {
			if ( is_plugin_active( $required_plugin['plugin'] ) ) {
				$this->required_plugins[ $key ]['active'] = true;
			} else {
				$all_active = false;
			}
		}

		return $all_active;
	}


	public function deactivation_plugin() {

		add_action( 'admin_notices', array( $this, 'show_plugin_not_found_notice' ) );
		if ( is_plugin_active( AWOOSEP_PLUGIN_FILE ) ) {

			deactivate_plugins( AWOOSEP_PLUGIN_FILE );
			// @codingStandardsIgnoreStart
			if ( isset( $_GET['activate'] ) ) {
				unset( $_GET['activate'] );
			}
			// @codingStandardsIgnoreEnd
			add_action( 'admin_notices', array( $this, 'show_deactivate_notice' ) );
		}
	}


	/**
	 * Show required plugins not found message.
	 *
	 * @since 1.0.0
	 */
	public function show_plugin_not_found_notice() {

		$message = sprintf(
		/* translators: 1: Name author plugin */ __( 'The %s requires installed and activated plugins: ', 'art-woocommerce-separation' ),
		                                         esc_attr( AWOOC_PLUGIN_NAME )
		);

		$message_parts = array();

		foreach ( $this->required_plugins as $key => $required_plugin ) {
			if ( ! $required_plugin['active'] ) {
				$href = '/wp-admin/plugin-install.php?tab=plugin-information&plugin=';

				$href .= $required_plugin['slug'] . '&TB_iframe=true&width=640&height=500';

				$message_parts[] = '<strong><em><a href="' . $href . '" class="thickbox">' . $required_plugin['name'] . __( ' version ', 'art-woocommerce-separation' ) .
				                   $required_plugin['version'] . '</a>' . __( ' or higher', 'art-woocommerce-separation' ) . '</em></strong>';
			}
		}

		$count = count( $message_parts );
		foreach ( $message_parts as $key => $message_part ) {
			if ( 0 !== $key ) {
				if ( ( ( $count - 1 ) === $key ) ) {
					$message .= __( ' and ', 'art-woocommerce-separation' );
				} else {
					$message .= ', ';
				}
			}

			$message .= $message_part;
		}

		$message .= '.';

		$this->admin_notice( $message, 'notice notice-error is-dismissible' );
	}


	/**
	 * Show a notice to inform the user that the plugin has been deactivated.
	 *
	 * @since 2.0.0
	 */
	public function show_deactivate_notice() {

		$message = sprintf(
		/* translators: 1: Name author plugin */ __( '%s plugin has been deactivated.', 'art-woocommerce-separation' ),
		                                         esc_attr( AWOOSEP_PLUGIN_NAME )
		);

		$this->admin_notice( $message, 'notice notice-warning is-dismissible' );
	}
}
