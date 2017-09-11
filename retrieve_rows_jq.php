

<?php
header("Content-Type: application/json; charset=UTF-8");






$myquery = "select distinct product_name from products p ";
$myquery = $myquery . "inner join inventory i on i.product_id=p.id ";


$conn = new mysqli("localhost","qasim","","mujju");
if ($conn->connect_error)
{
  die('Could not connect: ' . $con->connect_error);
}

$result = $conn->query($myquery);
$ans = array();
while($row = $result->fetch_assoc())
  $ans[] = $row;


mysqli_close ($conn);

echo json_encode($ans);



?>

