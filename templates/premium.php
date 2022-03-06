<?php
/*
Template Name: PREMIUM
*/

get_header();
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'premium.twig' );


$premium_id = get_field("premium");

$premiumwc = wc_get_product($premium_id);
$currency = get_woocommerce_currency();
$context['premium_courses'] = get_field("premium_courses",$premium_id);



//Lets see if the user has this active subscription
$is_premium = wcs_user_has_subscription( get_current_user_id(), $premiumwc->get_id(), 'active' );
if($is_premium)
{
	$item_line_n;
	$user_premium_product_id;
	$variations = $premiumwc->get_available_variations();
	$wc_subcription = array_values(wcs_get_users_subscriptions(get_current_user_id()))[0];
	$user_premium_subscription_id;
	$subs_id = $wc_subcription->get_ID();

	// Iterating through subscription items
	foreach( $wc_subcription->get_items() as $item_line_number => $item_arr){
	    // Get the name
	    $item_line_n = $item_line_number;
	    $user_premium_subscription_id = $item_arr["variation_id"];
	    $user_premium_product_id = $item_arr["product_id"];
	    break;
	}
}

//Get mentoring schedules
$args = array (
    'post_type'              => 'mentoring_schedule',
    'posts_per_page' => '5'

);
$context['schedules'] = Timber::get_posts( $args );

//Get semester and annual plans right away
$plans = $premiumwc->get_children();
$context['semester'] = wc_get_product($plans[1]);
$context['yearly'] = wc_get_product($plans[2]); 
$context['is_premium'] = $is_premium; 
$context['semester_in_cart'] = matched_cart_items($plans[1]) >= 1;
$context['yearly_in_cart'] = matched_cart_items($plans[2]) >= 1;

