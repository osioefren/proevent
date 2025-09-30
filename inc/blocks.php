<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Gutenberg blocks for ProEvent theme
 */
function proevent_register_blocks() {

    /**
     * Event Grid Block
     */
    $event_asset = include get_template_directory() . '/build/event-grid.asset.php';

    wp_register_script(
        'proevent-event-grid',
        get_template_directory_uri() . '/build/event-grid.js',
        $event_asset['dependencies'],
        $event_asset['version'],
        true
    );

    wp_register_style(
        'proevent-event-grid-editor',
        get_template_directory_uri() . '/build/event-grid-editor.css',
        [],
        filemtime( get_template_directory() . '/build/event-grid-editor.css' )
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

    /**
     * Hero with CTA Block
     */
    $hero_asset = include get_template_directory() . '/build/hero-cta.asset.php';

    wp_register_script(
        'proevent-hero-cta',
        get_template_directory_uri() . '/build/hero-cta.js',
        $hero_asset['dependencies'],
        $hero_asset['version'],
        true
    );

    wp_register_style(
        'proevent-hero-cta-editor',
        get_template_directory_uri() . '/build/hero-cta-editor.css',
        [],
        filemtime( get_template_directory() . '/build/hero-cta-editor.css' )
    );

    register_block_type( 'proevent/hero-cta', [
        'editor_script'   => 'proevent-hero-cta',
        'editor_style'    => 'proevent-hero-cta-editor',
        'render_callback' => 'proevent_render_hero_cta',
        'attributes'      => [
            'imageUrl' => [
                'type'    => 'string',
                'default' => '',
            ],
            'heading' => [
                'type'    => 'string',
                'default' => 'Welcome to ProEvent',
            ],
            'buttonText' => [
                'type'    => 'string',
                'default' => 'Learn More',
            ],
            'buttonUrl' => [
                'type'    => 'string',
                'default' => '#',
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
        get_template_part( 'template-parts/event-card' );
    }
    echo '</div>';

    wp_reset_postdata();

    return ob_get_clean();
}

/**
 * Render callback for Hero with CTA block
 */
function proevent_render_hero_cta( $attributes ) {
    $image     = esc_url( $attributes['imageUrl'] );
    $heading   = esc_html( $attributes['heading'] );
    $btn_text  = esc_html( $attributes['buttonText'] );
    $btn_url   = esc_url( $attributes['buttonUrl'] );

    ob_start();
    ?>
    <section class="relative bg-gray-100">
        <?php if ( $image ) : ?>
            <img src="<?php echo $image; ?>" alt="<?php echo $heading; ?>" class="w-full h-96 object-cover lazyload" loading="lazy" />
        <?php endif; ?>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-center bg-black/50 p-6">
            <h2 class="text-white text-3xl md:text-5xl font-bold mb-4"><?php echo $heading; ?></h2>
            <?php if ( $btn_text && $btn_url ) : ?>
                <a href="<?php echo $btn_url; ?>" class="inline-block bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-secondary transition">
                    <?php echo $btn_text; ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php
    return ob_get_clean();
}
