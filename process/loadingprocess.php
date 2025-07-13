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
$date = $_POST['date'];
$driverID = $_POST['driverID'];
$officerID = $_POST['officerID'];
$refID = $_POST['refID'];
$helperID = $_POST['helpername'];
$orderDetails = $_POST['orderDetails'];

$updatedatetime = date('Y-m-d h:i:s');

$insertloading = "INSERT INTO `tbl_vehicle_load`(`date`, `lorryid`, `driverid`, `officerid`, `refid`, `approvestatus`, `unloadstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('$date','$lorryID','$driverID','$officerID','$refID','0','0','1','$updatedatetime','$userID','$areaID')";
$stmtOrder = mysqli_prepare($conn, $insertloading);
mysqli_stmt_execute($stmtOrder);

$orderID = mysqli_insert_id($conn);

foreach ($orderDetails as $detail) {
    $productId = $detail['productId'];
    $newQty = $detail['newQty'];

    if (!empty($newQty)) {
        $insertloaddetail = "INSERT INTO `tbl_vehicle_load_detail`(`loadqty`,`qty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_vehicle_load_idtbl_vehicle_load`, `tbl_product_idtbl_product`) VALUES ('$newQty','$newQty','1','$updatedatetime','$userID','$orderID','$productId')";
        $stmtDetail = mysqli_prepare($conn, $insertloaddetail);
        mysqli_stmt_execute($stmtDetail);

        $checkStockQuery = "SELECT `fullqty`, `emptyqty` FROM tbl_stock WHERE tbl_product_idtbl_product = '$productId'";
        $result = $conn->query($checkStockQuery);
        $rowcheckstock = $result->fetch_assoc();

        //Insert tbl_stock_history start
        $prevfullstock = $rowcheckstock['fullqty'];
        $prevemptystock = $rowcheckstock['emptyqty'];
        $avafullstock = $prevfullstock - $newQty;
        $avaemptystock = $prevemptystock - 0;

        $inserthistory = "INSERT INTO `tbl_stock_history`(`transtype`, `date`, `prevfullqty`, `issuefullqty`, `avafullqty`, `prevemptyqty`, `issueemptyqty`, `avaemptyqty`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `record_id`) VALUES ('2','$date','$prevfullstock','$newQty','$avafullstock','$prevemptystock','0','$avaemptystock','1','$updatedatetime','$userID','$productId','$orderID')"; 
        $conn->query($inserthistory);   
        //Insert tbl_stock_history end

        $updatestock = "UPDATE `tbl_stock` SET `fullqty`=(`fullqty`-'$newQty') WHERE `tbl_product_idtbl_product`='$productId'";
        $stockDetail = mysqli_prepare($conn, $updatestock);
        mysqli_stmt_execute($stockDetail);
    }

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
