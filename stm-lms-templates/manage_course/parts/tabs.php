<?php if ( ! defined( 'ABSPATH' ) ) {
    exit;
} //Exit if accessed directly ?>


<?php
$tabs = array(
    'description'  => esc_html__( 'Description', 'masterstudy-child' ),
//    'curriculum'   => esc_html__( 'Curriculum', 'masterstudy-child' ),
    'glokit'   => esc_html__( 'GLOkit', 'masterstudy-child' ),
    'faq'          => esc_html__( 'FAQ', 'masterstudy-child' ),
    'announcement' => esc_html__( 'Announcement', 'masterstudy-child' ),
);

$active = 'description';
?>

<div class="nav-tabs-wrapper">
    <ul class="nav nav-tabs" role="tablist">

        <?php foreach ( $tabs as $slug => $name ) : ?>
            <li role="presentation" class="<?php echo ( $slug === $active ) ? 'active' : ''; ?>">
                <a href="<?php echo esc_attr( $slug ); ?>"
                   data-toggle="tab">
                    <?php echo wp_kses_post( $name ); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>


<div class="tab-content">
    <?php foreach ( $tabs as $slug => $name ) : ?>
        <div role="tabpanel"
             class="tab-pane <?php echo ( $slug === $active ) ? 'active' : ''; ?>"
             id="<?php echo esc_attr( $slug ); ?>">

            <?php if ( ! STM_LMS_Options::get_option( "course_tab_{$slug}", true ) ) : ?>
                <div class="stm-lms-message error">
                    <?php
                    printf(
                        esc_html__( 'The %s tab was temporarily disabled by the admin', 'masterstudy-child' ),
                        esc_html( $name )
                    );
                    ?>
                </div>
            <?php endif; ?>

            <?php STM_LMS_Templates::show_lms_template( 'manage_course/parts/tabs/' . $slug ); ?>

        </div>
    <?php endforeach; ?>
</div>
