<?php
STM_LMS_Course_Bundle_Helper::init();

class STM_LMS_Course_Bundle_Helper {
    public static function init() {
        add_action( 'wp_ajax_stm_lms_load_bundle', 'STM_LMS_Course_Bundle_Helper::load_bundle' );
        add_action( 'wp_ajax_nopriv_stm_lms_load_bundle', 'STM_LMS_Course_Bundle_Helper::load_bundle' );
    }

    public static function load_bundle() {

        check_ajax_referer( 'load_bundle', 'nonce' );

        $tpl = sanitize_text_field( 'courses/grid' );

        $args = ( ! empty( $_GET['args'] ) ) ? json_decode( stripslashes( sanitize_text_field( $_GET['args'] ) ), true ) : array();

        if ( ! empty( $_GET['search'] ) ) {
            $args['s'] = sanitize_text_field( $_GET['search'] );
        }

        if ( ! empty( $_GET['is_lms_filter'] ) ) {
            if ( ! empty( $args['meta_query'] ) ) {
                unset( $args['meta_query'] );
            }
            if ( ! empty( $args['tax_query'] ) ) {
                unset( $args['tax_query'] );
            }
        }

        $pp = ( ! empty( $_GET['per_page'] ) ) ? intval( $_GET['per_page'] ) : get_option( 'posts_per_page' );

        $args['featured']    = ( ! empty( $_GET['featured'] ) && 'true' == $_GET['featured'] ) ? true : false;
        $args['is_featured'] = $args['featured'];

        $args['posts_per_page'] = ( ! empty( $args['posts_per_page'] ) ) ? $args['posts_per_page'] : $pp;

        $args['offset'] = ( ! empty( $_GET['offset'] ) ) ? intval( $_GET['offset'] ) : 0;

        $page = $args['offset'];

        $args['offset'] = $args['offset'] * $args['posts_per_page'];

        $args['isAjax'] = true;

        $sort = '';
        if ( ! empty( $_GET['sort'] ) ) {
            $sort = sanitize_text_field( $_GET['sort'] );
        }
        if ( ! empty( $args['sort'] ) ) {
            $sort = sanitize_text_field( $args['sort'] );
        }

        if ( ! empty( $sort ) ) {
            $args = array_merge( $args, self::sort_query( $sort ) );
        }

        $link = STM_LMS_Course::courses_page_url();

        if ( ! empty( $args['term'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'stm_lms_course_taxonomy',
                    'field'    => 'term_id',
                    'terms'    => intval( $args['term'] ),
                ),
            );

            $link = get_term_link( intval( $args['term'] ), 'stm_lms_course_taxonomy' );
        }

        $args['post_status'] = 'publish';

        $args         = apply_filters( 'stm_lms_archive_filter_args', $args );
        $default_args = array(
            'post_type'      => 'stm-courses',
            'posts_per_page' => -1,
        );
        $args         = wp_parse_args( $args, $default_args );
        $args['posts_per_page'] = -1;
        $query_result = new WP_Query( $args );

        $args_bundle = array(
            'post_type' => 'stm-course-bundles',
            'post_status' => 'publish',
        );
        $bundles = new WP_Query($args_bundle);

        $posts_ids = [];
        $i = 0;
        foreach ($query_result->posts as $post) {
            $posts_ids[] = $post->ID;
            $i++;
        }

        $bundle_ids = [];
        $i_b = 0;
        foreach ($bundles->posts as $bundle) {
            foreach ($posts_ids as $id) {
                if(in_array($id, get_post_meta($bundle->ID, 'stm_lms_bundle_ids', true))){
                    $image = wp_get_attachment_url( get_post_thumbnail_id($id), 'thumbnail' );
                    $price = get_post_meta($id, 'price', true);

                    $bundle_rating = STM_LMS_Course_Bundle::get_bundle_rating($bundle->ID);
                    $bundle_course_price = STM_LMS_Course_Bundle::get_bundle_courses_price($bundle->ID);

                    $bundle_ids[$i_b]['bundle'] = $bundle;
                    $bundle_ids[$i_b]['bundle']->rating = $bundle_rating;
                    $bundle_ids[$i_b]['bundle']->price = $bundle_course_price;

                    $bundle_ids[$i_b]['courses'][$id] = array(
                        'id' => $id,
                        'title' => get_the_title( $id ),
                        'link' => get_the_permalink( $id ),
                        'image' => $image,
                        'price' => STM_LMS_Helpers::display_price(floatval($price)),
                    );
                }
            }
            $i_b++;
        }

        wp_send_json( $bundle_ids );
    }
}

add_action( 'admin_head', 'bundle_nonces' );
add_action( 'wp_head', 'bundle_nonces' );

function bundle_nonces() {
    $nonces = array(
        'load_bundle',
    );

    $nonces_list = array();

    foreach ( $nonces as $nonce_name ) {
        $nonces_list[ $nonce_name ] = wp_create_nonce( $nonce_name );
    }

    ?>
    <script>
        var bundle_nonces = <?php echo json_encode( $nonces_list ); ?>;
    </script>
    <?php
}