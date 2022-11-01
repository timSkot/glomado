<?php if (class_exists('STM_LMS_Templates')):
    $show_wishlist = stm_option('default_show_wishlist', true);
    ?>
    <?php if (is_user_logged_in()): ?>
    <?php STM_LMS_Templates::show_lms_template('global/account-dropdown'); ?>
    <?php if($show_wishlist) STM_LMS_Templates::show_lms_template('global/wishlist-button'); ?>
    <?php if ( class_exists( 'WooCommerce' ) ): ?>
    <div class="stm_lms_cart_button">
        <a href="<?php echo wc_get_cart_url() ?>"><i class="lnr lnr-cart"></i></a>
    </div>
    <?php endif; ?>
    <?php STM_LMS_Templates::show_lms_template('global/settings-button'); ?>
<?php else: ?>
    <?php get_template_part('partials/headers/parts/log-in'); ?>
    <?php get_template_part('partials/headers/parts/sign-up'); ?>
    <?php if($show_wishlist) STM_LMS_Templates::show_lms_template('global/wishlist-button'); ?>
<?php endif; ?>
<?php endif;