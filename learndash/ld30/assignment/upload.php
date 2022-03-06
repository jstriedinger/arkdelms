<?php
/**
 * lesson/topic assignment upload form.
 *
 * If the lesson/topic is set to be an assignment there will be an upload form displayed to the user.
 *
 * Available Variables:
 *
 * $course_step_post: WP_Post object for the Lesson/Topic being shown
 * $user_id: Current user ID
 * $assignment_upload_error_message: string of previous upload error. Will be empty if no previous upload attempt
 *
 * @since 3.0
 *
 * @package LearnDash\Lesson
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$current_user = wp_get_current_user();
$group_id =  get_field("bootcamp",$course_id)['mentorship_group'];
$groups = learndash_get_course_groups( $course_id );
if($groups)
{
	$group_id = $groups[0];
	$course_type = get_field("delivery_type",$course_id);
	//has a mentorship group
	if(learndash_is_user_in_group($current_user->ID, $group_id))
	{
		if(get_field("bootcamp",$course_id)['open_homework'])
		{
			//Submissions are open
			/**
			 * Identify the max upload file size. Compares the server enviornment limit to what's configured through LD
			 * @var $php_max_upload (int)
			 */

			$php_max_upload = ini_get( 'upload_max_filesize' );

			if ( isset( $post_settings['assignment_upload_limit_size'] ) && ! empty( $post_settings['assignment_upload_limit_size'] ) ) {
				if ( learndash_return_bytes_from_shorthand( $post_settings['assignment_upload_limit_size'] ) < learndash_return_bytes_from_shorthand( $php_max_upload ) ) {
					$php_max_upload = $post_settings['assignment_upload_limit_size'];
				}
			}

			/**
			 * Set the upload message based on upload size limit and limit of approved file extensions
			 *
			 * @var $upload_message (string)
			 */

			$upload_message = sprintf(
				// translators: placeholder: PHP file upload size.
				esc_html_x( 'Maximum upload file size: %s', 'placeholder: PHP file upload size', 'learndash' ),
				$php_max_upload
			);

			if ( isset( $post_settings['assignment_upload_limit_extensions'] ) && ! empty( $post_settings['assignment_upload_limit_extensions'] ) ) {
				$limit_file_exts = learndash_validate_extensions( $post_settings['assignment_upload_limit_extensions'] );
				if ( ! empty( $limit_file_exts ) ) {
					$upload_message .= ' ' . sprintf(
						// translators: placeholder: Comma separated list of file extentions
						esc_html_x( 'Allowed file types: %s', 'placeholder: Comma separated list of file extentions', 'learndash' ),
						implode( ', ', $limit_file_exts )
					);
				}
			}

			/**
			 * Check to see if the user has uploaded the maximium number of assignments
			 *
			 * @var null
			 */

			if ( isset( $post_settings['assignment_upload_limit_count'] ) ) {
				$assignment_upload_limit_count = intval( $post_settings['assignment_upload_limit_count'] );
				if ( $assignment_upload_limit_count > 0 ) {
					$assignments = learndash_get_user_assignments( $course_step_post->ID, $user_id );
					if ( ! empty( $assignments ) && count( $assignments ) >= $assignment_upload_limit_count ) {
						return;
					}
				}
			}

			/**
			 * Fires before the assignment uploads.
			 *
			 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
			 * @param int $course_id           Course ID.
			 * @param int $user_id             User ID.
			 */
			do_action( 'learndash-assignment-uploads-before', $course_step_post->ID, $course_id, $user_id ); ?>

			<div class="ld-file-upload">

				<div class="ld-file-upload-heading">
					<?php
					/**
					 * Fires before the assignment upload heading.
					 *
					 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
					 * @param int $course_id           Course ID.
					 * @param int $user_id             User ID.
					 */
					do_action( 'learndash-assignment-uploads-heading-before', $course_step_post->ID, $course_id, $user_id );

					echo "<h4>".translate( 'Upload Assignment', 'learndash' )."</h4>";
					?>


					<?php

					/**
					 * Fires after the assignment uploads.
					 *
					 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
					 * @param int $course_id           Course ID.
					 * @param int $user_id             User ID.
					 */
					do_action( 'learndash-assignment-uploads-heading-after', $course_step_post->ID, $course_id, $user_id );
					?>
				</div>

				<form name="uploadfile" id="uploadfile_form" class="ld-file-upload-form assignment" method="POST" enctype="multipart/form-data" action="" accept-charset="utf-8" >

					<?php 
					$content = '';
					$editor_id = 'assignment_answer';
					$settings =   array(
						'wpautop' => true, // use wpautop?
						'media_buttons' => false, // show insert/upload button(s)
						'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
						'textarea_rows' => get_option('default_post_edit_rows', 10), // rows="..."
						'editor_class' => 'arkdetinymce', // add extra class(es) to the editor textarea
						'teeny' => true, // output the minimal editor config used in Press This
						'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
						'tinymce' => array(
								'toolbar1' => 'formatselect,bold,italic,underline,|,' .
									'bullist,numlist,|,justifyleft,justifycenter' .
									',justifyright,|,link,unlink,|,undo,redo'

							), // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
						'quicktags' => false // load Quicktags, can be used to pass settings directly to Quicktags using an array()
					);?>
					<div class="ld-file-upload-heading has-tiny-margin-bottom">
						Es necesario que escribas algo en el campo de texto para enviar la tarea.
					</div>
					<?php wp_editor( $content, $editor_id, $settings ); 
					
					?>
					
					<div class="ld-file-upload-heading has-small-margin-top ">
						Necesitas adjuntar un archivo? <span><?php echo esc_html( '(' . $upload_message . ')' ); ?></span>
					</div>
					<div class="field has-addons has-tiny-margin-top">

						<input class="file-input ld-file-input" type="file" name="uploadfiles[]" id="uploadfiles">

						<label for="uploadfiles">
							<strong class="file-btn"><?php echo esc_html_e( 'Browse', 'learndash' ); ?> <i class="icon"><i class="fas fa-upload"></i></i></strong>
							<span class="file-info"><?php echo esc_html_e( 'No file selected', 'learndash' ); ?></span>
						</label>
					</div>
					<br>
					<?php
					/**
					 * Fires inside the assignment upload form.
					 *
					 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
					 * @param int $course_id           Course ID.
					 * @param int $user_id             User ID.
					 */
					do_action( 'learndash-assignment-uploads-form', $course_step_post->ID, $course_id, $user_id );
					?>

					<button class="button teal large is-square" type="submit" value="<?php esc_html_e( 'Upload', 'learndash' ); ?>" id="uploadfile_btn_arkde"><span><?php esc_html_e( 'Upload', 'learndash' ); ?> </span><span class="icon">
					<i class="fas fa-cloud-upload-alt"></i>
					</span>
					
					</button>

					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo learndash_return_bytes_from_shorthand( $php_max_upload ); ?>" />
					<input type="hidden" value="<?php echo esc_attr( $course_step_post->ID ); ?>" name="post"/>
					<input type="hidden" value="<?php echo esc_attr( learndash_get_course_id( $course_step_post->ID ) ); ?>" name="course_id"/>
					<input type="hidden" name="uploadfile" value="<?php echo esc_attr( wp_create_nonce( 'uploadfile_' . $current_user->ID . '_' . $course_step_post->ID ) ); ?>"  />

				</form>

				<div class="ld-file-upload-message">
					<?php
					/**
					 * Fires inside assignment upload message wrapper.
					 *
					 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
					 * @param int $course_id           Course ID.
					 * @param int $user_id             User ID.
					 */
					do_action( 'learndash-assignment-uploads-message', $course_step_post->ID, $course_id, $user_id );
					?>
				</div>

				<?php
				/**
				 * Fires after the assignments upload message.
				 *
				 * @since 3.0.0
				 *
				 * @param int $course_step_post_id Post ID for the Lesson/Topic being shown.
				 * @param int $course_id           Course ID.
				 * @param int $user_id             User ID.
				 */
				do_action( 'learndash-assignment-uploads-message-after', $course_step_post->ID, $course_id, $user_id );
				?>

			</div> <!--/.ld-file-upload-->
