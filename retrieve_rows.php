

<?php
include('db_conn.php');
header("Content-Type: application/json; charset=UTF-8");

$myjson = json_decode($_POST["x"], false);

//echo "ret_field: ". $myjson->ret_field." ptype: " . $myjson->ptype . " pname: " . $myjson->pname . " supp: " . $myjson->supplier;
//echo " stype: " . $myjson->stype;
//echo " receiver: " .$myjson->receiver;

$filters=array();
$n_filters=0;

if ($myjson->pname!=""){
  $st_name=" product_name='" .$myjson->pname."'";
  $n_filters = $n_filters+1;
  array_push($filters, $st_name);
}
if ($myjson->receiver!=""){
  $st_receiver=" receiver='" .$myjson->receiver."'";
  $n_filters = $n_filters+1;
  array_push($filters,$st_receiver);
}
if ($myjson->supplier!=""){
  $st_supplier=" supplier='" .$myjson->supplier."'";
  $n_filters = $n_filters+1;
  array_push($filters,$st_supplier);
}
if ($myjson->ptype!=""){
  $st_ptype=" product_type='" .$myjson->ptype."'";
  $n_filters = $n_filters+1;
  array_push($filters,$st_ptype);
}
if ($myjson->stype!=""){
  $st_stype=" product_sub_type='" .$myjson->stype."'";
  $n_filters = $n_filters+1;
  array_push($filters,$st_stype);
}

//echo "total filters : " . $n_filters . "<br>";


$myquery = "select distinct ".$myjson->ret_field." from products p ";
$myquery = $myquery . "inner join inventory i on i.product_id=p.id where del=0 ";

//if($n_filters>0)
//  $myquery= $myquery . "where ";

for ($i = 0 ; $i < sizeof($filters) ; $i++){
//  if($i!=0) 
    $myquery = $myquery." and ";

  $myquery = $myquery.$filters[$i];

}

//echo $myquery;

$conn = new mysqli($GLOBALS['host'],$GLOBALS['dbuser'],$GLOBALS['dbpass'],$GLOBALS['db']);
  if ($conn->connect_error)
{
  die('Could not connect: ' . $con->connect_error);
}

$result = $conn->query($myquery);

if($result->num_rows > 0) {
  while($row = $result->fetch_assoc()){

  	echo "<option value='".$row["$myjson->ret_field"]."'>". $row["$myjson->ret_field"]   ."</option>";


  }
}
mysqli_close ($conn);

?>

