<?php
get_header();
?>

<?php
// Hero Section
$hero_image = get_option('proevent_hero_image');
$hero_title = get_option('proevent_hero_title');
$hero_cta_text = get_option('proevent_hero_cta_text');
$hero_cta_link = get_option('proevent_hero_cta_link');
?>

<section class="relative bg-gray-100 mb-16 rounded-lg overflow-hidden">
    <?php if ($hero_image): ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>" class="w-full h-64 md:h-[500px] object-cover" loading="lazy">
    <?php endif; ?>
    <div class="absolute inset-0 bg-black/50 flex flex-col justify-center items-center text-center px-6">
        <?php if ($hero_title): ?>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4"><?php echo esc_html($hero_title); ?></h1>
        <?php endif; ?>
        <?php if ($hero_cta_text && $hero_cta_link): ?>
            <a href="<?php echo esc_url($hero_cta_link); ?>" class="px-8 py-3 bg-yellow-500 text-black font-semibold rounded hover:bg-yellow-600 transition">
                <?php echo esc_html($hero_cta_text); ?>
            </a>
        <?php endif; ?>
    </div>
</section>

<main id="main" class="container mx-auto px-4 py-12">
    <h2 class="text-3xl font-bold text-center mb-10">Upcoming Events</h2>

    <?php
    $today = date('Y-m-d');

    $events = get_posts([
        'post_type' => 'event',
        'posts_per_page' => 6,
        'meta_key' => 'date',  // primary key
        'meta_value' => $today,
        'meta_compare' => '>=',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    ]);

    if (!$events) {
        $events = get_posts([
            'post_type' => 'event',
            'posts_per_page' => 6,
            'orderby' => 'date',
            'order' => 'ASC',
        ]);
    }
    ?>

    <div class="grid gap-8 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
        <?php if ($events): ?>
            <?php foreach ($events as $post): setup_postdata($post);

                // Robust meta retrieval with fallback keys
                $event_date = get_post_meta($post->ID, 'date', true) ?: get_post_meta($post->ID, '_event_date', true);
                $event_time = get_post_meta($post->ID, 'time', true) ?: get_post_meta($post->ID, '_event_time', true);
                $location   = get_post_meta($post->ID, 'location', true) ?: get_post_meta($post->ID, '_event_location', true);
                $registration = get_post_meta($post->ID, 'registration_link', true) ?: get_post_meta($post->ID, '_event_registration_link', true);

                $thumbnail = get_the_post_thumbnail_url($post->ID, 'medium_large');
            ?>
                <article class="bg-white rounded-xl shadow-md hover:shadow-xl transition flex flex-col h-full overflow-hidden">
                    <?php if ($thumbnail): ?>
                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title($post)); ?>" class="w-full h-48 object-cover" loading="lazy">
                    <?php endif; ?>
                    <div class="p-6 flex flex-col flex-1">
                        <h3 class="text-xl font-semibold mb-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-yellow-500"><?php the_title(); ?></a>
                        </h3>

                        <p class="text-gray-600 mb-1"><strong>Date:</strong> <?php echo esc_html($event_date ?: 'TBA'); ?></p>
                        <p class="text-gray-600 mb-1"><strong>Time:</strong> <?php echo esc_html($event_time ?: 'TBA'); ?></p>
                        <p class="text-gray-600 mb-4"><strong>Location:</strong> <?php echo esc_html($location ?: 'TBA'); ?></p>

                        <?php if ($registration): ?>
                            <a href="<?php echo esc_url($registration); ?>" class="mt-auto inline-block bg-yellow-500 text-black font-semibold py-2 px-4 rounded hover:bg-yellow-600 transition text-center">
                                Register
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; wp_reset_postdata(); ?>
        <?php else: ?>
            <p class="col-span-3 text-center text-gray-500">No upcoming events found.</p>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
?>
