<?php
/**
 * Template part for displaying course list item
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BuddyBoss_Theme
 */

global $post, $wpdb;

$current_user_id        = get_current_user_id();
$course_id              = get_the_ID();
$cats                   = wp_get_post_terms( $course_id, 'ld_course_category' );
$lession_list           = learndash_get_lesson_list( $course_id );
$lesson_count           = learndash_get_lesson_list( $course_id, array( 'num' => - 1 ) );
$paypal_settings        = LearnDash_Settings_Section::get_section_settings_all( 'LearnDash_Settings_Section_PayPal' );
$course_price           = trim( learndash_get_course_meta_setting( $course_id, 'course_price' ) );
$course_price_type      = learndash_get_course_meta_setting( $course_id, 'course_price_type' );
$course_button_url      = learndash_get_course_meta_setting( $course_id, 'custom_button_url' );
$courses_progress       = buddyboss_theme()->learndash_helper()->get_courses_progress( $current_user_id );
$course_progress        = isset( $courses_progress[ $course_id ] ) ? $courses_progress[ $course_id ] : null;
$course_status          = learndash_course_status( $course_id, $current_user_id );
$grid_col               = is_active_sidebar( 'learndash_sidebar' ) ? 3 : 4;
$course_progress_new    = buddyboss_theme()->learndash_helper()->ld_get_progress_course_percentage( $current_user_id, $course_id );
$admin_enrolled         = LearnDash_Settings_Section::get_section_setting( 'LearnDash_Settings_Section_General_Admin_User', 'courses_autoenroll_admin_users' );
$course_pricing         = learndash_get_course_price( $course_id );
$user_course_has_access = sfwd_lms_has_access( $course_id, $current_user_id );


$wc_product =  get_field("wc_product",$course_id);
$product = wc_get_product( $wc_product->ID );
$product_category = get_the_terms ( $wc_product->ID, 'product_cat' )[0]->name;

//Get first category of course since we are only using one


$class = '';
if ( ! empty( $course_price ) && ( $course_price_type == 'paynow' || $course_price_type == 'subscribe' || $course_price_type == 'closed' ) ) {
	$class = 'bb-course-paid';
}

//Get member count
//$course_members_count = buddyboss_theme()->learndash_helper()->buddyboss_theme_ld_course_enrolled_users_list( $course_id );

$context = Timber::get_context();
$context['course'] = new Timber\Post($course_id);

if ( taxonomy_exists( 'ld_course_category' ) ) 
    $context["course_cats"] = get_the_terms( $course_id, 'ld_course_category' );

//Resume link
$resume_link = '';
if ( is_user_logged_in() && isset( $user_course_has_access ) && $user_course_has_access ) { 
	$resume_link = buddyboss_theme()->learndash_helper()->boss_theme_course_resume( $course_id );
}

/*Get the categories of the course
if ( taxonomy_exists( 'ld_course_category' ) ) 
{
    $course_cats = get_the_terms( $course_id, 'ld_course_category' );

    $cats_string = "";
    $len = count($course_cats);
    foreach ($course_cats as $index => $cat) {
        $cats_string .= $cat->name;  // het the id from the term object
        if($index != $len - 1)
            $cats_string .= ",";
    }
}*/

?>

<li class="bb-course-item-wrap">
	<a title="<?php the_title_attribute(); ?>" href="<?php echo (empty($resume_link) ? get_permalink() : $resume_link); ?>" class="has-card <?php echo empty($resume_link) ? 'item-click' : '' ?>" data-name="<?php echo $product->get_name();?>" data-id="<?php echo $product->get_id();?>" data-price="<?php echo $product->get_price();?>" data-category="<?php echo $product_category;?>" >
	<div class="card course hoverable max with-progress">
		<div class="card-info">
		<?php $context["is_enrolled"] = is_user_logged_in() && isset( $user_course_has_access ) && $user_course_has_access; 
		Timber::render('cards/course-item-progress.twig',$context); ?>
			
		</div>
		<?php 
		if ( is_user_logged_in() && isset( $user_course_has_access ) && $user_course_has_access ) { ?>

            <div class="course-progress-wrap">

				<?php learndash_get_template_part( 'modules/progress.php',
					array(
						'context'   => 'course',
						'user_id'   => $current_user_id,
						'course_id' => $course_id,
					),
					true ); ?>

            </div>

		<?php } ?>
	</div>
	</a>
	
</li>
