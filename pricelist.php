<?php 
/*
function err_alert($err_msg){
echo "<div class='alert alert-danger' role='alert'>".$err_msg."</div>";
}
*/
include('alerts.php');
include('db_funcs.php');

session_start();
$errmsg = "";


if(!$_SESSION["loggedin"]) 
  $logged = false;
else
  $logged = true;

?>


<html>
<head>
<link href="select2/select2.min.css" rel="stylesheet" />
<link href="bootstrap1/css/bootstrap.min.css" rel="stylesheet">
<title>GQFL - Price List</title>
<style>

label {

  padding-top: 4px;

}
  .navbar{
    margin-bottom:0;
    border-radius: 0;
  }
  .jumbotron{
    //margin-bottom: 0;
    
  }
  .form-group {

    margin-bottom:2px;


  }


</style>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script src="bootstrap1/js/bootstrap.min.js"></script>
<script src="select2/select2.min.js"></script>


</head>
<body>
 <?php include('navbar.html');

      if(!$logged) {
    echo "<div class='container' style='margin-top:10;'>";
    err_alert("<strong>Access Denied</strong> Please log in to access");
    echo "</div>";
    die;
  }


  $cnt = 0;
  ?>

    <!-- JUMPOTRON -->
  <div class="jumbotron">
    <div class="container">
      <strong>Price List</strong>
   </div>
 </div>


 <div class="container">
  <table class="table table-striped table-bordered table-condensed" id="rec_items" style="margin-bottom: 4px">
        <tr>
          <th>Supplier </th>
          <th>Product Name</th>
	  <th>Case Size</th>
          <th>Price</th>
        </tr>

  <?php 
  $result = get_current_prices(); 
  $curr_supp = ""; 
  while ($row = $result->fetch_assoc()){
    if($curr_supp != $row['supplier']) {
      echo "<tr><th colspan='3'>" .  $row['supplier']  . "</th></tr>";
      $curr_supp = $row['supplier'];
    }

    echo "<tr><td>".$row['product_id']."</td><td>".$row['product_name']."</td><td>".$row['casesize']." ".$row['units']."</td><td>".$row['current_rate']."</td></tr>";


  }
  ?>
</table>
</div>
</body>
</html>
