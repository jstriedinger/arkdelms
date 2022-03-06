<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
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

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">
	
	<h4 class="subtitle is-size-5" style="margin-bottom:5px">Total:</h4>
	<h2 class="title is-size-2"><?php echo WC()->cart->get_cart_total()."<small>".get_woocommerce_currency()."</small>" ?></h2>
	<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	<p class="content is-small is-marginless has-tiny-padding-top">Compra segura <i class="fas fa-shield-alt"></i></p>
	<img src="<?php bloginfo('stylesheet_directory'); ?>/dist/img/cards.png" alt="metodos de pago arkde"  class="is-tiny" style="padding-top:5px;">
</div>