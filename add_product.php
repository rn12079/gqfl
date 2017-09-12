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

    div.mcp {
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

    div.mrp {

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
    $txt_name = $_POST["txt_name"]; 
    $txt_type = $_POST["txt_type"];
    $txt_sub_type = $_POST["txt_sub_type"]; 
    $txt_supplier = $_POST["txt_supplier"];
//echo "txt ".$txt_name;
    if ($txt_name=="") die("error, no product name");
    /* add supplier already exist check */
    $chkquery = "select count(*) as cnt from products where";
    $chkquery = $chkquery. " product_name='".$txt_name."' and product_sub_type='".$txt_sub_type."' and supplier='".$txt_supplier."'";

//echo "<p>" . $chkquery." </p><br>";

    /*   */ 

    /* query for adding supplier */

    $myquery="insert into products(product_name,product_type,product_sub_type,supplier) values('";
    $myquery = $myquery . $txt_name . "','" . $txt_type . "','" . $txt_sub_type . "','" . $txt_supplier . "');";
//echo "<p>" . $myquery." </p><br>";

$conn = new mysqli("localhost","qasim","","mujju");
if ($conn->connect_error)
{
  die('Could not connect: ' . $conn->connect_error);
}

    /* check and stop if product already exists */
    $check = $conn->query($chkquery);
    $check1 = $check->fetch_assoc();
    $check2 = $check1['cnt'];
//echo "check == $check2";

    /* if product is not added continue to add else block */
    if($check2==0){
      if (!$conn->query($myquery))  {
        echo "<br><p class='err'> Insert query failed.</br>";
        die('Error: ' . $conn->error);
        
      }
      echo "Product Name :  ". $txt_name . "<br>";
      echo "Product Type :  ". $txt_type . "<br>";
      echo "Product Sub Type :  ". $txt_sub_type . "<br>";
      echo "Supplier :  ". $txt_supplier . "<br>";
      echo "<br><br>Successful :  1 record added ";
    }
    else {
     echo "<p class='err'>Product already added</p>";
     echo "<p class='err'>".$myquery."</p>";
   }
   mysqli_close ($conn);

   ?>
 </div>
 <br> Add another record <input type=button onclick="window.location('add_product_main.php')" value="Add new Record">
</body>
</html>

