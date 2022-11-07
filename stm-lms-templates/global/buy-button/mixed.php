<?php
/**
 * @var $course_id
 * @var $item_id
 */

use Bookly\Lib\Entities\Service;

stm_lms_register_script( 'buy-button', array( 'jquery.cookie' ) );
stm_lms_register_style( 'buy-button-mixed' );

$item_id      = ( ! empty( $item_id ) ) ? $item_id : '';
$has_course   = STM_LMS_User::has_course_access( $course_id, $item_id, false );
$course_price = STM_LMS_Course::get_course_price( $course_id );

if ( isset( $has_access ) ) {
    $has_course = $has_access;
}

$is_prerequisite_passed = true;

if ( class_exists( 'STM_LMS_Prerequisites' ) ) {
    $is_prerequisite_passed = STM_LMS_Prerequisites::is_prerequisite( true, $course_id );
}

do_action( 'stm_lms_before_button_mixed', $course_id );

if ( apply_filters( 'stm_lms_before_button_stop', false, $course_id ) && false == $has_course ) {
    return false;
}

global $bookly_services_sync;

$service = Service::find( $bookly_services_sync->getCurrentServiceId() );
$price_individual = '';
$price_small_group = '';
$price_large_group = '';
if($service) {
    $price_individual = Bookly\Lib\Utils\Price::format(intval($service->getPrice()));
    $price_small_group = Bookly\Lib\Utils\Price::format(intval($service->getPriceSmallGroup()));
    $price_large_group = Bookly\Lib\Utils\Price::format(intval($service->getPriceLargeGroup()));
}
 ?>

<div class="stm-lms-buy-buttons stm-lms-buy-buttons-mixed stm-lms-buy-buttons-mixed-pro">
    <div class="stm_lms_mixed_button subscription_enabled">
        <?php if(!$has_course): ?>
        <div class="buy-button btn btn-default btn_big heading_font">
            <span> <?php esc_html_e( 'Book Workshop', 'masterstudy-child' ); ?> </span>
        </div>
        <?php else: ?>
        <div class="stm_lms_mixed_button__list has-course">
            <button class="btn btn-default btn_big heading_font" data-course="<?php echo $course_id; ?>" data-purchased="<?php echo $has_course; ?>" data-learners="<?php echo intval($service->getPrice());?>" data-learners-label="Individual">
              <span><?php esc_html_e( 'Book Workshop', 'masterstudy-child' ); ?></span>
            </button>
        </div>
        <?php endif; ?>
        <?php if($service): ?>
        <div class="stm_lms_mixed_button__list">
            <?php if(!$has_course): ?>
                <button data-course="<?php echo $course_id; ?>" data-purchased="<?php echo $has_course; ?>" data-learners="<?php echo intval($service->getPrice());?>" data-learners-label="Individual"><span><?php esc_html_e('Individual', 'masterstudy-child'); ?><span class="price"><?php echo $price_individual; ?></span></span></button>
                <button data-course="<?php echo $course_id; ?>" data-purchased="<?php echo $has_course; ?>" data-learners="<?php echo intval($service->getPriceSmallGroup());?>" data-learners-label="Small Group"><span><?php esc_html_e('Small Group (2 - 5)', 'masterstudy-child'); ?><span class="price"><?php echo $price_small_group; ?></span></span></button>
                <button data-course="<?php echo $course_id; ?>" data-purchased="<?php echo $has_course; ?>" data-learners="<?php echo intval($service->getPriceLargeGroup());?>" data-learners-label="Large Group"><span><?php esc_html_e('Large Group (6 - 30)', 'masterstudy-child'); ?><span class="price"><?php echo $price_large_group; ?></span></span></button>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
