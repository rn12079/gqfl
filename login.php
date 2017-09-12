<?php 

session_start();
if(isset($_POST['sub']))
	if($_POST['pass'] == "hello"){
		$_SESSION['loggedin'] = true;
		echo "successfully logged in <br> ";
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