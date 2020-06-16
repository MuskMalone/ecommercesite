<style>
.carouselTitle {
  margin-left: 90px;
}

.productCarousel {
  position: relative;
  margin-left: 90px;
  margin-right: 90px;
  margin-bottom: 40px;
}

.carouselDiv {
  position:relative;
}
.carouselPrice {
  position: absolute;
  top: 8px;
  right: 10px;
  font-size: 3vh;
}
.carouselImg {
    width: 100%;
    position: relative;
    border:2px solid brown;
}
.carouselDesc {
    position: absolute;
    max-height:30%;
    bottom: 0px;
    text-align: center;
    width: 100%;
    padding: 5px;
    color: white;
    font-size: 3.3vh;
    background-color: rgba(0,0,0,0.5);
}
.carouselArrow {
    background-color: rgba(0,0,0,0.5);
    height: 50px;
    border-radius: 50%;
    margin-left: 5px;
    margin-right: 5px;
 }
 .carouselOverlay {
  position: absolute;
  width: 100%;
  height: 100%;
  z-index: 1;
 }
</style> 

<?php 
// Detect the current session
session_start();
include("mysql.php");

$MainContent = "<div class='row'>";
$MainContent .= "<img style='width:80%;margin-left:auto;margin-right:auto;display:block;' src='Images/misc/welcome2pointO.png' alt='Welcome'>";
$MainContent .= "</div></br>";
$MainContent .= "<h2 class='carouselTitle'>Ongoing Offers</h2>";

$conn = new Mysql_Driver();
$conn->connect();
$qry = "SELECT * FROM Product WHERE Offered = 1 AND OfferStartDate <= CURRENT_DATE AND CURRENT_DATE <= OfferEndDate";
$result = $conn->query($qry);
$count = 0;
while ($row = $conn->fetch_array($result)) {
  $formattedPrice = number_format($row["Price"],2);
  $formattedOPrice = number_format($row["OfferedPrice"],2);

  // If it's the first card of the carousel
  if ($count == 0) {
    $MainContent .= "
    <div id='carouselExampleControls' class='productCarousel carousel slide' data-ride='carousel'>
        <div class='carousel-inner'>
            <div class='carousel-item active'>
              <div class='row'>
                  <div class='col-md-4'>
                    <div class='carouselDiv'>
                      <a class='carouselOverlay' href='productDetails.php?pid=$row[ProductID]'></a>
                        <img class='carouselImg' src='./Images/products/$row[ProductImage]' alt='Product Image'>
                        <span class='carouselPrice'><span style='text-decoration: line-through;'>S$ $formattedPrice</span><span style='font-weight:bold;color:red;'>  S$ $formattedOPrice</span></span>
                        <div class='carouselDesc'>$row[ProductTitle]</div>
                    </div>
                  </div>";              
  }
  // If it's the first card in the row
  elseif ($count == 3) {
    $MainContent .= "
      </div>
    </div>
    <div class='carousel-item'>
      <div class='row'>
        <div class='col-md-4'>
          <div class='carouselDiv'>
            <a class='carouselOverlay' href='productDetails.php?pid=$row[ProductID]'></a>
              <img class='carouselImg' src='./Images/products/$row[ProductImage]' alt='Product Image'>
              <span class='carouselPrice'><span style='text-decoration: line-through;'>S$ $formattedPrice</span><span style='font-weight:bold; color:red;'>  S$ $formattedOPrice</span></span>
              <div class='carouselDesc'>$row[ProductTitle]</div>
            </div>
          </div>";
        $count = 0;
  }
  // Else append next item normally
  else {
    $MainContent .= "
    <div class='col-md-4'>
          <div class='carouselDiv'>
              <a class='carouselOverlay' href='productDetails.php?pid=$row[ProductID]'></a>
              <img class='carouselImg' src='./Images/products/$row[ProductImage]' alt='Product Image'>
                <span class='carouselPrice'><span style='text-decoration: line-through;'>S$ $formattedPrice</span><span style='font-weight:bold; color:red;'>  S$ $formattedOPrice</span></span>
                <div class='carouselDesc'>$row[ProductTitle]</div>
            </div>
        </div>";     
  }
  $count = $count + 1;
}

// Close tags after adding products
$MainContent .= "</div></div>";

// Do not display carousel arrows if there's only 1 page
if (mysqli_num_rows($result) > 3) {
  $MainContent .= "<a style='width: 50px;top:50%;transform: translateY(-50%);' class='carouselArrow carousel-control-prev' href='#carouselExampleControls' role='button' data-slide='prev'>
    <span class='carousel-control-prev-icon' aria-hidden='true'></span>
    <span class='sr-only'>Previous</span>
  </a>
  <a style='width: 50px;top:50%;transform: translateY(-50%);' class='carouselArrow carousel-control-next' href='#carouselExampleControls' role='button' data-slide='next'>
    <span class='carousel-control-next-icon' aria-hidden='true'></span>
    <span class='sr-only'>Next</span>
  </a>";
}

$MainContent .= "</div>";

$conn->close();
include("MasterTemplate.php"); 
?>
