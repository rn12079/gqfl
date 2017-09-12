  
<html>
<head>
<title> add Inventory Items </title>
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
      width:100%;
      //border-style: solid;
      padding: 10px;
      border-color: grey;
      background-color: white;
      float:left;
      overflow:hidden;
    }

    div.rp {
      //width:50%;
      padding:10px;

      overflow:hidden;
      text-align:center;
    }

    #slist option{
      width:100px;
    }
  </style>
  <script type="text/javascript">


/*
Ajax block to get items for search tab
*/
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
  document.getElementById("count").innerHTML = 1;

  document.getElementById("mcp").style.display = 'none';
  ajaxsearch("product_type","");
  ajaxsearch("product_name","Food");
  ajaxsearch("supplier","");
}

function pop_products(){
  var filter=document.getElementById("tlist").value;
  ajaxsearch("product_name",filter);
}

function pop_subtype(){
  var filter=document.getElementById("tlist").value;
  ajaxsearch("product_name",filter);
  

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
  var supplier = document.getElementById("slist").value;
  var receiver = document.getElementById("receiver").value;
  var invoice_ref = document.getElementById("inv_num").value;
  var id = "";


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
    else
      newcell.innerHTML = table.rows[1].cells[i].innerHTML;
    

  }


}


</script>
</head>
<body onload="init()">
  <div class="tp">
    GQFL Inventory
  </div>
  <div class="mp">

    <div class="mlp" onclick="window.location='add_product_main.php'">
      Add New Products
    </div>

    <div class="mcp" >
      Add Inventory Item
    </div>

    <div class="mrp" onclick="window.location='show_inventory.php'">
      Inventory
    </div>


  </div>  
  <!-- end mp div -->

  <div class="lp" id="mlp" >
    <br>

    New Inventory Record 
    <form action="add_inventory2.php" method="post" onsubmit="return validate_form()" enctype="multipart/form-data" style="font-size:8pt">
      <table border="1px" >
       
        <tr>
          <td><label for="Receiving Date">Date </label></td><td>  <input type="date" name="t_date" id ="t_date"></td>
        </tr>
      <tr>
        <td><label for="Invoice_ref">Invoice Number </label> </td>
        <td><input type="text" id="inv_num" name="inv_num" placeholder="e.g. INV-0101" onchange="ret_inv_item()"></td>
      </tr>
       <tr>
          <td>
            <label for="Receiver">Received by </label> </td> 
            <td><select name="receiver" id="receiver">
              <option value="Warehouse" selected>Warehouse</option>1
              <option value="Shehbaz">Shehbaz</option>
              <option value="Hydri">Hydri</option>
            </select>
          </td>
        </tr> 
       
        <tr>
          <td><label for="Supplier">Supplier </label> </td>
          <td><select name="supplier" id="slist" placeholder="Select Supplier" style="width: 200px" onclick="pop_subtype()" >
          </select>
        </td>
      </tr>
      <tr>
          <td><label for="Product Type">Product Type </label> </td>
          <td><select name="product_type" id="tlist" placeholder="Select Supplier" align="right" style="width: 100px" onchange="pop_products()">

          </select> </td>
        </tr>
        </table>
        <br>
      <table border="1px" id="rec_items">
      <tr>
          <td> serial no. </td><td>Product Name</td><td>Product Sub type</td><td>Cases</td><td>Net Amount</td><td>Tax Amount</td>
          <td> Gross Amount </td> </tr>

        <tr>
          <td>
            <div id="count"></div>

          </td>
            <td><input list="plist" placeholder="Product" id="product" name="product[]" onchange="validate_product()" autocomplete="off" >
             <datalist id="plist">
             </datalist>  <div id ="txt_valid" style="color:red" ></div>
           </td>
         

        <td><input type="text" id="product_sub_type" name="product_sub_type[]" placeholder="Product sub type" readonly="readonly" onclick="ajaxsearch('product_sub_type','',this)">
        </td>

        <td><input type="number" id="num_cases" name="qty[]" placeholder="e.g. 1" width="20px"></td>
      
        <td><input type="number" id="namount" name="namount[]" placeholder="e.g. 1000.24" width="20px"></td>
        <td><input type="number" id="tax" name="tax[]" placeholder="tax 1000.24" width="20px"></td>
        <td><input type="number" id="amount" name="amount[]" placeholder=" 12-0.24" width="20px"></td>
        
        
        </tr>
        </table>

        <input type="button" value="add new row" onclick="newrow()">
        
        <br>
        <br>
        <table border="1px"> 
      
      <tr>
        <td><label for="Invoice_ref">Account Img ref </label> </td>
        <td><input type="text" id="acc_ref" name="acc_ref" placeholder="5-701"></td>
      </tr>

      <tr>
        <td><label for="file">Invoice File : </label></td>
        <td>
          use existing <input type="checkbox" name="existing" id="existing"  onchange="useexisting()"/> <input list="imglist" name="img_name" value="">
          <datalist id="imglist">
          </datalist>
          <input type="file" accept="image/*" name="file" id="file" onchange="changeimage(this)">
        </tr>
        
        <tr>
          <td colspan="2"><input type="Reset" onclick="init()" ><input type="submit" name="submit" id="submit" value="Add Product"></td>
        </tr>

      </table>
    </form>
    <p id="show_status" class="error"></p></div>
    
    <div class="mcp" id="mcp" onclick="dispmcp()">
      
      <input type="button" value="Search" onclick="ajaxsearch()"><br><br>
      <div id="results"></div>
      
      <br>
      
    </div> 

    
    <div class="rp" id="rp"><br><img src="" id="img_path"></div>
  </body>
  </html>