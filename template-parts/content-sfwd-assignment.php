<?php
/**
 * Template part for displaying sfwd content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package BuddyBoss_Theme
 */
?>
<article class="ld-table-list ld-assignment-list arkde <?php esc_attr( implode( ' ', get_post_class() ) )?>" id="post-<?php the_ID(); ?>">
	<div class="ld-table-list-item">

	<div class="ld-table-list-item-preview assignment">

		<div class="ld-table-list-columns">
		    <div class="answer">
		        <?php echo get_field("answer");?>
		    </div>
		    <?php if (get_post_meta( $post->ID, 'file_link', true )) : ?>
		    <div class="answer-file">
		        <a href='<?php echo esc_attr( get_post_meta( $post->ID, 'file_link', true ) ); ?>' target="_blank">
	             <span class="ld-item-icon">
	                 <span class="ld-icon ld-icon-download" aria-label="<?php esc_html_e( 'Download Assignment', 'buddyboss-theme' ); ?>"></span>
	             </span>
	             <i>
	              <?php echo $post->file_name; ?>
	             </i>
	            </a>
		        
		    </div>
		    <?php endif; ?>
		    

		</div> <!--/.ld-table-list-columns-->

	</div>

	</div>
	<br>
	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
			sprintf(
			wp_kses(
			/* translators: %s: Name of current post. Only visible to screen readers */
			__( 'Edit <span class="screen-reader-text">%s</span>', 'buddyboss-theme' ), array(
				'span' => array(
					'class' => array(),
				),
			)
			), get_the_title()
			), '<span class="edit-link">', '</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</artcile>
<br>