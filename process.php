<?php
session_start();

// Check if user logged in
if (!isset($_SESSION["ShopperID"])) {
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

include("mypaypal.php");
include("mysql.php");
$MainContent = "";
$conn = new Mysql_Driver();

if($_POST)
{
	// Retrieve input from checkout-details.php
	$_SESSION["shipName"] = $_POST['txtName'];
	$_SESSION["shipAddress"] = $_POST['txtAddress'];
	$_SESSION["shipCountry"] = $_POST['txtCountry'];
	$_SESSION["shipPhone"] = "(65) ".$_POST['txtPhone'];
	$_SESSION["shipEmail"] = $_POST['txtEmail'];
	$_SESSION["deliveryMessage"] = $_POST['txtMessage'];
	if ($_SESSION["deliveryMessage"] == "") {
		$_SESSION["deliveryMessage"] = "NULL";
	}
	$_SESSION["billName"] = $_POST['txtBillName'];
	$_SESSION["billAddress"] = $_POST['txtBillAddress'];
	$_SESSION["billCountry"] = $_POST['txtBillCountry'];
	$_SESSION["billPhone"] = "(65) ".$_POST['txtBillPhone'];
	$_SESSION["billEmail"] = $_POST['txtBillEmail'];
	
	$paypal_data = '';
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach($_SESSION['Items'] as $key=>$item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	
	// Data to be sent to PayPal
	$padata = '&CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTACTION=Sale'.
			  '&ALLOWNOTE=1'.
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] +
				                             $_SESSION["TaxAmount"] + 
							     $_SESSION["DeliveryCharge"]).
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]). 
			  '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["DeliveryCharge"]). 
			  '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["TaxAmount"]). 	
			  '&BRANDNAME='.urlencode("Gerbern").
			  $paypal_data.				
			  '&RETURNURL='.urlencode($PayPalReturnURL).
			  '&CANCELURL='.urlencode($PayPalCancelURL);	
		
	// "SetExpressCheckOut" method needs to be executed to obtain paypal token
	$httpParsedResponseAr = PPHttpPost('SetExpressCheckout', $padata, $PayPalApiUsername, 
	                                   $PayPalApiPassword, $PayPalApiSignature, $PayPalMode);
		
	// Respond according to message we receive from Paypal
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {					
		if($PayPalMode=='sandbox')
			$paypalmode = '.sandbox';
		else
			$paypalmode = '';
				
		// Redirect user to PayPal store with Token received.
		$paypalurl ='https://www'.$paypalmode. 
		            '.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.
					$httpParsedResponseAr["TOKEN"].'';
		header('Location: '.$paypalurl);
	}
	else {
		// Show error message
		$MainContent .= "<div style='color:red'><b>SetExpressCheckout failed : </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"])."</div>";
		$MainContent .= "<pre>";
		$MainContent .= print_r($httpParsedResponseAr);
		$MainContent .= "</pre>";
	}
}

