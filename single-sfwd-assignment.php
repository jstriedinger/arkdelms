<?php
/**
 * The template for displaying single sfwd topic
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package BuddyBoss_Theme
 */

get_header();
$course_id = get_post_meta($post->ID,"course_id",true);
?>

<div id="primary" class="content-area bb-grid-cell">
	<main id="main" class="site-main has-medium-margin-top">
		<h1>Tarea de <i><?php echo get_the_author();?> </i></h1>
		<table class="table is-bordered">
		  <thead>
		    <tr>
		      <th>Curso</th>
		      <th>Lección</th>
		      <th>Instructor</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<tr>
		  		<td><?php echo get_the_title($course_id)?></td>
		  		<td><?php echo get_post_meta($post->ID,"lesson_title",true)?></td>
		  		<td>
		  			<div class="level">
		  				<div class="level-left">
				  			<?php foreach (get_field("teachers",$course_id) as $teacher) { ?>
				  				<div class="level-item">
			                        <div class="teacher-item small">
					  				<?php echo get_the_post_thumbnail( $teacher->ID, 'tiny', array( 'class' => 'circle  multi tiny' ) ); ?>
			                            <div class="content">
			                                <h5 class="is-marginless"><strong> <?php echo get_the_title($teacher->ID);?></strong></h5>
			                                <h6 class="is-marginless"><?php echo get_field("subtitle",$teacher->ID);?></h6>
			                            </div>
			                        </div>
				  				</div>
				  			<?php } ?>
		  				</div>
		  			</div>
		  		</td>
		  	</tr>
		  </tbody>
		</table>
		<?php
			the_post();
			do_action( THEME_HOOK_PREFIX . '_single_template_part_content', get_post_type() );

		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
