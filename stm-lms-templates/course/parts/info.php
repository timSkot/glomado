<?php if ( ! defined( 'ABSPATH' ) ) exit; //Exit if accessed directly ?>

<?php
/**
 * @var $course_id
 */

stm_lms_register_style('course_info');

$meta = STM_LMS_Helpers::parse_meta_field($course_id);
$meta_fields = array();

if(!empty($meta['current_students'])) {
    $meta_fields[esc_html__('Enrolled', 'masterstudy-child')] = array(
        'text' => sprintf(_n('%s student', '%s students', $meta['current_students'], 'masterstudy-child'), $meta['current_students']),
        'icon' => 'fa-icon-stm_icon_users'
    );
}
else {
    $meta_fields[esc_html__('Enrolled', 'masterstudy-child')] = array(
        'text' => sprintf(_n('%s student', '%s students', 0, 'masterstudy-child'), 0),
        'icon' => 'fa-icon-stm_icon_users'
    );
}

if(!empty($meta['duration_info'])) {
    $meta_fields[esc_html__('Duration', 'masterstudy-child')] = array(
        'text' => $meta['duration_info'],
        'icon' => 'fa-icon-stm_icon_clock'
    );
}

if(!empty($meta['curriculum'])) {
    $curriculum_info = STM_LMS_Course::curriculum_info($meta['curriculum']);
    $meta_fields[esc_html__('Lectures', 'masterstudy-child')] = array(
        'text' => $curriculum_info['lessons'],
        'icon' => 'fa-icon-stm_icon_bullhorn'
    );
}

if(!empty($meta['video_duration'])) {
    $meta_fields[esc_html__('Video', 'masterstudy-child')] = array(
        'text' => $meta['video_duration'],
        'icon' => 'fa-icon-stm_icon_film-play'
    );
}

if(!empty($meta['level'])) {
    $levels = STM_LMS_Helpers::get_course_levels();
    $meta_fields[esc_html__('Level', 'masterstudy-child')] = array(
        'text' => $levels[$meta['level']],
        'icon' => 'lnricons-chart-growth'
    );
}

if(!empty($meta['glokit_product']) || $meta['glokit_product'] != 0) {
    $stock = get_post_meta( $meta['glokit_product'], '_stock', true );
    $meta_fields[esc_html__('GLOkit available', 'masterstudy-child')] = array(
        'text' => $stock,
        'icon' => 'fa-icon-stm_icon_ecommerce_cart'
    );
}

if(!empty($meta_fields)): ?>
    <div class="stm-lms-course-info heading_font">
        <?php foreach($meta_fields as $meta_field_key => $meta_field): ?>
            <div class="stm-lms-course-info__single">
                <div class="stm-lms-course-info__single_label">
                    <span><?php echo sanitize_text_field($meta_field_key) ?></span>:
                    <strong><?php echo sanitize_text_field($meta_field['text']); ?></strong>
                </div>
                <div class="stm-lms-course-info__single_icon">
                    <i class="<?php echo sanitize_text_field($meta_field['icon']); ?>"></i>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="stm-lms-course-info"><div class="stm-lms-course-info__single"></div></div>
<?php endif;