<?php 
session_start();
include("mysql.php");  
$conn = new Mysql_Driver();
$eMail = "";
if (isset($_POST["eMail"])){
    $eMail = $_POST["eMail"];
    // Retrieve shopper record based on e-mail address
    $conn->connect();
    $qry = "SELECT * FROM shopper WHERE Email='$eMail'"; 
    $result = $conn->query($qry);
    $row = $conn->fetch_array($result);
    $conn->close();
    if ($conn->num_rows($result) > 0) {
        $securityqn = $row["PwdQuestion"];
        if ($row["PwdQuestion"] != NULL){
            $MainContent = "<div style='width:80%; margin:auto;'>";
            $MainContent .= "<form action='securityQuestion.php' method='post'>";
            $MainContent .= "<div class='form-group row'>";
            $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
            $MainContent .= "<span class='page-title'>Password Recovery</span>";
            $MainContent .= "</div>";
            $MainContent .= "</div>";
            $MainContent .= "<div class='form-group row'>";
            $MainContent .= "<label class='col-sm-3 col-form-label' for='SecurityQn'>
                            Security Question:</label>";
            $MainContent .= "<div class='col-sm-9'>";
            $MainContent .= "<label class='col-sm-3 col-form-label' for='SecurityQn'>
                            $securityqn</label>";
            $MainContent .= "</div>";
            $MainContent .= "</div>";
            $MainContent .= "<div class='form-group row'>";
            $MainContent .= "<label class='col-sm-3 col-form-label' for='SecurityAns'>
                            Security Answer:</label>";
            $MainContent .= "<div class='col-sm-9'>";
            $MainContent .= "<input class='form-control' name='SecurityAns' id='SecurityAns'
                                    type='text' required />";
            $MainContent .= "<input type='hidden' name='eMail2' value=$eMail />";
            $MainContent .= "</div>";
            $MainContent .= "</div>";
            $MainContent .= "<div class='form-group row'>";       
            $MainContent .= "<div class='col-sm-9 offset-sm-3'>";
            $MainContent .= "<button id='submit btn' class='btn btn-primary' type='submit'>Submit</button>";
            $MainContent .= "</div>";
            $MainContent .= "</div>";
            $MainContent .= "</form>";
        }
        else{
            $MainContent = "<p><span style='color:red;'>Security question not set!</span>";
        }

    }
    else {
        $MainContent = "<p><span style='color:red;'>Email is not registered!</span>";
    }
}


// Process after user click the submit button
if (isset($_POST["SecurityAns"])){
    $eMail = $_POST["eMail2"];
	$conn->connect();
	$qry = "SELECT * FROM shopper WHERE Email='$eMail'"; 
	$result = $conn->query($qry);
	$row = $conn->fetch_array($result);
	$conn->close();
	if ($_POST["SecurityAns"] == $row["PwdAnswer"]){
		$password = $row["Password"];
		$MainContent = "Your Password is $password<br />";
	}
	else{
		$MainContent = "<p><span style='color:red;'>Wrong answer</span>";
	}
}
$MainContent .= "</div>";
include("MasterTemplate.php");
?>