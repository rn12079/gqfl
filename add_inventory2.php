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
<title>GQFL - Add Inventory</title>
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




 <script type="text/javascript"></script>

<body onload="init()">
  <?php include('navbar.html');
  $bool_upload = false;
  $bool_succ = false;

  if(!$logged) {
    echo "<div class='container' style='margin-top:10;'>";
    err_alert("<strong>Access Denied</strong> Please log in to access");
    echo "</div>";
    die;
  }

  ?>

    <!-- JUMPOTRON -->
  <div class="jumbotron">
    <div class="container">
      <strong>New Inventory Record</strong>
   </div>
 </div>


 <div class="container">
   <?php

    if(!isset( $_POST['existing']) )
    {


      $upload_filename = $_FILES["file"]["name"];
      if ($upload_filename!="") {
//echo $upload_filename;
        if($_FILES['file']['error']!== UPLOAD_ERR_OK) {
          die(err_alert("Upload failed with error". $_FILES['file']['error']));
        }

        $filetemp = $_FILES["file"]["name"];
        $filename = str_replace(" ","_",$filetemp);


        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);
        $ok = false;

        $allowedmimes = array(
          'application/pdf',
          'text/pdf',
          'image/png',
          'image/jpeg');


        if(! (in_array($mime,$allowedmimes)))
          die (err_alert("Unknown file type"));

        
        $ofile = "upload/".$filename;
        $filen = end(explode(".", $_FILES["file"]["name"]));
        $filenam = current(explode(".", $_FILES["file"]["name"]));


        $n= 0;


        
        while (file_exists("upload/" . $filename))
        {

         $filename = $filenam . $n . "." . $filen;
       //echo $filename;
         $n = $n+1;
         $ofile = "upload/".$filename;
       }

       move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $filename);
       $bool_upload = true;
     }
     else 
      err_alert("No Image Uploaded");
  }
  else 
    {
      $ofile = $_POST["img_name"];
      $bool_upload = true;
      
    }


//echo "Stored in: " . "upload/" . $filename."<br>";
//echo "<p>Upload: " . $filename . "<br>";
//echo "Type: " . $_FILES["file"]["type"] . "<br>";
//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br></p>";


//if($check==1)

$t_date = $_POST["t_date"]; // date
$receiver = $_POST["receiver"]; // receiver 
$supplier = $_POST["supplier"];
$product_type = $_POST["product_type"]; // product_type
$inv_num = $_POST["inv_num"];

$product = $_POST["product2"];
$qty = $_POST["qty"];
$namount = $_POST["namount"];
$tad = $_POST["tad"];
$discount = $_POST["discount"];
$tax_rate = $_POST["taxrate"];
$tax = $_POST["tax"];
$amount = $_POST["amount"];

$acc_ref = $_POST["acc_ref"];

print_r($tad);
print_r($discount);
  $conn = new mysqli($GLOBALS['host'],$GLOBALS['dbuser'],$GLOBALS['dbpass'],$GLOBALS['db']);
if ($conn->connect_error)
{
  die('Could not connect: ' . $conn->connect_error);
}
$cnt=1;

foreach($product as $x => $y) {

/*
    echo "product " . $product[$x] . "<br>";
    echo "qty " . $qty[$x] . "<br>";
    echo "namount " . $namount[$x] . "<br>";
    echo "tad " .$tad[$x] . "<br>";
    echo "discount " . $discount[$x] . "<br>";
    echo "tax_rate " . $tax_rate[$x] . "<br>";
    echo "tax " . $tax[$x] . "<br>";
    echo "amount " . $amount[$x] . "<br>";
  
*/

$b_tad = 0;
  if($product[$x]!="") {

    if (isset($tad[$x])){
      $b_tad = 1;
      $tad_cnt++;
    }

    if ($discount[$x]=="") 
	$discount[$x]=0;
    $subquery = "select distinct id from products where product_name='".$product[$x]."' and product_type='".$product_type. "'";
    $subquery = $subquery . " and product_sub_type='".$product_sub_type[$x]."' and supplier='".$supplier."'";

    $myquery="insert into inventory(date,created_date,receiver,product_id,cases,namount,tad,discount,taxrate,tax,amount,invoice_ref,acc_ref,invoice_img_ref) values('";
    $myquery = $myquery . $t_date . "','" . date("Y-m-d") ."','" . $receiver . "',";
    $myquery = $myquery . $product[$x]."," . $qty[$x] . "," . $namount[$x] . "," .$b_tad. ",'";
    $myquery = $myquery . $discount[$x] . "','" . $tax_rate[$x] . "'," . $tax[$x] . "," . $amount[$x] ;
    $myquery= $myquery . ",'" . $inv_num . "','" . $acc_ref . "','" . $ofile . "')";
    echo "<p>" . $myquery  ." </p><br>";


    
    if (!$conn->query($myquery))  {
      unlink($ofile);
      err_alert( "<strong>Insert query failed.</strong> Image also deleted ");
      die('Could not connect: ' . $conn->error);
      
    }
    succ_alert("<strong>Successful</strong> :  ".$cnt++." record added");
    $bool_succ = true;
  }

}

if ($bool_upload==true)
    succ_alert("existing image file used: <strong>" . $ofile . "</strong>");
mysqli_close ($conn);
//}*/


?>

  <br><a href='add_invent.php' class="btn btn-primary" role="button">Add Another Record</a>
</div>
</body>
</html>


