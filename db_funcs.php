<?php 

function getlocs() {

  $myquery = "select name,type from locations";
  $conn = new mysqli("localhost","qasim","","mujju");
  if ($conn->connect_error)
  {
    die('Could not connect: ' . $con->connect_error);
  }

  $result = $conn->query($myquery);

  $ans = array();

  while($row = $result->fetch_assoc())
  $ans[] = $row;

return $ans;

$conn->close();

}

function getsups() {

  $myquery = "select distinct supplier id,supplier text from products";
  $conn = new mysqli("localhost","qasim","","mujju");
  if ($conn->connect_error)
  {
    die('Could not connect: ' . $con->connect_error);
  }

  $result = $conn->query($myquery);

$ans = array();

  while($row = $result->fetch_assoc())
  $ans[] = $row;



return json_encode($ans);

$conn->close();

}


function getprods($prod_id) {

  $myquery = "select distinct p.id,product_name,coalesce(concat(casesize,units,' | ',maker),'na') as hint from products p ";
  $myquery = $myquery . "inner join inventory i on i.product_id=p.id where p.id = '".$prod_id."'";

  $conn = new mysqli("localhost","qasim","","mujju");
  if ($conn->connect_error)
  {
    die('Could not connect: ' . $con->connect_error);
  }

  $result = $conn->query($myquery);



  $row = $result->fetch_assoc();
  $ans = $row['product_name'] . " || " . $row['hint'];



return $ans;

$conn->close();

}

function get_current_prices() {

  $myquery = "select * from current_prices order by supplier,product_name";
  
  $conn = new mysqli("localhost","qasim","","mujju");
  if ($conn->connect_error)
  {
    die('Could not connect: ' . $conn->connect_error);
  }

  if($result = $conn->query($myquery)){
  //  print_r($result);
  return $result;

  $conn->close();
  }
  echo $conn->error;

}


?>