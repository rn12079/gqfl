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

if (!$_SESSION["gqfllog"]) {
    $logged = false;
} else {
    if ($_SESSION["level"] == "superadmin" || $_SESSION["level"] == "admin"  || $_SESSION["level"] == "user") {
        $logged = true;
    }
}


?>


<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
  
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
    margin-bottom: 0;
    
  }
  .form-group {

    margin-bottom:2px;


  }


</style>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script src="bootstrap1/js/bootstrap.min.js"></script>
<script src="select2/select2.min.js"></script>
<script src="myjs.js"></script>
<script src="tabledata.js"></script>
<script>
   
   document.addEventListener("DOMContentLoaded", () => {
    //populate companies to select company;
    const company_select  = document.getElementById("company");
    
    //load default suppliers on company selected.
    getSelectOptions({purpose: "get_companies"},company_select).then(res => {
      onCompanySelect();
    });

  
    const myform = document.getElementById("myform");
    myform.addEventListener("change", () => {
    //console.log("form changed");
    compute_rows();

    });

    //blocking form submit and submiting through fetch API
    const ps = document.getElementById("myform");
      ps.addEventListener("submit", e => {
      e.preventDefault();
      const btnsubmit = document.getElementById("btnSubmit");
      const status = document.getElementById("status");
      btnsubmit.value = "Adding Record .... ";
      btnsubmit.disabled = true;

      console.log("submit pressed");

      const prodids = document.getElementsByName("product2[]");
      const qtys = document.getElementsByName("qty[]");
      const namounts = document.getElementsByName("namount[]");
      const discounts = document.getElementsByName("discount[]");
      const taxrates = document.getElementsByName("taxrate[]");
      const taxes = document.getElementsByName("tax[]");
      const amounts = document.getElementsByName("amount[]");

      let prodid = [];
      let qty = [];
      let namount = [];
      let tad = [];
      let discount = [];
      let taxrate = [];
      let tax = [];
      let amount = [];
     //console.log(tads);

      for(let i = 0 ; i < namounts.length ; i++){
        prodid.push(prodids[i].value);
        qty.push(qtys[i].value);
        namount.push(namounts[i].value);
        let d = "tad[" + i + "]";
        const tads = document.getElementById(d);     
        tad.push(tads.checked==true?1:0);
        discount.push(discounts[i].value);
        taxrate.push(taxrates[i].value);
        tax.push(taxes[i].value);
        amount.push(amounts[i].value);
        
      }

      let data = {
      purpose: "add_inventory",
      t_date : document.getElementById("t_date").value,
      comp_id : document.getElementById("company").value,
      receiver : document.getElementById("receiver").value,
      supplier : document.getElementById("supplier2").value,
      inv_num : document.getElementById("inv_num").value,
      acc_ref : document.getElementById("acc_ref").value,
      prodid,
      qty,
      namount,
      tad,
      discount,
      taxrate,
      tax,
      amount
    };
    
    //image upload before rest of data is inserted.
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
        if(res[0] == 'success'){
          data.img_ref = res[1];
        }else {
          data.img_ref="";
        }

          console.log("image uploading successful,attempting data");
          postData("setdata.php",data).then(res => {
            console.log(res);
            status.classList = "";
            status.classList.add("label","label-success");
            status.innerHTML = "inventory record successfully added"


          }).catch(res => {
            status.classList = "";
            status.classList.add("label","label-danger");
            status.innerHTML = "inventory record addition failed";

          })
        
      });
    
    
    
    
    // UPLOADING REST OF The DATA
    // let inv_id = -1;
    // postData("setdata.php",data).then(res => {
    //   console.log("inventory inserted = 1 == ",res);
    //   inv_id=res;
    // })
    // console.log(data);



    


    });
    
    status.innerHTML = "";
    t_date.focus();
    btnsubmit.disabled=false;
  });

const onCompanySelect = () => {
  console.log("company_selected");
  const comp_id = document.getElementById("company").value;
  const supplier = document.getElementById("supplier2");
  const locations = document.getElementById("receiver");

  
  // based on Company we will pop results for locations and suppliers
  //1. pop locations
  let data = {
    purpose: "getLocationsByCompanyId",
    id: comp_id
  };

  getSelectOptions(data,locations);

  //2. pop suppliers
  data = {
    purpose: "getSuppliersByCompanyId",
    id: comp_id
  };
  console.log("getsuppliers",data);
  getSelectOptions(data,supplier).then(res=>{
    pop_products2();
  });

}


const init = () => {

}

  
  

</script>




</head>
<body>
 <?php include('navbar.html');

  if (!$logged) {
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


    <form id="myform" method="post">
      
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
          <label for="Supplier">Company</label>
        </div>

        <div class="col-sm-3 form-group ">
          <select class="form-control" id="company" name="company" onchange="onCompanySelect()">
          </select>
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
          <select class="form-control" id="supplier2" name="supplier2"   onclick="pop_products2()">
          </select>
        </div>
        
      </div>
      
      <div class= "row">

        <div class="col-sm-2 form-group form-inline">
            <label for="Receiver">Received by </label>
        </div>

        <div class="col-sm-3 form-group">
          <select class="form-control" name="receiver" id="receiver" >
           
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
        <span class="glyphicon glyphicon-plus text-success" onclick="newrow()"></span>
        
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
              <input type="checkbox" name="existing" id="existing"  onchange="useexisting()"/> use existing 
              <input class="form-control" type="file" accept="image/*" name="file" id="file">
              <select class="form-control" id="imglist" name="img_name" value="">
                
              </select>
            </div>
          </div>

        <div class="row">
          <div class="col-sm-3">
            <input class="btn btn-info" type="Reset" onclick="init()" >
            <input class="btn btn-default" type="submit" name="btnSubmit" id="btnSubmit" value="Add Record" >
            <span id="status"></span>
          </div>

          

        </div>


        

      
    </form>
    
    </div>
    <div style="height:400px"></div>
  </body>
  

  </html>
