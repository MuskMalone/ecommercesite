<script type="text/javascript">
function validateForm()
{
    if (document.editprofile.pwdanswer.value == "" && document.editprofile.pwdquestion.value != ""){
        alert("Enter an answer for your security question!");
        return false;
    }

    if (document.editprofile.pwdanswer.value != "" && document.editprofile.pwdquestion.value == ""){
        alert("Enter a question for your security answer!");
        return false;
    }
    return true;  // No error found
}
</script>

<?php
// Detect the current session
session_start();
// include the utility class file for mysql database access
include("mysql.php");
// create an object for mysql database access
$conn = new Mysql_Driver();
// connect to the myssql database
$conn->connect();
if (isset($_SESSION["ShopperID"])){
    $shopperid = $_SESSION["ShopperID"];
    $qry = "select * from Shopper where ShopperID = $shopperid";
    $result = $conn->query($qry);
    if ($result){
        $values = $conn->fetch_array($result);
        $name = $values["Name"];
        $country = $values["Country"];
        $address = $values["Address"];
        $phone = $values["Phone"];
        $email = $values["Email"];
        $birthdate = $values["BirthDate"];
        $pwdquestion = $values["PwdQuestion"];
        $pwdanswer =  $values["PwdAnswer"];

        $MainContent = "<div style='width:80%; margin:auto;'>";
        $MainContent .= "<form name='editprofile' action='profileUpdate.php' method='post' 
                               onsubmit='return validateForm()'>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
        $MainContent .= "<span class='page-title'>Edit Profile</span>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='name'>Name:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<input class='form-control' name='name' id='name' 
                          type='text' value='$name' required /> (required)";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='address'>Address:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<textarea class='form-control' name='address' id='address'
                          cols='25' rows='4' >$address</textarea>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='country'>Country:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<input class='form-control' name='country' id='country' type='text' value='$country'/>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='phone'>Phone:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<input class='form-control' name='phone' id='phone' type='text' value='$phone'/>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='email'>
                         Email Address:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<input class='form-control' name='email' id='email' 
                          type='email' value='$email' required /> (required)";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='birthdate'>
                         Birth Date:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<input class='form-control' name='birthdate' id='birthdate' type='date' value='$birthdate'/>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='password'>
                         Password:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<a href='changePassword.php'>Change Password</a>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
        $MainContent .= "<div class='page-header'><h2>Enter a security question for account recovery</h2></div>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='pwdquestion'>Security Question:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<input class='form-control' name='pwdquestion' id='pwdquestion' type='text' value='$pwdquestion'/>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";
        $MainContent .= "<label class='col-sm-3 col-form-label' for='pwdanswer'>Answer:</label>";
        $MainContent .= "<div class='col-sm-9'>";
        $MainContent .= "<input class='form-control' name='pwdanswer' id='pwdanswer' type='text' value='$pwdanswer'/>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "<div class='form-group row'>";       
        $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
        $MainContent .= "<button class='btn btn-primary' type='submit'>Save</button>";
        $MainContent .= "</div>";
        $MainContent .= "</div>";
        $MainContent .= "</form>";
        $MainContent .= "</div>";
    }
    else{
        $MainContent = "<h3 style='color:red'>Failed to execute SQL query</h3>";
    }
}
$conn->close();
include("MasterTemplate.php"); 
?>