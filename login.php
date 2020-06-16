<?php
session_start();

// Create a centrally located container
$MainContent = "<div style='width:80%; margin:auto;'>";
// Create a html form within the container
$MainContent .= "<form action='checkLogin.php' method='post'>";
// 1st row - Header row
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Member Login</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";
// 2nd row - Entry of email address
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='email'>
                    Email Address:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' type='email'
                    name='email' id='email' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";
// 3rd row - Entry of email address
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='password'>
                    Password:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' type='password'
                    name='password' id='password' required />";
$MainContent .= "</div>";
$MainContent .= "</div>";
// 4th row- Login button
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<button class='btn btn-primary' type='submit'>Login</button></br></br>";
$MainContent .= "<a href='forgetPassword.php'>Forgot your password?</a>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</form>";
$MainContent .= "</div>";

// Include the Page Layout template
include("MasterTemplate.php");
?>