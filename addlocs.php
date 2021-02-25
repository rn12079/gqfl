<?php
include('alerts.php');
include_once('db_conn.php');

header('Content-Type: application/json');
$test = json_decode(file_get_contents("php://input"), true);

$name = $test['name'];
$type = $test['type'];

$locctrl = new Locations();
$res = $locctrl->addLocation($name,$type);

  if($res==1)
   echo json_encode('added successfully');
   else
   die('failed');



?>

