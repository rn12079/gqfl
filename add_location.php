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
     <h1><SMALL> Add New location </SMALL></h1>
   </div>
 </div>

 <div class="container">
  <div class="row">
     <label for="location">Company : </label>
     <select class="form-control" id="company">
     </select>
   </div>
   
   <div class="row">
     <label for="location">Site Name : </label>
     <input class="form-control" type="text" placeholder="b-20 v-1" id="locname"/>
   </div>

   <div class="row">
     <label for="location">Site Type : </label>
     <input class="form-control" type="text" placeholder="house/showroom" id="loctype"/>
   </div>
   
   <div class="gap" style="margin-bottom:20px"></div>
   <div class="row">
     <button class='btn btn-info' id="loc_create">Create Location</button>
   </div>
  <div class="gap" id="gap" style="margin-bottom:20px"></div>

  <div id="results">

  </div>
 

 </div>
</body>
<script>
  

  const updres = () => {
    results.innerHTML = "";
    data = {};
    postData('getlocs.php',data).then(getlocs=>{
      results.innerHTML = getlocs;
      console.log(getlocs);
    })

  };




  document.addEventListener("DOMContentLoaded",() => {
    updres();

    const companies = document.getElementById("company");
    const ret_comp_data = {
      purpose: "get_companies"
    }
    getSelectOptions(ret_comp_data,companies).then(res => {
      console.log("options retrieved");
    })

    const putloc = document.getElementById("loc_create");
    putloc.addEventListener("click",() =>{

      //putloc.disabled= true;
      const sitename=document.getElementById("locname").value;
      const sitetype=document.getElementById("loctype").value;

      const sitedata = {
        purpose: "add_location",
        company: company.value,
        name: sitename,
        type: sitetype
      };

      console.log(sitedata);

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

