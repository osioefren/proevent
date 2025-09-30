<article id="post-<?php the_ID(); ?>" <?php post_class('border rounded-lg shadow-sm overflow-hidden bg-white hover:shadow-lg transition-shadow duration-300'); ?>>

    <?php if ( has_post_thumbnail() ) : 
        $thumb_id  = get_post_thumbnail_id();
        $thumb_url = wp_get_attachment_image_url( $thumb_id, 'medium_large' );
    ?>
        <div class="h-48 overflow-hidden">
            <a href="<?php the_permalink(); ?>">
                <img 
                    src="<?php echo esc_url( $thumb_url ); ?>" 
                    alt="<?php the_title_attribute(); ?>" 
                    class="w-full h-full object-cover"
                    loading="lazy"
                >
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
            $registration_link = get_post_meta( get_the_ID(), 'registration_link', true );

            if ( $date ) {
                echo '<div><strong>Date:</strong> ' . esc_html( date_i18n( get_option('date_format'), strtotime($date) ) ) . '</div>';
            }
            if ( $time ) {
                echo '<div><strong>Time:</strong> ' . esc_html( date_i18n( get_option('time_format'), strtotime($time) ) ) . '</div>';
            }
            if ( $location ) {
                echo '<div><strong>Location:</strong> ' . esc_html( $location ) . '</div>';
            }
            ?>
        </div>

        <div class="flex gap-2 mt-2">
            <?php if ( $registration_link ) : ?>
                <a href="<?php echo esc_url( $registration_link ); ?>" target="_blank" rel="noopener noreferrer"
                   class="inline-block px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors duration-200">
                    <?php esc_html_e( 'Register', 'proevent' ); ?>
                </a>
            <?php endif; ?>

            <a href="<?php the_permalink(); ?>" 
               class="inline-block px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors duration-200">
                <?php esc_html_e( 'View Details', 'proevent' ); ?>
            </a>
        </div>
    </div>
</article>
