<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Custom Post Type: Event
 */
function proevent_register_event_cpt() {

    $labels = [
        'name'               => __( 'Events', 'proevent' ),
        'singular_name'      => __( 'Event', 'proevent' ),
        'add_new'            => __( 'Add New Event', 'proevent' ),
        'add_new_item'       => __( 'Add New Event', 'proevent' ),
        'edit_item'          => __( 'Edit Event', 'proevent' ),
        'new_item'           => __( 'New Event', 'proevent' ),
        'view_item'          => __( 'View Event', 'proevent' ),
        'search_items'       => __( 'Search Events', 'proevent' ),
        'not_found'          => __( 'No events found', 'proevent' ),
        'not_found_in_trash' => __( 'No events found in Trash', 'proevent' ),
        'all_items'          => __( 'All Events', 'proevent' ),
    ];

    $args = [
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'show_in_rest'       => true, // enable Gutenberg
        'rewrite'            => [ 'slug' => 'events' ],
    ];

    register_post_type( 'event', $args );

    // Register Event Category taxonomy
    register_taxonomy(
        'event-category',
        'event',
        [
            'label'        => __( 'Event Categories', 'proevent' ),
            'rewrite'      => [ 'slug' => 'event-category' ],
            'hierarchical' => true,
            'show_in_rest' => true,
        ]
    );
}
add_action( 'init', 'proevent_register_event_cpt' );
