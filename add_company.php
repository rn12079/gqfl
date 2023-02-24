<?php
/*
function err_alert($err_msg){
echo "<div class='alert alert-danger' role='alert'>".$err_msg."</div>";
}
*/
include('alerts.php');
include_once('db_conn.php');


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
  <script   src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="tabledata.js"></script>
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

<script type="text/javascript">
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
     <h1><SMALL> Add New Company </SMALL></h1>
   </div>
 </div>

 <div class="container">
   <div class="row">
     <label for="location">Company Name : </label>
     <input class="form-control" type="text" placeholder="Mozz'art" id="company_name"/>
   </div>

   <div class="row">
     <label for="location">Company Type : </label>
     <input class="form-control" type="text" placeholder="pizza/burger" id="company_type"/>
   </div>
   
   <div class="gap" style="margin-bottom:20px"></div>
   <div class="row">
     <button class='btn btn-info' id="company_create">Create Company</button>
   </div>
  <div class="gap" id="gap" style="margin-bottom:20px"></div>

  <div id="results">

  </div>
 

 </div>
</body>
<script>
  

  const updres = () => {
    const results = document.getElementById("results");
    results.innerHTML = "";
    let response = "";
    data = {purpose: "get_companies"};
    
    postData('getdata.php',data).then(companies=>{
      
      
      response = `<table class='table small table-striped table-bordered table-hover table-condensed'>`;
      response += `<tr><td>company id</td><td>company name</td><tr>`;
      console.log(response);
      companies.forEach(company => {
        response += `<tr><td>${company.id}</td><td>${company.name}</td></tr>`;
        console.log(response);
      });
      response += "</table>";
      results.innerHTML = response; 
    });
    
  };




  document.addEventListener("DOMContentLoaded",() => {
    updres();

    const putloc = document.getElementById("company_create");
    putloc.addEventListener("click",() =>{

      //putloc.disabled= true;
      const sitename=document.getElementById("company_name").value;
      const sitetype=document.getElementById("company_type").value;

      const sitedata = {
        purpose: "add_company",
        name: sitename,
        type: sitetype
      };

      postData('addlocs.php',sitedata).then(res => {
        //console.log(res);
        //document.getElementById("gap").innerHTML = res;
        document.getElementById("gap").innerHTML = res;
        updres();
      })
      


    })




  })


</script>

</html>

