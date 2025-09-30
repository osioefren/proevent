<header class="bg-gray-900 text-white">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
        <?php if ( has_custom_logo() ) : ?>
            <div class="site-logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php else : ?>
            <h1 class="text-2xl font-bold">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-blue-400">
                    <?php bloginfo( 'name' ); ?>
                </a>
            </h1>
        <?php endif; ?>

        <nav>
            <?php
            wp_nav_menu( array(
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex space-x-6 text-lg',
            ) );
            ?>
        </nav>
    </div>
</header>
