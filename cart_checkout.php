<?php

session_start();
include_once("config.php");
include_once("paypal_ecfunctions.php");


$_SESSION['useraction'] = 'commit';
	

//Post Data received from product list page (Home)
if($_POST) 
{
	
	//Mainly we need 5 variables from an item, Item Name, Item Desc, Item Price, Item Number and Item Quantity.
	$ItemName = $_POST["itemname"]; //Item Name
	$ItemDesc = $_POST["itemdesc"]; //Item Desc
	$ItemPrice = $_POST["itemprice"]; //Item Price
	$ItemNumber = $_POST["itemnumber"]; //Item Number
	$ItemQty = $_POST["itemQty"]; // Item Quantity
	$ItemTotalPrice = number_format(($ItemPrice*$ItemQty),2); //(Item Price x Quantity = Total) Get total amount of product; 
	
	// Keep in array
	$cart_item = array("$ItemName","$ItemDesc","$ItemNumber","$ItemPrice","$ItemQty","$ItemTotalPrice"); 
	
	// update cart
	cart_process($cart_item);
	
} 
//--------------------------------------------
// Display cart items
// 1. From Cart menu
// 2. From Paypal site - Click on Cancel url
//--------------------------------------------
else {

	// (2) 	If the Request object contains the variable 'token',
	// 		then it means that the user is coming from PayPal site (Cancel URL).
	//if (isset($_REQUEST['token']))
		//$cancel_token = $_REQUEST['token'];


	// Check have existing product
	if ($_SESSION['cart_item_arr']) 
	{
		$cart_item_arr = $_SESSION['cart_item_arr'];	
		$cart_no = count($cart_item_arr);
	}
	else { 
		$cart_item_arr[] = array();
		$cart_no=0;
	}
}


//====================================
// Cart items amount + Shipping + Tax
//------------------------------------
$paymentAmount = $_SESSION['cart_item_total_amt'] + $shipping_amt + $tax_amt;	
			
$_SESSION["Payment_Amount"] = $paymentAmount; 



	//-------------------------------------------
	// Prepare url for items details information
	//-------------------------------------------
	if ($_SESSION['cart_item_arr']) 
	{

		// Cart items
		$payment_request = get_payment_request();
		
		$paymentAmount = $_SESSION["Payment_Amount"];	// from cart.php
		
		
		//-------------------------------------------------
		// Data to be sent to paypal - in SetExpressCheckout
		//--------------------------------------------------
		$shipping_data = '';
		if($shipping_amt)
				$shipping_data = '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($shipping_amt);
				
		$tax_data = '';
		if($tax_amt)
				$tax_data = '&PAYMENTREQUEST_0_TAXAMT='.urlencode($tax_amt);		
		
		$padata = 	$shipping_data.
					$tax_data.					
				 	$payment_request;				
		//echo '<br>'.$padata;			
					
					
		//'--------------------------------------------------------------------		
		//'	Tips:  It is recommend that to save this info for the drop off rate 
		///	Function to save data into DB
		//'--------------------------------------------------------------------
		SaveCheckoutInfo($padata);
	
									
		//'-------------------------------------------------------------
		//' Calls the SetExpressCheckout API call
		//' Prepares the parameters for the SetExpressCheckout API Call
		//'-------------------------------------------------------------		
		$resArray = CallShortcutExpressCheckout ($paymentAmount, $PayPalCurrencyCode, $paymentType, $PayPalReturnURL, $PayPalCancelURL, $padata);
		
		$ack = strtoupper($resArray["ACK"]);
		if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING")
		{
			//print_r($resArray);
			RedirectToPayPal ( $resArray["TOKEN"] );	// redirect to PayPal side to login
		} 
		else  
		{
			//Display a user friendly Error on the page using any of the following error information returned by PayPal
			DisplayErrorMessage('SetExpressCheckout', $resArray, $padata);
			
		}
			
	
	}else {
	
		header("Location: cart.php"); // back to cart if don't have cart items 
		exit;
	
	}

