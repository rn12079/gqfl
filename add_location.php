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
  <script   src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
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

  if(!$logged) {
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


  //post or retrieve data depending on URL and data sent
// for post form using addparty.fetch.php , for retrieving tabledata.fetch.php
const postData = async (url, data) => {
  //console.log("postdata", data);
  const response = await fetch(url, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(data)
  });
  return await response.json();
};

  document.addEventListener("DOMContentLoaded",() => {
    updres();

    const putloc = document.getElementById("loc_create");
    putloc.addEventListener("click",() =>{

      //putloc.disabled= true;
      const sitename=document.getElementById("locname").value;
      const sitetype=document.getElementById("loctype").value;

      const sitedata = {
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

