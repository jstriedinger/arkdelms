<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 5.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
	return;
}
//added stuff for better learndash
$product_id = $product->get_id();
if($product->get_type() == "subscription_variation")
{ ?>
	<li class="cart-item premium">
		<div class="media">
			<?php echo get_the_post_thumbnail($product->get_id(),'thumbnail'); ?>
		</div>
		<div class="content">
			<p class="title is-marginless uppercase"><?php echo $product->get_name(); ?></p>
			<?php
				echo "<p class='is-marginless small'>".$product->get_description()."</p>";
			?>
		</div>
		<div class="price">
		<?php echo "<p>$".number_format($order->get_item_subtotal( $item ), 0, ',', '.').$order->get_currency()."</p>"; 
		?>
	</div>
	
	</li>

<?php }
else
{
	$cid = get_post_meta($product_id, '_related_course');
	$args = array(
	  'post__in' => $cid[0],
	  'post_type' => 'sfwd-courses'
	);
	$course_type = get_field("delivery_type",$product_id);
	$term = get_the_terms( $product_id, 'product_cat' )[0];
	$item_in_cart = get_field("career",$product_id); 
	if($term->slug == "career")
	{
		$courses = (new WP_Query($args))->posts;
	}
	elseif($term->slug == "mentorship")
	{
		$weeks = 1;
		$item_in_cart = get_field("ld_course_mentorship",$product_id); 
		$weeks = get_field('bootcamp',$item_in_cart->ID)['weeks'];
		$teachers = get_field("teachers",$item_in_cart->ID); 
	}
	elseif($term->slug == "course")
		$item_in_cart = ((new WP_Query($args))->posts)[0]; ?>

	<li class="cart-item <?php echo $term->slug == 'career' ? 'career' : 'single-course'?>">
		<div class="media with-icon">
			<?php echo get_the_post_thumbnail($product->get_id(),'thumbnail'); ?>
		</div>
		<div class="content">
			<?php if ($term->slug != "mentorship") : ?>
				<span class="tag is-light <?php echo $term->slug == 'career' ? 'is-primary' : '' ?>"><?php echo $term->name?></span>
				<?php if ($term->slug == "career"): ?>
				<span class="tag is-light"><?php echo count($courses); ?> Cursos</span>
				<?php endif; ?>
			<?php else: ?>
				<span class="tag is-light">Curso</span>
				<span class="tag is-light" >
					<?php echo  $weeks ?> mentorías en vivo
				</span>
			<?php endif;?>

			<p class="subtitle is-size-5 is-marginless"><?php 
				echo $product->get_title();
			?></p>
			<?php if ($term->slug == "mentorship"): ?>
				<p class="is-marginless small">Con <?php foreach ($teachers as $i => $teacher) {
					if($i > 0)
						echo ", ";
					echo $teacher->post_title;
				} ?></p>
			<?php endif; ?>

		</div>
		<div class="price">
			<p>
			<?php echo "$".number_format($order->get_item_subtotal( $item ), 0, ',', '.')." ".$order->get_currency(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</p>
		</div>
		
	</li>
	<?php if($term->slug == "career") : ?>
	<ul>
		<?php foreach ($courses as $coursec) { 
			$teachersc = get_field("teachers",$coursec->ID); 
		?>
			<li class="cart-item small">
				<div class="media">
					<?php echo get_the_post_thumbnail($coursec->ID,'thumbnail'); ?>
				</div>
				<div class="content">

					<p class="subtitle is-size-6 is-marginless"><?php echo $coursec->post_title ?></p>
					<p class="is-marginless small">Con <?php foreach ($teachersc as $i => $teacher) {
						if($i > 0)
							echo ", ";
						echo $teacher->post_title;
					} ?></p>
					

				</div>
			</li>

		<?php } 
		?>
	</ul>
	<?php endif; ?>

<?php };

?>
