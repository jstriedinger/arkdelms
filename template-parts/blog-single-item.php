<?php 
global $post;
?>

<?php if ( !is_single() || is_related_posts() ) { ?>
			<div class="post-inner-wrap">
		<?php } ?>

		<?php 
		if ( ( !is_single() || is_related_posts() ) && function_exists( 'buddyboss_theme_entry_header' ) ) {
			buddyboss_theme_entry_header( $post );
		} 
		?>

		<div class="entry-content-wrap">
			<?php 
			$featured_img_style = buddyboss_theme_get_option( 'blog_featured_img' );

			if ( !empty( $featured_img_style ) && $featured_img_style == "full-fi-invert" && is_single()) {
				if ( is_single() && ! is_related_posts() ) { ?>
					<?php if ( has_post_thumbnail() ) { ?>
						<figure class="entry-media entry-img bb-vw-container1">
							<?php the_post_thumbnail( 'large', array( 'sizes' => '(max-width:768px) 768px, (max-width:1024px) 1024px, (max-width:1920px) 1920px, 1024px' ) ); ?>
							
						</figure>
					<?php } ?>
					<header class="entry-header">
                        
                        <?php
                        if(has_tag())
                        {
                            echo "<div class='tags'>";
                             foreach(get_the_tags() as $tag) { ?>
                                 <span class="tag is-light"><?php echo $tag->name ?></span>
                                 
                     <?php     }
                            echo "</div>";
     
                        }
						if ( is_singular() && ! is_related_posts() ) :
							the_title( '<h1 class="entry-title title is-size-1-desktop">', '</h1>' );
						else :
							$prefix = "";
							if( has_post_format( 'link' ) ){
								$prefix = __( '[Link]', 'buddyboss-theme' );
								$prefix .= " ";//whitespace
							}
							the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $prefix, '</a></h2>' );
						endif;

						if( has_post_format( 'link' ) && function_exists( 'buddyboss_theme_get_first_url_content' ) && ( $first_url = buddyboss_theme_get_first_url_content( $post->post_content ) ) != "" ) : ?>
							<p class="post-main-link"><?php echo $first_url;?></p>
						<?php endif; ?>
                        <div class="content is-small ">
                            <i class="bb-icon-clock"></i> <?php echo get_the_date('j M, Y')?>
                        </div>
					</header><!-- .entry-header -->

				<?php } ?>

			<?php } else { ?>



				<?php if ( ( isset($post->post_type) && $post->post_type === 'post' ) || ( ! has_post_format( 'quote' ) && is_singular( 'post' ) ) ) : ?>
					<?php get_template_part( 'template-parts/entry-meta' ); ?>
				<?php endif; ?>

				<?php if ( is_single() && ! is_related_posts() ) { ?>
					<?php if ( has_post_thumbnail() ) { ?>
						<figure class="entry-media entry-img bb-vw-container1">
							<?php if ( !empty( $featured_img_style ) && $featured_img_style == "full-fi" ) {
								the_post_thumbnail( 'large', array( 'sizes' => '(max-width:768px) 768px, (max-width:1024px) 1024px, (max-width:1920px) 1920px, 1024px' ) );
							} else {
								the_post_thumbnail( 'large' ); 
							} ?>
						</figure>
					<?php } ?>
				<?php } ?>

			<?php } ?>
			

		</div>

		<?php if ( !is_single() || is_related_posts() ) { ?>
			</div><!--Close '.post-inner-wrap'-->
		<?php } 
		//ARKDE added to that content ins after entry-content-wrap
		if(!empty( $featured_img_style ) && $featured_img_style == "full-fi-invert") : ?>
			

			<?php if ( is_singular() && ! is_related_posts() ) { ?>
				<div class="entry-content content">
				<?php
					the_content( sprintf(
					wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'buddyboss-theme' ), array(
						'span' => array(
							'class' => array(),
						),
					)
					), get_the_title()
					) );
				?>
				</div><!-- .entry-content -->
			<?php } ?>
		<?php endif; ?>