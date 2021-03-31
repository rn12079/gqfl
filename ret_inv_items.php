

<?php
include_once('db_conn.php');
header("Content-Type: application/json; charset=UTF-8");

$myjson = json_decode($_POST["x"], false);



$myquery = "select date,product_name,cases,amount,invoice_img_ref from products p ";
$myquery = $myquery . "inner join inventory i on i.product_id=p.id where ";
$myquery = $myquery . "receiver='".$myjson->receiver."' and ";
$myquery = $myquery . "supplier='".$myjson->supplier."' and ";
$myquery = $myquery . "invoice_ref='".$myjson->invoice_ref."' and ";
$myquery = $myquery . "i.id!='".$myjson->id."' ";
$myquery = $myquery . "and del=0 ";


$conn = new mysqli($GLOBALS['host'], $GLOBALS['dbuser'], $GLOBALS['dbpass'], $GLOBALS['db']);
  if ($conn->connect_error) {
      die('Could not connect: ' . $conn->connect_error);
  }

//echo $myquery;

$result = $conn->query($myquery);


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option>".$row["invoice_img_ref"]."</option>";
        }
    }

mysqli_close($conn);

?>

