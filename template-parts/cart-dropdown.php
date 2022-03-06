<div class="notification-wrap header-cart-link-wrap cart-wrap">
	<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart-link notification-link">
        <span data-balloon-pos="down" data-balloon="<?php _e( 'Cart', 'buddyboss-theme' ); ?>">
			<i class="bb-icon-shopping-cart"></i>
            <?php
            $wc_cart_count = wc()->cart->get_cart_contents_count();
            if( $wc_cart_count != 0 ) { ?>
                <span class="count"><?php echo wc()->cart->get_cart_contents_count(); ?></span>
            <?php } ?>
        </span>
	</a>
    
</div>