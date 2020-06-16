<style>
	.navText {
		font-size: 16px;
	}
</style>

<?php 
//	Display guest welcome message, Login and Registration links
//	when shopper has yet to login,
$content1 = "Welcome Guest<br />";
$content2 = "<li class='nav-item'>
			<a class='nav-link naxText' style='font-size: 16px;' href='viewFeedback.php'>View Feedback</a></li>
			<li class='nav-item'>
		    <a class='nav-link naxText' style='font-size: 16px;' href='register.php'>Sign Up</a></li>
			<li class='nav-item'>
		    <a class='nav-link navText' href='login.php'>Login</a></li>";
$content3 = "<a href='index.php'>
			<img style='width:150px;' src='Images/misc/Logo2.png' alt='Logo' 
				class='img-fluid' style='width: 100%'/></a>";

if(isset($_SESSION["ShopperName"]) && isset($_SESSION["NumCartItem"])) { 
    //	Display a greeting message, Edit Profile and logout links 
    //	after shopper has logged in.
    $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "
				<li class='nav-item'>
					<a href='shoppingcart.php'>
						<img src='https://img.icons8.com/material-outlined/48/000000/shopping-cart.png' class='img-fluid' style='padding-right: 4px'/>
						<span class='badge' style= 'background-color: black; color: white; vertical-align: top;'>$_SESSION[NumCartItem]</span>
					</a>
				</li>
    			<li class='nav-item'>
					<a class='nav-link navText' href='editProfile.php'>Edit Profile</a><li>
				<li class='nav-item dropdown'>
					<a class='nav-link dropdown-toggle navText' href='#' id='navbarDropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Feedback</a>
					<div class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>
						<a class='dropdown-item navText' href='feedback.php'>Send Feedback</a>
						<a class='dropdown-item navText' href='viewFeedback.php'>View Feedback</a>
					</div>
				</li>
                <li class='nav-item navText'>
                	<a class='nav-link' href='logout.php'>Logout</a></li>";
}

else if(isset($_SESSION["ShopperName"])) { 
    //	Display a greeting message, Edit Profile and logout links 
    //	after shopper has logged in.
    $content1 = "Welcome <b>$_SESSION[ShopperName]</b>";
    $content2 = "
				<li class='nav-item'>
					<a href='shoppingcart.php'>
						<img src='https://img.icons8.com/material-outlined/48/000000/shopping-cart.png' class='img-fluid' style='padding-right: 4px'/>
					</a>
				</li>
    			<li class='nav-item'>
					<a class='nav-link navText' href='editProfile.php'>Edit Profile</a><li>
				<li class='nav-item dropdown'>
					<a class='nav-link dropdown-toggle navText' href='#' id='navbarDropdownMenuLink' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>Feedback</a>
					<div class='dropdown-menu' aria-labelledby='navbarDropdownMenuLink'>
						<a class='dropdown-item' navText' href='feedback.php'>Send Feedback</a>
						<a class='dropdown-item' navText' href='viewFeedback.php'>View Feedback</a>
					</div>
				</li>
                <li class='nav-item navText'>
                	<a class='nav-link' href='logout.php'>Logout</a></li>";
}
?>
<!-- Collapsible navbar -->
<nav class="navbar navbar-expand-md navbar-dark bg-custom"style="background-color:#7f9c90;">
	<!-- Collapsible part of navbar -->
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="collapsibleNavbar">
		<!-- Left-justified menu items -->
		<span class="" style="color:#F7BE81;maxwidth:80%;">
			<?php echo $content3; ?>
		</span>
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link navText" href="category.php">Product Categories</a>
			</li>
			<li class="nav-item">
				<a class="nav-link navText" href="search.php">Product Search</a>
			</li>
		</ul>
		<!-- Right-justified menu items -->
		<ul class="navbar-nav ml-auto">
			<?php echo $content2; ?>
		</ul>
	</div>
</nav>

