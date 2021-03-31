<?php
/*
function err_alert($err_msg){
echo "<div class='alert alert-danger' role='alert'>".$err_msg."</div>";
}
*/
include('alerts.php');
include_once('db_conn.php');


session_start();
$errmsg = "";
$logged = false;

if (!$_SESSION["loggedin"]) {
    $logged = false;
} else {
    if ($_SESSION["level"] == "admin") {
        $logged = true;
    }
}


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

  if (!$logged) {
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
 

 <?php
    
    $txt_id = $_POST["txt_id"];
    $txt_name = $_POST["txt_name"];
    $txt_type = $_POST["txt_type"];
    $txt_sub_type = $_POST["txt_sub_type"];
    $txt_source = $_POST["txt_source"];
    $txt_cases = $_POST["txt_case_size"];
    $txt_unit = $_POST["txt_unit"];





//echo "txt ".$txt_name;
    if ($txt_name=="") {
        die(err_alert("<strong> !No Product Name </strong> Please enter valid Product name"));
    }

    if ($txt_type=="") {
        die(err_alert("<strong> !No Product Type</strong> Please enter valid Product Type"));
    }
    /* add supplier already exist check */
    $chkquery = "select count(*) as cnt from products where";
    $chkquery = $chkquery. " product_name='".$txt_name."' and product_sub_type='".$txt_sub_type."'";
    $chkquery = $chkquery. " and product_type='".$txt_type."' and maker='".$txt_source."' and casesize='".$txt_cases."'";
    $chkquery = $chkquery. " and units='".$txt_unit."'";


//echo "<p>" . $chkquery." </p><br>";

    /*   */

    /* query for adding supplier */

    $myquery="update products set product_name='".$txt_name . "' ,product_type='".$txt_type."' , product_sub_type ='".$txt_sub_type."' , maker='".$txt_source."' ,casesize='".$txt_cases."', units = '".$txt_unit."' ";
    $myquery = $myquery .  "where id='".$txt_id."'";

    
   // echo "<p>" . $myquery." </p><br>";

$conn = new mysqli($GLOBALS['host'], $GLOBALS['dbuser'], $GLOBALS['dbpass'], $GLOBALS['db']);
  if ($conn->connect_error) {
      die('Could not connect: ' . $conn->connect_error);
  }

    // check and stop if product already exists
    if (!$check = $conn->query($chkquery)) {
        err_alert($conn->error);
        die;
    }
    $check1 = $check->fetch_assoc();
    $check2 = $check1['cnt'];
//echo "check == $check2";

    // if product is not added continue to add else block
    if ($check2==0) {
        if (!$conn->query($myquery)) {
            err_alert("edit query failed");
            die('Error: ' . $conn->error);
        }

        echo "<div class='panel panel-success'>";
        echo "<div class='panel-heading'>Product Successfully Added</div>";
        echo "<div class='panel-body'>";
        echo "Product Name :  ". $txt_name . "<br>";
        echo "Source/Maker :  ". $txt_source . "<br>";
        echo "Product Type :  ". $txt_type . "<br>";
        echo "Product Sub Type :  ". $txt_sub_type . "<br>";
        echo "Case Size : ".$txt_cases." "."$txt_unit";
      
        echo "</div>";
        echo "</div>";
    } else {
        err_alert("Product already added");
        err_alert($myquery);
    }
   mysqli_close($conn);

   ?>

    <br><a href="add_product_main.php" class="btn btn-primary" role="button"> Add another record </a>

 </div>
</body>
</html>

