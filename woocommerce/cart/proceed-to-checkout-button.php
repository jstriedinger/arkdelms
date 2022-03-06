<?php
/**
 * Empty cart page
 *
 * @version 5.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="checkout-button button full  wc-forward">
	<?php esc_html_e( 'Proceed to checkout', 'woocommerce' ); ?>
	<i class="bb-icons bb-icon-arrow-right"></i>
</a>
