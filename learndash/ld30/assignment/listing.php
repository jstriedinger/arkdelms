<?php
/**
 * lesson/topic assignment uploads list.
 *
 * If ther user has previouly uploaded assignment files they will be show via this template
 *
 * Available Variables:
 *
 * $course_step_post: WP_Post object for the Lesson/Topic being shown
 *
 * @since 3.0
 *
 * @package LearnDash\Lesson
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $course_step_post ) && $course_step_post instanceof WP_Post ) :

	$post_settings = learndash_get_setting( $course_step_post->ID );
	$assignments   = learndash_get_user_assignments( $course_step_post->ID, $user_id );


	/**
	 * Fires before the assignment list alerts.
	 *
	 * @since 3.0.0
	 *
	 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
	 * @param int $course_id           Course ID.
	 * @param int $user_id             User ID.
	 */
	do_action( 'learndash-assignment-alerts-before', $course_step_post->ID, $course_id, $user_id );

	$assignment_messages = get_user_meta( get_current_user_id(), 'ld_assignment_message', true );

	if ( ! empty( $assignment_messages ) && is_array( $assignment_messages ) ) :
		foreach ( $assignment_messages as $assignment_message ) :
			if ( ( isset( $assignment_message['message'] ) && ! empty( $assignment_message['message'] ) ) && ( isset( $assignment_message['type'] ) && ! empty( $assignment_message['type'] ) ) ) :

				$message = array(
					'type'    => 'warning',
					'icon'    => 'alert',
					'message' => $assignment_message['message'],
				);

				if ( 'success' === $assignment_message['type'] ) {
					$message['type'] = 'success';
					$message['icon'] = 'checkmark';
				}

				/**
				 * Filters message shown after assignment upload.
				 *
				 * @param string $message          The message showed after the assignment upload.
				 * @param int $course_step_post_id Course step post ID.
				 * @param int $course_id           Course ID.
				 * @param int $user_id             User ID.
				 */
				$message = apply_filters( 'learndash_assignment_upload_message', $message, $course_step_post->ID, $course_id, $user_id );

				learndash_get_template_part(
					'modules/alert.php',
					array(
						'type'    => $message['type'],
						'icon'    => $message['icon'],
						'message' => $message['message'],
					),
					true
				);

			endif;
		endforeach;

		delete_user_meta( get_current_user_id(), 'ld_assignment_message' );

	endif;


	/**
	 * Fires after the assignment list alerts.
	 *
	 * @since 3.0.0
	 *
	 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
	 * @param int $course_id           Course ID.
	 * @param int $user_id             User ID.
	 */
	do_action( 'learndash-assignment-alerts-after', $course_step_post->ID, $course_id, $user_id );

	// Default to empty to prevent count errors
	if ( ! $assignments || empty( $assignments ) ) {
		$assignments = array();
	}

	/**
	 * Fires before the assignment list.
	 *
	 * @since 3.0.0
	 *
	 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
	 * @param int $course_id           Course ID.
	 * @param int $user_id             User ID.
	 */
	do_action( 'learndash-assignment-list-before', $course_step_post->ID, $course_id, $user_id ); ?>

	<?php
	$assignment_stats = learndash_get_assignment_progress( $assignments );

	if ( absint( $assignment_stats['complete'] ) !== absint( $assignment_stats['total'] ) ) :

		/**
		 * Fires before the assignment approval alert.
		 *
		 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
		 * @param int $course_id           Course ID.
		 * @param int $user_id             User ID.
		 */
		do_action( 'learndash-assignment-list-before-aproval-alert', $course_step_post->ID, $course_id, $user_id );

		$approval_needed = $assignment_stats['total'] - $assignment_stats['complete'];

		/*learndash_get_template_part(
			'modules/alert.php',
			array(
				'type'    => 'warning',
				'icon'    => 'alert',
				'message' => ( $approval_needed > 1 ? __( 'You have assignments awaiting approval.', 'learndash' ) : __( 'You have an assignment awaiting approval.', 'learndash' ) ),
			),
			true
		);*/

		/**
		 * Fires after the assignment approval alert.
		 *
		 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
		 * @param int $course_id           Course ID.
		 * @param int $user_id             User ID.
		 */
		do_action( 'learndash-assignment-list-after-aproval-alert', $course_step_post->ID, $course_id, $user_id );

	endif;


	/**
	 * Fires after the alert and before the table in assignment list.
	 *
	 * @since 3.0.0
	 *
	 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
	 * @param int $course_id           Course ID.
	 * @param int $user_id             User ID.
	 */
	do_action( 'learndash-assignment-list-after-alert-before-table', $course_step_post->ID, $course_id, $user_id );
	?>

	<div class="ld-table-list ld-assignment-list arkde">
		<?php
		/**
		 * Fires after the assignment list header.
		 *
		 * @since 3.0.0
		 *
		 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
		 * @param int $course_id           Course ID.
		 * @param int $user_id             User ID.
		 */
		do_action( 'learndash-assignment-list-header-after', $course_step_post->ID, $course_id, $user_id );
		?>

		<div class="ld-table-list-items">

			<?php

			/** This action is documented in themes/ld30/templates/assignment/listing.php */
			do_action( 'learndash-assignment-list-before', $course_step_post->ID, $course_id, $user_id );

			if ( ! empty( $assignments ) ) :

				$assignment_post_type_object = get_post_type_object( 'sfwd-assignment' );

				foreach ( $assignments as $assignment ) :
					learndash_get_template_part(
						'assignment/partials/row.php',
						array(
							'assignment'                  => $assignment,
							'post_settings'               => $post_settings,
							'course_id'                   => $course_id,
							'user_id'                     => $user_id,
							'assignment_post_type_object' => $assignment_post_type_object,
						),
						true
					);
				endforeach;

				else :

					esc_html_x( 'No assignments submitted at this time', 'No assignments message', 'learndash' );

			endif;

				/**
				 * Fires after the assignment list.
				 *
				 * @since 3.0.0
				 *
				 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
				 * @param int $course_id           Course ID.
				 * @param int $user_id             User ID.
				 */
				do_action( 'learndash-assignment-list-after', $course_step_post->ID, $course_id, $user_id );

				learndash_get_template_part(
					'assignment/upload.php',
					array(
						'post_settings'    => $post_settings,
						'course_step_post' => $course_step_post,
						'user_id'          => $user_id,
						'course_id'        => $course_id,
					),
					true
				);


			/**
			 * Fires after the assignment upload.
			 *
			 * @since 3.0.0
			 *
			 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
			 * @param int $course_id           Course ID.
			 * @param int $user_id             User ID.
			 */
			do_action( 'learndash-assignment-upload-after', $course_step_post->ID, $course_id, $user_id );
			?>


			</div> <!--/.ld-table-list-items-->


		<div class="ld-table-list-footer"></div>
	</div>

	<?php
endif;

/** This action is documented in themes/ld30/templates/assignment/listing.php */
do_action( 'learndash-assignment-list-after', $course_step_post->ID, $course_id, $user_id ); ?>
