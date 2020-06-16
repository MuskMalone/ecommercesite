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
$password = $_POST["password"];
$pwdquestion = $_POST["pwdquestion"];
$pwdanswer = $_POST["pwdanswer"];

// include the utility class file for mysql database access
include("mysql.php");
// create an object for mysql database access
$conn = new Mysql_Driver();
// connect to the myssql database
$conn->connect();
$checkEmailExist = "SELECT * from Shopper where Email = '$email'";
//define the inset sql statement
$qry = "INSERT INTO Shopper (Name, Address, Country, Phone, Email, BirthDate, Password, PwdQuestion, PwdAnswer)
        VALUES ('$name','$address','$country','$phone','$email', convert('$birthdate', date), '$password', '$pwdquestion', '$pwdanswer')";
// execute the sql statement


if ($conn->num_rows($conn->query($checkEmailExist)) <= 0){
    $result = $conn->query($qry);
    if ($result == true) { // sql statement executed successfully       
        $qry = "SELECT LAST_INSERT_ID() AS ShopperID";
        $result = $conn->query($qry);
        //save the shipper id in a session variable
        while ($row = $conn->fetch_array($result)) {
            $_SESSION["ShopperID"] = $row["ShopperID"];
        }
    
        //display successful message and shopper id 
        $MainContent .= "Registration successful!<br />";
        $MainContent .= "Your Shopper ID is $_SESSION[ShopperID]<br />";
        //save the shopper name in a session variable
        $_SESSION["ShopperName"] = $name;
    }
    else {
        $MainContent .= "<h3 style='color:red'>Error in inserting record</h3>";    
    }
    
}
else{   
    $MainContent .= "<h3 style='color:red'>Email is already in use!!!</h3><br />";
}

//close db connection
$conn->close();

//include the master template file for this page
include("MasterTemplate.php");
?>