// Paypal redirects back to this page using ReturnURL, TOKEN and Payer ID should be received
if(isset($_GET["token"]) && isset($_GET["PayerID"])) {	
	// These two variables will be used to execute the "DoExpressCheckoutPayment"
	$token = $_GET["token"];
	$playerid = $_GET["PayerID"];
	$paypal_data = '';
	// Get all items from the shopping cart, concatenate to the variable $paypal_data
	// $_SESSION['Items'] is an associative array
	foreach($_SESSION['Items'] as $key=>$item) {
		$paypal_data .= '&L_PAYMENTREQUEST_0_QTY'.$key.'='.urlencode($item["quantity"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_AMT'.$key.'='.urlencode($item["price"]);
	  	$paypal_data .= '&L_PAYMENTREQUEST_0_NAME'.$key.'='.urlencode($item["name"]);
		$paypal_data .= '&L_PAYMENTREQUEST_0_NUMBER'.$key.'='.urlencode($item["productId"]);
	}
	// Data to be sent to PayPal
	$padata = '&TOKEN='.urlencode($token).
			  '&PAYERID='.urlencode($playerid).
			  '&PAYMENTREQUEST_0_PAYMENTACTION='.urlencode("SALE").
			  $paypal_data.	
			  '&PAYMENTREQUEST_0_ITEMAMT='.urlencode($_SESSION["SubTotal"]).
              '&PAYMENTREQUEST_0_TAXAMT='.urlencode($_SESSION["TaxAmount"]).
              '&PAYMENTREQUEST_0_SHIPPINGAMT='.urlencode($_SESSION["DeliveryCharge"]).
			  '&PAYMENTREQUEST_0_AMT='.urlencode($_SESSION["SubTotal"] + 
			                                     $_SESSION["TaxAmount"] + 
								                 $_SESSION["DeliveryCharge"]).
			  '&PAYMENTREQUEST_0_CURRENCYCODE='.urlencode($PayPalCurrencyCode);
	
	// We need to execute the "DoExpressCheckoutPayment" at this point 
	// to receive payment from user
	$httpParsedResponseAr = PPHttpPost('DoExpressCheckoutPayment', $padata, 
	                                   $PayPalApiUsername, $PayPalApiPassword, 
									   $PayPalApiSignature, $PayPalMode);
	
	// Check if successful
	if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
	   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
	
		$conn->connect();
	
		// Update stock inventory in product table after successful checkout
		$qry = "SELECT ProductID, Quantity FROM ShopCartItem WHERE ShopCartID=$_SESSION[Cart]";
		$result = $conn->query($qry);
		while ($row = $conn->fetch_array($result)) {
			$productid = $row["ProductID"];
			$quantityBought = $row["Quantity"];
			$conn->query("UPDATE Product SET Quantity=Quantity-$quantityBought WHERE ProductID=$productid");
		}
	
		// Update shopcart table, close the shopping cart (OrderPlaced=1)
		$total = $_SESSION["SubTotal"] + $_SESSION["TaxAmount"] + $_SESSION["DeliveryCharge"];
		$qry = "UPDATE shopcart SET Quantity=$_SESSION[NumCartItem], OrderPlaced=1, SubTotal=$_SESSION[SubTotal], ShipCharge=$_SESSION[DeliveryCharge], Tax=$_SESSION[TaxAmount], Discount=$_SESSION[Discount], Total=$total WHERE ShopCartID=$_SESSION[Cart]";
		//echo $qry;
		$conn->query($qry);
		
		// We need to execute the "GetTransactionDetails" API Call at this point 
		// to get customer details
		$transactionID = urlencode(
		                 $httpParsedResponseAr["PAYMENTINFO_0_TRANSACTIONID"]);
		$nvpStr = "&TRANSACTIONID=".$transactionID;
		$httpParsedResponseAr = PPHttpPost('GetTransactionDetails', $nvpStr, 
		                                   $PayPalApiUsername, $PayPalApiPassword, 
						   $PayPalApiSignature, $PayPalMode);

		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || 
		   "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			// Generate order entry and feed back orderID information
				
			// addslashes function adds escape code for special characters, e.g. '
			$ShipName = addslashes(urldecode($httpParsedResponseAr["SHIPTONAME"]));
			
                        $ShipAddress = urldecode($httpParsedResponseAr["SHIPTOSTREET"]);
			if (isset($httpParsedResponseAr["SHIPTOSTREET2"]))
				$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTREET2"]);
			if (isset($httpParsedResponseAr["SHIPTOCITY"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCITY"]);
			if (isset($httpParsedResponseAr["SHIPTOSTATE"]))
			    $ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOSTATE"]);
			$ShipAddress .= ' '.urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]). 
			                ' '.urldecode($httpParsedResponseAr["SHIPTOZIP"]);
			$ShipCountry = urldecode($httpParsedResponseAr["SHIPTOCOUNTRYNAME"]);
			$ShipEmail = urldecode($httpParsedResponseAr["EMAIL"]);			
			
			// Insert an Order record with shipping information
			// Get the Order ID and save it in session variable.
			$qry = "INSERT INTO OrderData (ShopCartID, ShipName, ShipAddress, ShipCountry, ShipPhone, ShipEMail, BillName, BillAddress, BillCountry, BillPhone, BillEMail, DeliveryDate, DeliveryTime, DeliveryMode, Message, DateOrdered) VALUES ($_SESSION[Cart], '$_SESSION[shipName]', '$_SESSION[shipAddress]', '$_SESSION[shipCountry]', '$_SESSION[shipPhone]', '$_SESSION[shipEmail]', '$_SESSION[billName]', '$_SESSION[billAddress]', '$_SESSION[billCountry]', '$_SESSION[billPhone]', '$_SESSION[billEmail]', DATE_ADD(CURRENT_DATE, INTERVAL $_SESSION[DeliveryDuration] DAY), '$_SESSION[DeliveryTime]', '$_SESSION[DeliveryMode]', '$_SESSION[deliveryMessage]', CURRENT_TIMESTAMP)";
			$conn->query($qry);
			$qry = "SELECT LAST_INSERT_ID() AS OrderID";
			$result = $conn->query($qry);
			$row = $conn->fetch_array($result);
			$_SESSION["OrderID"] = $row["OrderID"];
	        
			$conn->close();
				  
			// Reset the "Number of Items in Cart" session variable to zero.
	  		$_SESSION["NumCartItem"] = 0;
			// Clear the session variable that contains Shopping Cart ID.
			unset($_SESSION["Cart"]);
			// Redirect shopper to the order confirmed page.
			header("Location: orderConfirmed.php");
			exit;
		} 
		else {
		    $MainContent .= "<div style='color:red'><b>GetTransactionDetails failed :  </b>".
			                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
			$MainContent .= "<pre>";
			$MainContent .= print_r($httpParsedResponseAr);
			$MainContent .= "</pre>";
			
			$conn->close();
		}
	}
	else {
		$MainContent .= "<div style='color:red'><b>DoExpressCheckoutPayment failed :  </b>".
		                urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]).'</div>';
		$MainContent .= "<pre>";
		$MainContent .= print_r($httpParsedResponseAr);
		$MainContent .= "</pre>";
	}
}

include("MasterTemplate.php"); 
?>