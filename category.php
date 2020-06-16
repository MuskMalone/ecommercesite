<?php 
// Detect the current session
session_start();
// Create a container, 60% width of viewport
$MainContent = "<div style='margin:auto;'>";
// Display Page Header.
$MainContent .= "<div class='row' style='padding:5px'>"; // Start header row
$MainContent .= "<div class='col-12' c 25px'>";
$MainContent .= "<span class='page-title'>Categories</span>";
$MainContent .= "</div>";
$MainContent .= "</div>"; // End header row

include("mysql.php");  // Include the class file for database access
$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection

$qry = "SELECT * FROM Category ORDER BY CatName ASC"; // Form SQL to select all categories
$result = $conn->query($qry); // Execute the SQL statement
//$MainContent .="<section id='pattern' class='pattern'>";
//$MainContent .="<ul class='grid'>";
$MainContent .="<div>"; //class='grid-container'
$count = 0;
while ($row = $conn->fetch_array($result)) {
	$catname = urlencode($row["CatName"]);
	$catproduct = "ProductListing.php?cid=$row[CategoryID]&catName=$catname";
	$img = "./Images/category/$row[CatImage]";

	if ($count == 0){
		$MainContent .="<div class='Cat-Row'>";
	}		
	$MainContent .="<div class='cont'>";
	//$MainContent .="<div class='content'></div>";	
	$MainContent .= "<img src=$img class='catimg' href=$catproduct/>";		
	//$MainContent .="<div class='Col-half' style='background: url($img); background-color: #92a8d1; background-size:contain; background-repeat:no-repeat;'>";
	$c = str_replace("+"," ",$catname);
	$MainContent .= "<a href=$catproduct><div class='overlay'>";
	//$MainContent .="</div>";
	$MainContent .="<div class='bord'></div>";
	$MainContent .="<div class='vert'>$c</div>";
	$MainContent .="</div></a>";
	$MainContent .="</div>";
	//$MainContent .= "<img src=$img />";
	if ($count == 2){
		$MainContent .="</div>";
		$count = 0;
	}
	else{
		$count += 1;
	}	

}
$MainContent .="</div>";



$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php"); 
?>
