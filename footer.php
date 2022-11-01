    <?php
        do_action('masterstudy_before_footer');
        $status = get_post_status( get_the_ID() );
        $post_type = get_post_type( get_the_ID() );

    ?>

		<footer id="footer" class="<?php echo ( stm_option('footer_parallax_option') ) ? '' : 'parallax-off' ?>">
			<div class="footer_wrapper">
				<?php get_template_part('partials/footers/footer', 'top'); ?>
				<?php get_template_part('partials/footers/footer', 'bottom'); ?>
				<?php get_template_part('partials/footers/copyright'); ?>
			</div>
		</footer>

    <?php if($status === 'publish'): ?>
        <div id="bookly-popup" class="bookly-popup">
            <div class="bookly-popup_wr">
                <?php
                    global $bookly_services_sync;

                    $shortcode_bookly = '[bookly-form hide="categories"]';

                    $bookly_services_sync->shortcode_pre_render($shortcode_bookly);

                    echo do_shortcode( $shortcode_bookly );
                    ?>
            </div>
        </div>
        <div class="bookly-popup_layout"></div>
    <?php endif; ?>

        <?php do_action('masterstudy_after_footer'); ?>

	<?php wp_footer(); ?>
	</body>
</html>