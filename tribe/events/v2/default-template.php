<?php
/**
 * View: Default Template for Events
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/default-template.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @version 5.0.0
 */

use Tribe\Events\Views\V2\Template_Bootstrap;

get_header();?>
<div id="primary" class="content-area bb-grid-cell is-paddingless-bottom">
<section class="section has-huge-padding-bottom has-big-padding-top" id="home">
	<img src="<?php echo get_stylesheet_directory_uri()?>/dist/img/aboutus_bg.jpg" alt="calendario"
	             class="banner-img wp-post-image"/>
	<div class="container">
		<div class="columns is-centered">
			<div class="column is-two-thirds has-text-centered">
				<h1 class="is-big">CALENDARIO</h1>
				<p>¡Ven a algunas de nuestras charlas gratuitas en programación de videojuegos y arte digital!</p>
			</div>
			
		</div>
	</div>
	<div class="wave bottom">
		<?php include_once(locate_template('views/svg/wave.svg')); ?>
	    </div>
						
</section>

<?php
echo tribe( Template_Bootstrap::class )->get_view_html();?>
</div><?php
get_footer();