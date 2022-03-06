<?php

$context = Timber::get_context();
$context['post'] = new Timber\Post($course_id);
$current_user_id     = get_current_user_id();


//config
$is_enrolled         = false;
$current_user_id     = get_current_user_id();
$course_price        = learndash_get_course_meta_setting( $course_id, 'course_price' );
$course_price_type   = learndash_get_course_meta_setting( $course_id, 'course_price_type' );
$course_button_url   = learndash_get_course_meta_setting( $course_id, 'custom_button_url' );
$paypal_settings     = LearnDash_Settings_Section::get_section_settings_all( 'LearnDash_Settings_Section_PayPal' );
$course_video_embed  = get_post_meta( $course_id, '_buddyboss_lms_course_video', true );
$course_certificate  = learndash_get_course_meta_setting( $course_id, 'certificate' );
$courses_progress    = buddyboss_theme()->learndash_helper()->get_courses_progress( $current_user_id );
$course_progress     = isset( $courses_progress[ $course_id ] ) ? $courses_progress[ $course_id ] : null;
$course_progress_new = buddyboss_theme()->learndash_helper()->ld_get_progress_course_percentage( get_current_user_id(), $course_id );
$admin_enrolled      = LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Section_General_Admin_User', 'courses_autoenroll_admin_users' );
$lesson_count        = learndash_get_lesson_list( $course_id, array( 'num' => - 1 ) );
$course_pricing      = learndash_get_course_price( $course_id );
$has_access          = sfwd_lms_has_access( $course_id, $current_user_id );
$file_info           = pathinfo( $course_video_embed );


$course_cover_photo = false;
if ( class_exists( '\BuddyBossTheme\BuddyBossMultiPostThumbnails' ) ) {
	$course_cover_photo = \BuddyBossTheme\BuddyBossMultiPostThumbnails::get_post_thumbnail_url(
		'sfwd-courses',
		'course-cover-image'
	);
}
$context['course_cover_photo'] = $course_cover_photo;
$context['has_access'] = sfwd_lms_has_access( $course_id, get_current_user_id() );
$context['lessons'] = learndash_get_lesson_list( $course_id );

if ( taxonomy_exists( 'ld_course_category' ) ) //category
	$context["course_cats"] = get_the_terms( $context['post']->ID, 'ld_course_category' );

if ( buddyboss_theme_get_option( 'learndash_course_author' ) || buddyboss_theme_get_option( 'learndash_course_date' ) ) 
{
	$context["bb_single_meta_pfx"]  = 'bb_single_meta_pfx';
} else {
	$context["bb_single_meta_pfx"]  = 'bb_single_meta_off';
}


$context["show_author"] = buddyboss_theme_get_option( 'learndash_course_author' );
$context["has_buddypress"] = class_exists('BuddyPress');

if( buddyboss_theme_get_option( 'learndash_course_participants', null, true ) ) {
	$course_members_count = buddyboss_theme()->learndash_helper()->buddyboss_theme_ld_course_enrolled_users_list( $course_id );
	$members_arr          = learndash_get_users_for_course( $course_id, array( 'number' => 5 ), false );
	//var_dump(learndash_get_course_meta_setting( $course_id, 'course_access_list'));
	//var_dump(learndash_get_course_users_access_from_meta( $course_id ));

	if ( ( $members_arr instanceof WP_User_Query ) && ( property_exists( $members_arr, 'results' ) ) && ( ! empty( $members_arr->results ) ) ) {
		$context["course_members"] = $members_arr->get_results();

	} 
}
if ( sfwd_lms_has_access( $course_id, $current_user_id ) ) {
	$context["is_enrolled"] = true;
} else {
	$context["is_enrolled"] = false;
}

//wooCommerce
$wc_product =  get_field("wc_product",$course_id);
$context["wc_product_id"] = $wc_product->ID;
if( matched_cart_items($wc_product->ID) < 1 ){
	$context["in_cart"] = false;									//NOT in cart
} else {
    $context["in_cart"] = true;
}


//Resume link
$resume_link = '';

if ( empty( $course_progress ) && $course_progress < 100 ) {
	$btn_advance_class = 'btn-advance-start';
	$btn_advance_label = sprintf( __( 'Start %s', 'buddyboss-theme' ), LearnDash_Custom_Label::get_label( 'course' ) );
	$resume_link       = buddyboss_theme()->learndash_helper()->boss_theme_course_resume( $course_id );
} elseif ( $course_progress == 100 ) {
	$btn_advance_class = 'btn-advance-completed';
	$btn_advance_label = __( 'Completed', 'buddyboss-theme' );
} else {
	$btn_advance_class = 'btn-advance-continue';
	$btn_advance_label = __( 'Continue', 'buddyboss-theme' );
	$resume_link       = buddyboss_theme()->learndash_helper()->boss_theme_course_resume( $course_id );
}
$context["resume_link"] = $resume_link;
$context["btn_advance_class"] = $btn_advance_class;


Timber::render('learndash/template-banner.twig',$context);