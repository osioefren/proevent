<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Register Custom Post Type: Event + Taxonomy + Meta Boxes + REST Endpoint
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
        'publicly_queryable' => true,
        'has_archive'        => true,
        'menu_icon'          => 'dashicons-calendar-alt',
        'menu_position'      => 5,
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'show_in_rest'       => true, // enable Gutenberg
        'rewrite'            => [ 'slug' => 'events' ],
        'capability_type'    => 'post',
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


/**
 * Add Meta Boxes
 */
function proevent_add_event_meta_boxes() {
    add_meta_box(
        'proevent_event_details',
        __('Event Details', 'proevent'),
        'proevent_event_meta_box_callback',
        'event',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'proevent_add_event_meta_boxes');

/**
 * Meta Box Callback
 */
function proevent_event_meta_box_callback($post) {
    wp_nonce_field('proevent_save_event_meta', 'proevent_event_meta_nonce');

    $date = get_post_meta($post->ID, '_event_date', true);
    $time = get_post_meta($post->ID, '_event_time', true);
    $location = get_post_meta($post->ID, '_event_location', true);
    $registration = get_post_meta($post->ID, '_event_registration_link', true);
    ?>
    <p>
        <label for="event_date"><?php _e('Date', 'proevent'); ?></label><br>
        <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($date); ?>" />
    </p>
    <p>
        <label for="event_time"><?php _e('Time', 'proevent'); ?></label><br>
        <input type="time" id="event_time" name="event_time" value="<?php echo esc_attr($time); ?>" />
    </p>
    <p>
        <label for="event_location"><?php _e('Location', 'proevent'); ?></label><br>
        <input type="text" id="event_location" name="event_location" value="<?php echo esc_attr($location); ?>" />
    </p>
    <p>
        <label for="event_registration_link"><?php _e('Registration Link', 'proevent'); ?></label><br>
        <input type="url" id="event_registration_link" name="event_registration_link" value="<?php echo esc_attr($registration); ?>" />
    </p>
    <?php
}

/**
 * Save Meta Box Data
 */
function proevent_save_event_meta($post_id) {
    if (!isset($_POST['proevent_event_meta_nonce']) || !wp_verify_nonce($_POST['proevent_event_meta_nonce'], 'proevent_save_event_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event_date'] ?? ''));
    update_post_meta($post_id, '_event_time', sanitize_text_field($_POST['event_time'] ?? ''));
    update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event_location'] ?? ''));
    update_post_meta($post_id, '_event_registration_link', esc_url_raw($_POST['event_registration_link'] ?? ''));
}
add_action('save_post', 'proevent_save_event_meta');


/**
 * REST API Endpoint: /wp-json/proevent/v1/next
 * Returns the next 5 upcoming events
 */
function proevent_register_rest_endpoint() {
    register_rest_route('proevent/v1', '/next', [
        'methods' => 'GET',
        'callback' => function() {
            $events = get_posts([
                'post_type' => 'event',
                'meta_key' => '_event_date',
                'orderby' => 'meta_value',
                'order' => 'ASC',
                'meta_query' => [
                    [
                        'key' => '_event_date',
                        'value' => date('Y-m-d'),
                        'compare' => '>=',
                        'type' => 'DATE'
                    ]
                ],
                'posts_per_page' => 5
            ]);

            return array_map(function($event) {
                return [
                    'id' => $event->ID,
                    'title' => get_the_title($event),
                    'date' => get_post_meta($event->ID, '_event_date', true),
                    'time' => get_post_meta($event->ID, '_event_time', true),
                    'location' => get_post_meta($event->ID, '_event_location', true),
                    'registration_link' => get_post_meta($event->ID, '_event_registration_link', true),
                    'permalink' => get_permalink($event)
                ];
            }, $events);
        },
        'permission_callback' => '__return_true'
    ]);
}
add_action('rest_api_init', 'proevent_register_rest_endpoint');
