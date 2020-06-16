<?php 
session_start();

// Check if user logged in 
if (! isset($_SESSION["ShopperID"])) {
	// redirect to login page if the session variable shopperid is not set
	header ("Location: login.php");
	exit;
}

include("mysql.php");
$conn = new Mysql_Driver();

if (isset($_POST['actionA']))
{
	// Write code to implement: if a user clicks on "Add to Cart" button, insert/update the 
	// database and also the session variable for counting number of items in shopping cart.
	$conn->connect();
	// Check if a shopping cart exist, if not create a new shopping cart
	if (! isset($_SESSION["Cart"]))
	{
		$qry = "INSERT INTO shopcart (ShopperID) VALUE($_SESSION[ShopperID])"; // Associate ShopperID from session to shopcart
		$conn->query($qry);
		$qry = "SELECT LAST_INSERT_ID() AS ShopCartID";
		$result = $conn->query($qry);
		$row = $conn->fetch_array($result);
		$_SESSION["Cart"] = $row["ShopCartID"];
	}
  	// If the ProductID exists in the shopping cart, 
  	// update the quantity, else add the item to the Shopping Cart.
  	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	$cartid = $_SESSION["Cart"];
	$qry = "SELECT * from shopcartitem WHERE ShopCartID = $cartid AND ProductID = $pid";
	$result = $conn->query($qry);
	$addNewItem = 0;
	if ($conn->num_rows($result) > 0)
	{
		$qry = "SELECT * FROM product WHERE ProductID=$pid";
		$result = $conn->query($qry);
		if ($conn->num_rows($result) > 0)
		{
			$row = $conn->fetch_array($result);
			if ($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"]){
				$price = $row["OfferedPrice"];
				$Oprice = $row["Price"];
				$Disc = $_SESSION["Discount"];
				$_SESSION["Discount"] = $Disc += ($quantity*($Oprice-$price));
				echo $_SESSION["Discount"] ;
			}
			else{
				$price = $row["Price"];
			}
			$qry = "UPDATE shopcartitem SET Quantity = Quantity + $quantity
				WHERE ShopCartID = $cartid AND ProductID = $pid";
		}
		
		$conn->query($qry);
	}
	else
	{
		$qry = "SELECT * FROM product WHERE ProductID=$pid";
		$result = $conn->query($qry);
		if ($conn->num_rows($result) > 0)
		{
			$row = $conn->fetch_array($result);
			$productname = $row["ProductTitle"];
			if ($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"]){
				$price = $row["OfferedPrice"];
				$Oprice = $row["Price"];
				$Disc = $_SESSION["Discount"];
				$_SESSION["Discount"] = $Disc += ($quantity*($Oprice-$price));
				echo $_SESSION["Discount"] ;
			}
			else{
				$price = $row["Price"];
			}
			
			$qry = "INSERT INTO shopcartitem(ShopCartID, ProductID, Price, Name, Quantity) VALUES ($cartid, $pid, $price, '$productname', $quantity)";
			$conn->query($qry);
			$addNewItem = 1;
		}
	}
  	$conn->close();
	/*
  	// Update session variable used for counting number of items in the shopping cart.
	$conn->connect();
	$qry = "SELECT SUM(Quantity) FROM shopcartitem WHERE ShopCartID = $cartid";
	$result = $conn->query($qry);
	$row = $conn->fetch_array($result);
	$numofitems = $row["SUM(Quantity)"];
	$_SESSION["NumCartItem"] = $numofitems;
	$conn->close();
	*/
	
	// Redirect shopper to shopping cart page
	header ("Location: shoppingcart.php");
	exit;
}

if (isset($_POST['actionU']))
{	
	$cartid = $_SESSION["Cart"];
	$pid = $_POST["product_id"];
	$quantity = $_POST["quantity"];
	
	$conn->connect();
	$qry = "SELECT quantity FROM shopcartitem WHERE ProductID=$pid AND ShopCartID=$cartid limit 1";
	$result = $conn->query($qry);
	$row = $conn->fetch_array($result);
	$originalQuantity = $row["quantity"];
	$conn->close();
	
	$conn->connect();
	$qry = "SELECT * FROM product WHERE ProductID=$pid";
	$result = $conn->query($qry);
	if ($conn->num_rows($result) > 0)
	{
			$row = $conn->fetch_array($result);
			if ($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"]){
				$price = $row["OfferedPrice"];
				$Oprice = $row["Price"];
				$Disc = $_SESSION["Discount"];
				
				$Disc = $quantity*($Oprice-$price) - $originalQuantity*($Oprice-$price);
				$_SESSION["Discount"] = $_SESSION["Discount"] + $Disc;
				//echo $_SESSION["Discount"] ;
			}
		
			else{
				$price = $row["Price"];
			}
	}
	$conn->close();
	
	$conn->connect();
	$qry = "UPDATE shopcartitem SET Quantity=$quantity
			WHERE ProductID=$pid AND ShopCartID=$cartid";
	$conn->query($qry);
	$conn->close();
	
	/*
	$conn->connect();
	$qry = "SELECT SUM(Quantity) FROM shopcartitem WHERE ShopCartID = $cartid";
	$result = $conn->query($qry);
	$row = $conn->fetch_array($result);
	$numofitems = $row["SUM(Quantity)"];
	$_SESSION["NumCartItem"] = $numofitems;
	$conn->close();
	*/
	
	header("Location: shoppingcart.php");
	exit;
}

if (isset($_POST['actionR']))
{
	$cartid = $_SESSION["Cart"];
	$pid = $_POST["product_id"];
	
	$conn->connect();
	$qry = "SELECT quantity FROM shopcartitem WHERE ProductID=$pid AND ShopCartID=$cartid limit 1";
	$result = $conn->query($qry);
	$row = $conn->fetch_array($result);
	$originalQuantity = $row["quantity"];
	$conn->close();
	
	$conn->connect();
	$qry = "SELECT * FROM product WHERE ProductID=$pid";
	$result = $conn->query($qry);
	if ($conn->num_rows($result) > 0)
	{
			$row = $conn->fetch_array($result);
			if ($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"]){
				$price = $row["OfferedPrice"];
				$Oprice = $row["Price"];
				$Disc = $originalQuantity*($Oprice-$price);
				$_SESSION["Discount"] = $_SESSION["Discount"] - $Disc;
				//echo $_SESSION["Discount"] ;
			}
		
			else{
				$price = $row["Price"];
			}
	}
	$conn->close();	
	
	$conn->connect();
	$qry = "DELETE FROM shopcartitem WHERE ProductID=$pid AND ShopCartID=$cartid";
	$conn->query($qry);
	$conn->close();
	
	header("Location: shoppingcart.php");
	exit;
}		
?>

