<?php 
// Detect the current session
session_start();
// Create a container, 60% width of viewport
//$MainContent = "<div style='width:60%; margin:auto;'>";
// Display Page Header - 
// Category's name is read from query string passed from previous page.
$MainContent = "<div style='margin:auto;'>";
// Display Page Header.
$MainContent .= "<div class='row' style='padding:5px'>"; // Start header row
$MainContent .= "<div class='col-12' c 25px'>";
$MainContent .= "<span class='page-title'>$_GET[catName]</span>";
$MainContent .= "</div>";
$MainContent .= "</div>"; // End header row

include("mysql.php");  // Include the class file for database access
$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection

$cid=$_GET["cid"]; // Read Category ID from query string
// Form SQL to retrieve list of products asociated to the Category ID
$qry = "SELECT p.OfferedPrice, p.ProductID, p.ProductTitle, p.ProductImage, p.Price, p.Quantity,p.Offered, p.OfferStartDate,p.OfferEndDate FROM CatProduct cp INNER JOIN product p ON cp.ProductID = p.ProductID WHERE cp.CategoryID = $cid ORDER BY p.ProductTitle ASC";
$result = $conn->query($qry); // Execute the SQL statement

// Display each product in a row
/*while ($row = $conn->fetch_array($result)) {
	//Start a new row
	$MainContent .= "<div class='row' style='padding:5px'>";

	//Left Column - Display a text link showing the product's name, 
	// display the selling price in red in a new paragraph
	$product = "productDetails.php?pid=$row[ProductID]";
	$formattedPrice = number_format($row["Price"], 2);
	$MainContent .= "<div class='col-8'>"; // 67% of row width
	$MainContent .= "<p><a href=$product>$row[ProductTitle]</a></p>";
	$MainContent .= "Price:<span style='font-weight:bold; color;red;'>S$ $formattedPrice</span>";
	$MainContent .= "</div>";

	$img = "./Images/products/$row[ProductImage]";
	$MainContent .= "<div class='col-4'>";
	$MainContent .= "<img src='$img' />";
	$MainContent .= "</div>";
	$MainContent .= "</div>";
	
}*/
$MainContent .= "<div class='product-grid '>"; // 67% of row width
while ($row = $conn->fetch_array($result)) {
	$productn = "productDetails.php?pid=$row[ProductID]";
	$formattedPrice = number_format($row["Price"], 2);
	$img = "./Images/products/$row[ProductImage]";

	//Start a new row
	$MainContent .= "<div class='product'>";
	if($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"] ){
	    
		$MainContent .= "<div class='text-block'>";
		$MainContent .= "<h7>On Offer</h7>";
		$MainContent .= "</div>";
		}
    $MainContent .= "<div class='img_box'>";
	$MainContent .= "<img class='product_img' src='$img' />";
	$MainContent .= "</div>";
	// Left Column - Display a text link showing the product's name, 
	// display the selling price in red in a new paragraph
	$MainContent .= "<div class='SecondL'>"; // 67% of row width
	$MainContent .= "<div class='Info'>";
	$MainContent .= "<span></span><a href=$productn>$row[ProductTitle]</a>";
	$MainContent .= "<br>";
	$formattedPrice = number_format($row["Price"],2);
	$formattedOPrice = number_format($row["OfferedPrice"],2);
	if ($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"]){
		$MainContent .= "<span></span>Price:<span style='text-decoration: line-through;'>S$ $formattedPrice</span><span style='font-weight:bold; color:red;'>  S$ $formattedOPrice</span>";
	}
	else{
		$MainContent .= "<span></span>Price:<span style='font-weight:bold; color;red;'>S$ $formattedPrice</span>";
	}
	$MainContent .= "</div>";
	$MainContent .= "</div>";

	
	
	$MainContent .= "</div>";
	
}
$MainContent .= "</div>";

$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php");
?>
