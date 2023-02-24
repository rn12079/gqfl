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

  $comp_id = $_GET['id'];
?>


<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
<link href="select2/select2.min.css" rel="stylesheet" />
<link href="bootstrap1/css/bootstrap.min.css" rel="stylesheet">
<title>GQFL - Assign Products</title>
<style>
select {
  min-width:300px;
}


.grid-container {
  max-width: 100%;
  display: grid;
  grid-column-gap: 20px;
  grid-template-columns: auto 100px auto auto;
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
<script src="tabledata.js"></script>





<body>
  <?php include('navbar.html');
  $bool_upload = false;
  $bool_succ = false;

  if (!$logged) {
      echo "<div class='container' style='margin-top:10;'>";
      err_alert("<strong>Access Denied</strong> Please log in to access");
      echo "</div>";
      die;
  }

  ?>

    <!-- JUMPOTRON -->
  <div class="jumbotron">
    <div class="container">
      Assign Products to <strong>  
        <?php
          $supname = new Companies();
          $res = $supname->getCompanyById($comp_id);
          echo $res->name;
        ?>
      </strong>
   </div>
 </div>
  <div class="container">
  <div class="grid-container">
      <div class="grid-item">
          
          <select  id="pl" size="20" multiple>
              <?php
                
                $myprods = new Prods_assign();
                $prods = $myprods->getRemaining_ProductsByCompany($comp_id);

                foreach ($prods as $prod) {
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
                $prods = $myprods->getProductsByCompany($comp_id);

                foreach ($prods as $prod) {
                    echo "<option value=".$prod->id." disabled>".$prod->product_name."</option>";
                }
              ?>

          </select>
        </div>
        
        <div class="grid-item">
            <button id="assignproducts" class="btn btn-dark">Assign</button>
        </div>
    
     
  </div>
     
</div>
</body>
<script type="text/javascript">
document.addEventListener("DOMContentLoaded",() => {
  const fwd = document.getElementById("fwd");
  const bwd = document.getElementById("bwd");
  const btn_assign = document.getElementById("assignproducts");

  
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

  const urlparam = new URLSearchParams(window.location.search);
  const id = urlparam.get('id');

  btn_assign.addEventListener("click", ()=> {
    const sel = document.getElementById("prods_avail");
    const prod_ids = Array.from(sel).map(el => el.value);
    console.log(prod_ids);
    console.log(id);

    const data = {
      purpose: "assignprodtocomp",
      prod_ids,
      id
    }

    postData('setdata.php',data).then(res => {
      console.log(res);
    })


  })

})
</script>
</html>
