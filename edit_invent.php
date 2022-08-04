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

$logged = false;

if (!$_SESSION["loggedin"]) {
    $logged = false;
} else {
    if ($_SESSION["level"] == "admin" || $_SESSION["level"] == "superadmin") {
        $logged = true;
    }
}


?>


<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
<script src="tabledata.js"></script>
<script type="text/javascript">

document.addEventListener("DOMContentLoaded",e => {

  //getting inventory basics param fields
  const t_date = document.getElementById("t_date");
  const company = document.getElementById("company");
  const inv_num = document.getElementById("inv_num");
  const supplier = document.getElementById("supplier2");
  const receiver = document.getElementById("receiver");
  const acc_ref = document.getElementById("acc_ref");
  const img_ref = document.getElementById("imglist");
  const existing = document.getElementById("existing");

  const status = document.getElementById("status");

  //const for inventory item fields
  const itemids = document.getElementsByName("itemid[]");
  
  const product2s = document.getElementsByName("product2[]");
  const qtys = document.getElementsByName("qty[]");
  const namounts = document.getElementsByName("namount[]");
  const tads = document.getElementsByName("tad[]");
  const taxrates = document.getElementsByName("taxrate[]");
  const discounts = document.getElementsByName("discount[]");
  const amounts = document.getElementsByName("amount[]");
  const taxs = document.getElementsByName("tax[]");
  

  //getting single inventory item
  const urlparam = new URLSearchParams(window.location.search);
  const inv_id = urlparam.get('id');
  //retrieving other items from inv_id with unique date and invoice_ref and supplier
  const data = {
    purpose: "retrieveallparamsfrominvid",
    id: inv_id
  }
  
  postData("getdata.php",data).then(res => {
    t_date.value = res.date;
    inv_num.value = res.invoice_ref;
    //company
    company.innerHTML = `<option value=${res.comp_id}>${res.company}</option>`;  
    //supplier
    supplier.innerHTML = `<option value=${res.sup_id}>${res.supplier}</option>`;
    //location
    receiver.innerHTML = `<option value=${res.loc_id}>${res.location}</option>`;
    //image ref
    img_ref.innerHTML = `<option value=${res.invoice_img_ref}>${res.invoice_img_ref}</option>`;
    //acc_ref
    acc_ref.value = res.acc_ref;

    existing.checked = true;
    //retrieving inventory items from above data 
    res.purpose = "retrieve_inventory_items_from_params";
    
    postData("getdata.php",res).then(items => {
      let i = 0;
      items.forEach(item => {
        if(i!==0) addrowtotable(item.id);//add row to table
        itemids[i].value = item.id;
        product2s[i].innerHTML = `<option value='${item.prod_id}'>${item.product_name}</option'>`;
        qtys[i].value = item.cases;
        namounts[i].value = item.namount;
        tads[i].checked = (item.tad==0?false:true);
        discounts[i].value = item.discount;
        taxs[i].value = item.tax;
        taxrates[i].value = item.taxrate;
        amounts[i].value=item.amount;

        i++;
      })
      compute_rows();
    })
  })

  //update total if values in forms change
  const myform = document.getElementById("myform");
  myform.addEventListener("change",e => {
    compute_rows();
    console.log("form changed/updated totals")
  })

  const btn = document.getElementById("btnSubmit");
  //modify records upon form submit
  myform.addEventListener("submit",e => { 
    e.preventDefault();
    btn.disabled = true;
    const del_items = document.getElementsByName("del_item[]");
    
    console.log("submit pressed");  
      let delitem = [];
      let itemid = [];
      let qty = [];
      let namount = [];
      let tad = [];
      let discount = [];
      let taxrate = [];
      let tax = [];
      let amount = [];
     
      for(let i = 0 ; i < namounts.length ; i++){
        delitem.push(del_items[i].checked==true?1:0);
        itemid.push(itemids[i].value);
        qty.push(qtys[i].value);
        namount.push(namounts[i].value);
        tad.push(tads[i].checked==true?1:0);
        discount.push(discounts[i].value);
        taxrate.push(taxrates[i].value);
        tax.push(taxs[i].value);
        amount.push(amounts[i].value);
        
      }

      let data = {
        purpose: "update_inventory",
        delitem,
        itemid,
        qty,
        namount,
        tad,
        discount,
        taxrate,
        tax,
        amount,
        acc_ref: acc_ref.value,
        img_ref: img_ref.value
      } 
      console.log(data);
      
      if(document.getElementById("existing").checked == false ) {
        console.log("upload different photo");
        let fd = new FormData();
        let fileexists = true;     
        const upload = document.getElementById("file").files[0];
        if (upload===undefined) {
          console.log("no file exists")
          fileexists = false;
        }
        fd.append("fileupload", upload);
        console.log("fileupload",fd);
        
        postUploadData(fd,fileexists).then(res => {
          console.log("post upload res", res);
          if(res[0] == 'success'){
            data.img_ref = res[1];

            console.log("image uploading successful,attempting data");
            //console.log(data);
            postData("setdata.php",data).then(res => {
              
              console.log(res);
              status.classList = "";
              status.classList.add("label","label-success");
              status.innerHTML = "inventory record successfully updated"


            }).catch(res => {
              status.classList = "";
              status.classList.add("label","label-danger");
              status.innerHTML = "inventory record modification failed";

            })
          }
       });

      }else{
        postData("setdata.php",data).then(res => {
              console.log(res);
              status.classList = "";
              status.classList.add("label","label-success");
              status.innerHTML = "inventory record successfully updated"


            }).catch(res => {
              status.classList = "";
              status.classList.add("label","label-danger");
              status.innerHTML = "inventory record modification failed";

            })
      }
    
  })

  console.log("page loaded");


})


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
    if(tad[i].checked == false)
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

