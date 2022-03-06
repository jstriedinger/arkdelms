<?php 
if(!learndash_is_item_complete())
{
    $button_class = ' class="learndash_mark_complete_button" ';
    $form_fields = '<input type="hidden" value="' . $post->ID . '" name="post" />
    <input type="hidden" value="' . learndash_get_course_id( $post->ID ) . '" name="course_id" />
    <input type="hidden" value="' . wp_create_nonce( 'sfwd_mark_complete_' . get_current_user_id() . '_' . $post->ID ) . '" name="sfwd_mark_complete" />
    <input type="submit" value="' . LearnDash_Custom_Label::get_label( 'button_mark_complete' ) . '" ' . $button_class . '/>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Method escapes output
    /** This filter is documented in includes/course/ld-course-progress.php */
    $form_fields = apply_filters( 'learndash_mark_complete_form_fields', $form_fields, $post );
    
    echo '<form  method="post" action="">' . $form_fields . '</form>';
}


?>