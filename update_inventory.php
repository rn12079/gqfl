<html>
<head>
  <style type="text/css">
    p.info {

      color:grey;
      font-size:10px;

    }

    p.err {

      color:red;
      font-size:10px;

    }

    tr.cells:nth-child(even){
      background-color: #EBF4FA;
    }

    td.number {
      text-align:right; 
      padding-right:10px
    }
    td.bottom {
      background-color: #f0f5f5;
      text-align: right;
      font-size:12px;
      font-weight:bold;
      padding-right: 10px;
      height: 30px;
    }

    td.top {
      background-color: lightgrey;
      text-align: center;
      font-size:16px;
      font-weight:bold;
      height: 30px;
    }

    td {
      text-align: left;
      padding-left: 10px;
      font-size:12px;
      height: 20px;
    }

    label {
      text-align: left;
      padding-left: 10px;
      font-size:12px;
      vertical-align: middle;
    }

    div.tp {
      width:100%;
      height:15%;
      float:left;
      background-color: #b3d9ff;
      text-align:center;
      font-family:Arial;
      color:white;
      font-size:40px;


    }


    div.mp {
      float:left;
      width:100%;
      height:7%;
      background-color:#b3d9ff;
      border-bottom-style:solid;
      border-color:grey;
      border-width: 1px;
      display:flex;
    }

    div.mlp {
      border-radius: 15px 15px 1px 1px;
      cursor:hand;
      padding:10px;
      width:20%;
      float:left;
      background-color:#e6f2ff;
      border-style: solid;
      border-color: grey;
      border-width: 1px;


    }

    div.mcp {
      border-radius: 15px 15px 1px 1px;
      width:20%;
      padding:10px;
      height: 30px;
      float:left;
      background-color:white;
      border-style: solid;
      border-color: grey;
      border-width: 1px;
      border-bottom: none;

      overflow:hidden;
    }

    div.mrp {


      border-radius: 15px 15px 1px 1px;
      width:20%;
      padding:10px;
      float:left;
      background-color:#e6f2ff;
      border-style: solid;
      border-width: 1px;
      border-color: grey;
      cursor:hand;
    }


    div.lp {
      width:100%;
      //border-style: solid;
      border-color: grey;
      background-color: white;
      float:left;
      overflow:hidden;
    }

    div.rp {
      width:50%;
      float:left;

      overflow:hidden;
      text-align:center;
    }

  </style>
</head>
<body onload="init()">
  <div class="tp">
    GQFL Inventory
  </div>
  <div class="mp">

    <div class="mlp"  onclick="window.location='add_product.html'">
      Add New Products
    </div>

    <div class="mcp" onclick="window.location='add_invent.html'">
      Add Inventory Items
    </div>

    <div class="mrp" onclick="window.location='show_inventory.php'">
      Inventory
    </div>


  </div>  
  <!-- end mp div -->

  <div class="lp" id="mlp">
    <br>
    <?php

    if(!isset( $_POST['existing']) )
    {


      $upload_filename = $_FILES["file"]["name"];
      if ($upload_filename!="") {
//echo $upload_filename;
        if($_FILES['file']['error']!== UPLOAD_ERR_OK) {
          die("<p class='err'>Upload failed with error". $_FILES['file']['error']."</p>");
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
          die ("Unknown file type");

        
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
     }
     else 
      echo "<p class='err'>no image uploaded</p>";

  }
  else 
  {
    $ofile = $_POST["img_name"];
    echo "existing image file used: " . $ofile;
  }

//echo "Stored in: " . "upload/" . $filename."<br>";
//echo "<p>Upload: " . $filename . "<br>";
//echo "Type: " . $_FILES["file"]["type"] . "<br>";
//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br></p>";

  
//if($check==1)
$id = $_POST["id"]; // date
$t_date = $_POST["t_date"]; // date
$receiver = $_POST["receiver"]; // receiver 
$qty = $_POST["qty"];
$amount = $_POST["amount"];
$inv_num = $_POST["inv_num"];
$acc_ref = $_POST["acc_ref"];

$retquery = "select i.id,date,receiver,product_name,supplier,product_type,product_sub_type,cases,amount,invoice_ref,acc_ref,invoice_img_ref from products p ";
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

//echo $current;



$myquery = "update inventory set date='";
$myquery = $myquery . $t_date . "', receiver='" . $receiver . "', cases='" . $qty . "', amount='" . $amount;
$myquery = $myquery . "', invoice_ref='" . $inv_num;
$myquery = $myquery . "', acc_ref='" . $acc_ref;
$myquery = $myquery . "',  invoice_img_ref='" . $ofile . "'";
if (isset($_POST["discard"]))
  $myquery = $myquery . ", del=1 ";
$myquery = $myquery . " where id='".$id."'";

//lecho "<p>" . $myquery." </p><br>";



if ($conn->query($myquery) === TRUE) {
  if (isset($_POST["discard"]))
    echo "Record deleted successfully<br>";
  echo "Record updated successfully";
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
  echo "Error updating record: " . $conn->error;
}






$conn->close();
//}/= */
?>

<br> Add another record <input type=button onclick="window.location('add_invent.html')" value="Add new Record">
</body>
</html>