const addrowtotable = (inv_id) => {
  const itemtable = document.getElementById("rec_items");
  const tablerows = itemtable.rows.length;
  const tablecols = itemtable.rows[0].cells.length;
  //inserting new row to table;
  const newrow = itemtable.insertRow(tablerows);
  for (let i = 0; i < tablecols; i++) {
    //adding a cell for each col to newrow;
    let newcell = newrow.insertCell(i);
    if (i === 0) {
      newcell.innerHTML = `<td>
        <input type='hidden' id='item_id' name='itemid[]' value='${inv_id}'/>
        <input type='checkbox' name='del_item[]' id="delitem"  />
    </td>`;
    } else {
      newcell.innerHTML = itemtable.rows[1].cells[i].innerHTML;
      }
  }
}


</script>
</head>
<body>
<?php include('navbar.html');
  $bool_upload = false;
  $bool_succ = false;

  if (!$logged) {
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


<form id="myform" method="post">
  
  <div class="row">
    <div class="col-sm-2 form-group form-inline">
      <label for="Receiving Date">Date </label>  
    </div>
  
    <div class="col-sm-3 form-group">
      <input class="form-control" type="date" name="t_date" id ="t_date" readonly="readonly">
    </div>

  </div>

  <div class="row">
    
    <div class="col-sm-2 form-group form-inline">
      <label for="Supplier">Company</label>
    </div>

    <div class="col-sm-3 form-group ">
      <select class="form-control" id="company" name="company" readonly="readonly">
      </select>
    </div>
    
  </div>

  <div class="row">

    <div class="col-sm-2 form-group form-inline">
      <label for="Invoice_ref">Invoice #</label>
    </div>        
    <div class="col-sm-3 form-group ">
      <input  class="form-control" type="text" id="inv_num" name="inv_num" readonly="readonly">
    </div>

  </div>

  <div class="row">
    
    <div class="col-sm-2 form-group form-inline">
      <label for="Supplier">Supplier </label>
    </div>

    <div class="col-sm-3 form-group ">
      <select class="form-control" id="supplier2" name="supplier2" readonly="readonly">
      </select>
    </div>
    
  </div>
  
  <div class= "row">

    <div class="col-sm-2 form-group form-inline">
        <label for="Receiver">Received by </label>
    </div>

    <div class="col-sm-3 form-group">
      <select class="form-control" name="receiver" id="receiver" readonly="readonly">
       
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
        <input type='hidden' id='item_id' name='itemid[]' value='${inv_id}'/>
        <input type='checkbox' name='del_item[]' id="del_item"  />
        </td>

        <td>
         
            <select id="product2" name="product2[]" class="form-control" required> </select>
         
        </td>
        
        <td>
          <input class="col-xs-1 form-control" type="number" id="num_cases" name="qty[]" step=".01" placeholder="1" required="" >
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
    
    <br><br>
    
      
      
        
    
    
      <div class="row">
        <div class="col-xs-2 form-inline"> 
          <label for="Invoice_ref">Sub Net Total: </label>
        </div>
        <div class="col-xs-3 form-group"> 
          <input class="form-control" type="number" id="sub_total" name="sub_total" placeholder="auto" onclick="compute_totals()" readonly="readonly">
        </div>
      </div>
      
      <div class="row">
        <div class="col-xs-2 form-inline"> 
          <label for="Invoice_ref">Sub Total Disc: </label>
        </div>

        <div class="col-xs-3 form-group"> 
          <input class="form-control" type="number" id="sub_total_disc" name="sub_total_disc" placeholder="auto" onclick="compute_totals()" readonly="readonly"></td>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-2 form-inline"> 
          <label for="Invoice_ref">Sub Total Tax: </label>
        </div>
        <div class="col-xs-3 form-group"> 
          <input class="form-control" type="number" id="sub_total_tax" name="sub_total_tax" placeholder="auto" onclick="compute_totals()" readonly="readonly"></td>
        </div>
      </div>

      <div class="row" style="margin-bottom:10px">
        <div class="col-xs-2 form-inline"> 
          <label for="Invoice_ref">Invoice Total: </label>
        </div>
        <div class="col-xs-3 form-group"> 
          <input class="form-control" type="number" id="inv_total" name="inv_total" placeholder="auto" onclick="compute_totals()" readonly="readonly"></td>
        </div>
      </div>

      
      <div class="row">
        <div class="col-xs-2 form-inline">
          <label for="Invoice_ref">Account Img ref </label>
        </div>
        <div class="col-xs-3 form-group">
          <input class="form-control" type="text" id="acc_ref" name="acc_ref" placeholder="5-701">
        </div>
      </div>
    
      <div class="row">
        <div class="col-xs-2 form-group form-inline">
          <label for="file">Invoice File : </label>
        </div>

        <div class="col-xs-3 form-group ">
          <input type="checkbox" name="existing" id="existing"  /> use existing 
          <input class="form-control" type="file" accept="image/*" name="file" id="file">
          <select class="form-control" id="imglist" name="img_name" value="">
            
          </select>
        </div>
      </div>

    <div class="row">
      <div class="col-sm-3">
        <input class="btn btn-default" type="submit" name="btnSubmit" id="btnSubmit" value="Modify Record" >
        <span id="status"></span>
      </div>

      

    </div>


    

  
</form>

</div>
<div style="height:400px"></div>

  
  </body>
  </html>
