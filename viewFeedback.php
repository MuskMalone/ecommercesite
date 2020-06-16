<?php 
// Detect the current session
session_start();
include("mysql.php");  // Include the class file for database access
$conn = new Mysql_Driver();  // Create an object for database access
$conn->connect(); // Open database connnection

$averageRatingQry = "SELECT ROUND(AVG(Rank),1) AS AverageRank FROM Feedback";
$averageRatingResult = $conn->query($averageRatingQry);
$averageRating = $conn->fetch_array($averageRatingResult)["AverageRank"];
// Create a container, 60% width of viewport
$MainContent = "<div style='width:60%; margin:auto;'>";
// Display Page Header - 
// Category's name is read from query string passed from previous page.
$MainContent .= "<div class='row' style='padding:5px'>";
$MainContent .= "<div class='col-12'>";
$MainContent .= "<span class='page-title'><h1>$averageRating / 5 Stars</h1></span></br></br>";
$MainContent .= "<span class='page-title'>All Reviews:</h3></span><hr>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "<div style='overflow-y: scroll; height:400px; overflow-x:hidden;'>";
// form sql to retrieve list of products associated to the catogory id
$qry = "SELECT * from Feedback inner join Shopper on Feedback.ShopperID = Shopper.ShopperID";
$result = $conn->query($qry); //execute the sql statement

//display each product in a new row
while ($row =$conn->fetch_array($result)){

    $rating = "";
    $color = "";
    if ($row["Rank"] == 1){ $rating = "Terrible"; $color = "red";}
    elseif ($row["Rank"] == 2){ $rating = "Bad"; $color = "orange";}
    elseif ($row["Rank"] == 3){ $rating = "Neutral"; $color = "yellow";}
    elseif ($row["Rank"] == 4){ $rating = "Good"; $color = "#66ff66";}
    elseif ($row["Rank"] == 5){ $rating = "Excellent"; $color = "#33cc33";}
    //start a new row
    $MainContent .= "<div class='row' style='padding:5px'>";

    //left column - display a text link showing the product's name
    //            - display the selling price in red in a new paragraph
    $MainContent .= "<div class='col-8'>";    
    $MainContent .= "<label style='font-weight:bold; color:$color;'>
                        $rating</label></br>";
    $MainContent .= "<label>$row[Subject]</label>";
    $MainContent .= "<p>$row[Content]</p>";
    $MainContent .= "</div>";
    $MainContent .= "<div class='col-4'>"; // 33 percent of row width
    $MainContent .= "<label>$row[Name]</label>";
    $MainContent .= "</div>";
    $MainContent .= "<hr style='display:block; width:96%; height:0.1px; background-color:#ededed;'/>";
    $MainContent .= "</div>"; //end of a row
}
// To Do:  Ending ....

$conn->close(); // Close database connnection
$MainContent .= "</div>"; // End of container
$MainContent .= "</div>"; // End of container
include("MasterTemplate.php");  
?>