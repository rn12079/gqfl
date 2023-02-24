<?php
  
include('alerts.php');
session_start();
$errmsg = "";
$logged = false;

if (!$_SESSION["gqfllog"]) {
    $logged = false;
} else {
    if ($_SESSION["level"] == "superadmin") {
        $logged = true;
    }
}

?>

<html>


<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script   src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script   src="tabledata.js"></script>
  
  <link href="bootstrap1/css/bootstrap.min.css" rel="stylesheet">
  <style>
  .navbar{
    margin-bottom:0;
    border-radius: 0;
  }
  .jumbotron{
    //margin-bottom: 0;
    
  }


  </style>
  <script src="bootstrap1/js/bootstrap.min.js"></script>

  <title>
    GQFL
  </title>

  <script>
    document.addEventListener("DOMContentLoaded",() => {
      let bool_add_new=false;
      
      const t_name = document.getElementById("t_name");
      const t_source = document.getElementById("t_source");
      const t_type = document.getElementById("t_type");
      const t_sub_type = document.getElementById("t_sub_type");
      const t_cases = document.getElementById("t_cases");
      const t_units = document.getElementById("t_units");
      const submit = document.getElementById("add_prod");
      const addnew = document.getElementById("addnew");
      
      const status = document.getElementById("res");

      submit.addEventListener("click",() => {
        //console.log('submit clicked');
        submit.disabled = true;

        //getting values from FORM
        const t_name_val = t_name.value;
        const t_source_val = t_source.value;
        const t_type_val = t_type.value;
        const t_sub_type_val = t_sub_type.value;
        const t_cases_val = t_cases.value;
        const t_units_val = t_units.value;
        
        if(t_name_val == "" || t_source_val == "" || t_type_val == "" || t_sub_type_val == "" || t_cases_val == "" || t_units_val == "")
          {
              console.log("data empty");
              status.classList = "";
              status.classList.add("label","label-danger");
              status.innerHTML = "Required fields empty"
              return ;
          }
        
        //Preparing data
        const data = {
          purpose: 'add_product',
          t_name: t_name_val,
          t_source_val: t_source_val,
          t_type: t_type_val,
          t_sub_type: t_sub_type_val,
          t_cases: t_cases_val,
          t_units: t_units_val
        }

        console.log(data);

        //sending to setdata for inserting into database
        postData("setdata.php",data).then(res => {
          console.log("successful");
          console.log(res);
          status.classList = "";
          status.classList.add("label","label-success");
          status.innerHTML = "Product Addition Successful";
          addnew.disabled=false;
          bool_add_new = false;

          

          return "success";

          }).catch( e => {
          console.log("addition failed");
          status.classList = "";
          status.classList.add("label","label-danger");
          status.innerHTML = "Product addition failed";
        });


      })
      

      addnew.addEventListener("click", () => {
        bool_add_new = true;
        console.log("new product clicked");
        status.innerHTML = "";
        addnew.disabled=true;
  

      })

      t_name.addEventListener("keyup", () => {
        console.log("product name change")
          if (bool_add_new) {
            console.log("adding new product")
            submit.disabled = false;            
          }
          
        getproducts();
        })



    })

    const getproducts = () => {
    const prod = document.getElementById("t_name").value
    //console.log(sup);
    data = {
      purpose: 'get_products',
      suptext: '%'+ prod+'%'
    }
    console.log(data);
    const resdiv = document.getElementById("resdiv");
    postData('getdata.php',data).then(res => {
      ans = "<table class='table small table-striped table-bordered table-hover table-condensed'>";
      ans += `<tr><th>Product Name</th><th>Source/Make</th><th>Product Type</th><th>Sub Type</th><th>Case Size</th></tr>`
      res.forEach(prod => {
        ans += `
        <tr><td>${prod.product_name}</td>
        <td>${prod.maker}</td>
        <td>${prod.product_type}</td>
        <td>${prod.product_sub_type}</td>
        <td>${prod.casesize} ${prod.units}</td>
        </tr>`;

      
      })
      ans += '</table>';

      console.log(ans)
      resdiv.innerHTML = ans;


    })



};

  
  </script>
</head>
<body>


  <!-- NAVBAR + ERROR MSG IF NOT LOGGED IN-->

  <?php include('navbar.html');

   if (!$logged) {
       echo "<div class='container' style='margin-top:10;'>";
       err_alert("<strong>Access Denied</strong> Please log in to access");
       echo "</div>";
       die;
   }



  ?>

  <!-- JUMPOTRON -->
  <div class="jumbotron text-center">
    <div class="container">
     <h1><SMALL> New Product </SMALL></h1>
   </div>
 </div>

 <div class="container">
    
    <div class="form-group">
      <label for="Product Name">Product Name </label>
      <input type="text" class="form-control" name="txt_name" placeholder="Coke BIB" id ="t_name" required>
    </div>

    <div class="form-group">
      <label for="Product Maker">Product Source</label><input type="text" class="form-control" name="txt_source" placeholder="Source/Make" id ="t_source" reqiored>
      
    </div> 

    <div class="row">

      <div class="col-sm-3 form-group">
        <label for="Product Type">Product Type </label><input type="text" class="form-control" name="txt_type" placeholder="Food" id ="t_type" required>
      </div>

      <div class="col-sm-3 form-group">
        <label for="Product Sub Type">Product Subtype </label>
        <input type="text" class="form-control" name="txt_sub_type" placeholder="Beverage" id ="t_sub_type">
      </div>

    </div>
    
    <div class="row">

      <div class="col-sm-3 form-group">
        <label for="Case Size">Case Size</label>
        <input type="number" class="form-control" name="txt_case_size" placeholder="20" step=".01" id ="t_cases">
      </div>

      <div class="col-sm-3 form-group">
        <label for="Units">Case Size</label>
        <select class="form-control" name="txt_unit" id ="t_units" >
          <option value="ltrs">Ltrs</option>
          <option value="pcs">Pcs / Packets</option>
          <option value="kgs">Kgs</option>
          <option value="btls">Bottles</option>
          <option value="carton">Cartons</option>
          <option value="gal">Gallons</option>
        </select>
      </div>

    </div>

            
    <div>
            <BUTTON type="submit" class="btn btn-primary" id="add_prod">Add Product</BUTTON>
            <button type="button" class="btn btn-warning" id="addnew" disabled>Add New Product</button>
            <span id="res"></span>
          
    </div>
  
    <div style="height:100px"></div>
    <div id="resdiv"></div>
</div>

</body>
</html>
