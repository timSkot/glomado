<?php if (!defined('ABSPATH')) exit; //Exit if accessed directly ?>

<div class="stm_metaboxes_grid">
    <div class="stm_metaboxes_grid__inner">
        <input type="text"
               name="glokit_title"
               placeholder="Enter Title"
               v-model="glokit.title"/>

        <div class="stm_lms_glokit__description">
            <?php STM_LMS_Templates::show_lms_template('manage_course/forms/js/editor'); ?>

            <div class="stm_lms_manage_course__editor">
                <stm-editor v-bind:content="glokit.description"
                            v-bind:listener="false"
                            v-bind:content_edited.sync="glokit.description"
                            v-on:content-changed="glokit.description = $event"></stm-editor>

                <textarea class="hidden" v-model="glokit.description"></textarea>
            </div>
        </div>

        <div class="stm_lms_glokit__image">
            <?php STM_LMS_Templates::show_lms_template('manage_course/forms/js/glokit_image'); ?>

            <glokit-image v-bind:image_id="glokit.img"
                       v-on:image-changed="glokit.img = $event"></glokit-image>

            <input type="hidden" v-model="glokit.img" />
        </div>
    </div>
</div>
