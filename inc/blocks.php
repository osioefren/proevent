<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function proevent_register_blocks() {

    // automatically load dependencies and version
    $asset_file = include get_template_directory() . '/build/event-grid.asset.php';

    wp_register_script(
        'proevent-event-grid',
        get_template_directory_uri() . '/build/event-grid.js',
        $asset_file['dependencies'],
        $asset_file['version'],
        true
    );

    wp_register_style(
        'proevent-event-grid-editor',
        get_template_directory_uri() . '/build/event-grid-editor.css',
        [],
        filemtime( get_template_directory() . '/build/event-grid-editor.css' )
    );

    register_block_type( 'proevent/event-grid', [
        'editor_script' => 'proevent-event-grid',
        'editor_style'  => 'proevent-event-grid-editor',
        'render_callback' => 'proevent_render_event_grid',
        'attributes' => [
            'limit' => [
                'type' => 'number',
                'default' => 6
            ],
            'category' => [
                'type' => 'string',
                'default' => ''
            ],
            'order' => [
                'type' => 'string',
                'default' => 'ASC'
            ]
        ]
    ]);
}
add_action( 'init', 'proevent_register_blocks' );