Timber::render( "premium.twig", $context ); ?>
<section class="section" id="pricing">
	<div class="columns is-centered">
		<div class="column is-two-thirds has-text-centered mentoring-price-select">
			<h1 class="is-big">Elige to opción preferida</h1>

			
			<h3 class="is-marginless"><span class="select currencies white">
	              <select id="currencies">
	                <option selected value="<?php echo $currency?>"><?php echo $currency?></option>
	                <option value="COP">COP</option>
	                <option value="MXN">MXN</option>
	                <option value="ARS">ARS</option>
	                <option value="PEN">PEN</option>
	                <option value="CLP">CLP</option>
	              </select>
	            </span ></h3>
	        <p class="is-marginless"><small>El cambio de moneda es informativo, la compra se hace en USD</small></p>
		</div>
		
	</div>
	<div class="pricing">
		<?php foreach( $premiumwc->get_children() as $variation_id )  { 
            // Get the WC_Product_Variation object
            $variation = wc_get_product( $variation_id );
            $is_on_sale = $variation->is_on_sale();
            $tipo = $variation->get_attribute("tipo");
            $period = ($tipo == "Mensual" ? 'cada mes' : ($tipo == "Semestral" ? 'cada 6 meses' : 'anual'));
            $per_month = (int)($tipo == "Mensual" ? $variation->get_price() : ($tipo == "Semestral" ? ($variation->get_price()/6) : ($variation->get_price()/12)));
            if($is_on_sale)
            {
            	$per_month_regular = (int)($tipo == "Mensual" ? $variation->get_regular_price() : ($tipo == "Semestral" ? ($variation->get_regular_price()/6) : ($variation->get_regular_price()/12)));
            }

            ?>
            <div class="pricing-item <?php echo strtolower($tipo);?>" data-price="<?php echo $variation->get_price(); ?>" data-regular="<?php echo $variation->get_regular_price(); ?>">
        	<?php if($tipo == "Anual") { ?>
				<div class="preheader">
					<h3>anual</h3>
				</div>
			<?php } ?>
			<header>
				<?php if($tipo != "Anual") { ?>
					<h4 class="uppercase"><?php echo $tipo; ?></h4>
				<?php }  ?>


				<div class="price-item">
					
						<?php if ($is_on_sale): ?>
							<span class="before is-medium">$<?php echo $per_month_regular;?> </span><br>
						<?php endif; ?>
					<h2 class="is-big">
						$<span><?php echo $per_month;?></span><small > <span class="currency-lister"><?php echo $currency ?></span> / mes*</small></h2>
						

					<p>
						<?php  if ($tipo == "Mensual"): ?>
						*Cobro mensual
						<?php else : ?>
						*Cobro <?php echo $period; ?> de
						<span class="actual"><?php echo " $".$variation->get_price();?> </span><span class="currency-lister"><?php echo $currency ?></span>
						<?php endif; ?>
					</p>
				</div>
				<?php if ($is_premium) {
					if($user_premium_subscription_id == $variation_id) { ?>
						<button class="button is-full large is-square disabled"  diabled rel="nofollow">Suscripción actual</a>
					<?php } else { 
						$nonce = wp_create_nonce( 'wcs_switch_request' );
						?>
						<a href="?add-to-cart=<?php echo $user_premium_product_id;?>&switch-subscription=<?php echo $subs_id;?>&variation_id=<?php echo $variation_id;?>&item=<?php echo $item_line_number;?>&_wcsnonce=<?php echo $nonce;?>" class="button is-full large is-square <?php echo $tipo != 'Anual' ? 'is-outline is-white' : 'teal'?>" rel="nofollow">Cambiate ahora</a>
					<?php }
			 	}  else {?>
				<?php if( matched_cart_items($variation_id) < 1 ) {?>
				    <a href="?add-to-cart=<?php echo $variation_id;?>" class="button is-full large is-square <?php echo $tipo != 'Anual' ? 'is-outline is-white' : 'teal'?>" rel="nofollow">Únete ahora</a>
				<?php } else { ?>
				    <a href="<?php echo wc_get_checkout_url();?>" class="button is-full large is-square <?php echo $tipo != 'Anual' ? 'is-outline is-white' : 'teal'?>" rel="nofollow">Únete ahora</a>
				<?php }	?>
			<?php } ?>
				
			</header>
			<div class="content">
				<table>
					
					<tr>
						<td><i class="icon"><span class="bb-icon-check"></span></i></td>
						<td class="txt">Accede a todas las mentorías semanales</td>
					</tr>
					<tr>
						<td><i class="icon"><span class="bb-icon-check"></span></i></td>
						<td class="txt">Tarjeta de crédito, débito o Paypal</td>
					</tr>
					<tr>
						<td><i class="icon"><span class="bb-icon-check"></span></i></td>
						<td class="txt">Canal Discord privado</td>
					</tr>
					
					<tr>
						<td><i class="icon"><span class="<?php echo $tipo == 'Mensual' ? 'bb-icon-close' : 'bb-icon-check'?> "></span></i></td>
						<td class="txt">Revisión de tareas</td>
					</tr>
					<?php if ($tipo != 'Mensual') : ?>
					<tr>
						<td><i class="icon"><span class="fas fa-gift fa-lg"></span></i></td>
						<td class="txt">Acceso de por vida a los cursos de programación con tu primera suscripción</td>
					</tr>
					<?php endif; ?>
					
				</table>
				
			</div>
		</div>
            
        <?php } ?>
		
		
		
	</div>
	<div class="columns has-tiny-padding-top is-vcentered is-multiline is-centered">
		<div class="column is-narrow">
			<p class="content is-small is-marginless">Compra segura <i class="fas fa-shield-alt"></i></p>

		</div>
		<div class="column is-one-third" >
			<img src="<?php bloginfo('stylesheet_directory'); ?>/dist/img/cards.png" alt="metodos de pago arkde"  class="is-tiny">
			
		</div>
		
	</div>
	
</section>

<?php
Timber::render( "snippets/faq.twig", $context ); 
echo "</div>";

get_footer();
?>
