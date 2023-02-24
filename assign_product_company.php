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
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="select2/select2.min.css" rel="stylesheet" />
<link href="bootstrap1/css/bootstrap.min.css" rel="stylesheet">
<title>GQFL - Assign Products</title>
<style>

label {

  padding-top: 4px;

}
  .navbar{
    margin-bottom:0;
    border-radius: 0;
  }
  .jumbotron{
    margin-bottom: 0;
    
  }
  .form-group {

    margin-bottom:2px;


  }


</style>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script src="bootstrap1/js/bootstrap.min.js"></script>
<script src="select2/select2.min.js"></script>
<script src="tabledata.js"></script>


<script>

const getcompanies = () => {
    const sup = document.getElementById("companies").value;
    //console.log(sup);
    data = {
      purpose: 'get_companies',
      suptext: sup
    }
    console.log(data);
    const resdiv = document.getElementById("results");
    postData('getdata.php',data).then(res => {
      ans = "<table class='table small table-striped table-bordered table-hover table-condensed'>";
      res.forEach(sup => {
        ans += `<tr><td>${sup.name}</td>
        <td><a href='assignprodtocomp.php?id=${sup.id}' class='btn btn-info btn-xs'>Assign Products</a></td></tr>`;

      
      })
      ans += '</table>';

      console.log(ans)
      resdiv.innerHTML = ans;


    })



};

</script>




</head>
<body>
 <?php include('navbar.html');

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
      <strong>Assign Products to Company</strong>
   </div>
 </div>


 <div class="container">

    <label for="Supplier">Company</label>
    <input type='text' id='companies' placeholder="Hardee's" onkeyup='getcompanies()'/>
    
    <div id='results'>

    </div>

    </div>
    <div style="height:400px"></div>
  </body>
  

  </html>
