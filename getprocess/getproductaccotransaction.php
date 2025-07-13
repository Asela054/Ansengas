<?php
require_once('../connection/db.php');

$transID = isset($_POST['transID']) ? $_POST['transID'] : null;

if (!$transID) {
    echo json_encode(["error" => "Transaction ID is required"]);
    exit;
}

$transID = mysqli_real_escape_string($conn, $transID);

$sqlproduct = "SELECT `tbl_product`.`idtbl_product`,`tbl_product`.`product_name`,`tbl_tank_transaction`.`qty` AS collectqty FROM `tbl_product` LEFT JOIN `tbl_tank_transaction` 
ON `tbl_product`.`idtbl_product` = `tbl_tank_transaction`.`tbl_product_idtbl_product` WHERE `tbl_tank_transaction`.`idtbl_tank_transaction` = '$transID' AND `tbl_tank_transaction`.`issuestatus` = 0";

$resultproduct = $conn->query($sqlproduct);
$rowproduct = $resultproduct->fetch_assoc();

$sqlreturnproduct = "SELECT SUM(`tbl_tank_transaction_return`.`qty`) AS returnqty FROM `tbl_tank_transaction_return` WHERE `tbl_tank_transaction_return`.`tbl_tank_transaction_idtbl_tank_transaction` = '$transID'";

$resultreturnproduct = $conn->query($sqlreturnproduct);
$rowreturnproduct = $resultreturnproduct->fetch_assoc();

if ($resultproduct && $rowproduct) {
    $obj = new stdClass();
    $obj->id = $rowproduct['idtbl_product'];
    $obj->product = $rowproduct['product_name'];
    $obj->qty = $rowproduct['collectqty'];
    $obj->returnqty = $rowreturnproduct['returnqty'] ?? 0;

    echo json_encode($obj);
} else {
    echo json_encode(["error" => "No data found for the given transaction ID."]);
}

$conn->close();
?>
