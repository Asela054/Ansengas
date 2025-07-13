<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$orderDate = $_POST['orderdate'];
$remark = $_POST['remark'];
$orderDetails = $_POST['orderDetails'];
$total = $_POST['total'];
$totalwithoutvat = $_POST['totalwithoutvat'];
$updatedatetime = date('Y-m-d h:i:s');

$tax_amount=$total-$totalwithoutvat;

$insertOrderQuery = "INSERT INTO `tbl_porder`(`orderdate`, `total`, `taxamount`, `nettotal`, `remark`, `confirmstatus`, `dispatchissue`, `grnissuestatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) 
VALUES ('$orderDate', '$totalwithoutvat', '$tax_amount', '$total', '$remark', '0', '0', '0', '1', '$updatedatetime', '$userID')";

$stmtOrder = mysqli_prepare($conn, $insertOrderQuery);
mysqli_stmt_execute($stmtOrder);

$orderID = mysqli_insert_id($conn);

foreach ($orderDetails as $detail) {
    $productId = $detail['productId'];
    $unitPricewithoutvat = $detail['unitPricewithoutvat'];
    $refillPricewithoutvat = $detail['refillPricewithoutvat'];
    $emptyPricewithoutvat = $detail['emptyPricewithoutvat'];
    $unitPrice = $detail['unitPrice'];
    $refillPrice = $detail['refillPrice'];
    $emptyprice = $detail['emptyprice'];
    $newQty = str_replace(',', '', $detail['newQty']);
    $refillQty = str_replace(',', '', $detail['refillQty']);
    $emptyQty = str_replace(',', '', $detail['emptyQty']);
    $trustQty = str_replace(',', '', $detail['trustQty']);
    $returnQty = str_replace(',', '', $detail['returnQty']);
    $saftyQty = str_replace(',', '', $detail['saftyQty']);
    $saftyReturnQty = str_replace(',', '', $detail['saftyReturnQty']);

    $newPrice = !empty($newQty) ? $unitPrice : null;
    $newPricewithoutVat = !empty($newQty) ? $unitPricewithoutvat : null;
    $refillprice = !empty($refillQty) || !empty($trustQty) || !empty($returnQty) || !empty($saftyQty) || !empty($saftyReturnQty) ? $refillPrice : null;
    $refillPricewithoutVat = !empty($refillQty) || !empty($trustQty) || !empty($returnQty) || !empty($saftyQty) || !empty($saftyReturnQty) ? $refillPricewithoutvat : null;
    $emptyPrice = !empty($emptyQty) ? $emptyprice : null;
    $emptyPricewithoutVat = !empty($emptyQty) ? $emptyPricewithoutvat : null;

    if ($newPrice !== null || $refillprice !== null || $emptyPrice !== null || $newPricewithoutVat !== null || $refillPricewithoutVat !== null || $emptyPricewithoutVat !== null) {
        $insertDetailQuery = "INSERT INTO tbl_porder_detail (`type`, `refillqty`, `emptyqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice_withoutvat`, `refillprice_withoutvat`, `emptyprice_withoutvat`, `unitprice`, `refillprice`, `emptyprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`) 
                            VALUES ('0', '$refillQty', '$emptyQty', '$returnQty', '$newQty', '$trustQty', '$saftyQty', '$saftyReturnQty', '$newPricewithoutVat', '$refillPricewithoutVat', '$emptyPricewithoutVat', '$newPrice', '$refillprice','$emptyPrice', '1', '$updatedatetime', '$userID', '$orderID', '$productId')";

        $stmtDetail = mysqli_prepare($conn, $insertDetailQuery);
        mysqli_stmt_execute($stmtDetail);
    }
}



$insertPaymentQuery = "INSERT INTO `tbl_porder_payment`(`date`, `ordertotal`, `previousbill`, `balancetotal`, `accountstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`) 
                    VALUES ('$orderDate', '$total', '0', '0', '0', '1', '$updatedatetime', '$userID', '$orderID')";

if ($conn->query($insertPaymentQuery) == true) {
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

