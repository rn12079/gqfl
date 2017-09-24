<?php 

session_start();
$errmsg = "";


if(!$_SESSION["loggedin"]) 
	$logged = false;
else
	$logged = true;
?>


<html>

<head>
	<script   src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<link href="bootstrap1/css/bootstrap.min.css" rel="stylesheet">
	<style>
	.navbar{
		margin-bottom:0;
		border-radius: 0;
	}
	.jumbotron{
		margin-bottom: 0;
	}


	</style>
	<script src="bootstrap1/js/bootstrap.min.js"></script>
	
	<title>
		GQFL
	</title>
	
</head>
<body>
	<!-- NAVBAR -->
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
	    <!-- Brand and toggle get grouped for better mobile display -->
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">Global Quality Foods Limited</a>
	    </div>

	    <!-- Collect the nav links, forms, and other content for toggling -->
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    	<ul class="nav navbar-nav">
	        
	        	<?php 
	        		if($logged) {
	        			echo "<li><a href='add_product_main.php'>Add Products </a></li>";
	        			echo "<li><a href='add_invent.php'>Add Inventory Items </a></li>";
	        		} ?>
	            <li><a href="show_inventory.php">Show Inventory</a></li>
	      </ul>
	      <ul class="nav navbar-nav navbar-right">
	        <li>
	        	<?php 
	        		if($logged)
	        			echo "<a href='logout.php'>Log out </a>";
	        		else
	        			echo "<a href='login.php'>Login </a>";

	        	 ?>
	          
	        </li>
	      </ul>
	    </div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>	


	<!-- JUMPOTRON -->
	<div class="jumbotron text-center" >
		<div class="container">
			 <h1><SMALL> Inventory Management </SMALL></h1>
		</div>
	</div>



	<!-- TYPOGRAPHY -->
	<div class="container">
		
		<h1 class="page-header"> Bootstrap <small>Cheat Sheet</small> </h1>
		<p class="lead">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec sem risus. Maecenas sagittis placerat nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras et mattis leo, eu </p><p> <mark> vulputate diam. Nunc nec felis nunc.</mark> Duis facilisis faucibus libero et volutpat. Fusce quam libero, malesuada <del>sit amet mollis sit amet,</del> sollicitudin sollicitudin sem. Pellentesque consequat ultrices tortor in venenatis. Nunc eu nibh quam. <u>Ut aliquet, quam nec mattis fermentum, nisi nisl malesuada velit,</u> id congue metus sapien eget arcu. Morbi non massa vel augue aliquam dictum. Suspendisse id magna at nisi ultricies faucibus vel vitae tortor. Nam nec efficitur quam. Duis varius eu velit viverra interdum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam porta eget risus at cursus
		</p>
	
		<hr>
		
		<!-- ALIGNMENT -->
		<p class="text-left text-lowercase"> Left Aligned Text </p>
		<p class="text-center text-uppercase"> Center Aligned Text </p>
		<p class="text-right text-capitalize"> Right Aligned Text </p>
		<p class="text-justify"> Justified Aligned Text </p>
		<p class="text-nowrap"> No Wrap Aligned Text </p>

		<hr>

		<div class="pull-right"> Div Floated to right </div>
		<div class="pull-left"> Div Floated to left </div>

		<!-- CLEAR FLOAT -->
		<div class="clearfix"></div>
		

		<hr>
		<!-- BLOCKQUOTE  -->
		<blockquote class="blockquote-reverse">
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec sem risus. Maecenas sagittis placerat nisi. Pellentesque habitant morbi tristique</p>
			<footer>Quote By</footer><cite title="John Doe">John Doe</cite>
		</blockquote>


		<hr>

		<!-- LISTS -->

		<ul class="list-unstyled">
			<li> Item One </li>
			<li> Iem Two </li>
			<li> Item Three </li>
			<li> Item Four </li>
			<li> Item Five </li>
		</ul>


		<ul class="list-inline">
			<li> Item One </li>
			<li> Iem Two </li>
			<li> Item Three </li>
			<li> Item Four </li>
			<li> Item Five </li>
		</ul>

		<hr>
		<!-- CODE -->

		<code> &lt;h1&gt; Heading Text &lt;/h1&gt;</code>
		<br>
		Change Directory with <kbd>cd</kbd>
		<br>
		To edit setting. press <kbd>ctrl +,</kbd>


		<hr>

		<!-- Contextual Color -->
		<p class="text-primary">Primary</p>
		<p class="text-success">Success</p>
		<p class="text-info">Info</p>
		<p class="text-warning">Warning</p>
		<p class="text-danger">Danger</p>
		<p class="text-muted">Muted</p>

		<hr>
		<!-- Contextual BG Color -->
		<p class="bg-primary">Primary</p>
		<p class="bg-success">Success</p>
		<p class="bg-info">Info</p>
		<p class="bg-warning">Warning</p>
		<p class="bg-danger">Danger</p>
		<p class="bg-muted">Muted</p>


	</div>

	<div class="container">

		<hr>

		<!-- Buttons -->
		<button class="btn btn-default">Button</button>
		<a href="#" class="btn btn-default" role="button">Link</a>
		<input type="submit" class="btn btn-default" value="submit">


		<hr>
		<!-- Contextual Buttons -->
		<button class="btn btn-default">Default</button>
		<button class="btn btn-primary">Primary</button>
		<button class="btn btn-success">Success</button>
		<button class="btn btn-info">Info</button>
		<button class="btn btn-warning">Warning</button>
		<button class="btn btn-danger">Danger</button>
		<button class="btn btn-link">Link</button>

		<hr>
		<!-- Button Sizes -->
		<button class="btn btn-default">Button</button>
		<button class="btn btn-default btn-lg">Large Button</button>
		<button class="btn btn-primary btn-sm">Small Button</button>
		<button class="btn btn-default btn-xs" disabled="disabled"> X Small Button</button>



	</div>
	

	<!-- FORMS -->
	<div class="container">
	
		<hr>	
		<form>
			<div class="form-group">
				<label>Name</label>
				<input type="text" class="form-control" placeholder="Add Name">
			</div>
			
			<div class="form-group">
				<label>Address</label>
				<input type="text" class="form-control" placeholder="Add Address">
			</div>
			
			<div class="form-group">
				<label>Message</label>
				<textarea class="form-control" placeholder="Add Message">
				</textarea>
			</div>
			
			<div class="form-group">
				<label>Gender</label>
				<select class="form-control">
					<option value="male">Male</option>
					<option value="female">Female</option>
					<option value="other">other</option>
				</select>
			</div>
			
			<div class="form-group">
				<label>Upload File</label>
				<input type="file">
				<p class="help-block">Only Jpgs and Pngs </p>
			</div>

			<div class="form-group">
				<label>
				<input type="checkbox">
				Check out </label>
			</div>

			<input type="submit" class="btn btn-default" value="submit">

		</form>
		
		<hr>
		<!-- Inline Form -->

		<form class="form-inline">
			<div class="form-group">
				<label>Username</label>
				<input type="text" class="form-control" placeholder="username">
			</div>
			
			<div class="form-group">
				<label>Password</label>
				<input type="password" class="form-control" placeholder="password">
			</div>
			
			<div class="form-group">
				<label>
				<input type="checkbox">
				Remember me </label>
			</div>

			<input type="submit" class="btn btn-default" value="submit">
			
		</form>

	</div>

	<hr>

	<!-- TABLES -->

	<div class="container">
		<table class="table table-striped table-bordered table-hover table-condensed">
			<tr>
				<th>Date</th>
				<th>Location</th>
				<th>Product</th>
				<th>Supplier</th>
				<th>Type</th>
				<th>Subtype</th>
				<th>Cases</th>
				<th>Amount</th>
				<th>Inv</th>
			</tr>
			<tr>
				<td>2017-01-02</td>
				<td>Hydri</td>
				<td>Chicken Fillet</td>
				<td>K&N</td>
				<td>Food</td>
				<td>Chicken</td>
				<td>8</td>
				<td>36,288.00</td>
				<td>33</td>
			</tr>
			<tr class="danger">
				<td>2017-01-02</td>
				<td>Hydri</td>
				<td>Lemon Chicken</td>
				<td>K&N</td>
				<td>Food</td>
				<td>Chicken</td>
				<td>5</td>
				<td>21,420.00</td>
				<td>33</td>
			</tr>
			<tr>
				<td>2017-01-02</td>
				<td>Hydri</td>
				<td>Normal Tender</td>
				<td>K&N</td>
				<td>Food</td>
				<td>Chicken</td>
				<td>5</td>
				<td>19,500.00</td>
				<td>33</td>
			</tr>
			<tr>
				<td>2017-01-04</td>
				<td>Hydri</td>
				<td>Fanta BIB</td>
				<td>CCBPL</td>
				<td>Food</td>
				<td>Beverage</td>
				<td>2</td>
				<td>12,600.00</td>
				<td>65843</td>
			</tr>
		</table>

	</div>

	<!-- LISTS -->

	<div class="container">
		<hr>
		<ul class="list-group">
			<li class="list-group-item"> Item One </li>
			<li class="list-group-item"> Iem Two </li>
			<li class="list-group-item"> Item Three </li>
			<li class="list-group-item"> Item Four </li>
			<li class="list-group-item"> Item Five </li>
		</ul>

		<div class="list-group">
			<a href="#" class="list-group-item active"> Item One </a>
			<a href="#" class="list-group-item list-group-item-success"> Iem Two </a>
			<a href="#" class="list-group-item"> Item Three </a>
			<a href="#" class="list-group-item disabled"> Item Four </a>
			<a href="#" class="list-group-item"> Item Five </a>
		</div>
	</div>	
		<hr>
		

		<!-- PANELS -->

	<div class="container">
		<div class="panel panel-primary">
		<!-- Default panel contents -->
			<div class="panel-heading">Panel heading</div>
			  <div class="panel-body">
			    <p>...</p>
			  </div>
			<div class="panel-footer">PAnel Footer </div>
		</div>
	</div>

		<!--Wells -->

	<div class="container">
		<div class="well">
		    Wells
		</div>
		<div class="well well-lg">
		    Large Well
		</div>
		<div class="well well-sm">
		    Small Well
		</div>

		<hr>


	<!--Alerts -->
		<div class="alert alert-success" role="alert">A Success Alert</div>
		<div class="alert alert-info" role="alert">An info Alert</div>
		<div class="alert alert-warning" role="alert">A warning Alert</div>
		<div class="alert alert-danger" role="alert">A dangerAlert</div>
		
		<div class="alert alert-warning alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert">
				<span>&times;</span></button>
				you can close this alert 
		</div>

		
		<!-- Progress Bar -->

		<hr>

		<div class="progress">
			<div class="progress-bar progress-bar-success progress-bar-striped active" style="width:50%";>
				50%
			</div>
		</div>

		<hr>

		<!-- LABELS -->
		
		<span class="label label-default">Default</span>
		<h1> Hello <span class="label label-primary">Primary</span></h1>
		<span class="label label-success">Success</span>
		<span class="label label-info">info</span>
		<span class="label label-warning">warning</span>
		<span class="label label-danger">Danger</span>

		<hr>
		<!-- Badges -->
		<a href="#"> Inbox <span class="badge">50</span></a>
		
		<button class="btn btn-primary" type="button">
		Messages <span class="badge">2</span>
		</button>
	</div>

	<hr>
		<!-- GRIDS -->

	<div class="container">
		<div class="row">
			<div class="col-md-8">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec sem risus. Maecenas sagittis placerat nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras et mattis leo, eu<mark> vulputate diam. Nunc nec felis nunc.</mark> Duis facilisis faucibus libero et volutpat. Fusce quam libero, malesuada <del>sit amet mollis sit amet,</del> sollicitudin sollicitudin sem. Pellentesque consequat ultrices tortor in venenatis. Nunc eu nibh quam. <u>Ut aliquet, quam nec mattis fermentum, nisi nisl malesuada velit,</u> id congue metus sapien eget arcu. Morbi non massa vel augue aliquam dictum. Suspendisse id magna at nisi ultricies faucibus vel vitae tortor. Nam nec efficitur quam. Duis varius eu velit viverra interdum. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam porta eget risus at cursus

			</div>
			<div class="col-md-4">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec sem risus. Maecenas sagittis placerat nisi. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras et mattis leo, 

			</div>

		</div>

	</div>
	
	<hr>

	<div class="container">
		<div class="row">
			<div class="col-md-3">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec sem risus. Maecenas sagittis placerat nisi. Pellentesque habitant morbi tristique senectus et netus et 

			</div>
			<div class="col-md-3">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec sem risus. Maecenas sagittis placerat nisi. Pellentesque habitant morbi tristique senectus et netus et 

			</div>
			<div class="col-md-3">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec sem risus. Maecenas sagittis placerat nisi. Pellentesque habitant morbi tristique senectus et netus et 

			</div>
			<div class="col-md-3">
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec sem risus. Maecenas sagittis placerat nisi. Pellentesque habitant morbi tristique senectus et netus et 

			</div>
			

		</div>

	</div>



	<div style="height:400px"></div>

</body>

</html>
