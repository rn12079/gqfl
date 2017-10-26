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

if((!$_SESSION["loggedin"])||($_SESSION['user']!="admin")) 
  $logged = false;
else
  $logged = true;
  echo $_SESSION['user'];

?>


<html>
<head>
<link href="select2/select2.min.css" rel="stylesheet" />
<link href="bootstrap1/css/bootstrap.min.css" rel="stylesheet">
<title>GQFL - Edit Inventory</title>
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
$(document).ready(function() {

  $('.product2').select2();
  compute_totals();

  document.getElementById("myform").addEventListener("change", function() {
    console.log("form changed");
    compute_rows();
  });


});

function pop_products2(){
  $('.product2').select2({
    width: '200px',
    placeholder: "select a product",
    ajax: {
      url: 'retrieve_rows_jq.php',
      dataType: 'json',
      cache: true,
      data: function(params) {
        return {
          q: params.term,
          s: $('#supplier2').val()
        };


      },


      processResults: function(data){

        return {
          results: $.map(data, function(obj) {
            console.log(obj);
            return {
              id: obj.id,
              text: obj.text + " || " + obj.hint
            };

          })
        };


      }
    }
  });

}
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

function del_alert(box){
  var row = box.parentNode.parentNode;
  console.log("del_alert")
  if(box.checked) 
    row.className = "danger";
  else
    row.className = "";
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

function compute_rows(){
  console.log("focus out");
  var qty = document.getElementsByName("qty[]");
  var nam = document.getElementsByName("namount[]");
  var disc = document.getElementsByName("discount[]");
  var tax = document.getElementsByName("taxrate[]");
  var tam = document.getElementsByName("tax[]");
  var am = document.getElementsByName("amount[]");
  var tad = document.getElementsByName("tad[]");
  var up = document.getElementsByName("unitprice[]");

  console.log(nam.length);
  //console.log(nam[0].value);


  for(i = 0 ; i < nam.length ; i++) {
    var d = "tad["+i+"]";
    console.log(d);
    if(document.getElementById(d).checked == false)
      tam[i].value = Math.round(parseFloat(nam[i].value*tax[i].value) * 100) / 100;
    else
      tam[i].value = Math.round(parseFloat((nam[i].value-disc[i].value)*tax[i].value) * 100) / 100;

    am[i].value = Math.round((parseFloat(nam[i].value - disc[i].value) + parseFloat(tam[i].value)) * 100) / 100;
    up[i].value = Math.round(parseFloat(nam[i].value - disc[i].value) / parseFloat(qty[i].value) * 100) / 100;
  }

  compute_totals();  
  
}

function compute_totals(){
  var nam = document.getElementsByName("namount[]");
  var disc = document.getElementsByName("discount[]");
  var tax = document.getElementsByName("tax[]");
  var am = document.getElementsByName("amount[]");
  
  var nettotal = 0;
  var netdisc = 0;
  var nettax = 0;
  var invtotal = 0;

    for(i = 0 ; i < nam.length ; i++) {

      nettotal = parseInt(nettotal) + parseInt(nam[i].value || 0);
      netdisc = parseInt(netdisc) + parseInt(disc[i].value || 0);
      nettax = parseInt(nettax) + parseInt(tax[i].value || 0);
      invtotal = parseInt(invtotal) + parseInt(am[i].value||0);
    }

  document.getElementById("sub_total").value = nettotal;
  document.getElementById("sub_total_disc").value = netdisc;
  document.getElementById("sub_total_tax").value = nettax;
  document.getElementById("inv_total").value = invtotal;

}

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
      <strong>Edit Inventory Record</strong>
   </div>
 </div>


 <div class="container">
    <form id="myform" action="update_inventory.php" method="post" enctype="multipart/form-data">
    <?php

    $get_id = $_GET['id'];

    if($get_id=="")
      die(err_alert("no record to edit"));


    

    $conn = new mysqli($GLOBALS['host'],$GLOBALS['dbuser'],$GLOBALS['dbpass'],$GLOBALS['db']);
  if ($conn->connect_error)
    {
      die(err_alert('Could not connect: ' . $conn->connect_error));
    }

    $subquery = "select date,receiver,supplier,invoice_ref from products p inner join inventory i ";
    $subquery = $subquery. " on i.product_id=p.id where i.id=".$get_id;

    //echo $subquery;

    if($result = $conn->query($subquery))
      $row = $result->fetch_assoc();
    else echo $conn->error;
     

    $fildate = $row["date"];
    $filreceiver = $row["receiver"];
    $filsupplier = $row["supplier"];
    $filinvoice = $row["invoice_ref"];

    $myquery = "select i.id,date,receiver,product_id,product_name ,coalesce(concat(casesize,units,' | ',maker),'na') as hint,supplier,product_type,product_sub_type,cases,tad,amount,truncate((namount-discount)/cases,2) as unitprice, namount,discount,taxrate,tax,acc_ref,invoice_ref,invoice_img_ref from products p ";
    $myquery = $myquery . "inner join inventory i on i.product_id=p.id ";
    $myquery = $myquery . "where i.date='".$fildate."' ";
    $myquery = $myquery . "and i.receiver='".$filreceiver."' ";
    $myquery = $myquery . "and p.supplier='".$filsupplier."' ";
    $myquery = $myquery . "and i.invoice_ref='".$filinvoice."' ";
    $myquery = $myquery . "and i.del!=1 ";

    //echo $myquery."<br>";
    


    $run=0;

    if($result = $conn->query($myquery)) {
      $row = $result->fetch_assoc();
    
    $id=$row["id"];
    $date=$row["date"];
    $receiver=$row["receiver"];
    $product_id=$row["product_id"];
    $supplier=$row["supplier"];
    $cases=$row["cases"];
    $amount=$row["amount"];
    $invoice_ref=$row["invoice_ref"];
    $acc_ref=$row["acc_ref"];
    $invoice_img_ref=$row["invoice_img_ref"];
    
  
    }



    ?>

    

        <input type="hidden" name="id" id="id" value="<?php echo $get_id;?>">
        
        <div class="row">
          <div class="col-sm-2 form-group form-inline">
            <label for="Receiving Date">Date </label>  
          </div>
      
          <div class="col-sm-3 form-group">
            <input class="form-control" type="date" name="t_date" id ="t_date" value="<?php echo $date;?>" readonly="readonly">
          </div>
        </div>

        <div class="row">

        <div class="col-sm-2 form-group form-inline">
          <label for="Invoice_ref">Invoice #</label>
        </div>        
        <div class="col-sm-3 form-group ">
          <input  class="form-control" type="text" id="inv_num" name="inv_num" value="<?php echo $invoice_ref;?>" readonly="readonly">
        </div>

      </div>
       
      <div class="row">
        
        <div class="col-sm-2 form-group form-inline">
          <label for="Supplier">Supplier </label>
        </div>

        <div class="col-sm-3 form-group ">
          <select class="form-control" id="supplier2" name="supplier2" readonly="readonly">
            <option value="<?php echo $supplier;?>" selected><?php echo $supplier;?></option>
          </select>
        </div>
        
      </div> 
        <div class= "row">

        <div class="col-sm-2 form-group form-inline">
            <label for="Receiver">Received by </label>
        </div>

        <div class="col-sm-3 form-group">
          <select class="form-control" name="receiver" id="receiver" readonly="readonly">
            <?php 
                 echo "<option value='" . $receiver."' selected>".$receiver."</option>";
            ?>
          </select>
        </div>
      
      </div>

      <table class="table table-striped table-bordered table-condensed" id="rec_items" style="margin-bottom: 4px">
        <tr>
          <th>Del </th>
          <th>Product Name</th>
          <th>Cases</th>
          <th>Net Amount</th>
          <th>TAD</th>
          <th>Discount</th>
          <th>Tax Rate</th>
          <th>Unit Price</th>
          <th>Tax Amount</th>
          <th>Gross Amount</th>
          <th>*</th>
        </tr>
        <?php 
        $cnt=0;

        if($result = $conn->query($myquery)) {
          if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
    
    
        
            $id=$row["id"];
            $date=$row["date"];
            $receiver=$row["receiver"];
            $product_id=$row["product_id"];
            $supplier=$row["supplier"];
            $cases=$row["cases"];
            $namount=$row["namount"];
            $up=$row["unitprice"];
            $discount=$row["discount"];
            $tad=$row["tad"];
            $taxrate=$row["taxrate"];
            $tax=$row["tax"];
            $amount=$row["amount"];
            $invoice_ref=$row["invoice_ref"];
            $acc_ref=$row["acc_ref"];
            $invoice_img_ref=$row["invoice_img_ref"];
        
    ?>
        <tr>

            <td>
              <input type="hidden" name="i_id[]" value="<?php echo $id;?>">
              <input type="checkbox" name="discard[<?php echo $cnt; ?>]" onchange="del_alert(this)" >
            </td>
            <td>
             
                <select name="product2[]" class="product2" required> 
                  <option value="<?php echo getprods($product_id); ?>" selected> <?php echo getprods($product_id); ?> </option>
                </select>
            </td>

            <td>
              <input class="col-xs-1 form-control" type="number" id="num_cases" step=".01" name="qty[]" value="<?php echo $cases; ?>" >
            </td>

            <td>
              <input class="col-xs-1 form-control" type="number" id="namount" step=".01" name="namount[]" value="<?php echo $namount; ?>">
            </td>

            <td>
              <input type="checkbox" name="tad[<?php echo $cnt; ?>]" id="tad[<?php echo $cnt; ?>]" <?php if ($tad==1) echo "checked" ?>>
            </td>

            <td>
              <input class="col-xs-1 form-control" type="number" id="discount" step=".01" name="discount[]" value="<?php if ($discount=="") echo 0; else echo $discount; ?>">
            </td>

            <td><input class="col-xs-1 form-control" type="number" id="taxrate" step=".01" name="taxrate[]"  value="<?php if ($taxrate=="") echo 0; else echo $taxrate; ?>"onblur="compute_rows()"></td>

            <td>
                <input class="col-xs-1 form-control" type="number" id="unitprice" step=".01" name="unitprice[]" value="<?php echo $up; ?>" readonly="readonly">
            </td>

            <td><input class="col-xs-1 form-control" type="number" id="tax" name="tax[]" value="<?php echo $tax ?>" onclick="compute_val(this)" readonly="readonly"></td>

            <td><input class="col-xs-1 form-control" type="number" id="amount" name="amount[]" value="<?php echo $amount ?>" onclick="compute_val(this)" readonly="readonly"></td>
          
          <td></td>

        </tr>

          <?php $cnt++; } ?>
        </table>

        <br>
        
          
          <div class="col-xs-2 form-inline">
            <label for="Invoice_ref">Account Img ref </label>
          </div>
          <div class="col-xs-3 form-group">
            <input class="form-control" type="text" id="acc_ref" name="acc_ref" value="<?php echo $acc_ref ?>">
          </div>
            
        
          <div class="col-xs-4 form-inline text-right"> 
            <label for="Invoice_ref">Sub Net Total: </label>
          </div>
          <div class="col-xs-3 form-group text-right"> 
            <input class="form-control" type="number" id="sub_total" name="sub_total" placeholder="auto" onclick="compute_totals()" readonly="readonly">
          </div>
          
          <div class="col-xs-2 form-group form-inline">
            <label for="file">Invoice File : </label>
          </div>

          <div class="col-xs-3 form-group ">
            <input type="checkbox" name="existing" id="existing"  onchange="useexisting()"
             <?php if ($invoice_img_ref!='') echo "checked"; ?> /> use existing 
            <input class="form-control" type="file" accept="image/*" name="file" id="file">
            <input class="form-control" list="imglist" name="img_name" value="<?php echo $invoice_img_ref;?>">
              <datalist id="imglist">
              </datalist>
          </div>

          <div class="col-xs-4 form-group text-right"> 
            <label for="Invoice_ref">Sub Total Disc: </label>
          </div>

          <div class="col-xs-3 form-group text-right"> 
            <input class="form-control" type="number" id="sub_total_disc" name="sub_total_disc" placeholder="auto" onclick="compute_totals()" readonly="readonly"></td>
          </div>
          <div class="col-xs-4 form-group text-right"> 
            <label for="Invoice_ref">Sub Total Tax: </label>
          </div>
          <div class="col-xs-3 form-group text-right"> 
            <input class="form-control" type="number" id="sub_total_tax" name="sub_total_tax" placeholder="auto" onclick="compute_totals()" readonly="readonly"></td>
          </div>

          <div class="col-xs-4 form-group text-right"> 
            <label for="Invoice_ref">Invoice Total: </label>
          </div>
          <div class="col-xs-3 form-group text-right"> 
            <input class="form-control" type="number" id="inv_total" name="inv_total" placeholder="auto" onclick="compute_totals()" readonly="readonly"></td>
          </div>
        
          
          <div class="row">
          <div class="col-sm-3">
            <input class="btn btn-warning" type="submit" name="submit" id="submit" value="Modify Records" >
            <a href='show_inventory.php' class="btn btn-primary" role="button">Cancel</a>
          </div>

          

        </div>

        
      </form>
    
      
      
      
    <?php 

  }

    }
    else
      err_alert($conn->error);

 ?>
      
    </div>
  
  </body>
  </html>
