<?php
get_header();

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        // Custom fields
        $date  = get_post_meta( get_the_ID(), '_proevent_date', true );
        $time  = get_post_meta( get_the_ID(), '_proevent_time', true );
        $loc   = get_post_meta( get_the_ID(), '_proevent_location', true );
        $reg   = get_post_meta( get_the_ID(), '_proevent_registration', true );
        ?>
        
        <article <?php post_class('proevent-single container mx-auto py-8'); ?>>
            <h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>

            <div class="event-meta text-gray-700 mb-6">
                <?php if ( $date ) : ?>
                    <p><strong><?php _e( 'Date:', 'proevent' ); ?></strong> <?php echo esc_html( $date ); ?></p>
                <?php endif; ?>

                <?php if ( $time ) : ?>
                    <p><strong><?php _e( 'Time:', 'proevent' ); ?></strong> <?php echo esc_html( $time ); ?></p>
                <?php endif; ?>

                <?php if ( $loc ) : ?>
                    <p><strong><?php _e( 'Location:', 'proevent' ); ?></strong> <?php echo esc_html( $loc ); ?></p>
                <?php endif; ?>

                <?php if ( $reg ) : ?>
                    <p><a href="<?php echo esc_url( $reg ); ?>" class="bg-blue-600 text-white px-4 py-2 rounded inline-block mt-2" target="_blank">
                        <?php _e( 'Register Now', 'proevent' ); ?>
                    </a></p>
                <?php endif; ?>
            </div>

            <div class="event-content prose max-w-none">
                <?php the_content(); ?>
            </div>
        </article>

        <?php
    endwhile;
endif;

get_footer();
