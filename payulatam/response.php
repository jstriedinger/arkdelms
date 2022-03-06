<?php
include '../../../../wp-load.php';
require_once '../../../plugins/woocommerce-payu-latam/payu-latam.php';

if ( ! is_user_logged_in() || !defined( 'ABSPATH' ) || (!isset($_REQUEST['signature']) &&  !isset($_REQUEST['firma']))) {
	write_log("Unauthorized request to payu response.php");
	wp_redirect(home_url());
	exit;
}
else
{
	
	if(isset($_REQUEST['referenceCode'])){
		$referenceCode = $_REQUEST['referenceCode'];
	} else {
		$referenceCode = $_REQUEST['ref_venta'];
	}

	if(isset($_REQUEST['transactionState'])){
		$transactionState = $_REQUEST['transactionState'];
	} else {
		$transactionState = $_REQUEST['estado'];
	}

	if(isset($_REQUEST['polResponseCode'])){
		$polResponseCode = $_REQUEST['polResponseCode'];
	} else {
		$polResponseCode = $_REQUEST['codigo_respuesta_pol'];
	}
	
	try
	{
		$order = new WC_Order($referenceCode);
		var_dump($transactionState);
		var_dump($polResponseCode);
		//Noew lets complete the order if thats neccesary
		if($transactionState == 4 && $polResponseCode == 1)
		{
			$order->payment_complete();
		}
		$redirect_url = $order->get_checkout_order_received_url();
		wp_redirect($redirect_url);

	}
	catch (Exception $e)
	{
		//Problem getting the order, redirect to home and log error
		write_log($e);
		var_dump($e);
	}
	
	exit;

}



?>