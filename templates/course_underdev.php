<?php
//get the field
defined( 'ABSPATH' ) || exit;
$courseid = $arg1;

$is_under_dev = get_field("under_development",$courseid);
if($is_under_dev)
{
	$underdev_msg = get_field("under_dev_msg",$courseid); ?>
	<div class="alert-msg <?php echo $arg3; ?>">
			<div class="icon is-medium rotate">
				<i class="fas fa-sync-alt fa-2x"></i>
			</div>
			<div class="msg"><?php echo $underdev_msg; ?>
			 </div>
	</div>

<?php } 
?>