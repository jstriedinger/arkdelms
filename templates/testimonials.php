<?php
/*
Template Name: Testimonials
*/

get_header();
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'testimonials.twig' );

$args = array (
    'post_type'              => 'testimonial',
    'posts_per_page' => '2'

);
$args['meta_query'] =  array(array('key' => 'special','compare' => '==','value' => '1'));
$context['stories'] = Timber::get_posts( $args );

$args = array (
    'post_type'              => 'testimonial',
    'posts_per_page' => '-1'

);
$args['meta_query'] =  array(array('key' => 'special','compare' => '==','value' => '0'));
$context['testimonials'] = Timber::get_posts( $args );

//lets get upcoming event
$args = array (
    'post_type'              => 'event',
    'posts_per_page' => '1',
    'orderby'        => 'start_date',
    'order'          => 'ASC'

);
$events = Timber::get_posts( $args );
if(!empty($events))
	$context['next_event'] = $events[0];


Timber::render( $templates, $context );


get_footer();
?>
