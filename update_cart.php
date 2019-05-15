<?php
header('Content-Type: application/json');
if (!isset($_POST['cart_update'])) {
    echo '{
            "error": "no cart object sent";
        }';
    exit;
}

include_once "db.php";
include_once "checkLogin.php";
$user_id = $_SESSION["id"];

$old_restaurant_id = "";

$sql = "SELECT id,restaurant_id FROM tbl_orders WHERE user_id = $user_id AND address_id is NULL ";
if ($result = mysqli_query($db, $sql)) {

    $data = mysqli_fetch_array($result);
    $oid = $data['id'];
    $old_restaurant_id = $data['restaurant_id'];
}


if($_POST['cart_update'] != "") {
    $new_restaurant_id = $_POST['cart_update']['restaurant_id'];
}


if($old_restaurant_id == 0){
    $sql = "INSERT INTO tbl_orders(user_id,restaurant_id,created_on,updated_on) values( $user_id , $new_restaurant_id, NOW(), NOW())";
    $result = mysqli_query($db, $sql);
    $oid = mysqli_insert_id($db);
}
else {
    if ($old_restaurant_id != $new_restaurant_id) {
        $sql = "DELETE FROM tbl_orders WHERE user_id = $user_id and address_id is NULL"; // removes all items of previous cart
        if ($result = mysqli_query($db, $sql)) {
            if ($new_restaurant_id != "") {
                $sql = "INSERT INTO tbl_orders(user_id,restaurant_id,created_on,updated_on) values( $user_id , $new_restaurant_id, NOW(), NOW())";
                $result = mysqli_query($db, $sql);
                $oid = mysqli_insert_id($db);
            } else {
                echo '{
                    "success": "deleted cart";
                }';
                exit;
            }
        }
    }
}
if ($_POST['cart_update'] != "") {
    $item_id = $_POST['cart_update']['item_id'];
    $quantity = $_POST['cart_update']['quantity'];
    if ($quantity != 0) {
        $sql = "INSERT INTO tbl_order_details(order_id, item_id, qty) VALUES ($oid, $item_id, $quantity) 
          ON DUPLICATE KEY UPDATE qty = $quantity";
    } else{
        $sql = "DELETE FROM tbl_order_details WHERE order_id = $oid AND item_id = $item_id";
    }
    $result = mysqli_query($db,$sql);
}


$sql = "SELECT i.name, i.price, i.id, od.qty FROM tbl_orders o 
        JOIN tbl_order_details od on o.id = od.order_id 
        JOIN tbl_items i ON od.item_id = i.id 
        WHERE o.user_id = $user_id AND o.address_id is NULL";

if($result = mysqli_query($db, $sql)){
    $out = array();
    while ($item = mysqli_fetch_array($result)){
        array_push($out, array(
            "id" => $item['id'],
            "qty" => $item['qty'],
            "name" => $item['name'],
            "price" => $item['price']
        ));
    }
}


echo json_encode($out);

mysqli_close($db);