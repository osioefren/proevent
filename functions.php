<?php
if ( ! defined( 'ABSPATH' ) ) exit;  

// Enqueue Tailwind build output
function proevent_enqueue_scripts() {
    wp_enqueue_style(
        'proevent-style',
        get_template_directory_uri() . '/dist/style.css',
        [],
        filemtime( get_template_directory() . '/dist/style.css' ) // cache-busting
    );
}
add_action( 'wp_enqueue_scripts', 'proevent_enqueue_scripts' );

function proevent_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}

add_action( 'after_setup_theme', 'proevent_theme_setup' );

function proevent_register_features() {
    // menus
    register_nav_menus( [
        'primary' => __( 'Primary Menu', 'proevent' ),
        'footer'  => __( 'Footer Menu', 'proevent' ),
    ] );

    // sidebar
    register_sidebar( [
        'name'          => __( 'Sidebar', 'proevent' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ] );
}
add_action( 'after_setup_theme', 'proevent_register_features' );
