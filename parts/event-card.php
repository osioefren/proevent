<?php
/**
 * Event Card Partial
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('border rounded-lg shadow-sm overflow-hidden bg-white'); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="h-48 overflow-hidden">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail( 'medium_large', ['class' => 'w-full h-full object-cover'] ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="p-4">
        <h2 class="text-xl font-semibold mb-2">
            <a href="<?php the_permalink(); ?>" class="hover:text-blue-600">
                <?php the_title(); ?>
            </a>
        </h2>

        <div class="text-sm text-gray-600 mb-2">
            <?php
            $date     = get_post_meta( get_the_ID(), 'event_date', true );
            $time     = get_post_meta( get_the_ID(), 'event_time', true );
            $location = get_post_meta( get_the_ID(), 'event_location', true );

            if ( $date ) {
                echo '<div><strong>Date:</strong> ' . esc_html( $date ) . '</div>';
            }
            if ( $time ) {
                echo '<div><strong>Time:</strong> ' . esc_html( $time ) . '</div>';
            }
            if ( $location ) {
                echo '<div><strong>Location:</strong> ' . esc_html( $location ) . '</div>';
            }
            ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="inline-block mt-2 px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
            <?php esc_html_e( 'View Details', 'proevent' ); ?>
        </a>
    </div>
</article>
