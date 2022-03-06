<?php 
$context = Timber::get_context();
$context['post'] = new Timber\Post($course_id);
$is_enrolled = sfwd_lms_has_access( $course_id, $user_id );
Timber::render('learndash/teacher-banner.twig',$context);
if(get_field("student_projects",$course_id))
{ 
	Timber::render('snippets/students_gallery.twig',$context);
}
if(get_field("visible_reviews",$course_id) || $is_enrolled )
	echo do_shortcode('[rrf_course_review course_id="{${$course_id}}""]');
else
	Timber::render('learndash/course-testimonials.twig',$context);

Timber::render('learndash/faq_courses.twig',$context); 
?>