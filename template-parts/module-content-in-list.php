<?php
//get the field
defined( 'ABSPATH' ) || exit;
$the_lesson = get_post($lesson_id);
$lesson_content = $the_lesson->post_content;

if(!empty($lesson_content))
{
    //lets show the content text 
    ?>
    <div class="content is-small has-small-padding-top">
        <?php echo $lesson_content ?>
    </div>
<?php }
?>