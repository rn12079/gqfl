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

    <div class="mlp"  onclick="window.location='add_product_main.php'">
      Add New Products
    </div>

    <div class="mcp" onclick="window.location='add_invent.php'">
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
    {$ofile = $_POST["img_name"];
  echo "existing image file used: " . $ofile;
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

$product = $_POST["product"];
$product_sub_type = $_POST["product_sub_type"];
$qty = $_POST["qty"];
$namount = $_POST["namount"];
$tax = $_POST["tax"];
$amount = $_POST["amount"];

$acc_ref = $_POST["acc_ref"];


$conn = new mysqli("localhost","qasim","","mujju");
if ($conn->connect_error)
{
  die('Could not connect: ' . $conn->connect_error);
}
$cnt=1;

foreach($product as $x => $y) {


  /*  echo "product" . $product[$x] . "<br>";
    echo "sub" . $sub[$x] . "<br>";
    echo "qty" . $qty[$x] . "<br>";
    echo "namount" . $namount[$x] . "<br>";
    echo "tax" . $tax[$x] . "<br>";
    echo "amount" . $amount[$x] . "<br>";
  */




  if($product[$x]!="") {


    $subquery = "select distinct id from products where product_name='".$product[$x]."' and product_type='".$product_type. "'";
    $subquery = $subquery . " and product_sub_type='".$product_sub_type[$x]."' and supplier='".$supplier."'";

    $myquery="insert into inventory(date,receiver,product_id,cases,amount,invoice_ref,acc_ref,invoice_img_ref) values('";
    $myquery = $myquery . $t_date . "','" . $receiver . "',(" . $subquery . ")," . $qty[$x] . "," . $amount[$x];
    $myquery= $myquery . ",'" . $inv_num . "','" . $acc_ref . "','" . $ofile . "')";
    //echo "<p>" . $myquery." </p><br>";



    if (!$conn->query($myquery))  {
      unlink($ofile);
      echo "<br><p class='err'> Insert query failed. Image also deleted </p></br>";
      die('Could not connect: ' . $conn->error);
      
    }
    echo "Successful :  ".$cnt++." record added<br>";
  }
}

mysqli_close ($conn);
//}*/
?>

<br> Add another record <input type=button onclick="window.location('add_invent.html')" value="Add new Record">
</body>
</html>


