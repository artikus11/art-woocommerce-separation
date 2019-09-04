<?php
/**
 * Plugin Name: Art WooCommerce Separation
 * Plugin URI: wpruse.ru
 * Text Domain: art-woocommerce-separation
 * Domain Path: /languages
 * Description: Plugin for WooCommerce. Separates categories and products on archive pages.
 * Version: 1.0.0
 * Author: Artem Abramovich
 * Author URI: https://wpruse.ru/
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * WC requires at least: 3.3.0
 * WC tested up to: 3.7
 *
 * Copyright Artem Abramovich
 *
 *     This file is part of Art WooCommerce Separation,
 *     a plugin for WordPress.
 *
 *     Art WooCommerce Separation is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 3 of the License, or (at your option)
 *     any later version.
 *
 *     Art WooCommerce Separation is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$plugin_data = get_file_data(
	__FILE__,
	array(
		'ver'  => 'Version',
		'name' => 'Plugin Name',
	)
);

define( 'AWOOSEP_PLUGIN_DIR', __DIR__ );
define( 'AWOOSEP_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'AWOOSEP_PLUGIN_FILE', plugin_basename( __FILE__ ) );

define( 'AWOOSEP_PLUGIN_VER', $plugin_data['ver'] );
define( 'AWOOSEP_PLUGIN_NAME', $plugin_data['name'] );

require __DIR__ . '/includes/class-art-woocommerce-separation.php';

/**
 * The main function responsible for returning the Art_Woo_Sep object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php awoosep()->method_name(); ?>
 *
 * @since 1.0.0
 *
 * @return object Art_Woo_Sep class object.
 */
if ( ! function_exists( 'awoosep' ) ) {

	function awoosep() {

		return Art_Woo_Sep::instance();
	}
}

$GLOBALS['awoosep'] = awoosep();