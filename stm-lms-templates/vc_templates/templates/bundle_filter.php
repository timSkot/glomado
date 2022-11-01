<?php
$filters = array(
	'category',
//	'subcategory',
	'status',
	'levels',
	'rating',
	'instructor',
	'price',
);
?>

<div class="stm_lms_courses__archive_filter">

	<a href="#" class="btn btn-default stm_lms_courses__archive_filter_toggle">
		<?php esc_html_e( 'Filters', 'masterstudy-lms-learning-management-system' ); ?>
	</a>

	<form id="bundle_filter_form">

		<div class="stm_lms_courses__archive_filters">

			<?php
			foreach ( $filters as $filter ) :

				if ( ! STM_LMS_Options::get_option( "enable_courses_filter_{$filter}", '' ) ) {
					continue;
				}

				STM_LMS_Templates::show_lms_template( "courses/advanced_filters/filters/{$filter}" );

			endforeach;
			?>

			<div class="stm_lms_courses__filter_actions">
				<input type="submit"
					class="heading_font"
					value="<?php esc_attr_e( 'Show Results', 'masterstudy-lms-learning-management-system' ); ?>">
				<a href="/bundles"
					class="stm_lms_courses__filter_reset">
					<i class="lnr lnr-undo"></i>
					<span><?php esc_html_e( 'Reset all', 'masterstudy-lms-learning-management-system' ); ?></span>
				</a>
			</div>
		</div>
		<input type="hidden" name="search" value=""/>
		<input type="hidden" name="is_lms_filter" value="1"/>
	</form>
</div>