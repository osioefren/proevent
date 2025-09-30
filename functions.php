<?php
if ( ! defined( 'ABSPATH' ) ) exit; 


function proevent_enqueue_scripts() {
    wp_enqueue_style( 'proevent-style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'proevent_enqueue_scripts' );


function proevent_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
}
add_action( 'after_setup_theme', 'proevent_theme_setup' );
