<?php
/**
 * Displays a single lesson row that appears in the course content listing
 *
 * Available Variables:
 * TBD
 *
 * @since 3.0
 *
 * @package LearnDash\Course
 */

/**
 * Populate a list of topics and quizzes for this lesson
 *
 * @var $topics [array]
 * @var $quizzes [array]
 * @since 3.0
 */
$topics        = ! empty( $lesson_topics ) && ! empty( $lesson_topics[ $lesson['post']->ID ] ) ? $lesson_topics[ $lesson['post']->ID ] : '';
$quizzes       = learndash_get_lesson_quiz_list( $lesson['post']->ID, $user_id, $course_id );
$attributes    = learndash_get_lesson_attributes( $lesson );
$content_count = learndash_get_lesson_content_count( $lesson, $course_id );

// Fallbacks
$count           = ( isset( $count ) ? $count : 0 );
$sections        = ( isset($sections) ? $sections : array() );

$atts = apply_filters( 'learndash_lesson_row_atts', (  isset($has_access) && !$has_access && $lesson['sample'] === 'is_not_sample' ? 'data-balloon-pos="up" data-balloon="' . __( "You don't currently have access to this content", "buddyboss-theme" ) . '"'  : '' ) );
$atts_access_marker = apply_filters( 'learndash_lesson_row_atts', (  isset($has_access) && !$has_access && $lesson['sample'] === 'is_not_sample' ? '<span class="lms-is-locked-ico"><i class="bb-icons bb-icon-lock-fill"></i></span>'  : '' ) );

/**
 * New logic to override sample lessons access LEARNDASH-3854
 */
if ( ( empty( $atts ) ) && ( ! is_user_logged_in() ) ) {
	if ( 'is_sample' === $lesson['sample'] ) {
		if ( true !== (bool) apply_filters( 'learndash_lesson_sample_access', true, $lesson['post']->ID, $course_id, $user_id ) ) {
			$atts = apply_filters( 'learndash_lesson_row_atts_sample_no_access', 'data-ld-tooltip="' . esc_html__( 'Please login to view sample content', 'buddyboss-theme' ) . '"', $lesson['post']->ID, $course_id, $user_id );
		}
	}
}

/**
 * Action to add custom content before a row
 *
 * @since 3.0
 */
do_action( 'learndash-lesson-row-before', $lesson['post']->ID, $course_id, $user_id );

if( isset($sections[$lesson['post']->ID]) ):

    learndash_get_template_part( 'lesson/partials/section.php', array(
        'section'   => $sections[$lesson['post']->ID],
        'course_id' => $course_id,
        'user_id'   => $user_id,
    ), true );

endif; ?>

