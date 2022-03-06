<?php 
$context = Timber::get_context();
$context['post'] = new Timber\Post($course_id);

Timber::render('learndash/course-before-pensum.twig',$context);
?>