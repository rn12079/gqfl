<?php 
include('alerts.php');
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
    //margin-bottom: 0;
    
  }


</style>
<script src="bootstrap1/js/bootstrap.min.js"></script>

<title>
  GQFL
</title>

<script type="text/javascript">
</script>
</head>
<body>


  <!-- NAVBAR + ERROR MSG IF NOT LOGGED IN-->

  <?php include('navbar.html');

   if(!$logged) {
    echo "<div class='container' style='margin-top:10;'>";
    err_alert("<strong>Access Denied</strong> Please log in to access");
    echo "</div>";
    die;
  }



  ?>

  <!-- JUMPOTRON -->
  <div class="jumbotron text-center">
    <div class="container">
     <h1><SMALL> New Product </SMALL></h1>
   </div>
 </div>

 <div class="container">
  
  <form action="add_product.php" method="post" enctype="multipart/form-data">
    
    <div class="form-group">
      <label for="Product Name">Product Name </label>
      <input type="text" class="form-control" name="txt_name" placeholder="Coke BIB" id ="t_name">
    </div>

    <div class="form-group">
      <label for="Product Supplier">Product Supplier </label><input type="text" class="form-control" name="txt_supplier" placeholder="CCBPL" id ="t_supplier">
      
    </div> 

    <div class="form-group">
      <label for="Product Maker">Product Source</label><input type="text" class="form-control" name="txt_source" placeholder="Source/Make" id ="t_source">
      
    </div> 

    <div class="row">

      <div class="col-sm-3 form-group">
        <label for="Product Type">Product Type </label><input type="text" class="form-control" name="txt_type" placeholder="Food" id ="t_type">
      </div>

      <div class="col-sm-3 form-group">
        <label for="Product Sub Type">Product Subtype </label>
        <input type="text" class="form-control" name="txt_sub_type" placeholder="Beverage" id ="t_sub_type">
      </div>

    </div>
    
    <div class="row">

      <div class="col-sm-3 form-group">
        <label for="Case Size">Case Size</label>
        <input type="number" class="form-control" name="txt_case_size" placeholder="20" step=".01" id ="t_cases">
      </div>

      <div class="col-sm-3 form-group">
        <label for="Units">Case Size</label>
        <select class="form-control" name="txt_unit" id ="t_units" >
          <option value=""></option>
          <option value="ltrs">Ltrs</option>
          <option value="pcs">Pcs / Packets</option>
          <option value="kgs">Kgs</option>
          <option value="btls">Bottles</option>
          <option value="carton">Cartons</option>
          <option value="gal">Gallons</option>
        </select>
      </div>

    </div>

            

            <input type="submit" name="submit" value="Add Product">
          
    </div>
  </form>
</div>
<div style="height:400px"></div>
</body>
</html>
