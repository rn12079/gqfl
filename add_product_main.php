<?php 

session_start();
$errmsg = "";

if(!$_SESSION["loggedin"])
  {
    echo "not logged in <br>";
    echo "log in at <a href ='login.php'>log in </a>";


   die; }
?><html>
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

    div.mcp {
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
    function updateqty(){
      
      var newval = document.getElementById("new_XL").value;
      alert("run update query"+newval);
    }

/*
Ajax block to get items for search tab
*/
function ajaxsearch(){
  var prod_code = document.getElementById("search_code").value;
  var xhr;
  if(window.XMLHttpRequest){
    xhr=new XMLHttpRequest();
  } 
  else if (window.ActiveXObject) { // IE 8 and older  
    xhr = new ActiveXObject("Microsoft.XMLHTTP");  
  } 

  var data="product_code="+prod_code;
  xhr.open("POST","retrieve_item.php",true);
  xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  xhr.send(data);
  xhr.onreadystatechange = display_data;
  
  function display_data(){
    if(xhr.readyState==4){
      if(xhr.status == 200) {
        
        document.getElementById("results").innerHTML = xhr.responseText;
        
        
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
  document.getElementById("mcp").style.display = 'none';
  
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


function dispmcp(){
 document.getElementById("mcp").style.display = '';
 document.getElementById("mlp").style.display = 'none';
 document.getElementById("mrp").style.display = 'none';
 
}

function dispmlp(){
 document.getElementById("mlp").style.display = '';
 document.getElementById("mcp").style.display = 'none';
 document.getElementById("mrp").style.display = 'none';
}


</script>
</head>
<body onload="init()">
  <div class="tp">
    GQFL Inventory
  </div>
  <div class="mp">

    <div class="mlp">
      Add New Products
    </div>

    <div class="mcp" onclick="window.location='add_invent.html'" >
      Add Inventory Items
    </div>

    <div class="mrp" onclick="window.location='show_inventory.php'">
      Inventory
    </div>


  </div>  
  <!-- end mp div -->

  <div class="lp" id="mlp">
    <br>
    New Products

    <form action="add_product.php" method="post" enctype="multipart/form-data" style="font-size:8pt">
      
      <table border="1px">
        <tr>
          <td>
            <label for="Product Name">Product Name </label> </td><td> <input type="text" name="txt_name" placeholder="Coke BIB" id ="t_name">
          </td></tr>
          <tr><td>
            <label for="Product Type">Product Type </label></td><td>  <input type="text" name="txt_type" placeholder="Food" id ="t_type">
          </td></tr>
          <tr><td>
            <label for="Product Sub Type">Product Subtype </label></td><td>  <input type="text" name="txt_sub_type" placeholder="Beverage" id ="t_sub_type">
          </td></tr>
          <tr>
            <td>
              <label for="Product Supplier">Product Supplier </label></td><td>  <input type="text" name="txt_supplier" placeholder="CCBPL" id ="t_supplier">
            </td></tr>
            <tr>
              <td colspan=2 style="text-align: center">

                <input type="submit" name="submit" value="Add Product"></td>
              </tr>
              <table>
              </form></div>
              
              <div class="mcp" id="mcp" onclick="dispmcp()">
                
                <label for="Product Code">Product Code </label>  <input type="text" name="search_code"  id ="search_code" onKeyUp="ajaxsearch()">
                <input type="button" value="Search" onclick="ajaxsearch()"><br><br>
                <div id="results"></div>
                
                <br>
                
              </div> 

              
              <div class="rp" id="rp"><br><img src="" id="img_path"></div>
            </body>
            </html>