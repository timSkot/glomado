<?php
    $id = get_the_ID();

    $glokit_title = get_post_meta($id, 'glokit_title', true);
    $glokit_description = get_post_meta($id, 'glokit_description', true);
    $glokit_img_id = intval(get_post_meta($id, 'glokit_img', true));
?>

<?php if($glokit_title): ?>
    <div class="stm_lms__course__glokit__title">
        <h2>
            <?php echo $glokit_title; ?>
        </h2>
    </div>
<?php endif; ?>

<?php if($glokit_title): ?>
    <div class="stm_lms__course__glokit__description">
        <?php echo $glokit_description; ?>
    </div>
<?php endif; ?>

<?php if($glokit_img_id): ?>
    <div class="stm_lms__course__glokit__image">
        <?php echo wp_get_attachment_image( $glokit_img_id, 'full' ) ?>
    </div>
<?php endif; ?>

<div class="stm_lms__course__glokit__tech">
    <h3>
        <?php echo esc_html__( 'TECHNICAL REQUIREMENTS', 'masterstudy-child' ); ?>
    </h3>
    <p>
        <?php echo esc_html__('You will need a device (computer or tablet) that is equipped with a camera, microphone, and speakers. For more information, please check out our Technical Requirements page.', 'masterstudy-child' ); ?>
    </p>
</div>
