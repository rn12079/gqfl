<?php

include_once("db_conn.php");


//weird method to get POST variables in JSON;
header('Content-Type: application/json');
$test = json_decode(file_get_contents("php://input"), true);

$purpose = $test['purpose'];
$sup = $test['suptext'];
$id = $test['id'];

if (isset($test['purpose']) && $purpose=="getsuppliers") {
    $obj = new Suppliers;
    $res = $obj->getSuppliersByName($sup);
    echo json_encode($res);
}

if (isset($test['purpose']) && $purpose=="get_products") {
    $obj = new Prods_assign;
    $res = $obj->getProductByName($sup);
    echo json_encode($res);
}

if (isset($test['purpose']) && $purpose=="get_companies") {
    $obj = new Companies();
    $res = $obj->getAllCompanies($sup);
    echo json_encode($res);
}

if (isset($test['purpose']) && $purpose=="getSuppliersByCompanyId") {
    $obj = new Suppliers();
    $res = $obj->getSuppliersByCompanyId($id);
    echo json_encode($res);
}

if (isset($test['purpose']) && $purpose=="getLocationsByCompanyId") {
    $obj = new Locations();
    $res = $obj->getLocationsByCompanyId($id);
    echo json_encode($res);
}

if (isset($test['purpose']) && $purpose=="getProductsForInventory") {
    $comp_id = $test['comp_id'];
    $sup_id = $test['sup_id'];
    $obj = new Prods_assign();
    $res = $obj->getProductsForInventory($comp_id,$sup_id);
    echo json_encode($res);
}

if(isset($test['purpose']) && $purpose=="retrieveallparamsfrominvid"){

    $obj = new Inventory();
    $res = $obj->getInvBasics($test['id']);
    echo json_encode($res);
}

if(isset($test['purpose']) && $purpose=="retrieve_inventory_items_from_params"){

    $date = $test['date'];
    $comp_id=$test['comp_id'];
    $inv_ref = $test['invoice_ref'];
    $loc_id = $test['loc_id'];
    $sup_id = $test['sup_id'];
    

    $obj = new Inventory();
    $res = $obj->getInvItems($date,$comp_id,$sup_id,$loc_id,$inv_ref);
     
    echo json_encode($res);

}