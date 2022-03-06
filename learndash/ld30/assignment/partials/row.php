<?php
/**
 * Assignment list individual row
 *
 * Available Variables:
 *
 * $course_step_post 		: WP_Post object for the Lesson/Topic being shown
 *
 * @since 3.0
 *
 * @package LearnDash\Lesson
 */
$assignment_points = learndash_get_points_awarded_array( $assignment->ID );  ?>

 <div class="ld-table-list-item">

     <div class="ld-table-list-item-preview assignment">

     <?php
     /**
      * Action to add custom content before assignment list
      *
      * @since 3.0
      */
     do_action( 'learndash-assignment-row-before', $assignment, get_the_ID(), $course_id, $user_id ); ?>

     <div class="ld-table-list-title">
        <h4 class="is-marginless">Tu respuesta:</h4>
         <?php
         /**
          * Action to add custom content before assignment delete link
          *
          * @since 3.0
          */
         do_action( 'learndash-assignment-row-delete-before', $assignment, get_the_ID(), $course_id, $user_id );

         /**
          * Delete assignment link
          *
          */
         ?>
         <div class="actions">
             <?php 

             if( !learndash_is_assignment_approved_by_meta($assignment->ID) && !$assignment_points ){  ?>

            <span class="ld-status ld-status-waiting ld-tertiary-background">
                <span class="ld-icon ld-icon-calendar"></span>
                <span class="ld-text"><?php esc_html_e( 'Waiting Review', 'buddyboss-theme' ); ?></span>
            </span>
          <?php } elseif ( $assignment_points || learndash_is_assignment_approved_by_meta($assignment->ID) ) { ?>
              <span class="ld-status ld-status-complete">
                <span class="ld-icon ld-icon-checkmark"></span>
                <?php
                if( $assignment_points ):
                    echo sprintf( esc_html__( '%s/%s Points Awarded ', 'buddyboss-theme' ), $assignment_points['current'], $assignment_points['max'] ) . ' - ';
                endif;

                esc_html_e( 'Approved', 'buddyboss-theme' );
                ?>
            </span>
          <?php }

             if( ( isset($post_settings['lesson_assignment_deletion_enabled']) && $post_settings['lesson_assignment_deletion_enabled'] == 'on' && $assignment->post_author == $user_id && !learndash_is_assignment_approved_by_meta($assignment->ID)) || ( learndash_is_admin_user( $user_id ) ) ): ?>
                <a href="<?php echo add_query_arg('learndash_delete_attachment', $assignment->ID) ?>" title="<?php esc_html_e('Delete this uploaded Assignment', 'buddyboss-theme'); ?>" id="removeassignment">
                    <span class="bb-icon-trash red" aria-label="<?php esc_html_e( 'Delete Assignment', 'buddyboss-theme' ); ?>"></span>
                </a>
            <?php
            endif;

         /**
          * Action to add custom content before assignment title and link
          *
          * @since 3.0
          */
         do_action( 'learndash-assignment-row-title-before', $assignment, get_the_ID(), $course_id, $user_id ); ?>
           
         </div>

        

    </div> <!--/.ld-table-list-title-->

    <div class="ld-table-list-columns">
        <div class="answer">
            <?php echo get_field("answer",$assignment->ID);?>
        </div>
        <?php if (get_post_meta( $assignment->ID, 'file_link', true )) : ?>
          <br>
          <h4 class="has-tiny-margin-bottom">Archivos adjuntados:</h4>
        <div class="answer-file">
            <a href='<?php echo esc_attr( get_post_meta( $assignment->ID, 'file_link', true ) ); ?>' target="_blank">
             <span class="ld-item-icon">
                 <span class="ld-icon ld-icon-download" aria-label="<?php esc_html_e( 'Download Assignment', 'buddyboss-theme' ); ?>"></span>
             </span>
             <i>
              <?php echo $assignment->file_name; ?>
             </i>
            </a>
            
        </div>
        <?php endif; ?>
        

    </div> <!--/.ld-table-list-columns-->

    <?php
    /**
     * Action to add custom content after all the assignment row content
     *
     * @since 3.0
     */
    do_action( 'learndash-assignment-row-after', $assignment, get_the_ID(), $course_id, $user_id ); ?>
    </div>

</div>
<?php 

if( post_type_supports( 'sfwd-assignment', 'comments' ) && get_comments_number($assignment->ID) > 0): ?>
  <div id="feedback" class="has-small-margin-top">
      <h4 class="comments-title">Feedback de instructor:</h4>
      <?php 
      $comments = get_comments( array( 'post_id' => $assignment->ID) ); 
      ?>
      
      <ol class="comment-list">
      <?php
      wp_list_comments(
          array(
              'callback'    => 'arkde_comment',
              'style'       => 'ol',
              'short_ping'  => true,
              'avatar_size' => 80,
          ), $comments
      );
      ?>
      </ol>
  </div>

 <?php endif; ?>