<?php

use Bookly\Lib\Entities\StaffService;

$bookly_services_sync = new Glomado_Services_Sync_Bookly();
class Glomado_Services_Sync_Bookly {

    private $post_type = 'post';
    private $bookly_service_id;

    public function getCurrentServiceId()
    {
        $queried_object = get_queried_object();
        $queried_object_id = get_queried_object_id();

        if ( is_singular() && $queried_object instanceof \WP_Post ) {

            if(metadata_exists( $this->post_type, $queried_object_id, 'bookly_services' )) {
                $this->bookly_service_id = get_post_meta($queried_object_id, 'bookly_services', true);
            }
        }

        return $this->bookly_service_id;
    }

    public function shortcode_pre_render(&$shortcode)
    {
        $serviceId = $this->getCurrentServiceId();

        $staff_ids = StaffService::query( 'ss' )
            ->leftJoin( 'Staff', 's', 's.id = ss.staff_id' )
            ->where( 'ss.service_id', $serviceId )
            ->where( 's.visibility', 'public' )
            ->fetchCol( 'ss.staff_id' );

        $shortcode = str_replace(']', ' service_id="' . $serviceId . '" staff_member_id="' . $staff_ids[0] . '"]', $shortcode);

        return $shortcode;
    }
}