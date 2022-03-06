<?php 
get_header();
$context = Timber::get_context();
$context['post'] = new Timber\Post();

$templates = array( 'workshop.twig' );

//config
$wc_product 		= get_field("wc_product",$context['post']->ID);
$product 			= wc_get_product( $wc_product->ID );

$wc_in_cart 		= false;
$current_user_id 	= get_current_user_id();
$current_user 		= wp_get_current_user();



$context["wc_product_id"] = $wc_product->ID;
$context["wc_price"] = $product->get_price();
$context["wc_regular_price"] = $product->get_regular_price();
if($product->is_on_sale())
{
    //lets calculate the temp discount
    $context["discount"] = ceil(100 - (($context["wc_price"] * 100) / $context["wc_regular_price"]));
    $context["is_on_sale"] = true;
    $context["sale_end"] = $product->get_date_on_sale_to();
}

$context["currency"] = get_woocommerce_currency();



if( matched_cart_items($wc_product->ID) > 0 )
    $wc_in_cart = true;

$context["in_cart"] = $wc_in_cart;

Timber::render( $templates, $context );
get_footer();
?>