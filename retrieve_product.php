

<?php

$conn = new mysqli("localhost","qasim","","mujju");
if ($conn->connect_error)
{
	die('Could not connect: ' . $conn->connect_error);
}

$product_code = $_POST["retrieve"];
$filter = $_POST["filter"];


if ($product_code=="product_name"){
	$field='product_name';
	$supplier=$_POST["supplier"];
}
if ($product_code=="supplier")
	$field='supplier';
if ($product_code=="product_type")
	$field='product_type';
if ($product_code=="product_sub_type"){
	$field='product_sub_type';
	$product_code="product_sub_type";
	$filter=$_POST["filter"];
	$product_type=$_POST["type"];
	$supplier=$_POST["supplier"];
}

if($filter=="")
	$myquery="select distinct ".$product_code." from products";
if($product_code=="product_name"&&$filter!="")
	$myquery="select distinct ".$product_code." from products where product_type='".$filter."'";
if($product_code=="product_name"&&$supplier!="")
	$myquery="select concat(casesize,' ',units,' || ',maker) maker,".$product_code." from products where supplier='".$supplier."'";
if($product_code=="supplier"&&$filter!="")
	$myquery="select distinct ".$product_code." from products where product_name='".$filter."'";
if($product_code=="product_sub_type")
	$myquery = "select distinct ".$product_code." from products where product_name='".$filter."' and product_type='".$product_type."' and supplier='".$supplier."'";

$myquery=$myquery. " order by ".$product_code;
$result = $conn->query($myquery);


while($row = $result->fetch_assoc()) 
{
	if($field=='product_name')
		echo "<option value='".$row[$field]."'>".$row['maker']."</option>";
	else if($field=='product_sub_type')
		echo $row[$field];
	else
		echo "<option value='".$row[$field]."'>".$row[$field]."</option>";
	
	
}




mysqli_close ($conn);

?>

