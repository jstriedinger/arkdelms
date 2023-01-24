<?php
/**
 * Displays a course
 *
 * Available Variables:
 * $course_id       : (int) ID of the course
 * $course      : (object) Post object of the course
 * $course_settings : (array) Settings specific to current course
 *
 * $courses_options : Options/Settings as configured on Course Options page
 * $lessons_options : Options/Settings as configured on Lessons Options page
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 *
 * $user_id         : Current User ID
 * $logged_in       : User is logged in
 * $current_user    : (object) Currently logged in user object
 *
 * $course_status   : Course Status
 * $has_access  : User has access to course or is enrolled.
 * $materials       : Course Materials
 * $has_course_content      : Course has course content
 * $lessons         : Lessons Array
 * $quizzes         : Quizzes Array
 * $lesson_progression_enabled  : (true/false)
 * $has_topics      : (true/false)
 * $lesson_topics   : (array) lessons topics
 *
 * @since 3.0
 *
 * @package LearnDash\Course
 */

$materials              = ( isset( $materials ) ) ? $materials : '';
$lessons                = ( isset( $lessons ) ) ? $lessons : array();
$quizzes                = ( isset( $quizzes ) ) ? $quizzes : array();
$lesson_topics          = ( isset( $lesson_topics ) ) ? $lesson_topics : array();
$course_certficate_link = ( isset( $course_certficate_link ) ) ? $course_certficate_link : '';

$template_args = array(
	'course_id'                  => $course_id,
	'course'                     => $course,
	'course_settings'            => $course_settings,
	'courses_options'            => $courses_options,
	'lessons_options'            => $lessons_options,
	'quizzes_options'            => $quizzes_options,
	'user_id'                    => $user_id,
	'logged_in'                  => $logged_in,
	'current_user'               => $current_user,
	'course_status'              => $course_status,
	'has_access'                 => $has_access,
	'materials'                  => $materials,
	'has_course_content'         => $has_course_content,
	'lessons'                    => $lessons,
	'quizzes'                    => $quizzes,
	'lesson_progression_enabled' => $lesson_progression_enabled,
	'has_topics'                 => $has_topics,
	'lesson_topics'              => $lesson_topics,
);

$has_lesson_quizzes = learndash_30_has_lesson_quizzes( $course_id, $lessons );


$context         = Timber::get_context();
$context['post'] = new Timber\Post( $course_id );

// config
$is_enrolled                  = false;
$current_user_id              = get_current_user_id();
$course_price                 = learndash_get_course_meta_setting( $course_id, 'course_price' );
$context['course_price_type'] = learndash_get_course_meta_setting( $course_id, 'course_price_type' );
$course_button_url            = learndash_get_course_meta_setting( $course_id, 'custom_button_url' );
$paypal_settings              = LearnDash_Settings_Section::get_section_settings_all( 'LearnDash_Settings_Section_PayPal' );
$course_video_embed           = get_post_meta( $course_id, '_buddyboss_lms_course_video', true );
$course_certificate           = learndash_get_course_meta_setting( $course_id, 'certificate' );
$courses_progress             = buddyboss_theme()->learndash_helper()->get_courses_progress( $current_user_id );
$course_progress              = isset( $courses_progress[ $course_id ] ) ? $courses_progress[ $course_id ] : null;
$course_progress_new          = buddyboss_theme()->learndash_helper()->ld_get_progress_course_percentage( $current_user_id, $course_id );
$has_access                   = sfwd_lms_has_access( $course_id, $current_user_id );
$file_info                    = pathinfo( $course_video_embed );
$lesson_count                 = learndash_get_lesson_list( $course_id, array( 'num' => - 1 ) );


