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
$total = (float)str_replace(',', '', $_POST['total']);
$updatedatetime = date('Y-m-d H:i:s');
$customerType = $_POST['customer_type']; 

$conn->begin_transaction();

try {
    $customerID = null;
    $customerTypeValue = ($customerType === 'new') ? 2 : 1; 
    
    if ($customerType === 'new') {
        $name = $conn->real_escape_string($_POST['new_customer_name']);
        $phone = $conn->real_escape_string($_POST['new_customer_phone']);
        $address = $conn->real_escape_string($_POST['new_customer_address'] ?? '');
        
        $insertCustomerQuery = "INSERT INTO `tbl_local_purchase_customers` 
            (`name`, `phone`, `address`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) 
            VALUES ('$name', '$phone', '$address', 1, '$updatedatetime', $userID)";
        
        if (!$conn->query($insertCustomerQuery)) {
            throw new Exception("Error creating new customer: " . $conn->error);
        }
        
        $customerID = $conn->insert_id;
    } else {
        $customerID = (int)$_POST['customer'];
    }

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