<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>
	<div class="columns is-multiline arkde-cart is-variable-in-desktop is-10">
		<div class="column is-two-thirds">
				<ul class="">
				<?php
					//get if it has premiun sale benefits
					$current_user = wp_get_current_user();
					$currency = get_woocommerce_currency();

					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						$terms = get_the_terms( $product_id, 'product_cat' );
						$course_type = get_field("delivery_type",$product_id);

						$term = null;
						if(!empty($terms))
							$term = array_values($terms)[0];

						$is_mentorship = false;
						$is_bootcamp = false;
						$is_career = false;
						$is_workshop = false;
						
						if($term->slug == "mentorship")
						{
							$is_mentorship = true;
							$weeks = 1;
							//get the LD course
							$ld_course = get_field("ld_course_mentorship",$product_id);
							//Get the num of weeks of mentorship
							$weeks = get_field('bootcamp',$ld_course->ID)['weeks'];
							//get the next start date of mentorhsips
							$batch = get_field('bootcamp',$ld_course->ID)['next_start_date'];
								
						}
						else if($term->slug == "bootcamp")
						{
							$is_bootcamp =  true;
							$batch = get_field('bootcamp',(get_field("bootcamp",$product_id))->ID)['next_start_date']; 
						} 
						else if($term->slug == "career")
						{
							$is_career = true;
							$args = array(
								'post__in' => get_post_meta($product_id, '_related_course')[0],
								'post_type' => 'sfwd-courses'
							);
							$courses = (new WP_Query($args))->posts;
						}
						else if($term->slug == "workshop")
						{
							$is_workshop = true;
							$workshop = get_field("workshop",$product_id);
							//get the next start date of mentorhsips
							$batch = get_field('start_date',$workshop->ID);
							$hour = get_field('start_date_hour',$workshop->ID);

						}
						?>
				  	<li class='cart-item <?php echo $is_career ? "career" : "" ?>'>
						<?php
						// Active price: 
						$price = wc_get_price_to_display( $_product, array( 'price' => $_product->get_price() ) );

						if($_product->is_on_sale() )
							$reg_price = wc_get_price_to_display( $_product, array( 'price' => $_product->get_regular_price() ) );
						?>
						
						<div class="media">
							<?php echo get_the_post_thumbnail($_product->get_id(),'thumbnail'); ?>
						</div>
						<div class="content">
							<?php if($is_mentorship): ?>
								<span class="tag is-light <?php echo $is_career ? 'is-primary' : '' ?>" style="margin-bottom: 3px">
								Curso Online</span>
								<span class="tag is-light <?php echo $is_career ? 'is-primary' : '' ?>" style="margin-bottom: 3px">
									<?php echo  $weeks ?> mentorías en vivo
								</span>
							<?php else: ?>
								<span class="tag is-light <?php echo $is_career ? 'is-primary' : '' ?>" style="margin-bottom: 3px">
								<?php echo $term->name; ?>
								</span>
							<?php endif; ?>
							<p class="subtitle is-size-6 is-marginless"><?php echo $_product->get_title(); ?></p>
							<?php if ($is_bootcamp || $is_mentorship): ?>
							<span class="small is-hidden-mobile">Próximo batch: <?php echo empty($batch) ? 'TBA' : $batch; ?></span>
							<?php elseif($is_workshop): ?>
							<span class="small is-hidden-mobile">Próximo fecha: <?php echo empty($batch) ? 'TBA' : $batch." a las ".$hour; ?></span>
							<?php endif; ?>
							<div class="is-hidden-tablet price" style="padding-top:5px;">
							<?php
								if($_product->is_on_sale() )
								{
									echo "<p><span class='before'>$".number_format($reg_price, 0, ',', '.')."</span>";
									echo "  $".number_format($price, 0, ',', '.')."<small>".$currency."</small></p>";
								}
								else
									echo "<p>$".number_format($price, 0, ',', '.')."<small>".$currency."</small></p>";
							?>
							</div>
						</div>
						<div class="price">
							<?php
							if($_product->is_on_sale() )
							{
								echo "<span class='before'>$".number_format($reg_price, 0, ',', '.')."</span>";
								echo "<p>$".number_format($price, 0, ',', '.')."<small>".$currency."</small></p>";
							}
							else
								echo "<p>$".number_format($price, 0, ',', '.')."<small>".$currency."</small></p>";
						?>
						</div>
						<div class="product-remove">
							<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_html__( 'Remove this item', 'woocommerce' ),
									esc_attr( $product_id ),
									esc_attr( $_product->get_sku() )
								),
								$cart_item_key
							);
						?>
						</div>
					</li>

				<?php
					if($is_career)
					{ ?>
					<ul>
						<?php foreach ($courses as $coursec) { 
						?>
							<li class="cart-item small">
								<div class="media">
									<?php echo get_the_post_thumbnail($coursec->ID,'thumbnail'); ?>
								</div>
								<div class="content">
									<p class="is-marginless"><?php echo $coursec->post_title ?></p>
									
								</div>
							</li>

						<?php }	?>
					</ul>

				<?php	}
				}
					?>
					
				</ul>

				
			
			<?php if ( wc_coupons_enabled() ) { ?>
				<div class="coupon">
					 <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button apply-coupon" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			<?php } ?>
			

			<?php /*<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button> */ ?>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
		</div>
		<div class="column">
			<div class="box cart-collaterals">
				<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

					<?php
						/**
						 * Cart collaterals hook.
						 *
						 * @hooked woocommerce_cross_sell_display
						 * @hooked woocommerce_cart_totals - 10
						 */
						do_action( 'woocommerce_cart_collaterals' );
					?>
			</div>
			
		</div>
	</div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<?php do_action( 'woocommerce_after_cart' ); ?>