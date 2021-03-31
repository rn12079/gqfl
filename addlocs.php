<?php
include('alerts.php');
include_once('db_conn.php');

header('Content-Type: application/json');
$test = json_decode(file_get_contents("php://input"), true);

$name = $test['name'];
$type = $test['type'];


if (isset($test['purpose']) && $test['purpose'] =='add_company') {
    $compctrl = new Companies();
    $compctrl->addCompany($name);
    echo json_encode("in add company");
    // if ($res==1) {
    //     echo json_encode('added successfully');
    // } else {
    //      die('failed');
    //  }
}

if (isset($test['purpose']) && $test['purpose'] =='add_location') {
    $company = $test['company'];
    $locctrl = new Locations();
    $res = $locctrl->addLocation($name, $type, $company);

    if ($res==1) {
        echo json_encode('added successfully');
    } else {
        die('failed');
    }
}



?>