<?php	}
		else 
		{ ?>
			<div class="alert-msg ">
				<div class="icon  is-huge">
					<i class="bb-icon-alert-thin bb-icons is-huge" ></i>
				</div>
				<div class="msg">
					<p class="subtitle is-size-5 is-marginless">Temporalmente cerrado</p>
					<p class="is-marginless">Esta tarea no está recibiendo envíos en estos momentos, intenta cuando las sesiones de mentorías estén abiertas.</p>
				</div>
			</div>
<?php	}
	
	}
	else
	{
		//user has no acces 
		/*if($course_type == "selfpaced_bootcamp")
		{
			//Course is selfpaced, student has no access
			//1. Get course type
			$wc_mentorship_product =  get_field("bootcamp",$course_id)["wc_coursewmentorships"];
			$mentorship_product = wc_get_product( $wc_mentorship_product->ID );
			$teacher = get_field("teachers",$course_id)[0];
			$unixTimestamp = strtotime(get_field("bootcamp",$course_id)['next_start_date']);
			$day = date("d", $unixTimestamp);
			$month = date("F", $unixTimestamp);
			?>
			<div class="card colored" id="datalayer_course" data-name="<?php echo $mentorship_product->get_name();?>" data-id="<?php echo $mentorship_product->get_id();?>" data-price="<?php echo $mentorship_product->get_price();?>" data-category="<?php echo get_the_terms ( $mentorship_product->get_id(), 'product_cat' )[0]->name;?>">
				<div class="card-content">
					<div class="columns is-vcentered is-variable-in-desktop is-8">
						<div class="column is-7">
							<h2 class="title is-size-3 is-size-2-fullhd is-marginless" style="margin-bottom: 5px;">¡Recibe ayuda de expertos!</h2>
							<p class="is-marginless">Envía y recibe retroalimentación de esta tarea adquiriendo las <?php echo get_field("bootcamp",$course_id)['weeks']?> mentorías semanales en vivo para este curso online con <strong><?php echo get_the_title($teacher)?>, <?php echo get_field("subtitle_extra",$teacher->ID);?></strong></h4>
						</div>
						<div class="column has-text-centered">
							<?php 
							if(matched_cart_items($mentorship_product->get_id()) < 1)
							{
								echo do_shortcode('[add_to_cart id="'.$mentorship_product->get_id().'" style="text-align:center;"  class="from-learndash-banner is-marginless is-full"]'); 
							}
							else
							{ ?>
								<a href="<?php echo wc_get_checkout_url()?>" class="button teal is-medium is-full" title="Compralo Ahora">Compralo Ahora</a>
							<?php } ?>
							<p style="margin-top:10px;">Horario: <?php echo get_field("bootcamp",$course_id)['schedule'];?>
							<br>Próxima fecha: <strong><?php echo $day." de ".$month; ?></strong></p>	
						</div>
					</div>

				</div>

			</div>
<?php	}*/
	}
}
else{
	//Course has no group

}
?>
