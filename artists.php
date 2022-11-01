<?php /* Template Name: Artists Online Store */

defined( 'ABSPATH' ) || exit;

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
    <head>
        <?php do_action( 'masterstudy_head_start' ); ?>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <?php wp_head(); ?>
        <?php do_action( 'masterstudy_head_end' ); ?>
    </head>
<body <?php body_class("woocommerce"); ?> ontouchstart="">

<?php wp_body_open(); ?>

<?php get_template_part( 'partials/headers/main' ); ?>

<?php do_action( 'masterstudy_header_end' ); ?>


<?php
$shop_sidebar_id = stm_option( 'shop_sidebar' );
$enable_shop = stm_option( 'enable_shop', false );
$shop_sidebar_position = stm_option( 'shop_sidebar_position', 'none' );
$content_before = $content_after = $sidebar_before = $sidebar_after = '';
$sidebar_type = '';

// For demo
if( isset( $_GET[ 'sidebar_position' ] ) and $_GET[ 'sidebar_position' ] == 'right' ) {
    $shop_sidebar_position = 'right';
}
elseif( isset( $_GET[ 'sidebar_position' ] ) and $_GET[ 'sidebar_position' ] == 'left' ) {
    $shop_sidebar_position = 'left';
}
elseif( isset( $_GET[ 'sidebar_position' ] ) and $_GET[ 'sidebar_position' ] == 'none' ) {
    $shop_sidebar_position = 'none';
}

if( $shop_sidebar_id ) $shop_sidebar = get_post( $shop_sidebar_id );

if( is_active_sidebar( 'shop' ) ) {
    $shop_sidebar = 'widget_area';
//    $shop_sidebar_position = 'right';
}

if( $shop_sidebar_position == 'right' && isset( $shop_sidebar ) ) {
    $content_before .= '<div class="row">';
    $content_before .= '<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">';
    $content_before .= '<div class="sidebar_position_right">';
    // .products
    $content_after .= '</div>'; // sidebar right
    $content_after .= '</div>'; // col
    $sidebar_before .= '<div class="col-lg-3 col-md-3 hidden-sm hidden-xs">';
    $sidebar_before .= '<div class="sidebar-area sidebar-area-right">';
    // .sidebar-area
    $sidebar_after .= '</div>'; // sidebar area
    $sidebar_after .= '</div>'; // col
    $sidebar_after .= '</div>'; // row
}

if( $shop_sidebar_position == 'left' && isset( $shop_sidebar ) ) {
    $content_before .= '<div class="row">';
    $content_before .= '<div class="col-lg-9 col-lg-push-3 col-md-9 col-md-push-3 col-sm-12 col-xs-12">';
    $content_before .= '<div class="sidebar_position_left">';
    // .products
    $content_after .= '</div>'; // sidebar right
    $content_after .= '</div>'; // col
    $sidebar_before .= '<div class="col-lg-3 col-lg-pull-9 col-md-3 col-md-pull-9 hidden-sm hidden-xs">';
    $sidebar_before .= '<div class="sidebar-area sidebar-area-left">';
    // .sidebar-area
    $sidebar_after .= '</div>'; // sidebar area
    $sidebar_after .= '</div>'; // col
    $sidebar_after .= '</div>'; // row
};

// Grid or list
$layout_products = stm_option( 'shop_layout' );
if( isset( $_GET[ 'view_type' ] ) ) {
    if( $_GET[ 'view_type' ] == 'list' ) {
        $layout_products = 'list';
    }
    else {
        $layout_products = 'grid';
    }
}

$display_type = get_option( 'woocommerce_shop_page_display', '' );

get_template_part( 'partials/title_box' );

$filters = array(
    'category',
//	'subcategory',
    'status',
    'levels',
    'rating',
    'instructor',
    'price',
);

stm_lms_register_style( 'courses_filter' );
stm_lms_register_script( 'courses_filter' );

?>

    <div class="container">

        <?php echo wp_kses_post( $content_before ); ?>
        <?php if( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
            <h2 class="archive-course-title"><?php woocommerce_page_title(); ?></h2>
        <?php endif; ?>

        <?php
        do_action( 'woocommerce_archive_description' ); ?>

        <?php wc_get_template_part( 'global/helpbar' ); ?>
        <div class="stm_archive_product_inner_grid_content">
            <ul class="stm-courses row list-unstyled">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 9,
                    'product_cat' => 'artists',
                );
                $loop = new WP_Query( $args );
                if ( $loop->have_posts() ) {
                    while ( $loop->have_posts() ) : $loop->the_post();
                         if( $layout_products == 'list' ): ?>
                            <?php if( !$enable_shop ): ?>
                            <div class="stm_woo_archive_view_type_list">
                                <?php endif; ?>
                                <?php wc_get_template_part( 'content', 'product-list' ); ?>
                                <?php if( !$enable_shop ): ?>
                            </div>
                            <?php endif; ?>
                            <?php else: ?>

                            <?php wc_get_template_part( 'content', 'product' ); ?>

                            <?php endif;
                    endwhile;
                } else {
                    wc_get_template( 'loop/no-products-found.php' );
                }
                wp_reset_postdata();
                ?>
            </ul>
            <?php

            if( have_posts() ) : ?>

                <?php woocommerce_product_loop_start(); ?>

                <?php while ( have_posts() ) : the_post(); ?>

                    <?php if( $layout_products == 'list' ): ?>
                        <?php if( !$enable_shop ): ?>
                            <div class="stm_woo_archive_view_type_list">
                        <?php endif; ?>
                        <?php wc_get_template_part( 'content', 'product-list' ); ?>
                        <?php if( !$enable_shop ): ?>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                    <?php endif; ?>

                <?php endwhile; // end of the loop. ?>

                <?php woocommerce_product_loop_end(); ?>

                <div class="multiseparator <?php echo esc_attr( $layout_products ); ?>"></div>

                <?php do_action( 'woocommerce_after_shop_loop' ); /* Pagination */ ?>

            <?php elseif( !woocommerce_product_loop() ) : ?>

                <?php wc_get_template( 'loop/no-products-found.php' ); ?>

            <?php endif; ?>

        </div> <!-- stm_product_inner_grid_content -->
        <?php echo wp_kses_post( $content_after ); ?>

        <?php echo wp_kses_post( $sidebar_before ); ?>
        <div class="stm_lms_courses__archive_filters">
            <div class="stm_lms_courses__filter stm_lms_courses__category">
                <div class="stm_lms_courses__filter_heading">
                    <h3>
                        <?php esc_html_e('Category', 'masterstudy-child'); ?>
                        <div class="toggler"></div>
                    </h3>
                </div>
                <div class="stm_lms_courses__filter_content" style="display: none;">


<!--                        <div class="stm_lms_courses__filter_category">-->
<!--                            <label class="stm_lms_styled_checkbox">-->
<!--                    <span class="stm_lms_styled_checkbox__inner">-->
<!--                        <input type="checkbox"-->
<!--                               --><?php //if (in_array(intval($term->term_id), $values)) echo 'checked="checked"'; ?>
<!--                               value="--><?php //echo intval($term->term_id); ?><!--"-->
<!--                               name="category[]"/>-->
<!--                        <span><i class="fa fa-check"></i> </span>-->
<!--                    </span>-->
<!--                                <span>--><?php //echo esc_html($term->name); ?><!--</span>-->
<!--                            </label>-->
<!--                        </div>-->
<!---->
<!--                    --><?php //endforeach; ?>

                </div>
            </div>
        </div>
        <?php echo wp_kses_post( $sidebar_after ); ?>

    </div> <!-- container -->

<?php get_footer();
