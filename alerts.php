<?php 

function err_alert($err_msg){
	echo "<div class='alert alert-danger' role='alert'>".$err_msg."</div>";
}

function succ_alert($succ_msg){
	echo "<div class='alert alert-success' role='alert'>".$succ_msg."</div>";
}

function warn_alert($succ_msg){
	echo "<div class='alert alert-warning' role='alert'>".$succ_msg."</div>";
}

?>
