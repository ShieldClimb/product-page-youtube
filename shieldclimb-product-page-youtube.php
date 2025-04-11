<?php

/**
 * Plugin Name: ShieldClimb – Product Page YouTube for WooCommerce
 * Plugin URI: https://shieldclimb.com/free-woocommerce-plugins/product-page-youtube/
 * Description: Add Product Page YouTube videos to your WooCommerce store with this free plugin. Enhance engagement and improve the shopping experience.
 * Version: 1.0.1
 * Requires Plugins: woocommerce
 * Requires at least: 5.8
 * Tested up to: 6.8
 * WC requires at least: 5.8
 * WC tested up to: 9.8.1
 * Requires PHP: 7.2
 * Author: shieldclimb.com
 * Author URI: https://shieldclimb.com/about-us/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

 if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Run the plugin
add_action( 'plugins_loaded', 'shieldclimb_product_page_youtube' );

function shieldclimb_product_page_youtube() {
    if ( class_exists( 'WooCommerce' ) ) {
        include_once plugin_dir_path( __FILE__ ) . 'includes/shieldclimb-add-save-youtube-meta-box.php';
        include_once plugin_dir_path( __FILE__ ) . 'includes/shieldclimb-display-video-in-product-page.php';
    }
}

?>