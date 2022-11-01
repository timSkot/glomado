<?php

use Glomado\Bookly\Backend\Modules\Services\Glomado_Services;

add_filter(
    'stm_wpcfto_fields',
    function ($fields) {

        // Woocommerce Products
        $products_woo = get_products_glokit_woo();

        $products_woo_default_fields = array(
            '' => esc_html__( 'Select product', 'masterstudy-child' ),
        );

        $products_woo_fields = [];

        foreach($products_woo as $products) {
            $products_woo_fields[$products->ID] = $products->post_title;
        }

        $products_woo_default_fields += $products_woo_fields;

        // Bookly Services
        $bookly_services = new Glomado_Services;
        $bookly_services = $bookly_services->getServices();
        $bookly_services_default_fields = array(
            '' => esc_html__( 'Select service', 'masterstudy-child' ),
        );

        $bookly_services_fields = [];

        foreach($bookly_services['data'] as $service) {
            $bookly_services_fields[$service['id']] = $service['title'];
        }

        $bookly_services_default_fields += $bookly_services_fields;


        $decimals_num = STM_LMS_Options::get_option( 'decimals_num', 2 );
        $zeros        = str_repeat( '0', intval( $decimals_num ) - 1 );
        $step         = "0.{$zeros}1";

        $currency = STM_LMS_Helpers::get_currency();
        $course_levels = array(
            '' => esc_html__( 'Select level', 'masterstudy-child' ),
        );
        $course_levels += STM_LMS_Helpers::get_course_levels();

        $certificates = ( class_exists( 'WPCFTO_Settings' ) ) ? WPCFTO_Settings::stm_get_post_type_array( 'stm-certificates' ) : array();

        $fields['stm_courses_settings'] = array(
            'section_settings'      => array(
                'name'   => esc_html__( 'Settings', 'masterstudy-child' ),
                'label'  => esc_html__( 'General Settings', 'masterstudy-child' ),
                'icon'   => 'fa fa-cog',
                'fields' => array(
                    'featured'         => array(
                        'type'  => 'checkbox',
                        'label' => esc_html__( 'Featured Course', 'masterstudy-child' ),
                        'hint'  => esc_html__( 'Mark this checkbox to add badge to course "Featured".', 'masterstudy-child' ),
                    ),
                    'views'            => array(
                        'type'     => 'number',
                        'label'    => esc_html__( 'Course Views', 'masterstudy-child' ),
                        'sanitize' => 'wpcfto_save_number',
                        'hint'     => esc_html__( 'Field increments automatically when somebody views the course. But you can set certain amount of views.', 'masterstudy-child' ),
                    ),
                    'level'            => array(
                        'type'    => 'select',
                        'label'   => esc_html__( 'Course Level', 'masterstudy-child' ),
                        'options' => $course_levels,
                    ),
                    'current_students' => array(
                        'type'     => 'number',
                        'label'    => esc_html__( 'Current students', 'masterstudy-child' ),
                        'sanitize' => 'wpcfto_save_number',
                    ),
                    'duration_info'    => array(
                        'type'  => 'text',
                        'label' => esc_html__( 'Duration info', 'masterstudy-child' ),
                    ),
                    'video_duration'   => array(
                        'type'  => 'text',
                        'label' => esc_html__( 'Video Duration', 'masterstudy-child' ),
                    ),
                    'status'           => array(
                        'group'   => 'started',
                        'type'    => 'radio',
                        'label'   => esc_html__( 'Status', 'masterstudy-child' ),
                        'options' => array(
                            ''        => esc_html__( 'No status', 'masterstudy-child' ),
                            'hot'     => esc_html__( 'Hot', 'masterstudy-child' ),
                            'new'     => esc_html__( 'New', 'masterstudy-child' ),
                            'special' => esc_html__( 'Special', 'masterstudy-child' ),
                        ),
                    ),
                    'status_dates'     => array(
                        'group'      => 'ended',
                        'type'       => 'dates',
                        'label'      => esc_html__( 'Status Dates', 'masterstudy-child' ),
                        'sanitize'   => 'wpcfto_save_dates',
                        'dependency' => array(
                            'key'   => 'status',
                            'value' => 'not_empty',
                        ),
                    ),
                ),
            ),
            'section_accessibility' => array(
                'name'   => esc_html__( 'Course Price', 'masterstudy-child' ),
                'label'  => esc_html__( 'Accessibility', 'masterstudy-child' ),
                'icon'   => 'fas fa-dollar-sign',
                'fields' => array(

                    /*GROUP STARTED*/
                    'not_single_sale'       => array(
                        'group' => 'started',
                        'type'  => 'checkbox',
                        'label' => esc_html__( 'One-time purchase', 'masterstudy-child' ),
                        'hint'  => esc_html__( 'Disable one time purchase to make course available only from subscription plans. Also, you can make course free by leaving price field empty', 'masterstudy-child' ),
                    ),
                    'price'                 => array(
                        'type'        => 'number',
                        'label'       => sprintf(
                        /* translators: %s: number */
                            esc_html__( 'Price (%s)', 'masterstudy-child' ),
                            $currency
                        ),
                        'placeholder' => sprintf( esc_html__( 'Leave empty if course is free', 'masterstudy-child' ), $currency ),
                        'sanitize'    => 'wpcfto_save_number',
                        'step'        => $step,
                        'columns'     => 50,
                        'dependency'  => array(
                            'key'   => 'not_single_sale',
                            'value' => 'empty',
                        ),
                    ),
                    'sale_price'            => array(
                        'type'        => 'number',
                        'label'       => sprintf(
                        /* translators: %s: number */
                            esc_html__( 'Sale Price (%s)', 'masterstudy-child' ),
                            $currency
                        ),
                        'placeholder' => sprintf( esc_html__( 'Leave empty if no sale price', 'masterstudy-child' ), $currency ),
                        'sanitize'    => 'wpcfto_save_number',
                        'step'        => $step,
                        'columns'     => 50,
                        'dependency'  => array(
                            'key'   => 'not_single_sale',
                            'value' => 'empty',
                        ),
                    ),
                    'sale_price_dates'      => array(
                        'group'      => 'ended',
                        'type'       => 'dates',
                        'label'      => esc_html__( 'Sale Price Dates', 'masterstudy-child' ),
                        'sanitize'   => 'wpcfto_save_dates',
                        'dependency' => array(
                            'key'   => 'sale_price',
                            'value' => 'not_empty',
                        ),
                        'pro'        => true,
                    ),
                    /*GROUP ENDED*/

                    'enterprise_price'      => array(
                        'pre_open' => true,
                        'type'     => 'number',
                        'label'    => sprintf(
                        /* translators: %s: dollar */
                            esc_html__( 'Enterprise Price (%s)', 'masterstudy-child' ),
                            $currency
                        ),
                        'hint'     => sprintf( esc_html__( 'Price for group. Leave empty to disable group purchase', 'masterstudy-child' ), $currency ),
                        'pro'      => true,
                        'disabled' => true,
                    ),

                    'not_membership'        => array(
                        'type'  => 'checkbox',
                        'label' => esc_html__( 'Not included in membership', 'masterstudy-child' ),
                    ),
                    'affiliate_course'      => array(
                        'group'   => 'started',
                        'type'    => 'checkbox',
                        'label'   => esc_html__( 'Affiliate course', 'masterstudy-child' ),
                        'pro'     => true,
                        'pro_url' => 'https://stylemixthemes.com/wordpress-lms-plugin/?utm_source=wpadmin&utm_medium=ms-udemy&utm_campaign=masterstudy-plugin',
                    ),
                    'affiliate_course_text' => array(
                        'type'       => 'text',
                        'label'      => esc_html__( 'Button Text', 'masterstudy-child' ),
                        'dependency' => array(
                            'key'   => 'affiliate_course',
                            'value' => 'not_empty',
                        ),
                        'columns'    => 50,
                        'pro'        => true,
                    ),
                    'affiliate_course_link' => array(
                        'group'      => 'ended',
                        'type'       => 'text',
                        'label'      => esc_html__( 'Button Link', 'masterstudy-child' ),
                        'dependency' => array(
                            'key'   => 'affiliate_course',
                            'value' => 'not_empty',
                        ),
                        'columns'    => 50,
                        'pro'        => true,
                    ),
                ),
            ),
            'section_expiration'    => array(
                'name'   => esc_html__( 'Expiration', 'masterstudy-child' ),
                'icon'   => 'far fa-clock',
                'fields' => array(
                    'expiration_course' => array(
                        'group' => 'started',
                        'type'  => 'checkbox',
                        'label' => esc_html__( 'Time limit', 'masterstudy-child' ),
                    ),
                    'end_time'          => array(
                        'group'      => 'ended',
                        'type'       => 'number',
                        'label'      => esc_html__( 'Course expiration (days)', 'masterstudy-child' ),
                        'value'      => 3,
                        'dependency' => array(
                            'key'   => 'expiration_course',
                            'value' => 'not_empty',
                        ),
                    ),
                ),
            ),
            'section_drip_content'  => array(
                'name'   => esc_html__( 'Content Drip', 'masterstudy-child' ),
                'icon'   => 'fas fa-list',
                'fields' => array(
                    'drip_content' => array(
                        'type'      => 'drip_content',
                        'post_type' => array( 'stm-lessons', 'stm-quizzes' ),
                        'label'     => esc_html__( 'Sequential Drip Content', 'masterstudy-child' ),
                        'pro'       => true,
                    ),
                ),
            ),
            'section_prereqs'       => array(
                'name'   => esc_html__( 'Prerequisites', 'masterstudy-child' ),
                'icon'   => 'fas fa-flag-checkered',
                'fields' => array(
                    'prerequisites'              => array(
                        'type'      => 'autocomplete',
                        'post_type' => array( 'stm-courses' ),
                        'label'     => esc_html__( 'Prerequisite Courses', 'masterstudy-child' ),
                        'pro'       => true,
                    ),
                    'prerequisite_passing_level' => array(
                        'type'        => 'text',
                        'classes'     => array( 'short_field' ),
                        'placeholder' => esc_html__( 'Percent (%)', 'masterstudy-child' ),
                        'label'       => esc_html__( 'Prerequisite Passing Percent (%)', 'masterstudy-child' ),
                        'pro'         => true,
                    ),
                ),
            ),
            'section_announcement'  => array(
                'name'   => esc_html__( 'Announcement', 'masterstudy-child' ),
                'icon'   => 'fas fa-bullhorn',
                'fields' => array(
                    'announcement' => array(
                        'type'     => 'editor',
                        'label'    => esc_html__( 'Announcement', 'masterstudy-child' ),
                        'sanitize' => 'wpcfto_sanitize_editor',
                    ),
                ),
            ),
            'section_faq'           => array(
                'name'   => esc_html__( 'FAQ', 'masterstudy-child' ),
                'icon'   => 'fas fa-question',
                'fields' => array(
                    'faq' => array(
                        'type'  => 'faq',
                        'label' => esc_html__( 'FAQ', 'masterstudy-child' ),
                    ),
                ),
            ),
            'section_files'         => array(
                'name'   => esc_html__( 'Course files', 'masterstudy-child' ),
                'icon'   => 'fas fa-download',
                'fields' => array(
                    'course_files_pack' => stm_lms_course_files_data(),
                ),
            ),
            'section_certificate'   => array(
                'name'   => esc_html__( 'Certificate', 'masterstudy-child' ),
                'icon'   => 'fas fa-certificate',
                'fields' => array(
                    'course_certificate' => array(
                        'type'    => 'select',
                        'label'   => esc_html__( 'Select Certificate', 'masterstudy-child' ),
                        'options' => $certificates,
                        'value'   => '',
                        'pro'     => true,
                        'classes' => array( 'short_field' ),
                    ),
                ),
            ),
            'section_glokit'   => array(
                'name'   => esc_html__( 'GLOkit', 'masterstudy-child' ),
                'icon'   => 'fas fa-cube',
                'fields' => array(
                    'glokit_title' => array(
                        'type'  => 'text',
                        'label' => esc_html__( 'Title', 'masterstudy-child' ),
                    ),
                    'glokit_description' => array(
                        'type'     => 'editor',
                        'label'    => esc_html__( 'Description', 'masterstudy-child' ),
                        'sanitize' => 'wpcfto_sanitize_editor',
                    ),
                    'glokit_img' => array(
                        'type' => 'image',
                        'label' => esc_html__('Image', 'masterstudy-child'),
                    ),
                    'glokit_product' => array(
                        'type' => 'select',
                        'label' => esc_html__('Select Woocommerce Product', 'masterstudy-child'),
                        'options' => $products_woo_default_fields
                    )
                )
            ),
            'section_bookly' => array(
                'name'   => esc_html__( 'Bookly Services', 'masterstudy-child' ),
                'icon'   => 'fas fa-calendar',
                'fields' => array(
                    'bookly_services' => array(
                        'type' => 'select',
                        'label' => esc_html__('Select Bookly Service', 'masterstudy-child'),
                        'options' => $bookly_services_default_fields
                    )
                )
            )
        );

        return $fields;
    }
);

function get_products_glokit_woo() {
    return get_posts( array(
        'post_type' => 'product',
        'numberposts' => -1,
        'post_status' => 'publish',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => 'glokit',
                'operator' => 'IN',
            )
        ),
    ) );
}