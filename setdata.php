<?php

include_once("db_conn.php");


//weird method to get POST variables in JSON;
header('Content-Type: application/json');
$test = json_decode(file_get_contents("php://input"), true);

$purpose = $test['purpose'];


// Assigning product to Supplier
if (isset($test['purpose']) && $purpose=="assignprod") {
    $supid = $test['id'];
    $prodids = $test['prod_ids'];
    $err = FALSE;
    $obj = new Suppliers;
    
    foreach ($prodids as $prodid) {
        $res = $obj->setSupplierProducts($supid, $prodid);
        if (!$res) {
            //echo json_encode("$prodid inserted into $supid");
            $err=TRUE;
        }
    }

    if (!$err) {
        echo json_encode("successfully entered");
    } else {
        echo json_encode("failed");
    }
}

// Assigning product to company
if (isset($test['purpose']) && $purpose=="assignprodtocomp") {
    $comp_id = $test['id'];
    $prodids = $test['prod_ids'];
    $err = FALSE;
    $obj = new Companies();
    
    foreach ($prodids as $prodid) {
        $res = $obj->setCompanyProducts($comp_id, $prodid);
        if (!$res) {
            $err=TRUE;
        }
    }

    if (!$err) {
        echo json_encode("successfully entered");
    } else {
        echo json_encode("failed");
    }
}

// Adding new product
if (isset($test['purpose']) && $purpose=="add_product") {
    $t_name = $test['t_name'];
    $t_source = $test['t_source'];
    $t_type = $test['t_type'];
    $t_sub_type = $test['t_sub_type'];
    $t_cases = $test['t_cases'];
    $t_units = $test['t_units'];

    //sql
    try {
        $prod = new Prods_assign();
        $res = $prod->setProduct($t_name, $t_source, $t_type, $t_sub_type, $t_cases, $t_units);
    } catch (Exception $e) {
        die($e);
    }

    echo json_encode("product_added");
}


// Adding new supplier
if (isset($test['purpose']) && $purpose=="add_supplier") {
    $t_name = $test['t_name'];
    $t_address = $test['t_address'];
    $t_contact = $test['t_contact'];
    $t_number = $test['t_number'];

    //sql
    try {
        $prod = new Suppliers();
        $res = $prod->setSupplier($t_name, $t_address, $t_contact, $t_number);
    } catch (Exception $e) {
        die($e);
    }

    echo json_encode("supplier_added");
}

// Adding new inventory
if (isset($test['purpose']) && $purpose=="add_inventory") {
    $t_date = $test['t_date'];
    $comp_id = $test['comp_id'];
    $receiver = $test['receiver'];
    $supplier = $test['supplier'];
    $inv_num = $test['inv_num'];
    $acc_ref = $test['acc_ref'];
    $prodid = $test['prodid'];
    $qty = $test['qty'];
    $namount = $test['namount'];
    $tad = $test['tad'];
    $discount = $test['discount'];
    $taxrate = $test['taxrate'];
    $tax = $test['tax'];
    $amount = $test['amount'];
    $img_ref = $test['img_ref'];

    $count = count($prodid);
    $res = "";
    $invent = new Inventory();
    for ($i = 0 ; $i < $count ; $i++) {
        $invent->addInventory(
            $t_date,
            $comp_id,
            $receiver,
            $supplier,
            $inv_num,
            $acc_ref,
            $prodid[$i],
            $qty[$i],
            $namount[$i],
            $tad[$i],
            $discount[$i],
            $taxrate[$i],
            $tax[$i],
            $amount[$i],
            $img_ref
        );

        $res.= "\nData Entered Successfully";
    }

    echo json_encode($count);
}
    
    // Adding new inventory
if (isset($test['purpose']) && $purpose=="update_inventory") {
    $itemid = $test['itemid'];
    $acc_ref = $test['acc_ref'];
    $qty = $test['qty'];
    $namount = $test['namount'];
    $tad = $test['tad'];
    $discount = $test['discount'];
    $taxrate = $test['taxrate'];
    $tax = $test['tax'];
    $amount = $test['amount'];
    $img_ref = $test['img_ref'];
    $delitem = $test['delitem'];


    $count = count($itemid);
    $res = "";
    $invent = new Inventory();
    for($i = 0 ; $i < $count ; $i++){
        $invent->updateInventory($itemid[$i],$acc_ref,$delitem[$i],$qty[$i],$namount[$i],
                        $tad[$i],$discount[$i],$taxrate[$i],$tax[$i],$amount[$i],$img_ref);

        $res.= "\nData Entered Successfully". $itemid[$i]; 
    }

    echo json_encode($res);
    
    

    
}
