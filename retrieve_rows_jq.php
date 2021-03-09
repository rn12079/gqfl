<?php
include('db_conn.php');

header("Content-Type: application/json; charset=UTF-8");

$myquery = "select distinct p.id,product_name text,coalesce(concat(casesize,units,' | ',maker),'na') as hint from products p ";
$myquery = $myquery . "left join inventory i on i.product_id=p.id where product_name like '%".$_GET['q']."%'";
if ($_GET['s']!="")
	$myquery = $myquery . "and supplier = '".$_GET['s']."'";
if ($_GET['r']!="")
	$myquery = $myquery . "and receiver = '".$_GET['r']."'";
if ($_GET['pt']!="")
	$myquery = $myquery . "and product_type = '".$_GET['pt']."'";
if ($_GET['st']!="")
	$myquery = $myquery . "and product_sub_type = '".$_GET['st']."'";


$conn = new mysqli($GLOBALS['host'],$GLOBALS['dbuser'],$GLOBALS['dbpass'],$GLOBALS['db']);
  if ($conn->connect_error)
{
  die('Could not connect: ' . $conn->connect_error);
}

$result = $conn->query($myquery);
$ans = array();
while($row = $result->fetch_assoc())
  $ans[] = $row;


mysqli_close ($conn);

echo json_encode($ans);



?>

