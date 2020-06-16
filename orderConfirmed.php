<style>
  .shadedPrevious {
    background-color: rgb(75,75,75,0.5);
    color: white;
    height:55px;
    width:55px;
    border-radius: 50%;
    text-align:center;
    line-height:55px;
  }

	.shaded {
    background-color: rgb(75,75,75,1);
    color: white;
    height:55px;
    width:55px;
    border-radius: 50%;
    text-align:center;
    line-height:55px;
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
// Detect the current session
session_start();

// Check if user logged in
if (!isset($_SESSION["ShopperID"])) {
  // redirect to login page if the session variable shopperid is not set
  header ("Location: login.php");
  exit;
}

if(isset($_SESSION["OrderID"])) {	
	$MainContent = "
	<h1 style='text-align:center;'>Checkout</h1>
  	<div style='display:flex;flex-direction:row;justify-content:center;margin-top:10px;margin-bottom:80px;'>
    	<div>
      		<div class='shadedPrevious'>1</div>
      		<div class='breadcrumbText' style='color:rgb(75,75,75,0.5);margin-left:3px;'>Delivery</div>
   		</div>
    	<div class='shadedPrevious rectangle'></div>
    	<div>
      		<div class='shadedPrevious'>2</div>
      		<div class='breadcrumbText' style='margin-left:-16px;'>Customer Info</br>and Payment</div>
    	</div>
    	<div class='shadedPrevious rectangle'></div>
    	<div>
      		<div class='shaded'>3</div>
      		<div class='breadcrumbText' style='margin-left:-3px;'>Checkout</br>Complete</div>
    	</div>
  	</div>
	<p>
    Checkout successful. Your order number ID is <b>$_SESSION[OrderID]</b>.</br>
    Do note this down for reference in future.
  </p>";
  unset($_SESSION["DeliveryTime"]);
	$MainContent .= "<p>Thank you for your purchase.&nbsp;&nbsp;";
	$MainContent .= '<a href="index.php">Continue shopping</a></p>';
}

include("MasterTemplate.php"); 
?>