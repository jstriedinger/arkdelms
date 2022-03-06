<?php
/*
Template Name: Upgrade
*/

get_header();
$context = Timber::get_context();
$context['post'] = new Timber\Post();
$templates = array( 'upgrade.twig' );

//Lets see if the user has this active subscription
$is_premium = wcs_user_has_subscription( get_current_user_id(), '', 'active' );
if($is_premium)
{

	$currency = get_woocommerce_currency();

	


	//lets get the info of the users subscription
	$wc_subcription = array_values(wcs_get_users_subscriptions(get_current_user_id()))[0];
	$item_line_n;
	$subscription_v;
	$subs_id = $wc_subcription->get_ID();

	//get subscription data for the add to cart links
	foreach( $wc_subcription->get_items() as $item_line_number => $item_arr){
	    $premiumwc = $item_arr->get_product();
	    $item_line_n = $item_line_number;
	    $subscription_v = $item_arr["variation_id"];
	    $product_idd = $item_arr["product_id"];
	    break;
	} 
	$premiumwc = wc_get_product($premiumwc->get_parent_id());
	$variations = $premiumwc->get_available_variations();
	?>
	<section class="section" id="pricing">
	<div class="column is-full has-text-centered mentoring-price-select">
		<h1 class="is-big">Elige tu opción preferida</h1>
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
	</div>
	<div class="pricing">
		<?php foreach( $premiumwc->get_children() as $variation_id )  { 
            // Get the WC_Product_Variation object
            $variation = wc_get_product( $variation_id );
            $tipo = $variation->get_attribute("tipo");
            $period = ($tipo == "Mensual" ? 'cada mes' : ($tipo == "Semestral" ? 'cada 6 meses' : 'cada 12 meses'));
            $per_session = (int)($tipo == "Mensual" ? ($variation->get_price() / 4) : ($tipo == "Semestral" ? ($variation->get_price()/6/4) : ($variation->get_price()/12/4)));
            ?>
            <div class="pricing-item <?php echo strtolower($tipo);?>" data-price="<?php echo $variation->get_price(); ?>" data-regular="<?php echo $variation->get_regular_price(); ?>">
        	<?php if($tipo == "Anual") { ?>
				<div class="preheader">
					<h3>anual</h3>
				</div>
			<?php } ?>
			<header>
				<?php if($tipo != "Anual") { ?>
					<h4><?php echo $tipo; ?></h4>
				<?php }  ?>


				<div class="price-item">
					
					<h2 class="is-big">$<span><?php echo $per_session;?></span><small class="currency-lister"><?php echo $currency ?></small></h2>

					<p>Cada mentoría.<br>Total 
						<span class="actual"><?php echo " $".$variation->get_price();?> </span><span class="currency-lister"><?php echo $currency ?></span> <?php echo $period;?></p>
				</div>
				<?php if ($is_premium) {
					if($subscription_v == $variation_id) { ?>
						<button class="button is-full large is-square disabled"  diabled rel="nofollow">Suscripción actual</a>
					<?php } else { 
						$nonce = wp_create_nonce( 'wcs_switch_request' );
						?>
						<a href="?add-to-cart=<?php echo $product_idd;?>&switch-subscription=<?php echo $subs_id;?>&variation_id=<?php echo $variation_id;?>&item=<?php echo $item_line_number;?>&_wcsnonce=<?php echo $nonce;?>" class="button is-full large is-square <?php echo $tipo != 'Anual' ? 'teal' : ''?>" rel="nofollow">Cambiate ahora</a>
					<?php }
			 	}  else {?>
				<?php if( matched_cart_items($variation_id) < 1 ) {?>
				    <a href="?add-to-cart=<?php echo $variation_id;?>" class="button is-full large is-square <?php echo $tipo != 'Anual' ? 'teal' : ''?>" rel="nofollow">Únete ahora</a>
				<?php } else { ?>
				    <a href="<?php echo wc_get_checkout_url();?>" class="button is-full large is-square <?php echo $tipo != 'Anual' ? 'teal' : ''?>" rel="nofollow">Únete ahora</a>
				<?php }	?>
			<?php } ?>
				
			</header>
			<div class="content">
				<ul>
					<li>
						<i class="icon"><span class="fas fa-check"></span></i>Todos los horarios disponibles
					</li>
					<li>
						<i class="icon"><span class="fas fa-check"></span></i>1 mentoría x semana
					</li>
					<li>
						<i class="icon"><span class="fas fa-check"></span></i>Canal Discord privado
					</li>
					<li>
						<i class="icon"><span class="fas <?php echo $tipo == 'Mensual' ? 'fa-times' : 'fa-check'?>"></span></i>Dcto extra en cursos
					</li>
				</ul>
			</div>
		</div>
            
        <?php } ?>
		
		
		
	</div>
	
</section>
<?php }
else
{?>
	<div id="primary" class="content-area bb-grid-cell is-paddingless-bottom">
	<section class="section">
		<span class="tag is-warning is-medium is-full"><span class="bb-icons bb-icon-alert-triangle" style="margin-right:5px;"></span>No tienes acceso a esta pagina</span>
	</section>
<?php } ?>




<?php
echo "</div>";
get_footer();
?>
