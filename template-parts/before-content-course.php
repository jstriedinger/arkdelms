<?php
$context = Timber::get_context();
$context['post'] = new Timber\Post($course_id);

if(get_field("career",$course_id))
{
	Timber::render('learndash/career-mini-info.twig',$context);
}
Timber::render('learndash/learning-points.twig',$context);

?>