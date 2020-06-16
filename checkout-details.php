<style>
  .shadedPrevious {
    background-color: rgb(75,75,75,0.5);
    color: white;
    height: 55px;
    width: 55px;
    border-radius: 50%;
    text-align: center;
    line-height: 55px;
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
</style>

<?php 
	session_start();

	// Check if user logged in
	if (!isset($_SESSION["ShopperID"])) {
	  // redirect to login page if the session variable shopperid is not set
		header ("Location: login.php");
		exit;
	}
	
  include("mysql.php");
  
  // Retrieve input from checkout-delivery.php
  if ($_SESSION["FreeDelivery"] == 'Yes') {

    $_SESSION["DeliveryCharge"] = 0;
    $_SESSION["DeliveryMode"] = "Express";
    $_SESSION["DeliveryDuration"] = 1;
  }
  // Else check delivery type
  else {

    if ($_POST['deliverySelect'] == 5) {
      $_SESSION["DeliveryCharge"] = 5;
      $_SESSION["DeliveryMode"] = "Normal";
      $_SESSION["DeliveryDuration"] = 2;
  	}
    else {
      $_SESSION["DeliveryCharge"] = 10;
      $_SESSION["DeliveryMode"] = "Express";
      $_SESSION["DeliveryDuration"] = 1;
    }

  }

  $_SESSION["DeliveryTime"] = $_POST['dropdownDelivery'];


	$MainContent = "
  <h1 style='text-align:center;'>Checkout</h1>
  <div style='display:flex;flex-direction:row;justify-content:center;margin-top:10px;margin-bottom:80px;'>
    <div>
      <div class='shadedPrevious'>1</div>
      <div class='breadcrumbText' style='color:rgb(75,75,75,0.5);margin-left:3px;'>Delivery</div>
    </div>
    <div class='shadedPrevious rectangle'></div>
    <div>
      <div class='shaded'>2</div>
      <div class='breadcrumbText' style='margin-left:-16px;'>Customer Info</br>and Payment</div>
    </div>
    <div class='rectangle'></div>
    <div>
      <div class='circle'>3</div>
      <div class='breadcrumbText' style='color:rgb(150,150,150,0.5);margin-left:-3px;'>Checkout</br>Complete</div>
    </div>
  </div>

	<form style='' name='details' action='process.php' method='post' onsubmit='return validateForm()'>
		<h3 style='margin-bottom:20px;'>Deliver this to...</h3>
		<div class='form-group row'>
    	<label for='txtName' class='col-md-2 offset-2 col-form-label'>Name</label>
   		<div class='col-md-6'>
      	<input type='text' class='form-control' name='txtName' id='txtName' placeholder='Name' onchange='fillForm()' required>
    	</div>
  	</div>
  	<div class='form-group row'>
    	<label for='txtEmail' class='col-md-2 offset-2 col-form-label'>Email</label>
   		<div class='col-md-6'>
    		<input type='email' class='form-control' name='txtEmail' id='txtEmail' placeholder='Email' onchange='fillForm()' required>
    	</div>
  	</div>
  	<div class='form-group row'>
    	<label for='txtPhone' class='col-md-2 offset-2 col-form-label'>Phone No.</label>
    	<div class='col-md-1'>
   			<input type='text' class='form-control' value='+65' disabled>
    	</div>
   		<div class='col-md-2'>
     			<input type='text' maxlength='8' class='form-control' name='txtPhone' id='txtPhone' placeholder='Phone No.' onchange='fillForm()' required>
    	</div>
  	</div>
  	<div class='form-group row'>
    	<label for='txtCountry' class='col-md-2 offset-2 col-form-label'>Country</label>
   		<div class='col-md-3'>
    		<input type='text' class='form-control' name='txtCountry' id='txtCountry' value='Singapore' readOnly>
    	</div>
  	</div>
  	<div class='form-group row'>
    	<label for='txtAddress' class='col-md-2 offset-2 col-form-label'>Address</label>
   		<div class='col-md-6'>
    		<input type='text' class='form-control' name='txtAddress' id='txtAddress' placeholder='Address' onchange='fillForm()' required>
    	</div>
  	</div>
  	<div class='form-group row'>
    	<label for='txtMessage' class='col-md-2 offset-2 col-form-label'>Delivery Message (max. 255 characters)</label>
   		<div class='col-md-6'>
    		<input type='text' maxlength='255' class='form-control' name='txtMessage' id='txtMessage' placeholder='Message'>
        <p>(Leave blank if not required)</p>
    	</div>
  	</div>
  	</br><hr>

  	<h3>Bill to...</h3>
      <input id='chkBilling' type='checkbox' onchange='fillForm()'>
      <label style='margin-bottom:20px;' for='chkBilling'>Bill to the same address as delivery</label>
  		<div class='form-group row'>
    		<label for='txtBillName' class='col-md-2 offset-2 col-form-label'>Name</label>
   			<div class='col-md-6'>
      			<input type='text' class='form-control' name='txtBillName' id='txtBillName' placeholder='Name' required>
    		</div>
  		</div>
  		<div class='form-group row'>
    		<label for='txtBillEmail' class='col-md-2 offset-2 col-form-label'>Email</label>
   			<div class='col-md-6'>
      			<input type='email' class='form-control' name='txtBillEmail' id='txtBillEmail' placeholder='Email' required>
    		</div>
  		</div>
  		<div class='form-group row'>
    		<label for='txtBillPhone' class='col-md-2 offset-2 col-form-label'>Phone No.</label>
    		<div class='col-md-1'>
   				<input type='text' class='form-control' value='+65' disabled>
    		</div>
   			<div class='col-md-2'>
      			<input type='text' maxlength='8' class='form-control'name='txtBillPhone' id='txtBillPhone' placeholder='Phone No.' required>
    		</div>
  		</div>
  		<div class='form-group row'>
    		<label for='txtBillCountry' class='col-md-2 offset-2 col-form-label'>Country</label>
   			<div class='col-md-3'>
          <input type='text' class='form-control' name='txtBillCountry' id='txtBillCountry' value='Singapore' readOnly>
        </div>
  		</div>
  		<div class='form-group row'>
    		<label for='txtBillAddress' class='col-md-2 offset-2 col-form-label'>Address</label>
   			<div class='col-md-6'>
      			<input type='text' class='form-control' name='txtBillAddress' id='txtBillAddress' placeholder='Address' required>
    		</div>
  		</div>
      </br>
      <a style='float:left;' name='btnBack' href='checkout-delivery.php' class='btn btn-secondary'>Back</a>
      <input type='image' style='float:right;' src='https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif'>
  		<!---<button style='float: right;' type='submit' class='btn btn-secondary'>Submit</button>-->
	</form>";
?>	

<?php include("MasterTemplate.php"); ?>

<script type="text/javascript">

  function fillForm() {

    if (document.getElementById('chkBilling').checked) {
      // Disable input and autofill values
      document.getElementById('txtBillName').value = document.getElementById('txtName').value;
      document.getElementById('txtBillEmail').value = document.getElementById('txtEmail').value;
      document.getElementById('txtBillPhone').value = document.getElementById('txtPhone').value;
      document.getElementById('txtBillAddress').value = document.getElementById('txtAddress').value;

      document.getElementById('txtBillName').readOnly = true;
      document.getElementById('txtBillEmail').readOnly = true;
      document.getElementById('txtBillPhone').readOnly = true;
      document.getElementById('txtBillAddress').readOnly = true;
    }
    else {
      // Enable input and clear values
      document.getElementById('txtBillName').value = '';
      document.getElementById('txtBillEmail').value = '';
      document.getElementById('txtBillPhone').value = '';
      document.getElementById('txtBillAddress').value = '';
      document.getElementById('txtBillName').readOnly = false;
      document.getElementById('txtBillEmail').readOnly = false;
      document.getElementById('txtBillPhone').readOnly = false;
      document.getElementById('txtBillAddress').readOnly = false;
    }
  }

  function validateForm() {
    var str = document.details.txtPhone.value;
    var str2 = document.details.txtBillPhone.value;
    var isNum = /^\d+$/.test(str);
    var isNum2 = /^\d+$/.test(str2);

    // Check that input has 8 digits, is numerical, and starts with 6, 8 or 9
    if (str.length != 8 || str2.length != 8) {
      alert("Please enter an 8-digit phone number.");
      return false; // Cancel submission
    }
    else if (!isNum || !isNum2) {
      alert("Phone number should be numeric.");
      return false; // Cancel submission
    } 
    else if ((str.substr(0,1) != "6"&& str.substr(0,1) != "8" && str.substr(0,1) != "9") ||
            (str2.substr(0,1) != "6" && str2.substr(0,1) != "8" && str2.substr(0,1) != "9")) {
      alert("Phone number in Singapore should start with 6, 8 or 9.");
      return false; // Cancel Submission
    }

    return true;  // No error found
  }

</script>