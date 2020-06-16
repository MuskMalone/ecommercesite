<script type="text/javascript">
function validateCart()
{
	if (document.updateCart.quantity.value != "")
	{
		var str = document.updateCart.quantity.value;

		// Check that user does not enter a value lower than 0
		if (str < 0)
		{
			alert("You cannot enter a value lower than 0!");
			return false;
		}
		// Check that user does not enter a value higher than 99
		else if (str.length != 2 && str.length != 1)
		{
			alert("You cannot purchase more than 99 items at a time!");
			return false;
		}
		
		else if (str = 0)
		{
			return false;
		}
    }
	//document.updateCart.submit();
	//this.form.submit()
	return true;
}
</script>

<?php 
// Detect the current session
session_start();
// Check if user logged in 
if (! isset($_SESSION["ShopperID"])) 
{
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

// Include the class file for database access
include("mysql.php");
$conn = new Mysql_Driver();
$conn->connect();

if (isset($_SESSION["Cart"])) 
{
	// Update Cart Num of Items
	$cartid = $_SESSION["Cart"];
	$qry = "SELECT SUM(Quantity) FROM shopcartitem WHERE ShopCartID = $cartid";
	$result = $conn->query($qry);
	$row = $conn->fetch_array($result);
	$numofitems = $row["SUM(Quantity)"];
	$_SESSION["NumCartItem"] = $numofitems;
	
	// Retrieve from database and display shopping cart in a table
	$qry = "SELECT ProductID, Name, Price, Quantity, (Price*Quantity) AS Total
			FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
	$result = $conn->query($qry);
	
	if ($conn->num_rows($result) > 0) 
	{
		$MainContent = "<p class='page-title' style='text-align:center'>Shopping Cart</p>";
	
		if (isset($_SESSION["NumCartItem"]))
		{
			if ($_SESSION["NumCartItem"] > 0) // Added as UI change
			{
				$MainContent .= "<br /> <b>You have $_SESSION[NumCartItem] item(s) in Cart</b> <br /><br />";
			}
		}
		
		//echo $_SESSION["Discount"] ;
		$MainContent .= "<div class='table-responsive'>";
		$MainContent .= "<table class='table table-hover'>";
		$MainContent .= "<thead class='cart-header' style='background-color: #7f9c90'>";
		$MainContent .= "<tr style='border-style: solid; border-width: 5px; border-color: #7f9c90'>";
		$MainContent .= "<th style='width:40px; height:80'>ID</th>";
		$MainContent .= "<th style='width:250px; height:80'>Name</th>";
		$MainContent .= "<th style='width:90px; height:80'>Price (S$)</th>";
		$MainContent .= "<th style='width:60px; height:80'>Quantity</th>";
		$MainContent .= "<th style='width:120px; height:80'>Total (S$)</th>";
		$MainContent .= "<th>&nbsp;</th>";
		$MainContent .= "<th>&nbsp;</th>";
		$MainContent .= "</tr>";
		$MainContent .= "</thead>";
		
		// Declare an array to store the shopping cart items in session variable 
		$_SESSION["Items"]=array();
			
		// Display the shopping cart content
		$MainContent .= "<tbody>";
		
		while($row = $conn->fetch_array($result))
		{
			$MainContent .= "<tr style='border-style: solid; border-width: 5px; border-color: #7f9c90'>";
			$MainContent .= "<td height='80' >$row[ProductID]</td>";
			$MainContent .= "<td height='80'>$row[Name]</td>";
			$formattedPrice = number_format($row["Price"], 2);
			$MainContent .= "<td height='80'>$formattedPrice</td>";
			
			$MainContent .= "<form name='updateCart' id='cartUpdate' action='cart-functions.php' method='post'>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='number' name='quantity' style='width:40px' value='$row[Quantity]' min='1' max='10' required/>";
			//$MainContent .= "<input type='number' name='quantity' style='width:40px' value='$row[Quantity]' onchange='this.form.submit()' />";
			$MainContent .= "</td>";
			$formattedTotal = number_format($row["Total"], 2);
			$MainContent .= "<td>$formattedTotal</td>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='hidden' name='actionU' value='update'/>";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]'/>";
			//$MainContent .= "<button type='submit' onclick='return validateCart()' id='myBtn'>Update</button>";
			$MainContent .= "<input type='image' src='https://img.icons8.com/windows/32/000000/refresh.png'>";
			$MainContent .= "</td>";
			$MainContent .= "</form>";
			
			$MainContent .= "<form name='removeCart' action='cart-functions.php' method='post'>";
			$MainContent .= "<td>";
			$MainContent .= "<input type='hidden' name='actionR' value='remove' />";
			$MainContent .= "<input type='hidden' name='product_id' value='$row[ProductID]' />";
			//$MainContent .= "<input type='hidden' name='quantity' value='$row[Quantity]' />";
			$MainContent .= "<input type='image' style='float:right' src='https://img.icons8.com/windows/32/000000/cancel.png'>";
			// $MainContent .= "<button type='submit'>Remove</button>";
			$MainContent .="</td>";
			$MainContent .= "</form>";
			$MainContent .= "</tr>";

			// Store the shopping cart items in session variable as an associate array
		
			$_SESSION["Items"][] = array("productId"=>$row["ProductID"],
									"name"=>$row["Name"],
									"price"=>$row["Price"],
									"quantity"=>$row["Quantity"]);
		}
		
		$MainContent .= "</tbody>";
		$MainContent .= "</table>";
		$MainContent .= "</div>";

		// Display the subtotal at the end of the shopping cart
		$qry = "SELECT SUM(Price*Quantity) as SubTotal FROM shopcartitem WHERE ShopCartID=$_SESSION[Cart]";
		$result = $conn->query($qry);
		$row = $conn->fetch_array($result);
		$MainContent .= "<p style='text-align:right'>";
		$MainContent .= "Cart Subtotal: S$".number_format($row["SubTotal"], 2);
		$_SESSION["SubTotal"] = round($row["SubTotal"],2);
		// Add Checkout button on the shopping cart page
		$MainContent .= "<form method='post' action='checkout-delivery.php'>";
		$MainContent .= "<input class='btn btn-secondary' style='float:right' type='submit' value='Proceed to Checkout'>";
		$MainContent .= "</form></p>";
		
	}
	else {
		$MainContent = "<span style='font-weight:bold; color:red;'>
		                 Empty shopping cart!</span>";
	}
}
else {
	$MainContent = "<span style='font-weight:bold; color:red;'>
	                 Empty shopping cart!</span>";
}

$conn->close();

include("MasterTemplate.php"); 
?>
