<?php
add_filter( 'stm_lms_course_tabs', 'course_tabs_glokit', 20, 2 );
function course_tabs_glokit( $tabs, $id )
{
    $glokit_title = get_post_meta($id, 'glokit_title', true);
    $glokit_description = get_post_meta($id, 'glokit_description', true);
    $glokit_img_id = intval(get_post_meta($id, 'glokit_img', true));

    if ( !($glokit_title || $glokit_description || $glokit_img_id) ) {
        unset( $tabs['glokit'] );
    }

    return $tabs;
}