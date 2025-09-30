<?php
/**
 * Archive template for Events
 */
get_header();
?>

<main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6"><?php post_type_archive_title(); ?></h1>

    <?php if ( have_posts() ) : ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            while ( have_posts() ) :
                the_post();
                get_template_part( 'parts/event-card' );
            endwhile;
            ?>
        </div>

        <div class="mt-8">
            <?php the_posts_pagination(); ?>
        </div>
    <?php else : ?>
        <p><?php esc_html_e( 'No events found.', 'proevent' ); ?></p>
    <?php endif; ?>
</main>

<?php
get_footer();
