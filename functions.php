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

// Register CPT: Event
function proevent_register_cpt_event() {
    $labels = [
        'name'          => __( 'Events', 'proevent' ),
        'singular_name' => __( 'Event', 'proevent' ),
    ];
    $args = [
        'labels'       => $labels,
        'public'       => true,
        'has_archive'  => true,
        'menu_icon'    => 'dashicons-calendar',
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite'      => ['slug' => 'events'],
        'show_in_rest' => true,
    ];
    register_post_type( 'event', $args );

    // Taxonomy: Event Category
    register_taxonomy(
        'event-category',
        'event',
        [
            'label'        => __( 'Event Categories', 'proevent' ),
            'rewrite'      => ['slug' => 'event-category'],
            'hierarchical' => true,
            'show_in_rest' => true,
        ]
    );
}
add_action( 'init', 'proevent_register_cpt_event' );


function proevent_add_event_meta_boxes() {
    add_meta_box(
        'proevent_event_details',
        __( 'Event Details', 'proevent' ),
        'proevent_event_meta_callback',
        'event',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'proevent_add_event_meta_boxes' );

function proevent_event_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'proevent_event_nonce' );

    $date  = get_post_meta( $post->ID, '_proevent_date', true );
    $time  = get_post_meta( $post->ID, '_proevent_time', true );
    $loc   = get_post_meta( $post->ID, '_proevent_location', true );
    $reg   = get_post_meta( $post->ID, '_proevent_registration', true );

    ?>
    <p>
        <label for="proevent_date"><?php _e( 'Event Date', 'proevent' ); ?></label><br>
        <input type="date" name="proevent_date" id="proevent_date" value="<?php echo esc_attr( $date ); ?>" class="widefat">
    </p>
    <p>
        <label for="proevent_time"><?php _e( 'Event Time', 'proevent' ); ?></label><br>
        <input type="time" name="proevent_time" id="proevent_time" value="<?php echo esc_attr( $time ); ?>" class="widefat">
    </p>
    <p>
        <label for="proevent_location"><?php _e( 'Location', 'proevent' ); ?></label><br>
        <input type="text" name="proevent_location" id="proevent_location" value="<?php echo esc_attr( $loc ); ?>" class="widefat">
    </p>
    <p>
        <label for="proevent_registration"><?php _e( 'Registration Link', 'proevent' ); ?></label><br>
        <input type="url" name="proevent_registration" id="proevent_registration" value="<?php echo esc_attr( $reg ); ?>" class="widefat">
    </p>
    <?php
}

function proevent_save_event_meta( $post_id ) {
    if ( ! isset( $_POST['proevent_event_nonce'] ) ||
         ! wp_verify_nonce( $_POST['proevent_event_nonce'], basename( __FILE__ ) ) ) {
        return $post_id;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
    }

    if ( 'event' !== $_POST['post_type'] || ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
    }

    $fields = [
        'proevent_date'        => '_proevent_date',
        'proevent_time'        => '_proevent_time',
        'proevent_location'    => '_proevent_location',
        'proevent_registration'=> '_proevent_registration',
    ];

    foreach ( $fields as $field => $key ) {
        if ( isset( $_POST[$field] ) ) {
            update_post_meta( $post_id, $key, sanitize_text_field( $_POST[$field] ) );
        }
    }
}
add_action( 'save_post', 'proevent_save_event_meta' );
