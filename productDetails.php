<?php 
session_start(); // Detect the current session
// Create a container, 90% width of viewport
$MainContent = "<div style='width:90%; margin:auto;'>";

include("mysql.php");  // Include the class file for database access
$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection
$pid=$_GET["pid"]; // Read Product ID from query string
$qry = "SELECT * from product where ProductID=$pid" ;
$result = $conn->query($qry); // Execute the SQL statement

$MainContent .="<div class='col-sm-12' style='padding:5px'>";

while ($row = $conn->fetch_array($result))
{
	$MainContent .="<div class='row'>";
	$img = "./Images/products/$row[ProductImage]";
	$formattedPrice = number_format($row["Price"],2);
	$MainContent .="<div class='col-sm-5' style=' padding:5px;float:left;'>";
	$MainContent .="<p><img src=$img /></p>";
	$MainContent .= "</div>";
	$MainContent .="<div class ='col-sm-7' style='padding:5px;float:left;'>";
	$MainContent .="<div class='row'>";
	$MainContent .="<span class='page-title'>$row[ProductTitle]</span><br/>";
	$MainContent .= "</div>";	
	$formattedPrice = number_format($row["Price"],2);
	$formattedOPrice = number_format($row["OfferedPrice"],2);
	$MainContent .="<div class='row'>";
	if($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"]){
		$MainContent .= "<h5>Price:<span style='text-decoration: line-through;'>S$ $formattedPrice</span><span style='font-weight:bold; color:red;'>  S$ $formattedOPrice</span></h5>";
	}
	else{
		$MainContent .= "<h5>Price:<span style='font-weight:bold;'> S$ $formattedPrice</span></h5>";
	}
	$MainContent .= "</div>";
	$MainContent .="<div class='row'>";
	$MainContent .="<div class='AddToCart'>";
	if($row["Quantity"]>0){
		$MainContent .= "<form action='cart-functions.php' method='post'>";
		$MainContent .="<input type='hidden' name='actionA' value='add' />";
		$MainContent .="<input type='hidden' name='product_id' value='$pid' />";
		$MainContent .="<h6>Quantity: <input type='number' name='quantity' style='width:40px' value='1' min='1' max='10' required/>";
		$MainContent .="<button type='submit'> Add to Cart</button></h6>";
		$MainContent .="</form>";
		}
	else{
		$MainContent .= "<h5><span style='font-weight:bold; color:red;'>Out of Stock</span></h5>";
	}
	$MainContent .= "</div>";
	$MainContent .= "</div>";
	$MainContent .="</div>";
	$MainContent .= "</div>";
	$MainContent .="<div class='row'>";
	$MainContent .="<h4>Description</h4>"."<br/>";
	$MainContent .="</div>";
	$MainContent .="<div class='row'>";	
	$MainContent .="<p>$row[ProductDesc]</p>";
	$MainContent .="</div>";
	$MainContent .="<div class='row'>";
	$qry = "SELECT s.SpecName, ps.SpecVal FROM productspec ps INNER JOIN specification s ON ps.SpecID = s.SpecID WHERE ps.ProductID =$pid ORDER BY ps.priority";
	$result2 = $conn->query($qry);
	$MainContent .="<h6><table class='Spectable'style='width:100%'>"; 
	while ($row2 = $conn->fetch_array($result2)) {
	$MainContent .= "<tr><td>".$row2["SpecName"].":</td><td> ".$row2["SpecVal"]."</td></tr>";
		}
	$MainContent .="</table></h6></div>";






}
// Display Product information. Starting ....
$conn->close(); // Close database connnection
$MainContent .="</div>";
include("MasterTemplate.php");  
?>
