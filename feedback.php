<?php
session_start();
include("mysql.php");  
$conn = new Mysql_Driver();
// Detect the current session
$MainContent = "<div style='width:80%; margin:auto;'>";
$MainContent .= "<form name='register' action='sendFeedback.php' method='post' >";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<span class='page-title'>Send Gerbern Feedback</span>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='subject'>Subject:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<input class='form-control' name='subject' id='subject' 
                  type='text'/>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='content'>Content:</label>";
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<textarea class='form-control' name='content' id='content'
                  cols='25' rows='8' ></textarea>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";
$MainContent .= "<label class='col-sm-3 col-form-label' for='rating'>How would you rate your experience with Gerbern?:</label>";
$MainContent .= "<div style='vertical-align: middle;' class='col-sm-9'>";
$MainContent .= "<div class='form-check form-check-inline'>";
$MainContent .= "<input style='width:1em; height:1em;' class='form-check-input' name='rating' id='terrible rating' type='radio' value='1'/><label class='form-check-label' for='terrible rating'>Terrible</label>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-check form-check-inline'>";
$MainContent .= "<input style='width:1em; height:1em;' class='form-check-input' name='rating' id='bad rating' type='radio' value='2'/><label class='form-check-label' for='bad rating'>Bad</label>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-check form-check-inline'>";
$MainContent .= "<input style='width:1em; height:1em;' class='form-check-input' name='rating' id='neutral rating' type='radio' value='3' required/><label class='form-check-label' for='neutral rating'>Neutral</label>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-check form-check-inline'>";
$MainContent .= "<input style='width:1em; height:1em;' class='form-check-input' name='rating' id='good rating' type='radio' value='4'/><label class='form-check-label' for='good rating'>Good</label>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-check form-check-inline'>";
$MainContent .= "<input style='width:1em; height:1em;' class='form-check-input' name='rating' id='excellent rating' type='radio' value='5'/><label class='form-check-label' for='excellent rating'>Excellent</label>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div class='form-group row'>";       
$MainContent .= "<div class='col-sm-9 offset-sm-3'>";
$MainContent .= "<button class='btn btn-primary' type='submit'>Send</button>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</form>";
$MainContent .= "</div>";
include("MasterTemplate.php"); 
?>