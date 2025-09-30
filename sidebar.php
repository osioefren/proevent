<aside id="sidebar" class="site-sidebar">
    <?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
        <?php dynamic_sidebar( 'sidebar-1' ); ?>
    <?php else : ?>
        <div class="widget">
            <h3><?php esc_html_e( 'About', 'proevent' ); ?></h3>
            <p><?php esc_html_e( 'Add widgets in Appearance → Widgets.', 'proevent' ); ?></p>
        </div>
    <?php endif; ?>
</aside>
