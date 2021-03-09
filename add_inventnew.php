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

if(!$_SESSION["loggedin"]) 
  $logged = false;
else 
  {
    if($_SESSION["level"] == "admin")
    $logged = true;
  }


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
<script src="myjs.js"></script>
<script>
   $(document).ready(function() {
   
    $("#supplier2").select2({
      data: <?php echo getsups();  ?>
    })

   

document.getElementById("myform").addEventListener("change", function() {
  console.log("form changed");
  compute_rows();

});

})

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
            <div class="col-xs-4 form-inline"> 
              <label for="Invoice_ref">Sub Net Total: </label>
            </div>
            <div class="col-xs-3 form-group"> 
              <input class="form-control" type="number" id="sub_total" name="sub_total" placeholder="auto" onclick="compute_totals()" readonly="readonly">
            </div>
          </div>
          
          <div class="row">
            <div class="col-xs-4 form-group"> 
              <label for="Invoice_ref">Sub Total Disc: </label>
            </div>

            <div class="col-xs-3 form-group"> 
              <input class="form-control" type="number" id="sub_total_disc" name="sub_total_disc" placeholder="auto" onclick="compute_totals()" readonly="readonly"></td>
            </div>
          </div>

          <div class="row">
            <div class="col-xs-4 form-group"> 
              <label for="Invoice_ref">Sub Total Tax: </label>
            </div>
            <div class="col-xs-3 form-group"> 
              <input class="form-control" type="number" id="sub_total_tax" name="sub_total_tax" placeholder="auto" onclick="compute_totals()" readonly="readonly"></td>
            </div>
          </div>

          <div class="row" style="margin-bottom:10px">
            <div class="col-xs-4 form-group"> 
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
            <input class="btn btn-default" type="submit" name="btnSubmit" id="btnSubmit" value="Add Record" onclick="this.disabled=true;this.value='submitting.....';this.form.submit();" >
          </div>

          

        </div>


        

      
    </form>
    
    </div>
    <div style="height:400px"></div>
  </body>
  

  </html>
