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




 <script type="text/javascript">

  $(document).ready(function() {
   
    $("#supplier2").select2({
      data: <?php echo getsups();  ?>
    })

   

document.getElementById("myform").addEventListener("change", function() {
  console.log("form changed");
  compute_rows();

});

})
 

/*
Ajax block to get items for search tab
*/
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
  


function ajaxsearch(myitem,filter,subfield){
  //var prod_code = document.getElementById("product1").value;
  var xhr;
  if(window.XMLHttpRequest){
    xhr=new XMLHttpRequest();
  } 
  else if (window.ActiveXObject) { // IE 8 and older  
    xhr = new ActiveXObject("Microsoft.XMLHTTP");  
  } 

  var data="retrieve="+myitem+"&filter="+filter;
  if(myitem=="product_name"){
    var supplier= document.getElementById("slist").value;
    data = data + "&supplier="+escape(supplier);
    //alert(data);
  }
  
  xhr.open("POST","retrieve_product.php",true);
  xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  
  var update;
  if(myitem=="product_type")
    update="tlist";
  if(myitem=="product_name")
   update="plist";
 if(myitem=="supplier") 
   update="slist";
 if(myitem=="product_sub_type"){
  update="product_sub_type";
  var prod= document.getElementById("product").value;
    if(subfield!==undefined)
    {
      
        // check row index from <tr> tag .
      var row = subfield.parentNode.parentNode.rowIndex;
      //alert(subfield.parentNode.parentNode.nodeName);
      
      var table = document.getElementById("rec_items");
      prod=table.rows[row].cells[1].children[0].value;
       
  }

  var type= document.getElementById("tlist").value;
  var supplier= document.getElementById("slist").value;
  data = "retrieve="+escape(myitem)+"&filter="+escape(prod)+"&supplier="+escape(supplier)+"&type="+escape(type);
   
  }

  xhr.send(data);
  xhr.onreadystatechange = display_data;

  function display_data(){
    if(xhr.readyState==4){
      if(xhr.status == 200) {
        
        if(myitem=="product_sub_type")
          subfield.value =  xhr.responseText;
        else
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

function ret_inv_items(){
  var supplier = document.getElementById("slist").value;
  var receiver = document.getElementById("receiver").value;
  var invoice_ref = document.getElementById("inv_num").value;
  var id = "";


  var myjson = {"supplier":escape(supplier),"receiver":escape(receiver),"invoice_ref":escape(invoice_ref),"id":escape(id)};
  data_params = JSON.stringify(myjson);

 // document.getElementById("stats").innerHTML = "ret_field"+ myjson.ret_field+" ptype: " + myjson.ptype + " pname: " + myjson.pname + " supp: " + myjson.supplier + " stype: " + myjson.stype;

 
 var xhr;
 if(window.XMLHttpRequest){
  xhr=new XMLHttpRequest();
} 
  else if (window.ActiveXObject) { // IE 8 and older  
    xhr = new ActiveXObject("Microsoft.XMLHTTP");  
  } 

  xhr.open("POST","ret_inv_items.php",true);
  xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  xhr.send("x="+data_params);
  xhr.onreadystatechange = display_data;
  function display_data(){
    if(xhr.readyState==4){
      if(xhr.status == 200) {
        
        document.getElementById("rp").innerHTML = xhr.responseText;
        
      }
      else
      {
        alert('There was a problem with the request');
      }
      
    }
    
  }
  /*end Ajax block*/
  
}

function inits(){
  console.log("init initiated");
  pop_products2();
  document.getElementById("count").innerHTML = 1;
}

function validate_form(){
  var t_date = document.getElementById("t_date").value;
  var t_prod = $(product2).val();
  console.log(t_prod);
  return false;
  var t_cases = document.getElementById("num_cases").value;
  var t_amount = document.getElementById("amount").value;
  var t_inv_num = document.getElementById("inv_num").value;
  
  if(t_date==""||t_prod==""||t_cases==""||t_amount==""||t_inv_num=="")
  {
    document.getElementById("show_status").innerHTML = "Some fields are empty";
    return false;
  }
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

function ret_inv_items(retfields){
  var supplier = document.getElementById("supplier2").value;
  var d_date = document.getElementById("t_date").value;
  var receiver = document.getElementById("receiver").value;
  var invoice_ref = document.getElementById("inv_num").value;
  var id = "";


  var myjson = {"retfield":escape(retfields), "supplier":escape(supplier),"receiver":escape(receiver),"invoice_ref":escape(invoice_ref),"id":escape(id),"d_date":escape(d_date)};
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

function newrow(){
  var table = document.getElementById("rec_items");
  var rowcount = table.rows.length;
  
  //alert(rowcount);

  var row = table.insertRow(rowcount);
  var colcount = table.rows[0].cells.length;

  //alert(colcount);

  for(var i =0 ; i < colcount ; i++) {
    var newcell = row.insertCell(i);
    if(i===0) 
      newcell.innerHTML = rowcount;
    else if (i===1)
      newcell.innerHTML = "<select name='product2[]' class='product2'></select>";
    else if (i===10)
        newcell.innerHTML = "<span class='glyphicon glyphicon-remove text-danger' onclick='remove_row(this)'></span>";
    else if (i===4) 
        newcell.innerHTML = "<input type='checkbox' name='tad["+(rowcount-1)+"]' id='tad["+(rowcount-1)+"]'>"; 

    else

      newcell.innerHTML = table.rows[1].cells[i].innerHTML;
    

  }
  pop_products2();
  compute_totals();

}

function remove_row(field){
  var x = field.parentNode.parentNode.remove();
  compute_totals();


}

function compute_val(t_field){
  var row = t_field.parentNode.parentNode.rowIndex;
  var res;
  var fld = ($(t_field).attr("name"));
  console.log(row);
      
var table = document.getElementById("rec_items");

var chk=table.rows[row].cells[4].children[0].checked;
var nam=table.rows[row].cells[3].children[0].value;
var disc=table.rows[row].cells[5].children[0].value;
var tr=table.rows[row].cells[6].children[0].value;
var tam=table.rows[row].cells[7].children[0].value;

if (fld=="tax[]") {
  if(chk==true)
    res=parseInt((nam-disc)*tr);
  else
    res=parseInt((nam)*tr);
}
else {
  res = parseInt(nam-disc+parseInt(tam));
}

console.log(res);

t_field.value=res;
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
<body onload="inits()">
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
      <strong>New Inventory Record</strong>
   </div>
 </div>


 <div class="container">


    <form id="myform" action="add_inventory2.php" method="post" enctype="multipart/form-data">
      
      <div class="row">
        <div class="col-sm-2 form-group form-inline">
          <label for="Receiving Date">Date </label>  
        </div>
      
        <div class="col-sm-3 form-group">
          <input class="form-control" type="date" name="t_date" id ="t_date" required>
        </div>

      </div>

      <div class="row">

        <div class="col-sm-2 form-group form-inline">
          <label for="Invoice_ref">Invoice #</label>
        </div>        
        <div class="col-sm-3 form-group ">
          <input  class="form-control" type="text" id="inv_num" name="inv_num" placeholder="e.g. INV-0101" onchange="ret_inv_item()" required>
        </div>

      </div>

      <div class="row">
        
        <div class="col-sm-2 form-group form-inline">
          <label for="Supplier">Supplier </label>
        </div>

        <div class="col-sm-3 form-group ">
          <select class="form-control" id="supplier2" name="supplier2"   onchange="pop_products2()">
          </select>
        </div>
        
      </div>
      
      <div class= "row">

        <div class="col-sm-2 form-group form-inline">
            <label for="Receiver">Received by </label>
        </div>

        <div class="col-sm-3 form-group">
          <select class="form-control" name="receiver" id="receiver">
            <?php 
              $locs = getlocs();
              //print_r($locs);
              foreach($locs as $x => $y)
                 echo "<option value='".($y['name'])."'>".($y['name'])."</option>";
            ?>
          </select>
        </div>
      
      </div>
    
      <table class="table table-striped table-bordered table-condensed" id="rec_items" style="margin-bottom: 4px">
        <tr>
          <th> # </th>
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
        

          <tr>

            <td>
              <div id="count"></div>
            </td>

            <td>
             
                <select name="product2[]" class="product2" required> </select>
             
            </td>
            
            <td>
              <input class="col-xs-1 form-control" type="number" id="num_cases" name="qty[]" placeholder="1" required="" >
            </td>
           

          

            <td>
              <input class="col-xs-1 form-control" type="number" id="namount" step=".01" name="namount[]" placeholder="1000.24" required>
            </td>
        
            <td>
              <input type="checkbox" name="tad[]" id="tad[0]">
            </td>
            <td>
              <input class="col-xs-1 form-control" type="number" id="discount" step=".01" name="discount[]" value="0">
            </td>

            <td><input class="col-xs-1 form-control" type="number" id="taxrate" step=".01" name="taxrate[]" value="0.0"></td>
            <td>
                <input class="col-xs-1 form-control" type="number" id="unitprice" step=".01" name="unitprice[]" readonly="readonly">
            </td>

          <td><input class="col-xs-1 form-control" type="number" id="tax" name="tax[]" placeholder="auto" readonly="readonly"></td>
          <td><input class="col-xs-1 form-control" type="number" id="amount" name="amount[]" placeholder="auto" readonly="readonly"></td>
          <td></td>
          
          
          </tr>
          
            
      
        </table>
        <span class="glyphicon glyphicon-plus text-success" onclick="newrow()"></span>
        
        <br><br>
        
          
          <div class="col-xs-2 form-inline">
            <label for="Invoice_ref">Account Img ref </label>
          </div>
          <div class="col-xs-3 form-group">
            <input class="form-control" type="text" id="acc_ref" name="acc_ref" placeholder="5-701">
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
            <input type="checkbox" name="existing" id="existing"  onchange="useexisting()"/> use existing 
            <input class="form-control" type="file" accept="image/*" name="file" id="file">
            <input class="form-control" list="imglist" name="img_name" value="">
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
            <input class="btn btn-info" type="Reset" onclick="init()" >
            <input class="btn btn-default" type="submit" name="submit" id="submit" value="Add Record" >
          </div>

          

        </div>


        

      
    </form>
    
    </div>
    <div style="height:400px"></div>
  </body>
  

  </html>