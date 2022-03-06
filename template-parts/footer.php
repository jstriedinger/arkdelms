<?php
$footer_widgets = buddyboss_theme_get_option( 'footer_widgets' );
$footer_widgets_columns = buddyboss_theme_get_option( 'footer_widget_columns' );
$footer_copyright = buddyboss_theme_get_option( 'footer_copyright' ); ?>



<?php if ( ( $footer_copyright ) && (!is_singular('lesson')) && (!is_singular('llms_quiz')) &&  (!is_singular('llms_assignment')) &&  (!is_singular('llms_my_certificate')) ) { ?>
	<footer class="footer-bottom bb-footer footer style-<?php echo buddyboss_theme_get_option( 'footer_style' ); ?>">
		<div class="container flex">
			<?php
			$copyright_text = buddyboss_theme_get_option( 'copyright_text' );
			$footer_menu = buddyboss_theme_get_option( 'footer_menu' );
			$footer_secondary_menu = buddyboss_theme_get_option( 'footer_secondary_menu' );
			$footer_socials = buddyboss_theme_get_option( 'boss_footer_social_links' );
			$footer_description = buddyboss_theme_get_option( 'footer_description' );
			$footer_tagline = buddyboss_theme_get_option( 'footer_tagline' );

			if( !empty( $copyright_text ) || !empty( $footer_menu ) ) {
				echo '<div class="footer-bottom-l">';

				if( buddyboss_theme_get_option( 'footer_style' ) == '2' ) {
					$logo_id = buddyboss_theme_get_option( 'footer_logo', 'id' );
					$logo = ( $logo_id ) ? wp_get_attachment_image( $logo_id, 'full' ) : get_bloginfo( 'name' );
					?>
					<div class="footer-logo-wrap">
						<a class="footer-loto" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<?php echo $logo; ?>
						</a><?php

						if( !empty( $footer_tagline ) ) {
							echo '<span class="footer-tagline">' . $footer_tagline . '</span>';
						} ?>
					</div><?php
				}

				if( !empty( $footer_menu ) && buddyboss_theme_get_option( 'footer_style' ) == '1' ) {
					wp_nav_menu( array(
						'menu' 		=> $footer_menu,
						'container'	=> false,
						'menu_class'=> 'footer-menu',
						'depth' => 1)
					);
				}

				
				if( !empty( $copyright_text ) ) { ?>
					<div class="copyright"><?php echo do_shortcode( $copyright_text ); ?></div><?php
				}
                
               
				

				echo '</div>';
			} ?>
			
            <div class="footer-courses-list">
                        <div class="footer-courses">
                            <h4 class="title is-size-5">Cursos Programación</h4>
                            <?php 
                                wp_nav_menu( array(
                                    'theme_location' 		=> 'footer-coding',
                                    'container'	=> false,
                                    'menu_class'=> 'footer-menu',
                                    'depth' => 1)
                                );
                            
                            ?>
                        </div>
                        <div class="footer-courses">
                            <h4 class="title is-size-5">Cursos Diseño y arte</h4>
                            <?php 
                                wp_nav_menu( array(
                                    'theme_location' 		=> 'footer-design',
                                    'container'	=> false,
                                    'menu_class'=> 'footer-menu',
                                    'depth' => 1)
                                );
                            
                            ?>
                        </div>
                        
            </div>

	<?php	$container_set = false;
			if( !empty( $footer_socials ) || !empty( $footer_description ) ) {
				echo '<div class="footer-bottom-r">';

					foreach ( $footer_socials as $network => $url ) {
						if ( ! empty( $url ) ) {
							if ( ! $container_set ) {
								echo '<ul class="footer-socials">';
								$container_set = true;
							}
							if ( 'email' === $network ) {
								echo '<li><a href="mailto:' . sanitize_email( $url ) . '" target="_top"><i class="bb-icon-rounded-' . $network . '"></i></a></li>';
							} else {
								echo '<li><a href="' . esc_url( $url ) . '" target="_blank"><i class="bb-icon-rounded-' . $network . '"></i></a></li>';
                            }
						}
					}
					
					if( $container_set ){
						echo '</ul>';
					}

					if ( !empty( $footer_description ) ) {
						echo '<div class="footer-desc">' . wpautop( do_shortcode( $footer_description ) ) . '</div>';
					}

				echo '</div>';
			}
			?>
		</div>
	</footer>
<?php } ?>
