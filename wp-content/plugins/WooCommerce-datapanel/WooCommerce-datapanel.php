<?php
/**
 * Plugin Name:             Tutsplus WooCommerce Panel
 * Description:             Add a giftwrap panel to WooCommerce products
 * Version:                 1.0.0
 * Author:                  Gareth Harris
 * Author URI:              https://catapultthemes.com/
 * Text Domain:             tpwcp
 * WC requires at least:    3.2.0
 * WC tested up to:         3.3.0
 * License:                 GPL-2.0+
 * License URI:             https://www.gnu.org/licenses/gpl-2.0.txt
*/
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
 
/**
 * Define constants
 */
if ( ! defined( 'TPWCP_PLUGIN_VERSION' ) ) {
    define( 'TPWCP_PLUGIN_VERSION', '1.0.0' );
}
if ( ! defined( 'TPWCP_PLUGIN_DIR_PATH' ) ) {
    define( 'TPWCP_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}
 
require( TPWCP_PLUGIN_DIR_PATH . '/classes/class-tpwcp-admin.php' );
 
/**
 * Start the plugin.
 */
function tpwcp_init() {
    if ( is_admin() ) {
        $TPWCP = new TPWCP_Admin();
        $TPWCP->init();
    }
}
add_action( 'plugins_loaded', 'tpwcp_init' );