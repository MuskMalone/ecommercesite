<style>
	.form-control option {
		border:2px solid black; // Override default box border
	}

	.shaded {
		background-color: rgb(75,75,75,1);
		color: white;
		height: 55px;
    	width: 55px;
    	border-radius: 50%;
    	text-align: center;
    	line-height: 55px;
	}

	.circle {
    	height: 55px;
    	width: 55px;
    	border-radius: 50%;
    	border: 2px solid rgb(150,150,150,0.5);
    	color: rgb(150,150,150,0.5);
    	text-align: center;
    	line-height: 51px;
  	}
  
  	.rectangle {
    	width: 150px;
    	height: 2px;
    	border: 2px solid rgb(150,150,150,0.5);
    	border-radius: 25px;
    	margin-left: 20px;
    	margin-right: 20px;
    	margin-top: 26.5px;
  	}

  	.breadcrumbText {
  		font-size: 14px;
  		margin-top: 10px;
  		position: absolute;
  		text-align: center;
  	}

	.delivery-row div {
		//border: 1px solid black;
		width: 50%;
		word-wrap: break-word;
	}
	.deliveryText {
		//border: 1px solid black;		
	}
</style>

<?php 
	session_start();

	// Check if user logged in
	if (!isset($_SESSION["ShopperID"])) {
		// redirect to login page if the session variable shopperid is not set
		header ("Location: login.php");
		exit;
	}
	
	$MainContent = "";
	include("mysql.php");
	$conn = new Mysql_Driver();
	// Check to ensure each product item saved in the associative array is not out of stock
	$conn->connect();
	foreach($_SESSION['Items'] as $key=>$item) {
		$qry = "SELECT Quantity FROM Product WHERE ProductID = $item[productId]";
		$result = $conn->query($qry);
		$row = $conn->fetch_array($result);

		if ($item["quantity"] > $row["Quantity"]) {
			$MainContent .= "There is only $row[Quantity] of <b>$item[name]</b> in stock!<br />";
			$MainContent .= "Please return to <a href='shoppingCart.php'>shopping cart</a> to amend your purchase.<br />";
			include("MasterTemplate.php");
			exit;
		}
	}

	//Retrieve current GST rate from database
	$qry = "SELECT Max(TaxRate) as TaxRate FROM gst WHERE CURRENT_DATE > EffectiveDate";
	$result = $conn->query($qry);

	while ($row = $conn->fetch_array($result)) {
		$currentGSTRate = ($row["TaxRate"]);
	}

	$_SESSION["TaxAmount"] = round($_SESSION["SubTotal"]/100 * $currentGSTRate, 2);	

	if ($_SESSION["SubTotal"] > 200) {
		$_SESSION["FreeDelivery"] = 'Yes';
	}
	else {
		$_SESSION["FreeDelivery"] = 'No';
	}

	$MainContent = "
		<h1 style='text-align:center;'>Checkout</h1>
		<div style='display:flex;flex-direction:row;justify-content:center;margin-top:10px;margin-bottom:80px;'>
			<div>
  				<div class='shaded'>1</div>
  				<div class='breadcrumbText' style='margin-left:3px;'>Delivery</div>
  			</div>
  			<div class='rectangle'></div>
  			<div>
  				<div class='circle'>2</div>
  				<div class='breadcrumbText' style='color:rgb(150,150,150,0.5);margin-left:-16px;'>Customer Info</br>and Payment</div>
  			</div>
  			<div class='rectangle'></div>
  			<div>
  				<div class='circle'>3</div>
  				<div class='breadcrumbText' style='color:rgb(150,150,150,0.5);margin-left:-3px;'>Checkout</br>Complete</div>
  			</div>
		</div>

		<div class='' style='padding:0px;margin-bottom:60px;'>
			<form style='border:1px solid black;margin-top:20px;margin-bottom:0px;padding:10px;' name='delivery' action='checkout-details.php' method='post'>
				<h3>Delivery Options*</h3>
				<div class='row'>
					<div class='col-md-1'></div>
					<div class='col-md-3'>
						<b>Type</b>
					</div>
					<div class='col-md'>
						<b>Duration</b>
					</div>
					<div class='col-md-3'>
						<b>Price</b>
					</div>
				</div>

				<div class='row'>
					<div class='col-md-1'>
						<input id='radioNormal' name='deliverySelect' onchange='calculateDeliveryCost()' style='margin-top:5px;margin-left:15px;' type='radio' value='5'></input>
					</div>
					<div class='col-md-3'>Normal Delivery</div>
					<div class='col-md'>Receive within 2 working days</div>
					<div class='col-md-3'>$5</div>
				</div>

				<div style='margin-bottom:10px;' class='row'>
					<div class='col-md-1'>
						<input id='radioExpress' name='deliverySelect' onchange='calculateDeliveryCost()' style='margin-top:5px;margin-left:15px;' type='radio' value='10'></input>
					</div>
					<div class='col-md-3'>Express Delivery</div>
					<div class='col-md'>Receive within 24 hours</div>
					<div class='col-md-3'>$10</div>
				</div>
				<caption><i style='font-size:14px;'>*Delivery available within Singapore ONLY</i></caption>

				<div style='margin-top:40px;' class='row'>
					<div class='col-md-6' style='heght:100%;padding-top:20px;'>
						<div class='col-md-8 form-group'>
   							<label for='dropdownDelivery'>Preferred Time of Delivery</label>
    						<select class='form-control' name='dropdownDelivery'>
      							<option value='NULL'>None</option>
      							<option value='9 am – 12 noon'>9 am – 12 noon</option>
      							<option value='12 noon – 3 pm'>12 noon – 3 pm</option>
      							<option value='3 pm – 6 pm'>3 pm – 6 pm</option>
    						</select>
  						</div>
					</div>
					<div class='col-md-6' style='margin-top:20px;'>
						<div class='row delivery-row'>
							<div>Shopping Cart Total:</div>
							<div id='txtCartTotal' style='text-align: center;'></div>
						</div>
						<div class='row delivery-row'>
							<div>Goods and Services Tax ($currentGSTRate%):</div>
							<div id='txtGst' style='text-align: center;'></div>
						</div>
						<div class='row delivery-row'>
							<div>Delivery Charge:</div>
							<div id='txtDelivery' style='text-align: center;'>5</div>
						</div>
						
						<div class='row delivery-row'>
							<b><div id='specialMessage' style='font-size: 70%; color: red;'></div></b>
						</div>

						</br>
						<div class='row delivery-row'>
							<div><b>Total Amount Payable:</b></div>
							<div id='txtTotalCost' style='text-align: center;'></div>
						</div>
						<div style='margin-bottom:5px;' class='row delivery-row'>
							<div><b>Discount Applied:</b></div>
							<div id='txtDiscount' style='text-align: center;'></div>
						</div>
					</div>
				</div>

				<button style='position: absolute;bottom:0px;right: 15px;' name='btnNext' type='submit' class='btn btn-secondary'>Next</button>
			</form>
			
		</div>";
	?>

