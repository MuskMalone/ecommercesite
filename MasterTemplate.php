<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Gerbern</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<!--jQuery library -->
	<script src="bootstrap/js/jquery-3.3.1.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="bootstrap/js/bootstrap.min.js"></script>

	<!-- Site specific Cascading Stylesheet -->
	<link rel="stylesheet" href="bootstrap/css/site.css">
	<link rel="stylesheet" href="bootstrap/css/ProductPage.css">

	<!-- Bootstrap JS, jQuery, and Popper.js -->
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<!-- Font Awesome CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
	<!-- 1st Row -->
	<div class="row">
		<div class="col-sm-12">
			
		</div>
	</div>
	<!-- 2nd Row -->
	<div style="margin:0px;" class="row">
		<div style="padding:0px;" class="col-sm-12">
			<?php include("navbar.php"); ?>
		</div>
	</div>
<div class="container">
	<!-- 3rd Row -->
	<div class="row">
		<div class="col-sm-12" style="padding:15px;">
			<?php echo $MainContent; ?>
		</div>
	</div>
	<!-- 4th Row -->
	<div class="row" style="right: 10px;width: 100%;">
		<div class="col-sm-12" style="text-align: right;right: 10px;width: 100%;"><hr/>For any enquiries:</br>Contact us at <a href="mailto:mamaya@np.edu.sg">gerbern@np.edu.sg</a>
			<p style="font-size:12px">&copy;Copyright by Gerbern Solutions</p>
		</div>
	</div>
</div>
</body>