// Sidebar stuff
// class for video embedin sidebar
$context['course_video_embed'] = $course_video_embed;
$context['video_html_embed']   = wp_oembed_get( $course_video_embed );
$context['thumb_mode']         = 'thumbnail-container-img';
$context['has_certificate']    = $course_certificate ? true : false;
if ( '' != $course_video_embed ) {
	$context['thumb_mode'] = 'thumbnail-container-vid';
}
$topics_count = 0;
foreach ( $lesson_count as $lesson ) {
	$lesson_topics_tmp = learndash_get_topic_list( $lesson->ID );
	if ( $lesson_topics_tmp ) {
		$topics_count += sizeof( $lesson_topics_tmp );

	}
}
$context['lessons'] = $lesson_count;
$context['topics']  = $topics_count;

// course quizzes
$course_quizzes       = learndash_get_course_quiz_list( $course_id );
$course_quizzes_count = sizeof( $course_quizzes );

// lessons quizzes
if ( is_array( $lesson_count ) || is_object( $lesson_count ) ) {
	foreach ( $lesson_count as $lesson ) {
		$lesson_quizzes    = learndash_get_lesson_quiz_list( $lesson->ID, null, $course_id );
		$lesson_topics_tmp = learndash_topic_dots( $lesson->ID, false, 'array', null, $course_id );
		if ( $lesson_quizzes && ! empty( $lesson_quizzes ) ) {
			$course_quizzes_count += count( $lesson_quizzes );
			// this is a little test
		}
		if ( $lesson_topics_tmp && ! empty( $lesson_topics_tmp ) ) {
			foreach ( $lesson_topics_tmp as $topic ) {
				$lesson_quizzes = learndash_get_lesson_quiz_list( $topic, null, $course_id );
				if ( ! $lesson_quizzes || empty( $lesson_quizzes ) ) {
					continue;
				}
				$course_quizzes_count += count( $lesson_quizzes );
			}
		}
	}
}
$context['course_quizzes'] = $course_quizzes_count;

// cover photo
$course_cover_photo = false;
if ( class_exists( '\BuddyBossTheme\BuddyBossMultiPostThumbnails' ) ) {
	$course_cover_photo = \BuddyBossTheme\BuddyBossMultiPostThumbnails::get_post_thumbnail_url(
		'sfwd-courses',
		'course-cover-image'
	);
}
$context['course_cover_photo'] = $course_cover_photo;
$context['has_access']         = $has_access;
$context['lessons']            = learndash_get_lesson_list( $course_id );


// Get the course-categories of the course
if ( taxonomy_exists( 'ld_course_category' ) ) {
	$course_cats            = get_the_terms( $context['post']->ID, 'ld_course_category' );
	$context['course_cats'] = $course_cats;
}

if ( taxonomy_exists( 'ld_course_tag' ) ) {
	$context['course_tags'] = get_the_terms( $context['post']->ID, 'ld_course_tag' );
}


// get course members
$members_arr = learndash_get_users_for_course( $course_id, array( 'number' => -1 ), false );
if ( ( $members_arr instanceof WP_User_Query ) && ( property_exists( $members_arr, 'results' ) ) && ( ! empty( $members_arr->results ) ) ) {
	$context['course_members'] = sizeof( $members_arr->get_results() );
}

$context['is_enrolled'] = $has_access;

// Get the post object, not the WC object in ACF
$wc_product               = get_field( 'wc_product', $course_id );
$context['wc_product_id'] = $wc_product->ID;

// We need to get the WC object to see prices etc
$product          = wc_get_product( $context['wc_product_id'] );
$product_category = get_the_terms( $wc_product->ID, 'product_cat' )[0]->name;

$context['wc_price']         = $product->get_price();
$context['wc_regular_price'] = $product->get_regular_price();
$context['is_on_sale']       = $product->is_on_sale();

if ( $context['wc_price'] < $context['wc_regular_price'] ) {
	// lets calculate the temp discount
	$context['discount'] = ceil( 100 - ( ( $context['wc_price'] * 100 ) / $context['wc_regular_price'] ) );
}



$context['is_programming_course'] = has_term( 'programacion', 'ld_course_category', $post->ID );


