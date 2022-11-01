<?php

add_action( 'woocommerce_before_calculate_totals', 'bookly_custom_price' );

function bookly_custom_price( $cart_object ) {

    foreach ( $cart_object->get_cart() as $item ) {

        if( array_key_exists( 'bookly_price', $item ) ) {
            $item[ 'data' ]->set_price( $item[ 'bookly_price' ] );
        }

    }

}

add_action('woocommerce_checkout_order_processed', 'wh_pre_paymentcall');

function wh_pre_paymentcall($order_id) {

  //create an order instance
  $current_user = wp_get_current_user();
  $current_user_id = $current_user->ID;
  update_user_meta( $current_user_id, 'last_wc_order', $order_id );

}


add_action( 'woocommerce_payment_complete', 'so_payment_complete' );
function so_payment_complete( $order_id ){

  $current_user = wp_get_current_user();
  $current_user_id = $current_user->ID;
  if( $current_user_id ){
    $last_order = get_user_meta($current_user_id, 'last_wc_order', true);
    if($last_order == $order_id) {
      $order = wc_get_order( $last_order );
      $order->update_status( 'completed' );
      $appointment_id = get_user_meta($current_user_id, 'last_appointment', true);
      $product_app_id = null;
      $product_ids = [];

      foreach ($order->get_items() as $item ) {
        $product_ids[] = get_post_meta($item->get_product_id(), 'last_appointment', true);
      }

      if(!empty($product_ids)) {
        if(count($product_ids) > 1) {
          if(count(array_unique($product_ids)) == 1) {
            $product_app_id = array_unique($product_ids)[0];
          }
        } else {
          $product_app_id = $product_ids[0];
        }
      }

      if($product_app_id == $appointment_id) {

        global $wpdb;
        $table_name = $wpdb->prefix.'bookly_customer_appointments';
        $data_update = array('status' => 'approved');
        $data_where = array('appointment_id' => $appointment_id);
        $wpdb->update($table_name , $data_update, $data_where);

        $appointment_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bookly_appointments WHERE id = {$appointment_id}", OBJECT );
        $zoom_data = json_decode($appointment_data[0]->online_meeting_data, true);
        $start_url = null;
        $service_name = null;
        if($zoom_data) {
          $start_url = $zoom_data['start_url'];
          $service_name = $zoom_data['topic'];
        }

        $user_id = $wpdb->get_results( "SELECT customer_id FROM {$wpdb->prefix}bookly_customer_appointments WHERE appointment_id = {$appointment_id}", OBJECT );
        $customer_id = $user_id[0]->customer_id;

        $customer_data = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bookly_customers WHERE id = {$customer_id}", OBJECT );

        $notification = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}bookly_notifications WHERE id = 1", OBJECT );

        $d=strtotime($appointment_data[0]->start_date);

        $customer_email = $customer_data[0]->email;
        $customer_name = $customer_data[0]->full_name;
        $appointment_date = date("d M, Y", $d);
        $appointment_time = date("H:i", $d);
        $company_name = get_option('bookly_co_name');
        $company_address = get_option('bookly_co_address');
        $company_phone = get_option('bookly_co_phone');
        $company_website = get_option('bookly_co_website');
        $notification_subject = $notification[0]->subject;
        $notification_message = $notification[0]->message;

        $search = ["{client_name}", "{service_name}", "{company_address}", "{appointment_date}", "{appointment_time}", "{online_meeting_start_url}", "{company_name}", "{company_phone}", "{company_website}"];
        $replace = [$customer_name, $service_name, $company_address, $appointment_date, $appointment_time, $start_url, $company_name, $company_phone, $company_website];

        $notification_message = str_replace($search, $replace, $notification_message);

        wp_mail( $customer_email, $notification_subject, $notification_message );
      }

    }
  }
}
