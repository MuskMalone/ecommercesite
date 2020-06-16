
<?php 
// Detect the current session
session_start();

// HTML Form to collect search keyword and submit it to the same page 
// in server
$MainContent = "<div style='width:80%; margin:auto;'>"; // Container\

$MainContent .= "<form name='frmSearch' method='get' action=''>";
$MainContent .= "<div class='form-group row'>"; // 1st row
$MainContent .= "<div class='col-sm-9'>";
$MainContent .= "<span class='page-title'>Product Search</span>";
$MainContent .= "</div>";
$MainContent .= "</div>"; // End of 1st rows
$MainContent .= "<div class='form-group row'>"; // 2nd row
$MainContent .= "<div class='col-sm-10 searchForm row'>";
$MainContent .= "<div class='col-sm-8'style='padding-right:0;'>";
	//$MainContent .= "<div class='row'>"; // 2nd row
	$MainContent .= "<input class='form-control' name='keywords' placeholder='Search Product Name/Description...' id='keywords' type='search' onchange='test()' style='width:100%;'><br><br>";
	//$MainContent .= "</div>";
	//$MainContent .= "<div class='row'>"; // 2nd row
	$MainContent .= "<h6><div class='Radio' style='margin-top:10px;margin-bottom:10px;'><input type='radio' name='offers' value='All' checked='true'>All";
	$MainContent .= "<input type='radio' name='offers' value='On Offer'style='margin-left:10px;'>On Offer";
	$MainContent .= "<input type='radio' name='offers' value='Not On Offer'style='margin-left:10px;'>Not On Offer</br></div>";

	$MainContent .= "<div slider id='slider-distance' >
  							<div>
    							<div inverse-left style='width:0%;'></div>
    							<div inverse-right style='width:0%;'></div>
   								<div range style='left:0%;right:0%;''></div>
    							<span thumb style='left:0%;'></span>
   								<span thumb style='left:100%;''></span>
   								<div sign style='left:0%;''>
      								<span  id='value'>0</span>
   								</div>
    							<div sign style='left:100%;'>
      								<span  id='value'>1000</span>
    							</div>
 			 				</div>
  							<input id='min' name='mivalue' tabindex='0' type='range'   value='0' max='100' min='0' step='1' oninput= 'miny()'/>
  							<input id='max' name='mavalue' tabindex='0' type='range'   value='100' max='100' min='0' step='1' oninput='maxy()'/>
							</div>";	

	$MainContent .= "</h6>";
$MainContent .= "</div>";
$MainContent .= "<div class='col-sm-2'style='padding-left:0;'>";
	$MainContent .= "<button type='submit' style='padding-bottom:14px;padding-right:30px;margin:auto;'><i class='fa fa-search' style='padding-left:5px;'></i></button>";
$MainContent .= "</div>";
$MainContent .= "</div>";
$MainContent .= "</div>";  // End of 2nd row
$MainContent .= "</form>";