<?php include("MasterTemplate.php"); ?>

<script type="text/javascript">

	var restoreOptions = <?php if(isset($_SESSION["DeliveryTime"])){echo "true";}else{echo "false";} ?>;

	// If user entered input before, restore previous input
	if (restoreOptions == true)
	{	
		// Skip if delivery fee is waivered
		if (<?php echo $_SESSION["SubTotal"] ?> < 200)
		{
			var sessionDeliveryCharge = <?php if(isset($_SESSION["DeliveryCharge"])){echo $_SESSION["DeliveryCharge"];}else{echo "false";} ?>;
			if (sessionDeliveryCharge == 5)
			{
				document.delivery.radioNormal.checked = 'check';
			}
			else
			{
				document.delivery.radioExpress.checked = 'check';
			}
		}

		var sessionDeliveryTime = <?php if(isset($_SESSION["DeliveryTime"])){echo "'".$_SESSION["DeliveryTime"]."'";}else{echo "false";} ?>;
		switch (sessionDeliveryTime)
		{
			case '9 am – 12 noon':
				document.delivery.dropdownDelivery.selectedIndex = "1";
				break;
			case '12 noon – 3 pm':
				document.delivery.dropdownDelivery.selectedIndex = "2";
				break;
			case '3 pm – 6 pm':
				document.delivery.dropdownDelivery.selectedIndex = "3";
				break;
			default:
				document.delivery.dropdownDelivery.selectedIndex = "0";
		}
	}
	// Else check Normal Delivery by default
	else
	{
		document.delivery.radioNormal.checked = 'check';
	}

	// Calculate costs and update fields
	document.getElementById('txtCartTotal').innerHTML = <?php echo $_SESSION["SubTotal"] ?>;
	document.getElementById('txtDiscount').innerHTML = "<b>- $" + <?php echo $_SESSION["Discount"]; ?> + "</b>";
	calculateDeliveryCost();

	function calculateDeliveryCost() {
		
		// Check if subtotal is more than 200, if yes make delivery free
		if (<?php echo $_SESSION["SubTotal"] ?> > 200)
		{
			document.delivery.radioExpress.checked = 'check';
			document.delivery.radioNormal.checked = '';
			document.delivery.radioNormal.disabled = true;
			document.delivery.radioExpress.disabled = true;
			document.getElementById('txtDelivery').innerHTML = 'FREE EXPRESS';
			document.getElementById('specialMessage').innerHTML = '>> Enjoy delivery on us when you spend more than $200!';
			calculateTotal();
		}
		
		else
		{
			document.getElementById('txtDelivery').innerHTML = document.delivery.deliverySelect.value;
			calculateTotal();
		}
	}


	function calculateTotal() {
		
		//Check if subtotal is more than 200, if yes make delivery free
		if (<?php echo $_SESSION["SubTotal"] ?> > 200)
		{
			cartTotal = parseFloat(document.getElementById('txtCartTotal').textContent);
			calculatedGst = cartTotal/100 * <?php echo $currentGSTRate ?>;
			calculatedTotal = cartTotal + calculatedGst;
			document.getElementById('txtGst').innerHTML = Math.round(calculatedGst * 100) / 100;
			document.getElementById('txtTotalCost').innerHTML = "<b>$" + (Math.round(calculatedTotal * 100) / 100) + "</b>";
		}
		
		else
		{
			cartTotal = parseFloat(document.getElementById('txtCartTotal').textContent);
			calculatedGst = cartTotal/100 * <?php echo $currentGSTRate ?>;
			calculatedTotal = cartTotal + calculatedGst + parseFloat(document.getElementById('txtDelivery').textContent);
			document.getElementById('txtGst').innerHTML = Math.round(calculatedGst * 100) / 100;
			document.getElementById('txtTotalCost').innerHTML = "<b>$" + (Math.round(calculatedTotal * 100) / 100) + "</b>";
		}
	}

</script>