<?php
session_start();

if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$lorryID = $_POST['lorryID'];
$areaID = $_POST['areaID'];
$driverID = $_POST['driverID'];
$officerID = $_POST['officerID'];
$refID = $_POST['refID'];
$helperID = $_POST['helpername'];
$orderDetails = $_POST['orderDetails'];

$today = date('Y-m-d');
$updatedatetime = date('Y-m-d h:i:s');

$insertloading = "INSERT INTO `tbl_vehicle_load`(`date`, `lorryid`, `driverid`, `officerid`, `refid`, `approvestatus`, `unloadstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('$today','$lorryID','$driverID','$officerID','$refID','0','0','1','$updatedatetime','$userID','$areaID')";
$stmtOrder = mysqli_prepare($conn, $insertloading);
mysqli_stmt_execute($stmtOrder);

$orderID = mysqli_insert_id($conn);

foreach ($orderDetails as $detail) {
    $productId = $detail['productId'];
    $newQty = $detail['newQty'];

    if (!empty($newQty)) {

    $insertloaddetail = "INSERT INTO `tbl_vehicle_load_detail`(`qty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_vehicle_load_idtbl_vehicle_load`, `tbl_product_idtbl_product`) VALUES ('$newQty','1','$updatedatetime','$userID','$orderID','$productId')";
    $stmtDetail = mysqli_prepare($conn, $insertloaddetail);
    mysqli_stmt_execute($stmtDetail);
}

    $updatestock = "UPDATE `tbl_stock` SET `fullqty`=(`fullqty`-'$newQty') WHERE `tbl_product_idtbl_product`='$productId'";
    $stockDetail = mysqli_prepare($conn, $updatestock);
    mysqli_stmt_execute($stockDetail);
}

foreach ($helperID as $helperlist) {
    $inserthelperdetail = "INSERT INTO `tbl_employee_has_tbl_vehicle_load`(`tbl_employee_idtbl_employee`, `tbl_vehicle_load_idtbl_vehicle_load`) VALUES ('$helperlist','$orderID')";
    $conn->query($inserthelperdetail);
}

if ($conn->query($inserthelperdetail) === true) {
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-check-circle';
    $actionObj->title = '';
    $actionObj->message = 'Add Successfully';
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'success';

    echo $actionJSON = json_encode($actionObj, JSON_FORCE_OBJECT);
} else {
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-exclamation-triangle';
    $actionObj->title = '';
    $actionObj->message = 'Record Error';
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'danger';

    echo $actionJSON = json_encode($actionObj, JSON_FORCE_OBJECT);
}

mysqli_stmt_close($stmtOrder);
mysqli_close($conn);
?>
