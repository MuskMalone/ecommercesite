<?php
// Detect the current session
session_start();

// Reading inputs entered in previous page
$email = $_POST["email"];
$pwd = $_POST["password"];

// Validate login credentials with database

// include the utility class file for mysql database access
include("mysql.php");
// create an object for mysql database access
$conn = new Mysql_Driver();
// connect to the myssql database
$conn->connect();

$qry = "SELECT * FROM Shopper WHERE Email = '$email'";
// execute the sql statement
$result = $conn->query($qry);
$row = $conn->fetch_array($result);

$numberOfRows = $conn->num_rows($result);

if (($numberOfRows > 0)){
	$pwdResult = $row["Password"];
	$emailResult = $row["Email"];
	$hashed_pwd = $row["Password"];
	if (($email == $emailResult) && ($pwd == $pwdResult)) {//if (password_verify($pwd, $hashed_pwd) == true){
	// Save user's info in session variables
	$_SESSION["ShopperName"] = $row["Name"];
	$_SESSION["ShopperID"] = $row["ShopperID"];
	
	// Get active shopping cart
	$conn->connect();
	$qry = "select * from shopcart where ShopperID = $_SESSION[ShopperID]
			and OrderPlaced = 0";
	$result = $conn->query($qry);
	if ($conn->num_rows($result)>0){
		$row = $conn->fetch_array($result);
		$_SESSION["Cart"] = $row["ShopCartID"];
	}
	
	// Retrieve cart number information from previous cart
	$qry = "SELECT SUM(Quantity) FROM shopcartitem WHERE ShopCartID = $_SESSION[Cart]";
	$result = $conn->query($qry);
	$row = $conn->fetch_array($result);
	$numofitems = $row["SUM(Quantity)"];
	$_SESSION["NumCartItem"] = $numofitems;
	$conn->close();
		
	// Redirect to home page
	header("Location: index.php");
	exit;
	}
	else {
		$MainContent = "<h3 style='color:red'>Invalid Login Credentials</h3>";
	}
}
else{
	$MainContent = "<h3 style='color:red'>Account does not exist!</h3>";
}
include("MasterTemplate.php");
?>