<?php
/**
 *
 * @var $columns
 * @var $title
 * @var $posts_per_page
 * @var $wishlist
 * @var $select_bundles
 */
$posts_per_page = ( ! empty( $posts_per_page ) ) ? intval( $posts_per_page ) : 9;
$page           = get_query_var( 'page',  1 );
$public         = true;

if ( ! empty( $select_bundles ) ) {
    $wishlist = explode( ',', $select_bundles );
}

$args = array(
    'posts_per_page' => $posts_per_page,
    'post_status'    => array( 'publish' ),
    'stm_lms_page'   => $page,
    'author'         => '',
);

stm_lms_register_style( 'courses_filter' );
stm_lms_register_script( 'courses_filter' );
?>

<div class="stm_lms_my_course_bundles__vc stm_lms_bundles_with_filter">
    <?php require_once get_stylesheet_directory() .'/stm-lms-templates/vc_templates/templates/bundle_filter.php'; ?>
    <div class="stm_lms_bundles_with_filter_archive">
        <?php STM_LMS_Templates::show_lms_template(
            'bundles/card/php/list',
            compact( 'wishlist', 'columns', 'title', 'args', 'public' )
        ); ?>
    </div>
</div>
