<?php
  
include('alerts.php');
session_start();
$errmsg = "";
$logged = false;

if (!$_SESSION["loggedin"]) {
    $logged = false;
} else {
    if ($_SESSION["level"] == "superadmin" ) {
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
      const submit = document.getElementById("add_supplier");
      const status = document.getElementById("res");

      submit.addEventListener("click",() => {
        //console.log('submit clicked');
        submit.disabled = true;

        //getting values from FORM
        const t_name = document.getElementById("t_name").value;
        const t_address = document.getElementById("t_address").value;
        const t_contact = document.getElementById("t_contact").value;
        const t_number = document.getElementById("t_number").value;
        
        if(t_name == "" || t_address == "" || t_contact == "" || t_number == "" )
          {
              console.log("data empty");
              status.classList = "";
              status.classList.add("label","label-danger");
              status.innerHTML = "Required fields empty"
              return ;
          }
        
        //Preparing data
        const data = {
          purpose: 'add_supplier',
          t_name,
          t_address,
          t_contact,
          t_number
          
        }

        //sending to setdata for inserting into database
        postData("setdata.php",data).then(res => {
          console.log("successful");
          console.log(res);
          status.classList = "";
          status.classList.add("label","label-success");
          status.innerHTML = "Supplier Addition Successful";
          return "success";

          }).catch( e => {
          console.log("addition failed");
          status.classList = "";
          status.classList.add("label","label-danger");
          status.innerHTML = "Supplier addition failed";
        });


      })

    })
  
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
     <h1><SMALL> New Supplier </SMALL></h1>
   </div>
 </div>

 <div class="container">
    
    <div class="form-group">
      <label for="Product Name">Supplier Name </label>
      <input type="text" class="form-control" name="txt_name" placeholder="xyz traders" id ="t_name" required>
    </div>

    <div class="form-group">
      <label for="Product Maker">Supplier Address</label>
      <input type="text" class="form-control" name="txt_source" placeholder="falcon street" id ="t_address" reqiored>
      
    </div> 

    <div class="form-group">
      <label for="Product Maker">Supplier Contact Person</label>
      <input type="text" class="form-control" name="txt_source" placeholder="Mr. Irfan" id ="t_contact" reqiored>
      
    </div> 

    <div class="form-group">
      <label for="Product Maker">Supplier Phone</label>
      <input type="number" class="form-control" name="txt_source" placeholder="0333-0000000" id ="t_number" reqiored>
      
    </div> 


    <BUTTON type="submit" class="btn btn-primary" id="add_supplier">Add Supplier</BUTTON>
    <span id="res"></span>
          
    </div>
  
</div>
<div style="height:400px"></div>
</body>
</html>
