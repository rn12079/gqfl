<html>
<head>
  <meta charset="utf-8"> 
  <style type="text/css">
    p.info {

      color:grey;
      font-size:10px;

    }

    p.err {

      color:red;
      font-size:10px;

    }

    i {
      color:red;
      

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
      float:left;
      background-color:#e6f2ff;
      border-style: solid;
      border-color: grey;
      border-width: 1px;
      //border-bottom: none;

      //overflow:hidden;
    }

    div.mcrp {
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
      width:50%;
      //border-style: solid;
      border-color: grey;
      background-color: white;
      float:left;
      padding: 10px;
      overflow:hidden;
    }

    div.rp {
      //width:50%;
      float:left;
      //border-style: solid;
      padding: 10px;
      overflow:hidden;
      text-align:center;
    }
  </style>
  <script type="text/javascript">


/*
Ajax block to get items for search tab
*/
function ret_inv_items(retfields){
  var supplier = document.getElementById("slist").value;
  var receiver = document.getElementById("receiver").value;
  var invoice_ref = document.getElementById("inv_num").value;
  var id = document.getElementById("id").value;


  var myjson = {"retfield":escape(retfields), "supplier":escape(supplier),"receiver":escape(receiver),"invoice_ref":escape(invoice_ref),"id":escape(id)};
  data_params = JSON.stringify(myjson);

 // document.getElementById("stats").innerHTML = "ret_field"+ myjson.ret_field+" ptype: " + myjson.ptype + " pname: " + myjson.pname + " supp: " + myjson.supplier + " stype: " + myjson.stype;

 
 var xhr;
 if(window.XMLHttpRequest){
  xhr=new XMLHttpRequest();
} 
  else if (window.ActiveXObject) { // IE 8 and older  
    xhr = new ActiveXObject("Microsoft.XMLHTTP");  
  } 

  if(retfields=="all_fields")
    update = "rp";
  else
    update = "imglist";

  xhr.open("POST","ret_inv_items.php",true);
  xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  xhr.send("x="+data_params);
  xhr.onreadystatechange = display_data;
  function display_data(){
    if(xhr.readyState==4){
      if(xhr.status == 200) {
        
        document.getElementById(update).innerHTML = xhr.responseText;
        
      }
      else
      {
        alert('There was a problem with the request');
      }
      
    }
    
  }
  /*end Ajax block*/
  
}


function validate_product(){
  var options = document.getElementById("plist").options;
  var result = false;
  var value = document.getElementById("product").value;
  for(var i = 0 ; i < options.length ; i++ )
   if(document.getElementById("product").value == options[i].value) 
    result = true;

  if(!result){
   document.getElementById("txt_valid").innerHTML = "Product Name not Valid, Enter a Valid Product or Create a new product";
   document.getElementById("submit").disabled = true;
 } else
 {
   document.getElementById("txt_valid").innerHTML = "";
   document.getElementById("submit").disabled = false;
   ajaxsearch("supplier",value);
   

   
 }




}


function init(){
  document.getElementById("mcp").style.display = 'none';
  ajaxsearch("product_type","");
  ajaxsearch("product_name","Food");
}

function pop_products(){
  var filter=document.getElementById("tlist").value;
  ajaxsearch("product_name",filter);
}

function pop_subtype(){
  ajaxsearch("product_sub_type","");
}


function changeimage(fileInput) {
  var files = fileInput.files;
  for (var i = 0; i < files.length; i++) {           
    var file = files[i];
    var imageType = /image.*/;     
    if (!file.type.match(imageType)) {
      continue;
    }           
    var img=document.getElementById("img_path");            
    img.file = file;    
    var reader = new FileReader();
    reader.onload = (function(aImg) { 
      return function(e) { 
        aImg.src = e.target.result; 
      }; 
    })(img);
    reader.readAsDataURL(file);
  }    
}

function validate_form(){
  var t_date = document.getElementById("t_date").value;
  var t_prod = document.getElementById("product").value;
  var t_cases = document.getElementById("num_cases").value;
  var t_amount = document.getElementById("amount").value;
  var t_inv_num = document.getElementById("inv_num").value;
  
  if(t_date==""||t_prod==""||t_cases==""||t_amount==""||t_inv_num=="")
  {
    document.getElementById("show_status").innerHTML = "Some fields are empty";
    return false;
  }
}

function dis_card(){
  if(document.getElementById("discard").checked)
    alert("are you sure?");

}

function useexisting(){
  if(document.getElementById("existing").checked)
  {
    document.getElementById("file").disabled = true;
    ret_inv_items("inv_ref");
  }
  else
    document.getElementById("file").disabled = false;


}

function ret_inv_item(){

  ret_inv_items("all_fields");
}

