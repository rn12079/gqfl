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
<link href="select2/select2.min.css" rel="stylesheet" />
<link href="bootstrap1/css/bootstrap.min.css" rel="stylesheet">
<title>GQFL - Add Inventory</title>
<style>
select {
  min-width:300px;
}


.grid-container {
  max-width: 100%;
  display: inline-grid;
  grid-column-gap: 20px;
  grid-template-columns: auto auto auto;
  text-align: center;
  align-items: center;
}


label {

  padding-top: 4px;

}
  .navbar{
    margin-bottom:0;
    border-radius: 0;
}

button {
  margin: 20px;
}


</style>
<script src="jquery/jquery-3.2.1.min.js"></script>
<script src="bootstrap1/js/bootstrap.min.js"></script>
<script src="select2/select2.min.js"></script>






<body>
  <?php include('navbar.html');
  $bool_upload = false;
  $bool_succ = false;

  if(!$logged) {
    echo "<div class='container' style='margin-top:10;'>";
    err_alert("<strong>Access Denied</strong> Please log in to access");
    echo "</div>";
    die;
  }

  ?>

    <!-- JUMPOTRON -->
  <div class="jumbotron">
    <div class="container">
      <strong>Assign Products</strong>
   </div>
 </div>
  <div class="container">
  <div class="grid-container">
      <div class="grid-item">
          <select  id="pl" size="20" multiple>
              <?php 
                
                $myprods = new Prods_assign();
                $prods = $myprods->getProducts();

                foreach($prods as $prod){
                echo "<option value=".$prod->id.">".$prod->product_name."</option>";
                }
              ?>
          </select>
        </div>
        <div class="grid-item">
          <button id="fwd"> >>> </button>
          <button id="bwd"> <<< </button>
        </div>
        
        <div class="grid-item">
          <select id="prods_avail" size="20" multiple>
              <?php 
                
                $myprods = new Prods_assign();
                $prods = $myprods->getProductsByCompany($compid);

                foreach($prods as $prod){
                echo "<option value=".$prod->id.">".$prod->product_name."</option>";
                }
              ?>

          </select>
        </div>
    
     
  </div>
     
</div>
</body>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded",() => {
  const fwd = document.getElementById("fwd");
  const bwd = document.getElementById("bwd");
  
  const selbox = document.getElementById("pl");
  const assbox = document.getElementById("prods_avail");

  fwd.addEventListener("click", () => {
    console.log("fwd pressed");
    const sel = document.querySelectorAll("#pl option:checked");

    const names = Array.from(sel).map(el => el.text);
    const values= Array.from(sel).map(el => el.value);
    const index = Array.from(sel).map(el => el.index);
    //console.log(values)
    let cnt=0;
    for(i=0;i<names.length;i++) {

      let myop = document.createElement("option");
      myop.value = values[i];
      myop.text = names[i];
      console.log("fwd",myop);
      assbox.add(myop);
    }
    
    index.forEach(ind =>{ 
      selbox.remove(ind-cnt);
      console.log(ind)
      cnt++;
      
    })


  })


  bwd.addEventListener("click", () => {
    console.log("bwd pressed");
    const sel = document.querySelectorAll("#prods_avail option:checked");

    const names = Array.from(sel).map(el => el.text);
    const values= Array.from(sel).map(el => el.value);
    const index = Array.from(sel).map(el => el.index);
    //console.log(values)
    let cnt=0;
    for(i=0;i<names.length;i++) {

      let myop = document.createElement("option");
      myop.value = values[i];
      myop.text = names[i];
      console.log("bwd",myop);
      selbox.add(myop);
      
      
    }

    index.forEach(ind =>{ 
    assbox.remove(ind-cnt);
    console.log(ind)
    cnt++;
  
  })


})

})
</script>
</html>
