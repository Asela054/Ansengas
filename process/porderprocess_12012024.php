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
$updatedatetime = date('Y-m-d h:i:s');

$insertOrderQuery = "INSERT INTO `tbl_porder`(`orderdate`, `nettotal`, `remark`, `confirmstatus`, `dispatchissue`, `grnissuestatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$orderDate', '$total', '$remark', '0', '0', '0', '1', '$updatedatetime', '$userID')";
$stmtOrder = mysqli_prepare($conn, $insertOrderQuery);

mysqli_stmt_execute($stmtOrder);

$orderID = mysqli_insert_id($conn);

foreach ($orderDetails as $detail) {
    $productId = $detail['productId'];
    $unitPrice = $detail['unitPrice'];
    $refillPrice = $detail['refillPrice'];
    $newQty = $detail['newQty'];
    $refillQty = $detail['refillQty'];
    $emptyQty = $detail['emptyQty'];
    $trustQty = $detail['trustQty'];
    $returnQty = $detail['returnQty'];
    $saftyQty = $detail['saftyQty'];
    $saftyReturnQty = $detail['saftyReturnQty'];

    if (!empty($newQty) || !empty($refillQty) || !empty($trustQty) || !empty($returnQty) || !empty($saftyQty) || !empty($saftyReturnQty)) { $newPrice = !empty($newQty) ? $unitPrice : null;  $refillprice = !empty($refillQty) ? $refillPrice : null;  $refillprice = !empty($trustQty) ? $refillPrice : null; $refillprice = !empty($returnQty) ? $refillPrice : null; $refillprice = !empty($saftyQty) ? $refillPrice : null; $refillprice = !empty($saftyReturnQty) ? $refillPrice : null;

        $insertDetailQuery = "INSERT INTO tbl_porder_detail (`type`, `refillqty`, `emptyqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice`, `refillprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`) 
                             VALUES ('0', '$refillQty', '$emptyQty', '$returnQty', '$newQty', '$trustQty', '$saftyQty', '$saftyReturnQty', '$newPrice', '$refillprice', '1', '$updatedatetime', '$userID', '$orderID', '$productId')";
        $stmtDetail = mysqli_prepare($conn, $insertDetailQuery);
        
        mysqli_stmt_execute($stmtDetail);
    }
}

$insertPaymentQuery = "INSERT INTO `tbl_porder_payment`(`date`, `ordertotal`, `previousbill`, `balancetotal`, `accountstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`) VALUES ('$orderDate', '$total', '0', '0', '0', '1', '$updatedatetime', '$userID', '$orderID')";

if($conn->query($insertPaymentQuery)==true){        
    $actionObj=new stdClass();
        $actionObj->icon='fas fa-check-circle';
        $actionObj->title='';
        $actionObj->message='Add Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='success';

        echo $actionJSON = json_encode($actionObj, JSON_FORCE_OBJECT);
    }
    else{
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-exclamation-triangle';
        $actionObj->title='';
        $actionObj->message='Record Error';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='danger';

        echo $actionJSON = json_encode($actionObj, JSON_FORCE_OBJECT);
    }

mysqli_stmt_close($stmtOrder);
mysqli_close($conn);
?>