document.onreadystatechange = function(){
 if(document.readyState === 'complete'){
  ret_inv_item();  
}
}
</script>
</head>
<body onload="init()">
  <div class="tp">
    GQFL Inventory
  </div>
  <div class="mp">

    <div class="mlp" onclick="window.location='add_product.html'">
      Add New Products
    </div>

    <div class="mcp" onclick="window.location='add_invent.html'">
      Add Inventory Item
    </div>
    <div class="mcrp">
      Edit Inventory Item
    </div>
    <div class="mrp" onclick="window.location='show_inventory.php'">
      Inventory
    </div>


  </div>  
  <!-- end mp div -->

  <div class="lp" id="mlp" >
    <br>

    <?php

    $get_id = $_GET['id'];

    if($get_id=="")
      die("no record to edit");


    $myquery = "select i.id,date,receiver,product_name,supplier,product_type,product_sub_type,cases,amount,acc_ref,invoice_ref,invoice_img_ref from products p ";
    $myquery = $myquery . "inner join inventory i on i.product_id=p.id ";
    $myquery = $myquery . "where i.id=".$get_id;


    $conn = new mysqli("localhost","qasim","","mujju");
    if ($conn->connect_error)
    {
      die('Could not connect: ' . $conn->connect_error);
    }

    $result = $conn->query($myquery);
    $row = $result->fetch_assoc();

    $date=$row["date"];
    $receiver=$row["receiver"];
    $product_name=$row["product_name"];
    $supplier=$row["supplier"];
    $product_type=$row["product_type"];
    $product_sub_type=$row["product_sub_type"];
    $cases=$row["cases"];
    $amount=$row["amount"];
    $invoice_ref=$row["invoice_ref"];
    $acc_ref=$row["acc_ref"];
    $invoice_img_ref=$row["invoice_img_ref"];





    ?>


    Edit Inventory Record 
    <form action="update_inventory.php" method="post" onsubmit="return validate_form()" enctype="multipart/form-data" style="font-size:8pt">
      <table border="1px" >
        <input type="hidden" name="id" id="id" value="<?php echo $get_id;?>">
        <tr>
          <td><label for="Receiving Date">Date </label></td><td>  <input type="date" name="t_date" id ="t_date" value="<?php echo $date;?>"></td>
        </tr>
        <tr>
          <td>
            <label for="Receiver">Received by </label> </td> 
            <td><select name="receiver" id="receiver" onchange="ret_inv_item()">
              <option value="Warehouse" <?php if ($receiver=="Warehouse") echo "selected";?> > Warehouse</option>
              <option value="Shehbaz" <?php if ($receiver=="Shehbaz") echo "selected";?> >Shehbaz</option>
              <option value="Hydri" <?php if ($receiver=="Hydri") echo "selected";?> >Hydri</option>
            </select>
          </td>
        </tr>
        <tr>
          <td><label for="Product Type">Product Type </label> </td>
          <td><input name="product_type" id="tlist" value="<?php echo $product_type;?>" placeholder="Select Supplier" align="right" style="width: 100px"  onchange="pop_products()" readonly="readonly"> <i> *  <i>

          </td>
        </tr>
        <tr>
          <td>    
            <label for="Product Name">Product Name </label> </td> 
            <td><input list="plist" placeholder="Product" id="product" value="<?php echo $product_name;?>" name="product" onchange="validate_product()" autocomplete="off" readonly="readonly"><i> *  <i>
             <datalist id="plist">
             </datalist>  <div id ="txt_valid" style="color:red" ></div>
           </td>
         </tr>
         <tr>
          <td><label for="Supplier">Supplier </label> </td>
          <td><input name="supplier" id="slist" placeholder="Select Supplier" style="width: 100px" onclick="pop_subtype()" value="<?php echo $supplier;?>" readonly="readonly"><i> *  <i>
            
          </td>
        </tr>

        <tr>
          <td><label for="Product Sub Type">Product Sub Type </label> </td>
          <td><input type="text" id="product_sub_type" name="product_sub_type" placeholder="Product sub type" value="<?php echo $product_sub_type;?>" readonly="readonly" onclick="pop_subtype()" readonly="readonly"><i> *  <i>
          </td>
        </tr>

        <tr>
          <td><label for="Cases">Cases </label> </td>
          <td><input type="number" id="num_cases" name="qty" placeholder="e.g. 1" value="<?php echo $cases;?>"></td>
        </tr>
        
        <tr>      
          <td><label for="Amount">Amount </label> </td>
          <td><input type="number" id="amount" name="amount" placeholder="e.g. 1000.24" value="<?php echo $amount;?>"></td>
        </tr>

        <tr>
          <td><label for="Invoice_ref">Invoice Number </label> </td>
          <td><input type="text" id="inv_num" name="inv_num" placeholder="e.g. INV-0101" value="<?php echo $invoice_ref;?>" onchange="ret_inv_items()"></td>
        </tr>

        <tr>
          <td><label for="Invoice_ref">Acc Img ref </label> </td>
          <td><input type="text" id="acc_ref" name="acc_ref" placeholder="5-701" value="<?php echo $acc_ref;?>"></td>
        </tr>
        
        <tr>
          <td><label for="file">Invoice File : </label></td>
          <td>
            use existing <input type="checkbox" name="existing" id="existing"  onchange="useexisting()"/> <input list="imglist" name="img_name" value="<?php echo $invoice_img_ref;?>">
            <datalist id="imglist">
            </datalist>
            <?php echo "<a target='_blank' href='".$invoice_img_ref."'> invoice  </a>   ";?>
            

            <input type="file" accept="image/*" name="file"  id="file" onchange="changeimage(this)"></td>
          </tr>
          <tr>
            <td><label for="file">Discard Record </label></td>
            <td><input type="checkbox" name="discard" id="discard" onclick="dis_card()" ></td>
          </tr>
          
          <tr>
            <td colspan="2"><input type="submit" name="submit" id="submit" value="Modify Product"></td>
          </tr>

        </table>
      </form>
      <p id="show_status" class="error"></p>
      
      <i> * : can't be modified  </i>
      
      
    </div>
    <div id="rp" class="rp"></div>

  </body>
  </html>