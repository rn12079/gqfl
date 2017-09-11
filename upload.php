<html>
<head>
<style type="text/css">
p {
  font-size:10px;
}

</style>
<?php
$allowedExts = array("jpg", "jpeg", "gif", "png","JPG","JPEG","GIF","PNG");
$extension = end(explode(".", $_FILES["file"]["name"]));
$filetemp = $_FILES["file"]["name"];
$filename = str_replace(" ","_",$filetemp);
//echo $filename;
//echo $extension;
$ofile = "upload/".$filename;
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/png")
|| ($_FILES["file"]["type"] == "image/pjpeg"))
&& ($_FILES["file"]["size"] < 5000000)
&& in_array($extension, $allowedExts))
  {
  if ($_FILES["file"]["error"] > 0)
    {
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }
  else
    {
    
    if (file_exists("upload/" . $filename))
      {
     echo $filename . " already exists. Please use a different file name";
     $check=0;
      }
      else
	  {
	move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $filename);
	echo "Stored in: " . "upload/" . $filename."<br>";
	echo "<p>Upload: " . $filename . "<br>";
	echo "Type: " . $_FILES["file"]["type"] . "<br>";
	echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
	echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br></p>";

      
      $check=1;
	}
    }
  }
else
  {
  echo "<td class='smfonts'>ooops, there was some error, please try again! :)</td></tr>";
}

if($check==1){
$txt_code = $_POST["txt_code"];
$txt_name = $_POST["txt_name"]; 
$sex = $_POST["sex"];
$price = $_POST["num_price"];
$num_S = $_POST["num_S"];
$num_M = $_POST["num_M"];
$num_L = $_POST["num_L"];
$num_XL = $_POST["num_XL"];
$description = $_POST["txt_desc"];
$file_path = $_POST["file"];

$myquery="insert into item(product_code,product_name,sex,price,s_S,s_M,s_L,s_XL,descript,img_path) values('";
$myquery = $myquery . $txt_code . "','" . $txt_name . "','" . $sex . "'," . $price . "," . $num_S;
$myquery= $myquery . "," . $num_M . "," . $num_L . "," . $num_XL . ",'" . $description . "','upload/" . $filename . "')";
echo "<p>" . $myquery." </p><br>";

$myquery1="select * from item";

$con = mysql_connect("localhost","qasim","");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("mujju", $con);
/*$result = mysql_query($myquery1);
while($row = mysql_fetch_array($result)) 
  {
  echo $row['product_code'] . "\t" . $row['product_name'] . "\t" . $row['sex'] . "\t";
  echo $row['price'] . "\t" . $row['s_S'] . "\t" . $row['s_M'] . "\t";
  echo $row['s_L'] . "\t" . $row['s_XL'] . "\t" . $row['descript'] . "\t" . $row['img_path'];
  echo "\n";
  }


*/
if (!mysql_query($myquery))  {
  unlink("upload/" . $filename);
  echo "<br> Insert query failed. Image also deleted </br>";
  die('Error: ' . mysql_error($con));
  
}
echo "Successful :  1 record added + image uploaded";
mysql_close($con);
}
?>



