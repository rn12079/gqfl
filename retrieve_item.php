<table border=1>
  <tr><td>Product code</td><td>Product Name</td><td>Sex</td><td>Price</td><td>S Size</td><td>M Size</td><td>L Size</td><td>XL Size</td>
    <td>description</td><td>Image</td></tr>

    <?php

    function getSex($sex)
    {
        if ($sex=='M') {
            return "Men";
        }
        if ($sex=='W') {
            return "Women";
        }
        if ($sex=='C') {
            return "Children";
        }
    }


    $con = mysql_connect("localhost", "qasim", "");
    if (!$con) {
        die('Could not connect: ' . mysql_error());
    }

    mysql_select_db("mujju", $con);


    $product_code = $_POST["product_code"];

    $myquery="select * from item where product_code like '$product_code%'";
    $myquery1="select count(*) cnt from ($myquery) abc";
    $result = mysql_query($myquery);
    $count = mysql_query($myquery1);
    $cnt = mysql_fetch_array($count);
    while ($row = mysql_fetch_array($result)) {
        echo "<form action='edit.php' method='post'>";
        echo "<tr><td><input type='text' id='prod_code' disabled value=" . $row['product_code'] . "></td><td>";
        echo $row['product_name'] . "</td><td>" . getSex($row['sex']) . "</td><td>";
        echo $row['price'] . "</td>";
        echo "<td><input type='text' size=5 value=" . $row['s_S'] . "></td>";
        echo "<td><input type='text' size=5 value=" . $row['s_M'] . "></td>";
        echo "<td><input type='text' size=5 value=" . $row['s_L'] . "></td>";
        echo "<td><input type='text' id='new_XL' size=5 value=" . $row['s_XL'] ."></td><td>" . $row['descript'] . "</td><td>";
        echo "<img src='" . $row['img_path'] ."' width=100px height=100px></td>";
        if ($cnt['cnt']==1) {
            echo "<td><input type='button' value='Edit' onclick='updateqty()'></td></tr></form>";
        } else {
            echo "</tr></form>";
        }
    }



    mysql_close($con);
    ?>
  </table>
  <a href="http://www.google.com">google</a>

