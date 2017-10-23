<?php 

session_start();
if(isset($_POST['sub']))
	if($_POST['pass'] == "hello"){
		$_SESSION['loggedin'] = true;
		$_SESSION['user'] = 'admin';
		$_SESSION['allowed_location'] = 'all';
		$_SESSION['edit_allowed'] = true;
		$_SESSION['del_allowed'] = true;


		echo "successfully logged in  as <strong>Admin</strong> <br>";
		echo "add inventory item <a href='add_invent.php'> here </a>";	

	}
	elseif ($_POST['pass'] == 'warehouse'){
		$_SESSION['loggedin'] = true;
		$_SESSION['user'] = 'warehouse';
		$_SESSION['allowed_location'] = 'warehouse';
		$_SESSION['edit_allowed'] = true;
		$_SESSION['del_allowed'] = false;		

		echo "successfully logged in as <strong>Warehouse</strong> <br> ";
		echo "add inventory item <a href='add_invent.php'> here </a>";
	}

	else
		echo "wrong password";

?>



<html>
<form method="post">
<label for="pass">Password<input type="Password" name="pass">
<input type="submit" name="sub">


</html>