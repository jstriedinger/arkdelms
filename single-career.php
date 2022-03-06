<?php 
get_header();
$context = Timber::get_context();
$context['post'] = new Timber\Post();

$templates = array( 'career.twig' );

//config
$wc_product 		= get_field("wc_product",$context['post']->ID);

$product 			= wc_get_product( $wc_product->ID );
$wc_in_cart 		= false;
$current_user_id 	= get_current_user_id();
$is_enrolled		= false;
$current_user 		= wp_get_current_user();
$mycourses_link 	= (function_exists( 'bp_core_get_user_domain' ) ? bp_core_get_user_domain( $current_user->ID ) : get_author_posts_url( $current_user->ID ))."cursos";




$context["mycourses_link"] = $mycourses_link;
$context["wc_product_id"] = $wc_product->ID;
$context["wc_price"] = $product->get_price();
$context["wc_regular_price"] = $product->get_regular_price();

if($context["wc_price"] <  $context["wc_regular_price"])
{
    //lets calculate the temp discount
    $context["discount"] = ceil(100 - (($context["wc_price"] * 100) / $context["wc_regular_price"]));
}

$context["currency"] = get_woocommerce_currency();

//Get stats of career. Sum of modules, lessons etc
$topics_count = 0;
$lessons_count = 0;
$duration_h = 0;
$duration_min = 0;
$students_total = 0;
foreach ( get_field("courses",$context['post']->ID) as $course) {
	$lessons = learndash_get_lesson_list( $course->ID, array( 'num' => - 1 ) );
	$lessons_count += sizeof($lessons);
	$duration_h += get_field("duration",$course->ID)["hours"];
	$duration_min += get_field("duration",$course->ID)["mins"];
	$members_arr   = learndash_get_users_for_course( $course->ID, array( 'number' => -1 ), false );
	if ( ( $members_arr instanceof WP_User_Query ) && ( property_exists( $members_arr, 'results' ) ) && ( ! empty( $members_arr->results ) ) ) {
	    $students_total += sizeof($members_arr->get_results());
	}
	foreach ( $lessons as $lesson ) {
	    $lesson_topics_tmp = learndash_get_topic_list( $lesson->ID );
	    if ( $lesson_topics_tmp ) {
	        $topics_count += sizeof( $lesson_topics_tmp );
	    }
	}

}

if($duration_min / 60 >= 1)
{
	$tmp = intval($duration_min/60);
	$duration_h += $tmp;
	$duration_min = $duration_min - (60 * $tmp);
}


$context["duration"] = get_field("duration",$context['post']->ID);
if(empty($context["duration"]))
	$context["duration"] = $duration_h."h".$duration_min."m";
$context["lessons_count"] = $lessons_count;
$context["topics_count"] = $topics_count;
$context["students_total"] = $students_total;



//Video preview
$course_video_embed  = get_field("trailer_video",$context['post']->ID);
$file_info           = pathinfo( $course_video_embed );

$context["course_video_embed"] = $course_video_embed;
$context["video_html_embed"] = wp_oembed_get($course_video_embed);
$context["thumb_mode"] = 'thumbnail-container-img';
if ( '' != $course_video_embed ) {
    $context["thumb_mode"] = 'thumbnail-container-vid';
}


//Now lets check if user already bought product AKA has acces to all the courses in the career
if (is_user_logged_in() )
{
	$current_user = wp_get_current_user();
	if(wc_customer_bought_product( $current_user->user_email, $current_user->ID, $product->get_id() ) )
		$is_enrolled = true;

}
$context["is_enrolled"] = $is_enrolled;


if( matched_cart_items($wc_product->ID) > 0 )
    $wc_in_cart = true;

$context["in_cart"] = $wc_in_cart;


//Lets see if the pack has mentorships available
/*if(get_field("has_mentorships",$context['post']->ID))
{
    $wc_mentorship_product = wc_get_product(get_field("mentorship",$context['post']->ID)["wc_career_mentorship"]);
    $wc_with_mentorship_price =  $wc_mentorship_product->get_price() + $context["wc_price"];
    $wc_with_mentorship_reg_price = $wc_mentorship_product->get_regular_price() + $context["wc_regular_price"];
        

    $context["wc_mentorship_price"] = $wc_mentorship_product->get_price();
    $context["wc_mentorship_reg_price"] = $wc_mentorship_product->get_regular_price();

    $context["with_mentorship_price"] = $wc_with_mentorship_price;
    $context["with_mentorship_reg_price"] = $wc_with_mentorship_reg_price;
    $context["mentorship_is_on_sale"] = $wc_mentorship_product->is_on_sale();

    if( matched_cart_items($wc_mentorship_product->get_id()) < 1 )
        $context["mentorship_in_cart"] = false;
    else
        $context["mentorship_in_cart"] = true;
    
    Timber::render('modals/mentorships-career.twig',$context);
}*/

Timber::render( $templates, $context );
get_footer();
?>