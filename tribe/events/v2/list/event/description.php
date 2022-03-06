<?php
/**
 * View: List Single Event Description
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/list/event/description.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://m.tri.be/1aiy
 *
 * @version 5.0.0
 *
 * @var WP_Post $event The event post object with properties added by the `tribe_get_event` function.
 *
 * @see tribe_get_event() For the format of the event object.
 */

if ( empty( (string) $event->excerpt ) ) {
	return;
}
?>
<div class="tribe-events-calendar-list__event-description tribe-common-b2">
	<span class="is-hidden-mobile">
		<?php echo (string) $event->excerpt; ?>
		</span>
	<br>
	<div class="level is-mobile">
		<div class="level-left">
			<div class="level-item">
				Miralo en: 
			</div>
			<div class="level-item">
				<a href="<?php echo get_field('facebook',$event->ID)?>">
					<i class="bb-icons bb-icon-rounded-facebook"></i>
				</a>
			</div>
			<div class="level-item">
				<a href="<?php echo get_field('youtube',$event->ID)?>">
					<i class="bb-icons bb-icon-rounded-youtube"></i>
				</a>
			</div>
		</div>
	</div>
</div>