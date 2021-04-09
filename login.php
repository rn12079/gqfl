<?php


include('alerts.php');

session_start();
$admin =false;

if (isset($_POST['sub'])) {
    if ($_POST['pass'] == "superuser") {
        $_SESSION['loggedin'] = true;
        $_SESSION['level'] = 'superadmin';
        $_SESSION['allowed_location'] = 'all';
        $_SESSION['edit_allowed'] = true;
        $_SESSION['del_allowed'] = true;

        $logged = true;
        $admin = true;
    }elseif ($_POST['pass'] == "admin") {
      $_SESSION['loggedin'] = true;
      $_SESSION['level'] = 'admin';
      $_SESSION['allowed_location'] = 'all';
      $_SESSION['edit_allowed'] = true;
      $_SESSION['del_allowed'] = true;

      $logged = true;
      $admin = true;
  } elseif ($_POST['pass'] == "hello") {
        $_SESSION['loggedin'] = true;
        $_SESSION['level'] = 'user';
        $_SESSION['allowed_location'] = 'all';
        $_SESSION['edit_allowed'] = true;
        $_SESSION['del_allowed'] = true;

        $logged = true;
        $admin = true;
    } elseif ($_POST['pass'] == 'pakistan') {
        $_SESSION['loggedin'] = true;
        $_SESSION['level'] = 'viewer';
        $_SESSION['allowed_location'] = 'viewer';
        $_SESSION['edit_allowed'] = true;
        $_SESSION['del_allowed'] = false;

        $logged = true;
    } else {
        $logged = false;
    }
}
?>


<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    
   if (!$logged) {
       if (isset($_POST['sub'])) {
           echo "<div class='container' style='margin-top:10;'>";
           err_alert("<strong>Access Denied</strong> Please log in to access");
           echo "</div>";
       }
   } else {
       echo "<div class='container' style='margin-top:10;'>";
       succ_alert("<strong>Successfully logged </strong> ");
    
    
       if ($admin) {
           echo "add inventory item <a href='add_invent.php'> here </a>";
       } else {
           echo "goto inventory page <a href='show_inventory.php'> here </a>";
       }


       echo "</div>";
       die;
   }




  ?>

<div class="jumbotron text-center">
    <div class="container">
     <h1><SMALL> Log in</SMALL></h1>
   </div>
 </div>

 <div class="container">


<form method="post">
<label for="pass">Password<input type="Password" name="pass">
<input type="submit" name="sub">

</div>

</html>