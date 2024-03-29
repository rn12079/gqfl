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

if (!$_SESSION["gqfllog"]) {
    $logged = false;
} else {
    $logged = true;
}

if (!isset($_POST['sdate'])) {
    $_POST['sdate'] = date('Y-m-d', strtotime("-30 days"));
}

?>



<html>
<head>
<link href="select2/select2.min.css" rel="stylesheet" />
<link href="bootstrap1/css/bootstrap.min.css" rel="stylesheet">
<title>GQFL - Inventory</title>
<style>
*{
touch-action: manipulation;
}
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
  .select2-results__option[aria-selected=true] { display: none;}
  .select2-results__option--selected { display: none;}

    p.info {

      color:grey;
      font-size:10px;

    }
    tr.cells:nth-child(4n-2){
      background-color: #EBF4FA;
    }

    td.number {
      text-align:right; 
      padding-right:20px
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
      text-align: left;
      font-size:16px;
      font-weight:bold;
      height: 30px;
    }

    td.topnum {
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
      border-width: 1px;
      border-color: grey;
      cursor:hand;
    }

    div.mrp {
      border-radius: 15px 15px 1px 1px;
      width:20%;
      padding:10px;
      height: 130px;
      float:left;
      background-color:white;
      border-style: solid;
      border-color: grey;
      border-width: 1px;
      border-bottom: none;

      overflow:hidden;
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
</style>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script src="bootstrap1/js/bootstrap.min.js"></script>
<script src="select2/select2.min.js"></script>
<script src="tabledata.js"></script>
<script type="text/javascript">
 $(document).ready(function() {

  $('[data-toggle="tooltip"]').tooltip();  
  let data = JSON.stringify({purpose: 'get_companies'});
  //var sup = $('#supplier').val();
  $('#company').select2({
    width: '200px',
    ajax: {
      url: 'getdata.php',
      type: 'POST',
      contentType: 'application/json; charset=utf-8',
      dataType: 'json',
      cache: true,
      data,
      processResults: function(data){
        return {
          results:$.map(data,function(obj){
          return {
            id: obj.id,
            text: obj.name
          }
        })
      }
    }

  }
})

  $('#test').select2({
    
    closeOnSelect: false,
    placeholder: "select a product",
    width:'300px',
  ajax: {
    url: 'retrieve_rows_jq.php',
    dataType: 'json',
    cache: true,
    data: function(params) {
      
      return {
        q: params.term,
        s: $('#supplier').val(),
        r: $('#receiver').val(),
        pt: $('#ptype').val(),
        st: $('#stype').val()
      };


    },


    processResults: function(data){

      return {
        results: $.map(data, function(obj) {
          return {
            id: obj.id,
            text: obj.text + ' || ' +  obj.hint,
          };

        })
      };


    }
    }
});

const company = document.getElementById("company");
company.addEventListener("change",e => {
  console.log(company.id, company.name);
}) 

});


/*
Ajax block to get items for search tab
/*
Ajax block to get items for search tab
*/
function ajaxsearch(ret_field,upd_field){
  var chk = document.getElementById("chk").checked 
  if (ret_field=="product_type") chk=false;
  var ptype = chk ? document.getElementById("ptype").value : "";
  var pname = chk ?document.getElementById("product_name").value : "";
  var supplier = chk ? document.getElementById("supplier").value : "";
  var stype = chk ? document.getElementById("stype").value : "";
  var receiver = chk ?  document.getElementById("receiver").value : "";

  var myjson = {"ret_field":ret_field,"ptype":ptype,"pname":pname,"supplier":supplier,"stype":stype,"receiver":receiver};
  data_params = JSON.stringify(myjson);

 // document.getElementById("stats").innerHTML = "ret_field"+ myjson.ret_field+" ptype: " + myjson.ptype + " pname: " + myjson.pname + " supp: " + myjson.supplier + " stype: " + myjson.stype;


 var xhr;
 if(window.XMLHttpRequest){
  xhr=new XMLHttpRequest();
} 
  else if (window.ActiveXObject) { // IE 8 and older  
    xhr = new ActiveXObject("Microsoft.XMLHTTP");  
  } 

  var data="product_name="+pname;
  xhr.open("POST","retrieve_rows.php",true);
  xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  xhr.send("x="+data_params);
  xhr.onreadystatechange = display_data;
  
  function display_data(){
    if(xhr.readyState==4){
      if(xhr.status == 200) {

        document.getElementById(upd_field=="l.name"?"receiver":(upd_field=="s.name"?"supplier":upd_field)).innerHTML = xhr.responseText;

      }
      else
      {
        alert('There was a problem with the request');
      }

    }

  }
  /*end Ajax block*/
  
}




function init(){
  //ajaxsearch("product_name","plist");
  ajaxsearch("product_type","tlist");
  ajaxsearch("l.name","rlist");
  ajaxsearch("s.name","slist");
  ajaxsearch("product_sub_type","stlist");
  

}

function emptyall(){
  document.getElementById("ptype").value = "";
  document.getElementById("supplier").value = "";
  document.getElementById("stype").value = "";
  document.getElementById("receiver").value = "";
  document.getElementById("chk").checked = false;
  document.getElementById("sdate").value = "";
  document.getElementById("edate").value = "";
  document.getElementById("inv_ref").value = "";
  $('#test').val('').trigger('change');
  
}



var dtrange=false;

function toggle_dtrange(){
  if(!dtrange){
    document.getElementById("dtrange").style.display = 'block';
    dtrange=true;
  }
  else {
    document.getElementById("dtrange").style.display = 'none';
    dtrange=false;  
  }
}

function toggle_details(t_row){
  var t_status = t_row.style.display;
  console.log(t_status);
  if(t_status=='none')
    t_row.style.display = 'table-row';
  else
    t_row.style.display = 'none';

}


</script>


</head>
<body onload="init()">
 <?php 
 include('navbar.html');

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
      <strong>Inventory</strong>
   </div>
 </div>


 <div class="container">

      <form  action="show_inventory.php" method="post" enctype="multipart/form-data">
        <table border="1px" style="padding: 5px">
          <tr><td>
            <label for="Product Type">Product Type </label></td><td>  
            <input list="tlist" id="ptype" name="ptype" placeholder="Product Type" align="right" 
            value="<?php echo isset($_POST['ptype']) ? htmlentities($_POST['ptype']) : '' ?>">
            <datalist id="tlist">
            </datalist>           
          </td>
          <td><label for="Receiver">Received by </label></td>
          <td>
            <input list="rlist" name="receiver"  id ="receiver"
            value="<?php echo isset($_POST['receiver']) ? htmlentities($_POST['receiver']) : '' ?>">
            <datalist id="rlist">
            </datalist> 
          </td>
          <td>
            <label for="Product sub type">Sub type </label>  </td>

            <td>
              <input list="stlist" type="text" name="stype"  id ="stype" 
              value="<?php echo isset($_POST['stype']) ? htmlentities($_POST['stype']) : '' ?>">
              <datalist id="stlist">
              </datalist> 
            </td>


          </tr>
          <tr>
             <td>
              <label for="Product Name">Product Name </label>  </td>
              <td>
              <select id="test" multiple="multiple" name="test[]" width="100px">
              <?php
              
              $prodobj = new Prods_assign();
              $arr = $_POST['test'];
              if (count((array)$arr)>0) {
                  foreach ($arr as $x => $y) {
                      echo "<option value='".$arr[$x]."' selected>".$prodobj->getProductName($arr[$x])."</option>";
                  }
              }
                ?>
                </select>

              </td>
              <td>
                <label for="Supplier">Supplier </label>  </td>
                <td>
                  <input list="slist" type="text" name="supplier" id="supplier" 
                  value="<?php echo isset($_POST['supplier']) ? htmlentities($_POST['supplier']) : '' ?>">
                  <datalist id="slist">
                  </datalist> 
                </td>
                <td>
                  <label for="Invoice Reference">Invoice Ref</label></td><td>  <input type="text" name="inv_ref"  id ="inv_ref" 
                  value="<?php echo isset($_POST['inv_ref']) ? htmlentities($_POST['inv_ref']) : '' ?>">

                </td>

              </tr>
              <tr>

                <td colspan=3 onclick="toggle_dtrange()" class="<?php
                if (isset($_POST['sdate']) || isset($_POST['edate'])) {
                    echo "bg-success";
                }
                  ?>">
                  <label for="Start date">Date Range: </label></td>
                  <td>

                    <label for="subfilter">Sub filtering </label>  <input type="checkbox" name="chk"  id ="chk" 
                    <?php echo isset($_POST['chk']) ? "checked='checked'" : '' ?>">
                  </td>
                  <td>

                    <label for="subfilter">Company </label>  
                  </td>
                  <td>
                  <select name="company[]" id="company" multiple="multiple">
                  <?php $comp_arr = $_POST['company'];
                  if (count((array)$comp_arr)>0) {
                    $compobj = new Companies();
                    foreach ($comp_arr as $x => $y) {
                      echo "<option value='".$comp_arr[$x]."' selected>".$compobj->getCompanyName($comp_arr[$x])."</option>";
                    }
                  }
                ?>
                  </select>
                  </td>
                  
                  
                </tr>
              </table>

              <div id="dtrange" style="display:none">
                <table>
                  <tr>

                    <td><label for="Start date">from </label></td><td> <input type="date" name="sdate"  id ="sdate" 
                    value="<?php echo isset($_POST['sdate']) ? htmlentities($_POST['sdate']) : ""; ?>"></td>


                  </tr>
                  <tr>
                   <td >
                    <label for="End date"> to </label> </td><td> <input type="date" name="edate"  id ="edate" 
                    value="<?php echo isset($_POST['edate']) ? htmlentities($_POST['edate']) : '' ?>">
                  </td>
                </tr>
              </table>
            </div>
            <tr>
              <td>


                <input type="button" value="Reset" onclick="emptyall()" ></td><td><input type="submit" value="Search">
              </td>
            </tr>

          </form> 
        </div>
          <table align="center" style="margin:20px">
            <col width="8%"> 
            <col width="8%">
            <col width="8%">
            <col width="16%">
            <col width="16%">
            <col width="5%">
            <col width="5%">
            <col width="5%">
            <col width="8%">
            <col width="10%">
            <col width="5%">
            <tr>
              <td  class="top" >Date</td><td  class="top">Company</td><td  class="top">Receiver</td><td  class="top">Product Name</td><td  class="top">Supplier</td><td  class="top">Type</td><td  class="top">Subtype</td><td class="topnum">Qty</td><td class="topnum">U. price</td>
              <td  class="topnum">Amount</td><td  class="top">Inv</td></tr>

              <?php
              $arr = $_POST['test'];
              $filters=array();
              $n_filters=0;

              if (count((array)$arr)>0) {
                  $c=0;
                  $st_name=" p.id in (";
                  foreach ($arr as $x => $y) {
                      if ($c>0) {
                          $st_name = $st_name . ",";
                      }
                      $st_name = $st_name."'".$arr[$x]."'";
                      $c++;
                  }

                  $st_name = $st_name . ")";

                  $n_filters = $n_filters+1;
                  array_push($filters, $st_name);
              }
              if (isset($_POST['receiver'])&&$_POST['receiver']!="") {
                  $st_receiver=" l.name='" .$_POST['receiver'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_receiver);
              }
              if (isset($_POST['supplier'])&&$_POST['supplier']!="") {
                  $st_supplier=" s.name='" .$_POST['supplier'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_supplier);
              }
              if (isset($_POST['ptype'])&&$_POST['ptype']!="") {
                  $st_ptype=" product_type='" .$_POST['ptype'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_ptype);
              }
              if (isset($_POST['stype'])&&$_POST['stype']!="") {
                  $st_stype=" product_sub_type='" .$_POST['stype'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_stype);
              }
              if (isset($_POST['sdate'])&&$_POST['sdate']!="") {
                  $st_sdate=" date>='" .$_POST['sdate'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_sdate);
              }
              if (isset($_POST['edate'])&&$_POST['edate']!="") {
                  $st_edate=" date<='" .$_POST['edate'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_edate);
              }
              if (isset($_POST['inv_ref'])&&$_POST['inv_ref']!="") {
                  $st_inv_ref=" invoice_ref='" .$_POST['inv_ref'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_inv_ref);
              }

              if (isset($_POST['img_ref'])&&$_POST['img_ref']!="") {
                  $st_img_ref=" invoice_img_ref='" .$_POST['img_ref'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_img_ref);
              }

              $comp = $_POST['company'];
              
              if (count((array)$comp)>0) {
                  $c=0;
                  $st_name=" c.id in (";
                  foreach ($comp as $x => $y) {
                      if ($c>0) {
                          $st_name = $st_name . ",";
                      }
                      $st_name = $st_name."'".$comp[$x]."'";
                      $c++;
                  }

                  $st_name = $st_name . ")";

                  $n_filters = $n_filters+1;
                  array_push($filters, $st_name);
              }

//echo "total filters : " . $n_filters . "<br>";


              $myquery = "select i.id,date,c.name company,l.name receiver,
                            product_name,s.name supplier,product_type,product_sub_type,
                            cases,casesize,units,maker,amount,discount,
                            truncate((namount-discount)/cases,2) as unitprice,tax,namount,
                            taxrate,invoice_ref,invoice_img_ref 
                          from inventory i join products p on i.product_id=p.id 
                          join locations l on i.loc_id=l.id 
                          join suppliers s on i.sup_id=s.id
                          left join company c on i.comp_id=c.id 
                          where del=0 ";

              //if($n_filters>0)
              //  $myquery= $myquery . "and ";

              for ($i = 0 ; $i < sizeof($filters) ; $i++) {
                  //   if($i!=0)
                  $myquery = $myquery." and ";

                  $myquery = $myquery.$filters[$i];
              }
              $myquery = $myquery . " order by date";

//echo $myquery;

              $conn = new mysqli($GLOBALS['host'], $GLOBALS['dbuser'], $GLOBALS['dbpass'], $GLOBALS['db']);
  if ($conn->connect_error) {
      die('Could not connect: ' . $con->connect_error);
  }

              $result = $conn->query($myquery);
              $totalqty =0;
              $totalamount=0;
              $url = "edit_invent.php?id=";
              $myarray = array();
              $p_cnt = 0;
              $up = 0;
              $tr_cnt = 0;

              if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                      echo "<tr onclick='toggle_details(tr".$tr_cnt.")' class='cells' ";
                      $up = $row["unitprice"];

                  

                      if ($logged && ($_SESSION['level']=='admin' || $_SESSION['level']=='superadmin')) {
                          echo "ondblclick=\"window.location.href='".$url.$row["id"]."'\"";
                      }
                      echo"><td>".$row["date"]."</td><td>".$row["company"]."</td><td>".$row["receiver"]."</td><td>".$row["product_name"]."</td><td>".$row["supplier"]."</td><td>".$row["product_type"];
                      echo "</td><td>".$row["product_sub_type"]."</td><td class='number'>".number_format($row["cases"], 2)."</td><td class='number'>";

                      // Unit price calculation + arrow sign upon price increase of decrease
                      if (isset($myarray[$row["product_name"]])) {
                          $pval = $myarray[$row["product_name"]];
                          if ($up > $pval && ($up/$pval-1.0)>0.015) {
                              echo /*$up. " / ".$pval. " = ". */bcadd(bcdiv($up, $pval, 3), -1, 2)*100 . "%";
                              $myarray[$row["product_name"]] = $up;
                              echo "<span class='glyphicon glyphicon-arrow-up text-danger'></span>";
                          }
                          if ($up < $myarray[$row["product_name"]] && ($pval/$up-1.0>0.015)) {
                              echo /*$up. " / ".$pval. " = ". */bcadd(bcdiv($up, $pval, 3), -1, 2)*100 . "%";
                              $myarray[$row["product_name"]] = $up;
                              echo "  <span class='glyphicon glyphicon-arrow-down text-success'></span>";
                          }
                      } else {
                          $myarray[$row["product_name"]] = $up;
                      }


                      echo number_format($row["unitprice"], 2, '.', ',')."</td>";
                  

                      echo "<td class='number'>";
                      if ($row['discount']>0) {
                          echo "<a href='#' data-toggle='tooltip' title='Discount = ";
                          echo number_format($row["discount"], 2, '.', ',');
                          echo "'>";
                      }
                      echo number_format($row["amount"], 2, '.', ',');
                  
                      echo ($row['discount'] > 0) ? "</a>" : "";

                      echo "<td><a target='_blank' href='".$row["invoice_img_ref"]."'>".$row["invoice_ref"]."</td>";
                      echo "</tr>";

                      /* Collapseable Rows for Additional Details of each Invoice */
                      echo "<tr style='display:none;border:solid 1px;!important' id='tr".$tr_cnt++."'>";
                      echo "<td colspan=6 style='text-align:center'>";
                      echo "<strong> Case Size : </strong>". $row['casesize']." ".$row['units']. "<br> ";
                      echo "<strong> Brand / Make : </strong>". $row['maker']."<br> ";
                  
                      echo "</td>";
                      echo "<td colspan=2 style='text-align:right;'>";
                      echo "<strong>Net Amount = <br>";
                      echo "<strong>Discount = <br>";
                      echo "<strong>Taxrate = <br>";
                      echo "<strong>Tax = <br>";
                      echo "<strong>Total Amount = </strong><br>";

                      echo "</td><td colspan=3>";
                      echo number_format($row['namount'], 2, '.', ',') . "<br>";
                      echo $row['discount'] == "" ? 0 : number_format($row['discount'], 2, '.', ',') ;
                      echo "<br>".($row['taxrate']*100)."%<br>";
                      echo $row['tax']=="" ? 0 : number_format($row['tax'], 2, '.', ',')  ;
                      echo "<br><strong>".number_format($row['amount'], 2, '.', ',') . "</strong><br>";


                      echo "</td></tr>";
                      /* End of Collapseable Rows */
                  
                  
                      $totalqty=$totalqty+$row["cases"];
                      $totalamount=$totalamount+$row["amount"];
                  }
              }

              echo "<tr><td colspan=7  class='bottom'>Total</td><td class='bottom'>".number_format($totalqty, 2)."</td><td class='bottom'></td>";
              echo "<td class='bottom'>".number_format($totalamount, 2, '.', ',')."</td><td colspan=2 class='bottom'></td></tr>";
              echo "</table>";
              echo "<br><p class='info'>  total number of records : ".$result->num_rows ."</p>" ;
              mysqli_close($conn);
              
              ;
              
              ?>

            
            

          </body>
          
          </html>
