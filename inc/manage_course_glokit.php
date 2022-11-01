<?php

STM_LMS_Manage_Course_Glokit::init();

class STM_LMS_Manage_Course_Glokit extends STM_LMS_Manage_Course {
    public static function init() {
        add_action( 'wp_ajax_stm_lms_pro_save_front_course_glokit', 'STM_LMS_Manage_Course_Glokit::save_course_glokit' );
    }

    public static function save_course_glokit() {

        check_ajax_referer( 'stm_lms_pro_save_front_course', 'nonce' );

        $validation = new Validation();

        $required_fields = apply_filters(
            'stm_lms_manage_course_required_fields',
            array(
                'title'      => 'required',
                'category'   => 'required',
                'image'      => 'required|integer',
                'content'    => 'required',
                'price'      => 'float',
//                'curriculum' => 'required',
            )
        );

        $validation->validation_rules( $required_fields );

        $validation->filter_rules(
            array(
                'title'                      => 'trim|sanitize_string',
                'category'                   => 'trim|sanitize_string',
                'image'                      => 'sanitize_numbers',
                'content'                    => 'trim',
                'price'                      => 'sanitize_floats',
                'sale_price'                 => 'sanitize_floats',
//                'curriculum'                 => 'sanitize_string',
                'duration'                   => 'sanitize_string',
                'video'                      => 'sanitize_string',
                'prerequisites'              => 'sanitize_string',
                'prerequisite_passing_level' => 'sanitize_floats',
                'enterprise_price'           => 'sanitize_floats',
                'co_instructor'              => 'sanitize_floats',
            )
        );

        $validated_data = $validation->run( $_POST );

        if ( false === $validated_data ) {
            wp_send_json(
                array(
                    'status'  => 'error',
                    'message' => $validation->get_readable_errors( true ),
                )
            );
        }

        $user = STM_LMS_User::get_current_user();

        do_action( 'stm_lms_pro_course_data_validated', $validated_data, $user );

        $is_updated = ( ! empty( $validated_data['post_id'] ) );

        $course_id = self::create_course( $validated_data, $user, $is_updated );

        self::update_course_meta( $course_id, $validated_data );

        self::update_course_category( $course_id, $validated_data );

        self::update_course_image( $course_id, $validated_data );

        do_action( 'stm_lms_pro_course_added', $validated_data, $course_id, $is_updated );

        $course_url = get_the_permalink( $course_id );

        wp_send_json(
            array(
                'status'  => 'success',
                'message' => esc_html__( 'Course Saved, redirecting...', 'masterstudy-child' ),
                'url'     => $course_url,
            )
        );

    }

    public static function update_course_meta( $course_id, $data ) {
        /*Update Course Post Meta*/
        $post_metas = array(
            'price',
            'sale_price',
//            'curriculum',
            'faq',
            'announcement',
            'duration_info',
            'level',
            'prerequisites',
            'prerequisite_passing_level',
            'enterprise_price',
            'co_instructor',
            'course_files_pack',
            'video_duration',
            'glokit_title',
            'glokit_description',
            'glokit_img',
        );

        foreach ( $post_metas as $post_meta_key ) {
            if ( isset( $data[ $post_meta_key ] ) ) {
                update_post_meta( $course_id, $post_meta_key, $data[ $post_meta_key ] );
            }
        }

    }
}