if ( $context['is_on_sale'] ) {
	$context['sale_end'] = $product->get_date_on_sale_to();
}


if ( matched_cart_items( $wc_product->ID ) < 1 ) {
	$context['in_cart'] = false;                                    // NOT in cart
} else {
	$context['in_cart'] = true;
}


// Resume link
$resume_link = '';
if ( empty( $course_progress ) && $course_progress < 100 ) {
	$btn_advance_class      = 'btn-advance-start';
	$btn_advance_label      = sprintf( __( 'Start %s', 'buddyboss-theme' ), LearnDash_Custom_Label::get_label( 'course' ) );
	$context['resume_link'] = buddyboss_theme()->learndash_helper()->boss_theme_course_resume( $course_id );
} elseif ( $course_progress == 100 ) {
	$btn_advance_class      = 'btn-advance-completed';
	$btn_advance_label      = __( 'Completed', 'buddyboss-theme' );
	$context['resume_link'] = buddyboss_theme()->learndash_helper()->boss_theme_course_resume( $course_id );
} else {
	$btn_advance_class      = 'btn-advance-continue';
	$btn_advance_label      = __( 'Continue', 'buddyboss-theme' );
	$context['resume_link'] = buddyboss_theme()->learndash_helper()->boss_theme_course_resume( $course_id );
}

// Get the progress data
$progress = learndash_course_progress(
	array(
		'user_id'   => $current_user_id,
		'course_id' => $course_id,
		'array'     => true,
	)
);

$context['currency'] = get_woocommerce_currency();

$career = get_field( 'career', $course_id );

if ( $career ) {
	// now lets get the WC products of that career
	$wc_career_product       = get_field( 'wc_product', $career->ID );
	$career_product          = wc_get_product( $wc_career_product->ID );
	$wc_career_regular_price = $career_product->get_regular_price();
	$wc_career_sale_price    = $career_product->get_sale_price();

	$cid            = get_post_meta( $wc_career_product->ID, '_related_course' );
	$args           = array(
		'post__in'  => $cid[0],
		'post_type' => 'sfwd-courses',
	);
	$career_courses = Timber::get_posts( $args );

	// calculate discount
	$discount                   = 100 - intval( ( $wc_career_sale_price * 100 ) / $wc_career_regular_price );
	$context['career_discount'] = $discount;
	$context['career_courses']  = $career_courses;
}

?>


