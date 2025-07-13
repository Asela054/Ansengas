<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$orderDate = $_POST['orderdate'];
$porderID = $_POST['porderID'];
$remark = $_POST['remark'];
$orderDetails = $_POST['orderDetails'];
$total = $_POST['total'];
$totalwithoutvat = $_POST['totalwithoutvat'];
$updatedatetime = date('Y-m-d h:i:s');

$tax_amount=$total-$totalwithoutvat;

$insertOrderQuery = "INSERT INTO `tbl_credit_note`(`date`, `total`, `taxamount`, `nettotal`, `remark`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`) 
VALUES ('$orderDate', '$totalwithoutvat', '$tax_amount', '$total', '$remark', '1', '$updatedatetime', '$userID', '$porderID')";

if ($conn->query($insertOrderQuery) == true) {

$creditnoteID = mysqli_insert_id($conn);

foreach ($orderDetails as $detail) {
    $productId = $detail['productId'];
    $emptyPricewithoutvat = $detail['emptyPricewithoutvat'];
    $emptyprice = $detail['emptyprice'];
    $emptyQty = str_replace(',', '', $detail['emptyQty']);

    $emptyPrice = !empty($emptyQty) ? $emptyprice : null;
    $emptyPricewithoutVat = !empty($emptyQty) ? $emptyPricewithoutvat : null;

    if ($emptyPrice !== null || $emptyPricewithoutVat !== null) {
        $insertDetailQuery = "INSERT INTO tbl_credit_note_detail (`refillqty`, `emptyqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice_withoutvat`, `refillprice_withoutvat`, `emptyprice_withoutvat`, `unitprice`, `refillprice`, `emptyprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_credit_note_idtbl_credit_note`, `tbl_product_idtbl_product`) 
        VALUES ('0', '$emptyQty', '0', '0', '0', '0', '0', '0', '0', '$emptyPricewithoutVat', '0', '0','$emptyPrice', '1', '$updatedatetime', '$userID', '$creditnoteID', '$productId')";

        $conn->query($insertDetailQuery);

    }
}

$actionObj=new stdClass();
$actionObj->icon='fas fa-check-circle';
$actionObj->title='';
$actionObj->message='Add Successfully';
$actionObj->url='';
$actionObj->target='_blank';
$actionObj->type='success';

echo $actionJSON=json_encode($actionObj);
}
else{
$actionObj=new stdClass();
$actionObj->icon='fas fa-exclamation-triangle';
$actionObj->title='';
$actionObj->message='Record Error';
$actionObj->url='';
$actionObj->target='_blank';
$actionObj->type='danger';

echo $actionJSON=json_encode($actionObj);
}

