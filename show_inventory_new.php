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

if (!$_SESSION["loggedin"]) {
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
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

#toggle_custom{
    text-decoration: none;
    border: none;
    font-size: 12px;

}




</style>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script src="bootstrap1/js/bootstrap.min.js"></script>
<script src="select2/select2.min.js"></script>
<script src="tabledata.js"></script>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded", () => {
    //hiding custom_search fields
    const custom_area = document.getElementById("custom_search");
    custom_area.style.display = "none"; 

    //showing custom search field upon click custom search
    const btn_custom = document.getElementById("toggle_custom");
    btn_custom.addEventListener("click",a => {
        custom_area.style.display=="none"?custom_area.style.display="block":custom_area.style.display="none";
    });


    $('#prod_name').select2({
        closeOnSelect: false,
        placeholder: "select a product",
        width: '300px'
    });

})
</script>


</head>
<body>
 <?php
 include('navbar.html');

    if (!$logged) {
        echo "<div class='container' style='margin-top:10;'>";
        err_alert("<strong>Access Denied</strong> Please log in to access");
        echo "</div>";
        die;
    }


 
  ?>




 <div class="container" style="margin-top:10px">
    <fieldset>
    <label for="Product Name">Product Name </label>  
              
    <select id="prod_name" multiple="multiple" name="prod_name[]" width="100px">

    </select>

          
    <label for="Receiver">Received by </label>
    <input list="rlist" name="receiver"  id ="receiver">
    <datalist id="rlist">
    </datalist> 
          
            <label for="Supplier">Supplier </label>  
                
                <input list="slist" type="text" name="supplier" id="supplier" 
                value="">
                <datalist id="slist">
                </datalist> 
          
            
    </fieldset>
    <fieldset><legend><button id="toggle_custom">Custom Search >>> </button></legend>
    <section id="custom_search">


            <label for="Product Type">Product Type </label>  
            <input list="tlist" id="ptype" name="ptype" placeholder="Product Type" align="right" 
            value="">
            <datalist id="tlist">
            </datalist>           
          
             
              
              
              
                
                
                  <label for="Invoice Reference">Invoice Ref</label>  <input type="text" name="inv_ref"  id ="inv_ref" 
                  value="">

                
                  <label for="Product sub type">Sub type </label>  

            
              <input list="stlist" type="text" name="stype"  id ="stype" 
              value="">
              <datalist id="stlist">
              </datalist> 
            
              
              
                  <label for="Start date">Date Range: </label>
                  

                    <label for="subfilter">Sub filtering </label>  <input type="checkbox" name="chk"  id ="chk" 
                    ">
                  
                  

                    <label for="subfilter">Company </label>  
                  
                  
                  <select name="company[]" id="company" multiple="multiple">
                  
                  </select>
                  
                  
                  
                
              

                
                  

                    <label for="Start date">from </label> <input type="date" name="sdate"  id ="sdate" 
                    value="">


                  
                  
                    <label for="End date"> to </label>  <input type="date" name="edate"  id ="edate" 
                    value="">
                  
                
              
            
              <section>

              
              </fieldset>
              <input type="submit" value="Search">
              <td  class="top" >Date<td  class="top">Company<td  class="top">Receiver<td  class="top">Product Name<td  class="top">Supplier<td  class="top">Type<td  class="top">Subtype<td class="topnum">Qty<td class="topnum">U. price
              <td  class="topnum">Amount<td  class="top">Inv

              <?php
              $arr = $_POST['test'];
              $filters=array();
              $n_filters=0;

              if (count($arr)>0) {
                  $c=0;
                  $st_name=" p.product_name in (";
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
                  $st_receiver=" receiver='" .$_POST['receiver'] ."'";
                  $n_filters = $n_filters+1;
                  array_push($filters, $st_receiver);
              }
              if (isset($_POST['supplier'])&&$_POST['supplier']!="") {
                  $st_supplier=" supplier='" .$_POST['supplier'] ."'";
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
                      echo">".$row["date"]."".$row["company"]."".$row["receiver"]."".$row["product_name"]."".$row["supplier"]."".$row["product_type"];
                      echo "".$row["product_sub_type"]."<td class='number'>".number_format($row["cases"], 2)."<td class='number'>";

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


                      echo number_format($row["unitprice"], 2, '.', ',')."";
                  

                      echo "<td class='number'>";
                      if ($row['discount']>0) {
                          echo "<a href='#' data-toggle='tooltip' title='Discount = ";
                          echo number_format($row["discount"], 2, '.', ',');
                          echo "'>";
                      }
                      echo number_format($row["amount"], 2, '.', ',');
                  
                      echo ($row['discount'] > 0) ? "</a>" : "";

                      echo "<a target='_blank' href='".$row["invoice_img_ref"]."'>".$row["invoice_ref"]."";
                      echo "";

                      /* Collapseable Rows for Additional Details of each Invoice */
                      echo "<tr style='display:none;border:solid 1px;!important' id='tr".$tr_cnt++."'>";
                      echo "<td colspan=6 style='text-align:center'>";
                      echo "<strong> Case Size : </strong>". $row['casesize']." ".$row['units']. "<br> ";
                      echo "<strong> Brand / Make : </strong>". $row['maker']."<br> ";
                  
                      echo "";
                      echo "<td colspan=2 style='text-align:right;'>";
                      echo "<strong>Net Amount = <br>";
                      echo "<strong>Discount = <br>";
                      echo "<strong>Taxrate = <br>";
                      echo "<strong>Tax = <br>";
                      echo "<strong>Total Amount = </strong><br>";

                      echo "<td colspan=3>";
                      echo number_format($row['namount'], 2, '.', ',') . "<br>";
                      echo $row['discount'] == "" ? 0 : number_format($row['discount'], 2, '.', ',') ;
                      echo "<br>".($row['taxrate']*100)."%<br>";
                      echo $row['tax']=="" ? 0 : number_format($row['tax'], 2, '.', ',')  ;
                      echo "<br><strong>".number_format($row['amount'], 2, '.', ',') . "</strong><br>";


                      echo "";
                      /* End of Collapseable Rows */
                  
                  
                      $totalqty=$totalqty+$row["cases"];
                      $totalamount=$totalamount+$row["amount"];
                  }
              }

              echo "<td colspan=7  class='bottom'>Total<td class='bottom'>".number_format($totalqty, 2)."<td class='bottom'>";
              echo "<td class='bottom'>".number_format($totalamount, 2, '.', ',')."<td colspan=2 class='bottom'>";
              echo "";
              echo "<br><p class='info'>  total number of records : ".$result->num_rows ."</p>" ;
              mysqli_close($conn);
              
              ;
              
              ?>

            
            

          </body>
          
          </html>