<div class="<?php echo esc_attr( learndash_the_wrapper_class() ); ?>" id="datalayer_course" data-name="<?php echo $product->get_name(); ?>" data-id="<?php echo $product->get_id(); ?>" data-price="<?php echo $product->get_price(); ?>" data-category="<?php echo $product_category; ?>" >

	<?php
	global $course_pager_results;
	if ( get_field( 'delivery_type', $course_id ) == 'selfpaced_bootcamp' ) {
		$wc_mentorship_product               = get_field( 'bootcamp', $course_id )['wc_coursewmentorships'];
		$context['wc_mentorship_product_id'] = $wc_mentorship_product->ID;
		$mentorship_product                  = wc_get_product( $context['wc_mentorship_product_id'] );


		$context['wc_mentorship_price']             = $mentorship_product->get_price();
		$context['wc_mentorship_with_course_price'] = $context['wc_mentorship_price'] + $context['wc_price'];

		$context['wc_mentorship_regular_price']             = $mentorship_product->get_regular_price();
		$context['wc_mentorship_with_course_regular_price'] = $context['wc_mentorship_regular_price'] + $context['wc_regular_price'];
		$context['mentorship_is_on_sale']                   = $mentorship_product->is_on_sale();


		if ( $mentorship_product->is_on_sale() ) {
			// discount and sale_end
			$context['discount'] = ceil( 100 - ( ( $context['wc_mentorship_price'] * 100 ) / $context['wc_mentorship_regular_price'] ) );
			$context['sale_end'] = $mentorship_product->get_date_on_sale_to();
		}

		if ( matched_cart_items( $mentorship_product->get_id() ) < 1 ) {
			$context['mentorship_in_cart'] = false;
		} else {
			$context['mentorship_in_cart'] = true;
		}

		// Normal mentorship prices

		Timber::render( 'modals/mentorships-course.twig', $context );
	}

	/**
	 * Action to add custom content before the topic
	 *
	 * @since 3.0
	 */

	Timber::render( 'learndash/template-banner.twig', $context );


	?>

	
	<div class="bb-grid">

		<div class="bb-learndash-content-wrap has-small-margin-bottom">

			<?php
			/**
			 * Action to add custom content before the course certificate link
			 *
			 * @since 3.0
			 */
			do_action( 'learndash-course-certificate-link-before', $course_id, $user_id );



			/**
			 * Certificate link
			 * Can not change since is the default Leanrdash alert message template
			 */
			if ( isset( $course_certficate_link ) && $course_certficate_link && ! empty( $course_certficate_link ) ) :
				if ( function_exists( 'rrf_get_user_course_review_id' ) ) {
					$user_course_review = rrf_get_user_course_review_id( $user_id, $course_id );
					if ( ! empty( $user_course_review ) || current_user_can( 'administrator' ) ) {
						learndash_get_template_part(
							'modules/alert.php',
							array(
								'type'    => 'success ld-alert-certificate',
								'icon'    => 'certificate',
								'message' => __( 'You\'ve earned a certificate!', 'buddyboss-theme' ),
								'button'  => array(
									'url'   => $course_certficate_link,
									'icon'  => 'download',
									'label' => __( 'Download Certificate', 'buddyboss-theme' ),
								),
							),
							true
						);
					}
				} else {
					learndash_get_template_part(
						'modules/alert.php',
						array(
							'type'    => 'success ld-alert-certificate',
							'icon'    => 'certificate',
							'message' => __( 'You\'ve earned a certificate!', 'buddyboss-theme' ),
							'button'  => array(
								'url'   => $course_certficate_link,
								'icon'  => 'download',
								'label' => __( 'Download Certificate', 'buddyboss-theme' ),
							),
						),
						true
					);

				}

			endif;

			 /**
			  * Action to add custom content after the course certificate link
			  *
			  * @since 3.0
			  */
			 do_action( 'learndash-course-certificate-link-after', $course_id, $user_id );


			/**
			 * Course info bar
			 *
			learndash_get_template_part( 'modules/infobar.php', array(
					'context'       => 'course',
					'course_id'     => $course_id,
					'user_id'       => $user_id,
					'has_access'    => $has_access,
					'course_status' => $course_status,
					'post'          => $post
				), true ); */
			?>
		
			<?php
			/**
			 * Filter to add custom content after the Course Status section of the Course template output.
			 *
			 * @since 2.3
			 * See https://bitbucket.org/snippets/learndash/7oe9K for example use of this filter.
			 */
			echo apply_filters( 'ld_after_course_status_template_container', '', learndash_course_status_idx( $course_status ), $course_id, $user_id );



			/**
			 * Content tabs
			 */
			echo '<div class="bb-ld-tabs">';
			echo '<div id="learndash-course-content"></div>';
			$ct = get_the_content();

			?>

			<div class="course-content content">
			  <?php echo $ct; ?>
			</div>
			<div class="show-more">
			  <a href="#" class="button is-outline is-purple is-small has-text-weight-bold">Mostrar más información<i class="bb-icon-chevron-down bb-icons is-large" style="vertical-align:bottom"></i></a>
			</div>
			<?php

			/*
			learndash_get_template_part( 'modules/tabs.php', array(
				 'course_id' => $course_id,
				 'post_id'   => get_the_ID(),
				 'user_id'   => $user_id,
				 'content'   => $content,
				 'materials' => $materials,
				 'context'   => 'course'
			 ), true );*/
			 echo '</div>';

			/**
			 * Identify if we should show the course content listing
			 *
			 * @var $show_course_content [bool]
			 */
			$show_course_content = ( ! $has_access && 'on' === $course_meta['sfwd-courses_course_disable_content_table'] ? false : true );

			if ( $has_course_content && $show_course_content ) :
				?>
		
				<div class="ld-item-list ld-lesson-list">
					<div >
		
						<?php
						/**
						 * Action to add custom content before the course heading
						 *
						 * @since 3.0
						 */
						do_action( 'learndash-course-heading-before', $course_id, $user_id );
						?>


						
		
						<?php
						/**
						 * Action to add custom content after the course heading
						 *
						 * @since 3.0
						 */
						do_action( 'learndash-course-heading-after', $course_id, $user_id );
						?>
		
						<div class="ld-item-list-actions" data-ld-expand-list="true">
							<p class="subtitle is-size-4 is-size-3-widescreen title-color has-text-weight-bold has-small-padding-top">Plan de Estudios  <i class="bb-icons is-large bb-icon-book-open "></i></p>
		
							<?php
							/**
							 * Action to add custom content after the course content progress bar
							 *
							 * @since 3.0
							 */
							do_action( 'learndash-course-expand-before', $course_id, $user_id );
							?>
		
							<?php
							// Only display if there is something to expand
							if ( $has_topics || $has_lesson_quizzes ) :
								?>
								<?php
								// TODO @37designs Need to test this
								if ( apply_filters( 'learndash_course_steps_expand_all', false, $course_id, 'course_lessons_listing_main' ) ) :
									?>
									<script>
										jQuery(document).ready(function(){
											jQuery("<?php echo '#ld-expand-button-' . $course_id; ?>").click();
										});
									</script>
									<?php
								endif;

							endif;

							/**
							 * Action to add custom content after the course content expand button
							 *
							 * @since 3.0
							 */
							do_action( 'learndash-course-expand-after', $course_id, $user_id );
							?>
		
						</div> <!--/.ld-item-list-actions-->
					</div> <!--/.ld-section-heading-->
		
					<?php
					/**
					 * Action to add custom content before the course content listing
					 *
					 * @since 3.0
					 */
					do_action( 'learndash-course-content-list-before', $course_id, $user_id );

					/**
					 * Content content listing
					 *
					 * @since 3.0
					 *
					 * ('listing.php');
				   */

					learndash_get_template_part(
						'course/listing.php',
						array(
							'course_id'                  => $course_id,
							'user_id'                    => $user_id,
							'lessons'                    => $lessons,
							'lesson_topics'              => @$lesson_topics,
							'quizzes'                    => $quizzes,
							'has_access'                 => $has_access,
							'course_pager_results'       => $course_pager_results,
							'lesson_progression_enabled' => $lesson_progression_enabled,
						),
						true
					);

					/**
					 * Action to add custom content before the course content listing
					 *
					 * @since 3.0
					 */
					do_action( 'learndash-course-content-list-after', $course_id, $user_id );
					?>
		
				</div> <!--/.ld-item-list-->
		
				<?php
			endif;

			?>


		</div>

		<?php
		// Single course sidebar
		Timber::render( 'learndash/single-course-sidebar.twig', $context );
		// learndash_get_template_part( 'template-single-course-sidebar.php', $template_args, true );
		?>

	</div>


	<?php

	/**
	 * Action to add custom content before the topic
	 *
	 * @since 3.0
	 */

	do_action( 'learndash-course-after', get_the_ID(), $course_id, $user_id );
	if ( ! is_user_logged_in() ) {
		global $login_model_load_once;
		$login_model_load_once      = false;
		$learndash_login_model_html = learndash_get_template_part( 'modules/login-modal.php', array(), false );
		echo '<div class="learndash-wrapper learndash-wrapper-login-modal">' . $learndash_login_model_html . '</div>';
	}

	?>

</div>
<?php
/*
if($career)
{
	Timber::render('snippets/career-from-course.twig',$context);
}*/
?>
