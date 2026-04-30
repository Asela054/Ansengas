<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$orderDate = $_POST['orderdate'];
$invoicenumber = $_POST['invoicenumber'];
$remark = $_POST['remark'];
$orderDetails = $_POST['orderDetails'];
$total = $_POST['total'];
$totalwithoutvat = $_POST['totalwithoutvat'];
$updatedatetime = date('Y-m-d h:i:s');

$tax_amount=$total-$totalwithoutvat;

$insertOrderQuery = "INSERT INTO `tbl_purchase_return`(`invoicenum`, `date`, `total`, `taxamount`, `nettotal`, `remark`, `approvestatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) 
VALUES ('$invoicenumber', '$orderDate', '$totalwithoutvat', '$tax_amount', '$total', '$remark', '0', '1', '$updatedatetime', '$userID')";

if ($conn->query($insertOrderQuery) == true) {

$purchasereturnID = mysqli_insert_id($conn);

foreach ($orderDetails as $detail) {
    $productId = $detail['productId'];
    
    // Empty cylinder details
    $emptyPricewithoutvat = $detail['emptyPricewithoutvat'];
    $emptyprice = $detail['emptyprice'];
    $emptyQty = str_replace(',', '', $detail['emptyQty']);
    
    // Refill cylinder details
    $refillPricewithoutvat = $detail['refillPricewithoutvat'];
    $refillprice = $detail['refillprice'];
    $refillQty = str_replace(',', '', $detail['refillQty']);
    
    // Only insert if at least one qty is present
    if ((!empty($emptyQty) && $emptyQty > 0) || (!empty($refillQty) && $refillQty > 0)) {
        $emptyQty = (!empty($emptyQty) && $emptyQty > 0) ? $emptyQty : 0;
        $refillQty = (!empty($refillQty) && $refillQty > 0) ? $refillQty : 0;
        $emptyPricewithoutvat = !empty($emptyPricewithoutvat) ? $emptyPricewithoutvat : 0;
        $refillPricewithoutvat = !empty($refillPricewithoutvat) ? $refillPricewithoutvat : 0;
        $emptyprice = !empty($emptyprice) ? $emptyprice : 0;
        $refillprice = !empty($refillprice) ? $refillprice : 0;
        
        $insertDetailQuery = "INSERT INTO tbl_purchase_return_detail (`emptyqty`, `refillqty`, `emptyprice_withoutvat`, `refillprice_withoutvat`, `emptyprice`, `refillprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_purchase_return_idtbl_purchase_return`, `tbl_product_idtbl_product`) 
        VALUES ('$emptyQty', '$refillQty', '$emptyPricewithoutvat', '$refillPricewithoutvat', '$emptyprice', '$refillprice', '1', '$updatedatetime', '$userID', '$purchasereturnID', '$productId')";
        $conn->query($insertDetailQuery);
    }
}

$actionObj=new stdClass();
$actionObj->icon='fas fa-check-circle';
$actionObj->title='';
$actionObj->message='Purchase Return Created Successfully';
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