<?php get_header(); ?>

<div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8 text-center">Upcoming Eventssss</h1>
    
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 
            $event_date = get_post_meta(get_the_ID(), '_event_date', true);
            $event_time = get_post_meta(get_the_ID(), '_event_time', true);
            $event_location = get_post_meta(get_the_ID(), '_event_location', true);
            $registration_link = get_post_meta(get_the_ID(), '_event_registration_link', true);
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium_large');
        ?>
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 flex flex-col">
            <?php if($thumbnail): ?>
                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php the_title(); ?>" class="w-full h-48 object-cover" loading="lazy" />
            <?php endif; ?>
            <div class="p-6 flex-1 flex flex-col">
                <h2 class="text-xl font-semibold mb-2"><?php the_title(); ?></h2>
                <p class="text-gray-500 mb-2"><?php echo date('F j, Y', strtotime($event_date)); ?> at <?php echo esc_html($event_time); ?></p>
                <p class="text-gray-600 mb-4"><?php echo esc_html($event_location); ?></p>
                <?php if($registration_link): ?>
                    <a href="<?php echo esc_url($registration_link); ?>" target="_blank" class="mt-auto inline-block bg-blue-600 text-white font-medium py-2 px-4 rounded hover:bg-blue-700 transition">
                        Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; else: ?>
            <p class="text-center text-gray-500 col-span-3">No upcoming events found.</p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>
