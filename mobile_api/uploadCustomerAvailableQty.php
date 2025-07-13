<?php
require_once('dbConnect.php');
$date = $_POST["date"];
$userId = $_POST["usrId"];
$customerId = $_POST["customerId"];
$fullqty = $_POST["fullqty"];
$emptyqty = $_POST["emptyqty"];
$rejectreason = $_POST["rejectreason"];
$customreason = $_POST["customreason"];
$loadid = $_POST["loadid"];
$lorryid = $_POST["lorryid"];
$details = $_POST["details"];

$detailsJson = json_decode($details, true);
// print_r($detailsJson);

$flag = true;
$con->autocommit(FALSE);

$sqlMain = "INSERT INTO `tbl_customer_buffer_stock`(`date`,`fullqty`,`emptyqty`, `customreason`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_reject_reason_idtbl_reject_reason`,`tbl_vehicle_load_idtbl_vehicle_load`,`tbl_vehicle_idtbl_vehicle`) VALUES ('$date','$fullqty','$emptyqty','$customreason','1',NOW(),'$userId','$customerId','$rejectreason','$loadid','$lorryid')";
$result = mysqli_query($con, $sqlMain);
$last_id = $con->insert_id;
if (!$result) {
    $flag = false;
}

foreach ($detailsJson as $indet) {
    $productId = $indet['productId'];
    $fullqty = $indet['fullqty'];
    $emptyqty = $indet['emptyqty'];

    $sqlinsrtitm = "INSERT INTO `tbl_customer_buffer_stock_detail`( `fullqty`, `emptyqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_buffer_stock_idtbl_customer_buffer_stock`, `tbl_product_idtbl_product`) VALUES ('$fullqty','$emptyqty','1',NOW(),'$userId','$last_id','$productId')";
    $resultItem = mysqli_query($con, $sqlinsrtitm);

    if (!$resultItem) {
        $flag = false;
    }
}

if ($flag) {
    $con->commit();
    $response = array("code" => '200', "message" => 'Update Complete');
    print_r(json_encode($response));
} else {
    $con->rollback();
    $response = array("code" => '500', "message" => 'Update Not Complete');
    print_r(json_encode($response));
}

?>
