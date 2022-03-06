<?php
/*
Template Name: Beca
*/

get_header();
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'scholarship.twig' );


$today = new DateTime('NOW');
$end_date = get_field('end_date',$context['post']);
$dateF = DateTime::createFromFormat('d/m/Y', $end_date);

$context["available"] = false;
if ($today <= $dateF) { 
    $context["available"] = true;
}
$share_box = buddyboss_theme_get_option( 'blog_share_box' );
if ( !empty( $share_box )  ) :
    get_template_part( 'template-parts/share' ); 
endif;

Timber::render( $templates, $context );


get_footer();
?>
