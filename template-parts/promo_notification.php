<div class="notification-bar" id="promo-bar">
	<div class="container">
		<div class="content is-marginless">
			<?php 
			$notif_date = get_field('end_date',$notif->ID);
			$dateF = DateTime::createFromFormat('d/m/Y', $notif_date);
			 ?>
			<p ><?php echo wp_strip_all_tags( $notif->post_content );?><span style='font-size:18px;margin-left:5px;'>&#127881;</span><i class="bb-icon-watch-alarm is-medium"></i><span id="clock" data-date="<?php echo $dateF->format("Y/m/d");?>"></span> </p>


		</div>
		<div class="cta">
			<?php $cta = get_field('cta',$notif->ID);?>
			<a href="<?php echo $cta['link']?>" class="button teal"><?php echo $cta['txt'];?></a>
		</div>
		<button class="modal-close is-medium close-button" data-modal="premium-course-modal" aria-label="close"></button>
		
	</div>
</div>