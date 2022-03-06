<?php
/*
Template Name: Mentorias
*/

get_header();
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'premium.twig' );



Timber::render( "mentorships.twig", $context ); 
Timber::render( "snippets/faq.twig", $context ); 
echo "</div>";

get_footer();
?>
