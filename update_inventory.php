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
<title>GQFL - Update Inventory</title>
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
<script type="text/javascript">
  
</script>

</head>
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
      <strong>Update Inventory Record</strong>
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
       succ_alert("Image file uploaded successfully: <strong>". $filename ."</strong>");
     }
     else 
      warn_alert("Warning! No image uploaded");

  }
  else 
  {
    $ofile = $_POST["img_name"];
    succ_alert("Existing image file used: <strong>" . $ofile . "</strong>");
  }

//echo "Stored in: " . "upload/" . $filename."<br>";
//echo "<p>Upload: " . $filename . "<br>";
//echo "Type: " . $_FILES["file"]["type"] . "<br>";
//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br></p>";

  
//if($check==1)


    $id=$_POST["i_id"];
    //$date=$_POST["date"];
    //$receiver=$_POST["receiver"];
    //$product_id=$_POST["product_id"];
    //$supplier=$_POST["supplier"];
    $del=$_POST["discard"];
    $cases=$_POST["qty"];
    $namount=$_POST["namount"];
    $discount=$_POST["discount"];
    $tad=$_POST["tad"];
    $taxrate=$_POST["taxrate"];
    $tax=$_POST["tax"];
    $amount=$_POST["amount"];
    $invoice_ref=$_POST["inv_num"];
    $acc_ref=$_POST["acc_ref"];
    $invoice_img_ref=$_POST["invoice_img_ref"];

    print_r($tad);

$upd_cnt = 0;

while($upd_cnt < count($id)) {
  $b_taf=0;

  $retquery = "select i.id,date,receiver,product_name,supplier,cases,amount,invoice_ref,acc_ref,invoice_img_ref from products p ";
  $retquery = $retquery . "inner join inventory i on i.product_id=p.id where i.id='".$id."'";

  $conn = new mysqli("localhost","qasim","","mujju");
  if ($conn->connect_error)
  {
    die('Could not connect: ' . $conn->connect_error);
  }

  // updating log file , putting old data
  $result = $conn->query($retquery);
  $row = $result->fetch_assoc();
  $logfile = 'log.txt';

  $current = file_get_contents($logfile);

  $current = $current. "old="."\t".$row["date"]."\t".$row["receiver"]."\t".$row["product_name"]."\t".$row["supplier"]."\t".$row["product_type"];
  $current = $current."\t".$row["product_sub_type"]."\t".$row["cases"]."\t".$row["amount"]."\t".$row["invoice_ref"]."\t".$row["acc_ref"]."\t".$row["invoice_img_ref"];
  $current = $current."\n";


    

    if(isset($tad[$upd_cnt]))
      $b_taf=1;

  $myquery = "update inventory set cases='" .$cases[$upd_cnt]. "', namount='".$namount[$upd_cnt]."'";
  $myquery = $myquery . ", discount='" . $discount[$upd_cnt] . "', tad='" . $b_taf . "', taxrate='" . $taxrate[$upd_cnt] . "', tax='" . $tax[$upd_cnt]. "', amount='".$amount[$upd_cnt];
  $myquery = $myquery . "', invoice_ref='" . $invoice_ref;
  $myquery = $myquery . "', acc_ref='" . $acc_ref;
  $myquery = $myquery . "',  invoice_img_ref='" . $ofile . "'";
  if (isset($del[$upd_cnt]))
    $myquery = $myquery . ", del=1 ";
  $myquery = $myquery . " where id='".$id[$upd_cnt]."'";

  echo $myquery;
  


  if ($conn->query($myquery) === TRUE) {
    if (isset($del[$upd_cnt]))
      warn_alert("1 Record deleted successfully");
    else
      succ_alert("1 Record updated successfully");
    $result = $conn->query($retquery);
    $row = $result->fetch_assoc();
    
    if (isset($_POST["discard"]))
      $current=$current."del=";
    else 
      $current=$current."new=";

    
    $current = $current."\t".$row["date"]."\t".$row["receiver"]."\t".$row["product_name"]."\t".$row["supplier"]."\t".$row["product_type"];
    $current = $current."\t".$row["product_sub_type"]."\t".$row["cases"]."\t".$row["amount"]."\t".$row["invoice_ref"]."\t".$row["acc_ref"]."\t".$row["invoice_img_ref"];
    $current = $current."\n\n";
    file_put_contents($logfile, $current);
    


} else {
  err_alert("Error updating record: " . $conn->error);
}

  




$conn->close();
//}/= */
$upd_cnt++;
}

?>

<a href='show_inventory.php' class="btn btn-primary" role="button">Inventory</a>
</div>


</body>
</html>