<div class="<?php learndash_lesson_row_class( $lesson, $has_access ); ?>" id="<?php echo esc_attr( 'ld-expand-' . $lesson['post']->ID ); ?>" <?php echo wp_kses_post($atts); ?>>
    <div class="ld-item-list-item-preview">
        <?php
        /**
         * Action to add custom content before lesson title
         *
         * @since 3.0
         */
        do_action( 'learndash-lesson-row-title-before', $lesson['post']->ID, $course_id, $user_id ); ?>

        

        <?php
        /**
         * Action to add custom content after lesson title
         *
         * @since 3.0
         */
        do_action( 'learndash-lesson-row-title-after', $lesson['post']->ID, $course_id, $user_id ); ?>

        <div class="ld-item-details">
            <?php

            /**
            * Action to add custom content before the attribute bubbles
            *
            * @since 3.0
            */
            do_action( 'learndash-lesson-row-attributes-before', $lesson['post']->ID, $course_id, $user_id );

            /**
             * If this lesson has topics or quizzes show an expand button
             * @var [type]
             */
            if( !empty($topics) || !empty($quizzes) ): ?>

                <?php
                /**
                 * Action to add custom content before expand button
                 *
                 * @since 3.0
                 */
                do_action( 'learndash-lesson-row-expand-before', $lesson['post']->ID, $course_id, $user_id ); ?>

                <div class="ld-expand-button ld-button-alternate" data-ld-expands="<?php echo esc_attr( 'ld-expand-' . $lesson['post']->ID ); ?>">
                    <span class="ld-icon-arrow-down ld-icon ld-primary-background"></span>
                    <div class="ld-item-name ld-primary-color-hover" href="<?php echo esc_attr( learndash_get_step_permalink( $lesson['post']->ID, $course_id ) ); ?>">
                        <?php
                        //ARKDE remove the need to see module/lesson status in course page
                        /*
                        $lesson_progress = learndash_lesson_progress( $lesson['post'] );
                        if ( is_array( $lesson_progress ) ) {
                            $status = ( $lesson_progress['completed'] > 0 && 'completed' !== $lesson['status'] ? 'progress' : $lesson['status'] );
                        } else {
                            $status = $lesson['status'];
                        }
                        
                        learndash_status_icon( $status, get_post_type(), null, true ); */?>
                        <div class="ld-item-title">
                            <?php
                            echo '<span>';
                            echo wp_kses_post($lesson['post']->post_title);
                            echo '</span>';

                            /**
                             * Display content counts if the lesson has topics
                             */
                            if( !empty($topics) || !empty($quizzes) || !empty($attributes) || apply_filters( 'learndash-lesson-row-attributes', false ) ): ?>
                                <?php

                                /**
                                * Action to add custom content after the lesson topic counts
                                *
                                * @since 3.0
                                */
                                do_action( 'learndash-lesson-row-topic-count-before', $lesson['post']->ID, $course_id, $user_id ); ?>

                                <span class="ld-item-components">

                                    <?php

                                    /**
                                    * Action to add custom content after the lesson topic counts
                                    *
                                    * @since 3.0
                                    */
                                    do_action( 'learndash-lesson-components-before', $lesson['post']->ID, $course_id, $user_id );

                                    if( $content_count['topics'] > 0 ): ?>

                                        <span class="ld-item-component">
                                            <?php
                                                echo sprintf( esc_html__( '%s', 'buddyboss-theme' ), $content_count['topics'] ) . ' ' .
                                                _n(
                                                    sprintf( esc_html__( '%s', 'buddyboss-theme' ), LearnDash_Custom_Label::get_label('topic') ),
                                                    sprintf( esc_html__( '%s', 'buddyboss-theme' ), LearnDash_Custom_Label::get_label('topics') ),
                                                    $content_count['topics'],
                                                    'buddyboss-theme'
                                                );
                                                //adding duration of module
                                                $duration = get_field("duration",$lesson['post']->ID);
                                                if($duration)
                                                    echo "&nbsp; • &nbsp;".$duration;
                                            ?>
                                        </span>

                                    <?php
                                    endif;

                                    if( $content_count['topics'] > 0 && $content_count['quizzes'] > 0 ) {
                                        echo '<span class="ld-sep">|</span>';
                                    }

                                    if( $content_count['quizzes'] > 0 ): ?>
                                        <span class="ld-item-component">
                                            <?php
                                                echo sprintf( esc_html__( '%s', 'buddyboss-theme' ), $content_count['quizzes'] ) . ' ' .
                                                _n(
                                                    sprintf( esc_html__( '%s', 'buddyboss-theme' ), LearnDash_Custom_Label::get_label('quiz') ),
                                                    sprintf( esc_html__( '%s', 'buddyboss-theme' ), LearnDash_Custom_Label::get_label('quizzes') ),
                                                    $content_count['quizzes'],
                                                    'buddyboss-theme'
                                                );
                                            ?>
                                        </span>
                                    <?php endif;

                                    if( !empty($attributes) ): foreach( $attributes as $attribute ): ?>
                                        <span class="<?php echo esc_attr( 'ld-status ' . $attribute['class'] ); ?>">
                                            <span class="<?php echo esc_attr( 'ld-icon ' . $attribute['icon']); ?>"></span>
                                            <?php echo esc_html($attribute['label']); ?>
                                        </span>
                                    <?php endforeach; endif;

                                    /**
                                    * Action to add custom content after the lesson topic counts
                                    *
                                    * @since 3.0
                                    */
                                    do_action( 'learndash-lesson-components-after', $lesson['post']->ID, $course_id, $user_id ); ?>

                                </span> <!--/.ld-item-components-->
                                <?php
                                /**
                                * Action to add custom content after the lesson topic counts
                                *
                                * @since 3.0
                                */
                                
                                do_action( 'learndash-lesson-preview-after', $lesson['post']->ID, $course_id, $user_id ); ?>
                            <?php endif; ?>

                        </div> <!--/.ld-item-title-->
                    </div>
                </div> <!--/.ld-expand-button-->

                <?php
                /**
                 * Action to add custom content after lesson title
                 *
                 * @since 3.0
                 */
                
                do_action( 'learndash-lesson-row-expand-after', $lesson['post']->ID, $course_id, $user_id );

            endif; ?>
        </div> <!--/.ld-item-details-->

        <?php
        /**
        * Action to add custom content after the attribute bubbles
        *
        * @since 3.0
        */
        
        do_action( 'learndash-lesson-row-attributes-after', $lesson['post']->ID, $course_id, $user_id ); ?>

    </div> <!--/.ld-item-list-item-preview-->
    <?php
    /**
     * If the lesson has associated topics, display a list
     *
     * @var $topics [array]
     * @since 3.0
     */
    if( !empty($topics) || !empty($quizzes) ): ?>
        <div class="ld-item-list-item-expanded">
            <?php
            /**
            * Action to add custom content before the topic/quiz list
            *
            * @since 3.0
            */
            do_action( 'learndash-lesson-row-topic-list-before', $lesson['post']->ID, $course_id, $user_id );

            learndash_get_template_part( 'lesson/listing.php', array(
                'lesson'    =>  $lesson,
                'topics'    =>  $topics,
                'quizzes'   =>  $quizzes,
                'course_id' =>  $course_id,
                'user_id'   =>  $user_id,
                'course_pager_results' =>  $course_pager_results
            ), true );

            /**
            * Action to add custom content after the topic/quiz list
            *
            * @since 3.0
            */
            do_action( 'learndash-lesson-row-topic-list-after', $lesson['post']->ID, $course_id, $user_id ); ?>
        </div> <!--/.ld-item-list-item-expanded-->
    <?php endif; ?>
</div> <!--/.ld-item-list-item-->
 <?php
 /**
  * Action to add custom content after a row
  *
  * @since 3.0
  */
 do_action( 'learndash-lesson-row-after', $lesson['post']->ID, $course_id, $user_id ); ?>
