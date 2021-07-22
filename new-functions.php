<?php
// Theme version
define( 'GW_VSN', '0.9.1' );

function maintenance_mode() {

    if ( !current_user_can( 'edit_themes' ) || !is_user_logged_in() ) {wp_die('<h1>Grosset Wines</h1><p>Grosset Wines is currently down for maintenance. Please return back soon. Thank you!</p>');}

}
// add_action('get_header', 'maintenance_mode');

include 'inc/functions-global.php';
include 'inc/functions-admin.php';
include 'inc/functions-widgets.php';
include 'inc/functions-wines.php';
include 'inc/functions-temp.php';
include 'inc/functions-story.php';
include 'inc/functions-forms.php';
include 'inc/functions-woo.php';

add_theme_support( 'post-thumbnails' );

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
    set_post_thumbnail_size( 50, 50 ); // 50 pixels wide by 50 pixels tall, resize mode
 
    // additional image sizes
    // delete the next line if you do not need additional image sizes
    add_image_size( 'category-thumb', 300, 9999 ); // 300 pixels wide (and unlimited height)
 }

add_action( 'wp_enqueue_scripts', 'bc_styles' );
add_action( 'wp_enqueue_scripts', 'bc_scripts' );
add_action( 'after_setup_theme', 'register_bc_menu' );
add_action( 'admin_menu', 'grosset_settings_menu' );
add_action( 'widgets_init', 'mh_widgets_init' );

add_filter( 'excerpt_more', 'new_excerpt_more' );
add_filter( 'wp_title', 'bc_wp_title', 10, 2 );
add_filter( 'the_content', 'add_image_responsive_class' );
add_filter( 'next_posts_link_attributes', 'posts_link_attributes' );
add_filter( 'previous_posts_link_attributes', 'posts_link_attributes' );
add_filter( 'xmlrpc_enabled', '__return_false' );

add_shortcode( 'wine', 'wine_shortcode' );
add_shortcode( 'story', 'story_shortcode' );
add_shortcode( 'vineyard', 'vineyard_shortcode' );

// WooCommerce functions
add_action( 'personal_options_update', 'update_user_profile_customer_status' );
add_action( 'edit_user_profile_update', 'update_user_profile_customer_status' );
add_action( 'show_user_profile', 'user_profile_customer_status' );
add_action( 'edit_user_profile', 'user_profile_customer_status' );
add_action( 'woocommerce_variation_options_pricing', 'add_variation_members_pricing', 10, 3 );
add_action( 'woocommerce_save_product_variation', 'save_variation_members_pricing', 10, 2 );
add_filter( 'woocommerce_product_variation_get_price', 'member_get_price', 10, 2 );
