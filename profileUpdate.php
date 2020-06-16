<?php
// detect the current session
session_start();
$MainContent = "";

// read the data input from previous page
$name = $_POST["name"];
$address = $_POST["address"];
$country = $_POST["country"];
$phone = $_POST["phone"]; 
$email = $_POST["email"];
$birthdate = $_POST["birthdate"];
$pwdquestion = $_POST["pwdquestion"];
$pwdanswer = $_POST["pwdanswer"];
$shopperid = $_SESSION["ShopperID"];

// include the utility class file for mysql database access
include("mysql.php");
// create an object for mysql database access
$conn = new Mysql_Driver();
// connect to the myssql database
$conn->connect();
$checkEmailExist = "SELECT * from Shopper where Email = '$email'";
$currentEmail = $conn->fetch_array($conn->query("select * from Shopper where ShopperID = $shopperid"))["Email"];
// define the inset sql statement
$qry = "update Shopper set Name = '$name', Address = '$address', Country = '$country', Phone = '$phone', Email = '$email', BirthDate = convert('$birthdate', date), PwdQuestion = '$pwdquestion', PwdAnswer = '$pwdanswer' 
        where ShopperID = $shopperid";
// execute the sql statement


if ($conn->num_rows($conn->query($checkEmailExist)) == 0 || $email == $currentEmail){
    $result = $conn->query($qry);
    if ($result == true) { // sql statement executed successfully
        // display successful message and shopper id 
        $MainContent .= "Changes saved!<br />";
        $MainContent .= "Your Shopper ID is $_SESSION[ShopperID]<br />";
        // save the shopper name in a session variable
        $_SESSION["ShopperName"] = $name;
    }
    else {
        $MainContent .= "<h3 style='color:red'>Error in inserting record</h3>";    
    }
    
}
else{
    $MainContent .= "<h3 style='color:red'>Email is already in use!!!</h3><br />";
}

// close db connection
$conn->close();

// include the master template file for this page
include("MasterTemplate.php");
?>