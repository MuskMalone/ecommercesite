<?php
// detect the current session
session_start();
$MainContent = "";

// read the data input from previous page
$subject = $_POST["subject"];
$content = $_POST["content"];
$rating = (int)$_POST["rating"];
$shopperid = $_SESSION["ShopperID"];

// include the utility class file for mysql database access
include("mysql.php");
// create an object for mysql database access
$conn = new Mysql_Driver();
// connect to the myssql database
$conn->connect();
//define the inset sql statement
$qry = "INSERT INTO Feedback (ShopperID, Subject, Content, Rank)
        VALUES ($shopperid, '$subject', '$content', $rating)";
// execute the sql statement
$result = $conn->query($qry);
if ($result == true) { // sql statement executed successfully       
    //display successful message and shopper id 
    $MainContent .= "Feedback sent!<br />Thank you for your feedback, you opinion is valuable to us.";
}
else {
    $MainContent .= "<h3 style='color:red'>Error in inserting record</h3>";    
}

//close db connection
$conn->close();

//include the master template file for this page
include("MasterTemplate.php");
?>