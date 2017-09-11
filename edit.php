<?php

echo "this is edit page";
/*$con = mysql_connect("localhost","qasim","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("mujju", $con);


$product_code = $_POST["product_code"];

$myquery="select * from item where product_code like '$product_code%'";

$result = mysql_query($myquery);
while($row = mysql_fetch_array($result)) 
{
  echo "<form action='edit.php' method='POST' enctype='multipart/form-data' style='font-size:8pt'>";
  echo "<tr><td><input type='text' disabled value=" . $row['product_code'] . "></td><td>";
  echo $row['product_name'] . "</td><td>" . $row['sex'] . "</td><td>";
  echo $row['price'] . "</td>";
  echo "<td><input type='text' size=5 value=" . $row['s_S'] . "></td>";
  echo "<td><input type='text' size=5 value=" . $row['s_M'] . "></td>";
  echo "<td><input type='text' size=5 value=" . $row['s_L'] . "></td>";
  echo "<td><input type='text' size=5 value=" . $row['s_XL'] ."></td><td>" . $row['descript'] . "</td><td>";
  echo "<img src='" . $row['img_path'] ."' width=100px height=100px></td><td><input type='submit' value='Edit'></td></tr>";
  }



mysql_close($con);*/
?>



