<?php

get_header();
$context = Timber::get_context();
$context['post'] = new Timber\Post();

$templates = array( 'frontpage.twig' );


echo "<div id='primary' class='content-area bb-grid-cell'>";
Timber::render( $templates, $context );
echo"</div>";


get_footer();
?>