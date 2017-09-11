<html>
<head>
  <meta charset="utf-8"> 
  <style type="text/css">
    p.info {

      color:grey;
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
      height: 30px;
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
  <script type="text/javascript">

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

        document.getElementById(upd_field).innerHTML = xhr.responseText;

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
  ajaxsearch("product_name","plist");
  ajaxsearch("product_type","tlist");
  ajaxsearch("receiver","rlist");
  ajaxsearch("supplier","slist");
  ajaxsearch("product_sub_type","stlist");

}

function emptyall(){
  document.getElementById("ptype").value = "";
  document.getElementById("product_name").value = "";
  document.getElementById("supplier").value = "";
  document.getElementById("stype").value = "";
  document.getElementById("receiver").value = "";
  document.getElementById("chk").checked = false;
  document.getElementById("sdate").value = "";
  document.getElementById("edate").value = "";
  document.getElementById("inv_ref").value = "";
  
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


</script>
</head>

<body onload="init()">
  <div class="tp">
    <div>
      GQFL Inventory
    </div></div>
    <div class="mp">

      <div class="mlp"  onclick="window.location='add_product.html'">
        Add New Products
      </div>

      <div class="mcp" onclick="window.location='add_invent.html'">
        Add Inventory Items
      </div>

      <div class="mrp">
        Inventory
      </div>


    </div>  
    <!-- end mp div -->

    <div class="lp" id="mlp">

      <br>

      <form  action="show_inventory.php" method="post" enctype="multipart/form-data">
        <table border="1px">
          <tr><td>
            <label for="Product Type">Product Type </label></td><td>  
            <input list="tlist" id="ptype" name="ptype" placeholder="Product Type" align="right" autocomplete="off"
            value="<?php echo isset($_POST['ptype']) ? htmlentities($_POST['ptype']) : '' ?>">
            <datalist id="tlist">
            </datalist>           
          </td>
          <td><label for="Receiver">Received by </label></td>
          <td>
            <input list="rlist" name="receiver"  id ="receiver" autocomplete="off"
            value="<?php echo isset($_POST['receiver']) ? htmlentities($_POST['receiver']) : '' ?>">
            <datalist id="rlist">
            </datalist> 
          </td>
          <td>
            <label for="Product sub type">Sub type </label>  </td>

            <td>
              <input list="stlist" type="text" name="stype"  id ="stype" autocomplete="off"
              value="<?php echo isset($_POST['stype']) ? htmlentities($_POST['stype']) : '' ?>">
              <datalist id="stlist">
              </datalist> 
            </td>


          </tr>
          <tr>
            <td>
              <label for="Product Name">Product Name </label>  </td>
              <td><input list="plist" placeholder="Product" id="product_name" name="product_name"  autocomplete="off" 
                value="<?php echo isset($_POST['product_name']) ? htmlentities($_POST['product_name']) : '' ?>">
                <datalist id="plist">
                </datalist>          
              </td>
              <td>
                <label for="Supplier">Supplier </label>  </td>
                <td>
                  <input list="slist" type="text" name="supplier"  id ="supplier" autocomplete="off"
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

                <td colspan=3 onclick="toggle_dtrange()">
                  <label for="Start date">Date Range: </label></td>
                  <td>

                    <label for="subfilter">Sub filtering </label>  <input type="checkbox" name="chk"  id ="chk" 
                    <?php echo isset($_POST['chk']) ? "checked='checked'" : '' ?>">
                  </td>

                </tr>
              </table>

              <div id="dtrange" style="display:none">
                <table>
                  <tr>

                    <td><label for="Start date">from </label></td><td> <input type="date" name="sdate"  id ="sdate" 
                    value="<?php echo isset($_POST['sdate']) ? htmlentities($_POST['sdate']) : '' ?>"></td>


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
          <table align="center">
            <col width="10%"> <col width="10%"><col width="25%"><col width="15%"><col width="8%"><col width="8%"><col width="8%"><col width="8%"><col width="8%"><col width="10%">
            <tr>
              <td  class="top" >Date</td><td  class="top">Receiver</td><td  class="top">Product Name</td><td  class="top">Supplier</td><td  class="top">Product Type</td><td  class="top">Subtype</td><td class="top">Qty</td>
              <td  class="top">Amount</td><td  class="top">Invoice No.</td><td  class="top">Ref:</td></tr>

              <?php

              $filters=array();
              $n_filters=0;

              if (isset($_POST['product_name'])&&$_POST['product_name']!=""){
                $st_name=" product_name='" .$_POST['product_name'] ."'";

                $n_filters = $n_filters+1;
                array_push($filters, $st_name);
              }
              if (isset($_POST['receiver'])&&$_POST['receiver']!=""){
                $st_receiver=" receiver='" .$_POST['receiver'] ."'";
                $n_filters = $n_filters+1;
                array_push($filters,$st_receiver);
              }
              if (isset($_POST['supplier'])&&$_POST['supplier']!=""){
                $st_supplier=" supplier='" .$_POST['supplier'] ."'";
                $n_filters = $n_filters+1;
                array_push($filters,$st_supplier);
              }
              if (isset($_POST['ptype'])&&$_POST['ptype']!=""){
                $st_ptype=" product_type='" .$_POST['ptype'] ."'";
                $n_filters = $n_filters+1;
                array_push($filters,$st_ptype);
              }
              if (isset($_POST['stype'])&&$_POST['stype']!=""){
                $st_stype=" product_sub_type='" .$_POST['stype'] ."'";
                $n_filters = $n_filters+1;
                array_push($filters,$st_stype);
              }
              if (isset($_POST['sdate'])&&$_POST['sdate']!=""){
                $st_sdate=" date>='" .$_POST['sdate'] ."'";
                $n_filters = $n_filters+1;
                array_push($filters,$st_sdate);
              }
              if (isset($_POST['edate'])&&$_POST['edate']!=""){
                $st_edate=" date<='" .$_POST['edate'] ."'";
                $n_filters = $n_filters+1;
                array_push($filters,$st_edate);
              }
              if (isset($_POST['inv_ref'])&&$_POST['inv_ref']!=""){
                $st_inv_ref=" invoice_ref='" .$_POST['inv_ref'] ."'";
                $n_filters = $n_filters+1;
                array_push($filters,$st_inv_ref);
              }

              if (isset($_POST['img_ref'])&&$_POST['img_ref']!=""){
                $st_img_ref=" invoice_img_ref='" .$_POST['img_ref'] ."'";
                $n_filters = $n_filters+1;
                array_push($filters,$st_img_ref);
              }

//echo "total filters : " . $n_filters . "<br>";


              $myquery = "select i.id,date,receiver,product_name,supplier,product_type,product_sub_type,cases,amount,invoice_ref,invoice_img_ref from products p ";
              $myquery = $myquery . "inner join inventory i on i.product_id=p.id where del=0 ";

              //if($n_filters>0)
              //  $myquery= $myquery . "and ";

              for ($i = 0 ; $i < sizeof($filters) ; $i++){
             //   if($i!=0) 
                  $myquery = $myquery." and ";

                $myquery = $myquery.$filters[$i];

              }
              $myquery = $myquery . " order by date"; 

//echo $myquery;

              $conn = new mysqli("localhost","qasim","","mujju");
              if ($conn->connect_error)
              {
                die('Could not connect: ' . $con->connect_error);
              }

              $result = $conn->query($myquery);
              $totalqty =0;
              $totalamount=0;
              $url = "edit_invent.php?id=";

              if($result->num_rows > 0) {
                while($row = $result->fetch_assoc()){
                  echo "<tr class='cells' onclick=\"window.location.href='".$url.$row["id"]."'\"><td>".$row["date"]."</td><td>".$row["receiver"]."</td><td>".$row["product_name"]."</td><td>".$row["supplier"]."</td><td>".$row["product_type"];
                  echo "</td><td>".$row["product_sub_type"]."</td><td class='number'>".number_format($row["cases"])."</td><td class='number'>".number_format($row["amount"],2,'.',',')."</td><td>".$row["invoice_ref"];
                  echo "</td><td><a target='_blank' href='".$row["invoice_img_ref"]."'>".$row["invoice_img_ref"]."</td></tr>";
                  $totalqty=$totalqty+$row["cases"];
                  $totalamount=$totalamount+$row["amount"];
                }
              }

              echo "<tr><td colspan=6  class='bottom'>Total</td><td class='bottom'>".number_format($totalqty)."</td>";
              echo "<td class='bottom'>".number_format($totalamount,2,'.',',')."</td><td colspan=2 class='bottom'></td></tr>";
              echo "</table>";
              echo "<br><p class='info'>  total number of records : ".$result->num_rows ."</p>" ;
              mysqli_close ($conn);
              ?>

            </div> 


            <div id="stats"></div> 


          </body>
          </html>