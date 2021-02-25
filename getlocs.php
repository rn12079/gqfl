<?php
include "db_conn.php";
 
        $locctrl = new Locations();
        $locs = $locctrl->getLocations();
        $res = "<table class='table small table-striped table-bordered table-hover table-condensed'><tbody>
        <tr><th>Site Name</th><th>Site Type</th></tr>";
        foreach($locs as $loc){
          $res = $res. "<tr><td>".$loc->name."</td><td>".$loc->type."</td></tr>";
        }
        $res = $res. "<tbody></table>";

        echo json_encode($res);
   ?>