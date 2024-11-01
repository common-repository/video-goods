<?php
/**
    Plugin Name: Venby
    Plugin URI: https://venby.tv
    Description: Create & share ecommerce enabled links on Instagram
    Version: 1.7.1
    Author: Venby
    Author URI: https://venby.tv
    @since 1.0.0
    @package VGGB
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$venby_functions_dir = plugin_dir_path( __FILE__ ) . 'includes/';
// Include all the various functions
include_once( $venby_functions_dir . 'settings.php' );
include_once( $venby_functions_dir . 'button.php' );
include_once( $venby_functions_dir . 'view.php' );


// Adding settings link to plugins list page
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'venby_add_action_links' );

function venby_add_action_links ( $links ) {
    $mylinks = array(
        '<a href="' . admin_url( 'options-general.php?page=venby' ) . '">Settings</a>',
    );
    return array_merge( $links, $mylinks );
}

function venby_activate() {
    //on activate plugin function
}
register_activation_hook( __FILE__, 'venby_activate' );

function venby_deactivate(){
    delete_option('ops_apikey_venby');
    delete_option('ops_getcampaignsscripts_venby');
}
register_deactivation_hook( __FILE__, 'venby_deactivate' );

function venby_public_style(){
  wp_enqueue_style( 'vg_plugin_puglic_css', plugin_dir_url( __FILE__ ) . 'css/public_style.css', array(), '1.6', 'all' );
}
add_action( 'wp_enqueue_scripts', 'venby_public_style' );

/**
 * Define global constants.
 *
 * @since 1.0.0
 */
// Plugin version.
if ( ! defined( 'VGGB_VERSION' ) ) {
    define( 'VGGB_VERSION', '1.0' );
}

if ( ! defined( 'TGB_NAME' ) ) {
    define( 'VGGB_NAME', trim( dirname( plugin_basename( __FILE__ ) ), '/' ) );
}

if ( ! defined( 'TGB_DIR' ) ) {
    define( 'VGGB_DIR', WP_PLUGIN_DIR . '/' . VGGB_NAME );
}

if ( ! defined( 'VGGB_URL' ) ) {
    define( 'VGGB_URL', WP_PLUGIN_URL . '/' . VGGB_NAME );
}

/**
 * BLOCK: Venby Block.
 */
require_once( VGGB_DIR . '/block/index.php' );

// Before VC Init
add_action( 'vc_before_init', 'vc_before_init_actions' );
function vc_before_init_actions() {

    require_once( VGGB_DIR . '/venby_block/index.php' );

}

