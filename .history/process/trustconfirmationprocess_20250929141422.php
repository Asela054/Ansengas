<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$purchasedate = $conn->real_escape_string($_POST['purchasedate']);
$remark = $conn->real_escape_string($_POST['remark']);
$orderDetails = json_decode($_POST['orderDetails'], true);
$updatedatetime = date('Y-m-d H:i:s');
$customer = $_POST['customer']; 
$customer = $_POST['customer']; 

$conn->begin_transaction();

try {

    $insertOrderQuery = "INSERT INTO `tbl_local_purchase` 
        (`date`, `total`, `taxamount`, `nettotal`, `remark`, `status`, `updatedatetime`, `customertype`, `tbl_customer_idtbl_customer`, `tbl_user_idtbl_user`) 
        VALUES ('$purchasedate', $total, '0', '0', '$remark', 1, '$updatedatetime', $customerTypeValue, $customerID, $userID)";
    
    if (!$conn->query($insertOrderQuery)) {
        throw new Exception("Error inserting purchase record: " . $conn->error);
    }

    $purchaseID = $conn->insert_id;

    foreach ($orderDetails as $detail) {
        $productId = (int)$detail['productId'];
        $fullQty = (int)str_replace(',', '', $detail['fullQty']);
        $emptyQty = (int)str_replace(',', '', $detail['emptyQty']);
        $fullpricewithoutvat = (float)str_replace(',', '', $detail['fullpricewithoutvat']);
        $emptypricewithoutvat = (float)str_replace(',', '', $detail['emptypricewithoutvat']);

        if ($fullQty == 0 && $emptyQty == 0) {
            continue;
        }

        // Insert detail record
        $insertDetailQuery = "INSERT INTO tbl_local_purchasedetail 
            (`fullqty`, `emptyqty`, `full_unitprice`, `full_unitprice_withoutvat`, 
             `empty_unitprice`, `empty_unitprice_withoutvat`, `status`, 
             `updatedatetime`, `tbl_user_idtbl_user`, 
             `tbl_local_purchase_idtbl_local_purchase`, `tbl_product_idtbl_product`) 
             VALUES ($fullQty, $emptyQty, '0', $fullpricewithoutvat, '0', $emptypricewithoutvat, 1, '$updatedatetime', $userID, $purchaseID, $productId)";
        
        if (!$conn->query($insertDetailQuery)) {
            throw new Exception("Error inserting order detail: " . $conn->error);
        }
    }

    $conn->commit();

    echo json_encode([
        'icon' => 'fas fa-check-circle',
        'title' => '',
        'message' => 'Purchase Order Added Successfully',
        'url' => '',
        'target' => '_blank',
        'type' => 'success'
    ]);

} catch (Exception $e) {
    $conn->rollback();

    echo json_encode([
        'icon' => 'fas fa-exclamation-triangle',
        'title' => '',
        'message' => 'Error: ' . $e->getMessage(),
        'url' => '',
        'target' => '_blank',
        'type' => 'danger'
    ]);
}
?>