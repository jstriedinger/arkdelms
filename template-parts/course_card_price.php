<?php
$wc_product =  get_field("wc_product",$course_id);
if($wc_product )
{
	$wc_product_id = $wc_product->ID;

	$product = wc_get_product( $wc_product_id );

	if($product->is_on_sale())
	{
		echo "<span class='before'>$".$product->get_regular_price()."</span><br>";
		echo "<span class='actual'>$".$product->get_price()."</span>";
	}
	else 
	{
		echo "<span class='actual'>$".$product->get_price()."</span>";
	}

}
	

