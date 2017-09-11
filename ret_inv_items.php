

<?php
header("Content-Type: application/json; charset=UTF-8");

$myjson = json_decode($_POST["x"], false);



$myquery = "select date,product_name,cases,amount,invoice_img_ref from products p ";
$myquery = $myquery . "inner join inventory i on i.product_id=p.id where ";
$myquery = $myquery . "receiver='".$myjson->receiver."' and ";
$myquery = $myquery . "supplier='".$myjson->supplier."' and ";
$myquery = $myquery . "invoice_ref='".$myjson->invoice_ref."' and ";
$myquery = $myquery . "i.id!='".$myjson->id."' ";
$myquery = $myquery . "and del=0 ";


$conn = new mysqli("localhost","qasim","","mujju");
if ($conn->connect_error)
{
	die('Could not connect: ' . $conn->connect_error);
}

$result = $conn->query($myquery);

if($myjson->retfield=="all_fields"){
	if($result->num_rows > 0) {
		echo "<table border='1px' width='500px'><tr><td colspan=5>Existing items on same invoice</td></tr>";
		echo "<tr><td>date</td><td>product name</td><td>qty</td><td>amount</td><td>Ref</td></tr>";
		while($row = $result->fetch_assoc()){

			echo "<tr><td>".$row["date"]."</td><td>".$row["product_name"]."</td><td>".number_format($row["cases"])."</td>";
			echo "<td>".number_format($row["amount"],2,'.',',')."</td><td><a target='_blank' href='".$row["invoice_img_ref"]."'>".$row["invoice_img_ref"]."</td></tr>";



		}
		echo "</table>";
	}
}
else
{
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){

			echo "<option>".$row["invoice_img_ref"]."</option>";



		}
	}
}
mysqli_close ($conn);

?>

