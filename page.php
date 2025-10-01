<?php
get_header();
?>

<main id="main" class="container mx-auto px-4 py-12">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>
            <article class="proevent-page max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md mb-12">
                
                <!-- Page Title -->
                <header class="mb-6">
                    <h1 class="text-4xl font-bold text-gray-800"><?php the_title(); ?></h1>
                </header>

                <!-- Page Content -->
                <div class="proevent-content text-gray-700 leading-relaxed space-y-6">
                    <?php the_content(); ?>
                </div>
                
            </article>
        <?php
        endwhile;
    else : ?>
        <p class="text-center text-gray-500">No content found.</p>
    <?php endif; ?>
</main>

<?php
get_footer();
?>