// The search keyword is sent to server
if (isset($_GET['keywords'])) {
	$SearchText=$_GET["keywords"];
    // Retrieve list of product records with "ProductTitle" 
	// contains the keyword entered by shopper, and display them in a table.
	include("mysql.php");
	$conn = new Mysql_Driver();
	$conn->connect();

	if(isset($_GET["mivalue"]) and isset($_GET["mavalue"])){
		$MinP = number_format($_GET["mivalue"],2)*10;
		$MaxP = number_format($_GET["mavalue"],2)*10;
	}
	else{
		 $MaxP = 1000 ;
		 $MinP = 0;
	}
	$CD = date("Y-m-d");
	if($_GET["offers"] == "All"){
		$qry = "SELECT * FROM product WHERE Price>=$MinP AND Price<=$MaxP AND ProductTitle LIKE '%$SearchText%' UNION SELECT * FROM product WHERE Price>=$MinP AND Price<=$MaxP AND  ProductDesc LIKE '%$SearchText%' UNION SELECT * FROM product WHERE ProductTitle LIKE '%$SearchText%' AND Offered = 1 and OfferStartDate <= CURRENT_DATE and OfferEndDate >= CURRENT_DATE and OfferedPrice>=$MinP and OfferedPrice<=$MaxP UNION SELECT * FROM product WHERE ProductDesc LIKE '%$SearchText%' AND Offered = 1 and OfferStartDate <= CURRENT_DATE and OfferEndDate >= CURRENT_DATE and OfferedPrice>=$MinP and OfferedPrice<=$MaxP";
	}
	elseif ($_GET["offers"] == "On Offer") {
		$qry = "SELECT * FROM product WHERE ProductTitle LIKE '%$SearchText%' AND Offered = 1 and OfferStartDate <= CURRENT_DATE and OfferEndDate >= CURRENT_DATE and OfferedPrice>=$MinP and OfferedPrice<=$MaxP UNION SELECT * FROM product WHERE ProductDesc LIKE '%$SearchText%' AND Offered = 1 and OfferStartDate <= CURRENT_DATE and OfferEndDate >= CURRENT_DATE and OfferedPrice>=$MinP and OfferedPrice<=$MaxP";
	}
	else{
		$qry = "SELECT * FROM product WHERE Price>=$MinP AND Price<=$MaxP AND ProductTitle LIKE '%$SearchText%' AND Offered != 1 UNION SELECT * FROM product WHERE Price>=$MinP AND Price<=$MaxP AND  ProductDesc LIKE '%$SearchText%' AND Offered != 1 UNION 
		SELECT * FROM product WHERE ProductTitle LIKE '%$SearchText%' AND Offered = 1 and OfferStartDate > CURRENT_DATE  UNION SELECT * FROM product WHERE ProductTitle LIKE '%$SearchText%' AND Offered = 1 and  OfferEndDate < CURRENT_DATE UNION
		SELECT * FROM product WHERE ProductDesc LIKE '%$SearchText%' AND Offered = 1 and OfferStartDate > CURRENT_DATE  UNION SELECT * FROM product WHERE ProductDesc LIKE '%$SearchText%' AND Offered = 1 and  OfferEndDate < CURRENT_DATE";
	}
	
	$result = $conn->query($qry);
	
	
	

	$MainContent .= "<div class='product-grid '>"; // 67% of row width
	while ($row = $conn->fetch_array($result)) {
		$productn = "productDetails.php?pid=$row[ProductID]";
		$formattedPrice = number_format($row["Price"], 2);
		$img = "./Images/products/$row[ProductImage]";

		//Start a new row
		$MainContent .= "<div class='product'>";
		if($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"] ){
	    
		$MainContent .= "<div class='text-block'>";
		$MainContent .= "<h7>On Offer</h7>";
		$MainContent .= "</div>";
		}
	    $MainContent .= "<div class='img_box'>";
		$MainContent .= "<img class='product_img' src='$img' />";
		$MainContent .= "</div>";
		// Left Column - Display a text link showing the product's name, 
		// display the selling price in red in a new paragraph
		$MainContent .= "<div class='SecondL'>"; // 67% of row width
		$MainContent .= "<div class='Info'>";
		$MainContent .= "<span></span><a href=$productn>$row[ProductTitle]</a>";
		$MainContent .= "<br>";
		if ($row["Offered"]==1 AND date("Y-m-d")>=$row["OfferStartDate"] AND date("Y-m-d")<=$row["OfferEndDate"]){
			$formattedOPrice = number_format($row["OfferedPrice"], 2);
		$MainContent .= "<span></span>Price:<span style='text-decoration: line-through;'>S$ $formattedPrice</span><span style='font-weight:bold; color:red;'>  S$ $formattedOPrice</span>";
			}
		else{
		$MainContent .= "<span></span>Price:<span style='font-weight:bold; color;red;'>S$ $formattedPrice</span>";
			}
		$MainContent .= "</div>";
		$MainContent .= "</div>";		
		$MainContent .= "</div>";	
	}
	$MainContent .= "</div>";
	// End of Code
	$conn->close();
}

$MainContent .= "</div>"; // End of Container
include("MasterTemplate.php");
?>
<script type="text/javascript">
	function miny(){
		
		   document.getElementById('min').value=Math.min(document.getElementById('min').value,document.getElementById('min').parentNode.childNodes[5].value-1);
  var value=(parseInt(document.getElementById('min').max)/(parseInt(document.getElementById('min').max)-parseInt(document.getElementById('min').min)))*parseInt(document.getElementById('min').value)-(parseInt(document.getElementById('min').max)/(parseInt(document.getElementById('min').max)-parseInt(document.getElementById('min').min)))*parseInt(document.getElementById('min').min);
  
  var children = document.getElementById('min').parentNode.childNodes[1].childNodes;
  children[1].style.width=value+'%';
  children[5].style.left=value+'%';
  children[7].style.left=value+'%';children[11].style.left=value+'%';
  children[11].childNodes[1].innerHTML=document.getElementById('min').value*10;
	}

	function maxy(){
		 
		document.getElementById('max').value=Math.max( document.getElementById('max').value, document.getElementById('max').parentNode.childNodes[3].value-(-1));
  var value=(parseInt(document.getElementById('max').max)/(parseInt(document.getElementById('max').max)-parseInt( document.getElementById('max').min)))*parseInt( document.getElementById('max').value)-(parseInt(document.getElementById('max').max)/(parseInt( document.getElementById('max').max)-parseInt( document.getElementById('max').min)))*parseInt(document.getElementById('max').min);
 
  var children =  document.getElementById('max').parentNode.childNodes[1].childNodes;
  children[3].style.width=(100-value)+'%';
  children[5].style.right=(100-value)+'%';
  children[9].style.left=value+'%';children[13].style.left=value+'%';
  children[13].childNodes[1].innerHTML= document.getElementById('max').value*10;
	}

</script>