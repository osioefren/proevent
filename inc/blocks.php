<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Gutenberg blocks
 */
function proevent_register_blocks() {

    // Event Grid Block
    $asset_file = include get_template_directory() . '/dist/event-grid.asset.php';

    wp_register_script(
        'proevent-event-grid',
        get_template_directory_uri() . '/dist/event-grid.js',
        $asset_file['dependencies'],
        $asset_file['version'],
        true
    );

    wp_register_style(
        'proevent-event-grid-editor',
        get_template_directory_uri() . '/dist/event-grid-editor.css',
        [],
        filemtime( get_template_directory() . '/dist/event-grid-editor.css' )
    );

    register_block_type( 'proevent/event-grid', [
        'editor_script'   => 'proevent-event-grid',
        'editor_style'    => 'proevent-event-grid-editor',
        'render_callback' => 'proevent_render_event_grid',
        'attributes'      => [
            'limit' => [
                'type'    => 'number',
                'default' => 6,
            ],
            'category' => [
                'type'    => 'string',
                'default' => '',
            ],
            'order' => [
                'type'    => 'string',
                'default' => 'ASC',
            ],
        ],
    ] );
}
add_action( 'init', 'proevent_register_blocks' );

/**
 * Render callback for Event Grid block
 */
function proevent_render_event_grid( $attributes ) {

    $args = [
        'post_type'      => 'event',
        'posts_per_page' => intval( $attributes['limit'] ),
        'orderby'        => 'meta_value',
        'meta_key'       => 'event_date',
        'order'          => $attributes['order'],
        'meta_query'     => [
            [
                'key'     => 'event_date',
                'value'   => date( 'Ymd' ),
                'compare' => '>=',
                'type'    => 'NUMERIC',
            ],
        ],
    ];

    if ( ! empty( $attributes['category'] ) ) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'event-category',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $attributes['category'] ),
            ],
        ];
    }

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        return '<p>' . esc_html__( 'No events found.', 'proevent' ) . '</p>';
    }

    ob_start();

    echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">';
    while ( $query->have_posts() ) {
        $query->the_post();
        get_template_part( 'parts/event-card' );
    }
    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}
