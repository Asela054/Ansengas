<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

$confirmationdate = $conn->real_escape_string($_POST['confirmationdate']);
$remark = $conn->real_escape_string($_POST['remark']);
$orderDetails = json_decode($_POST['orderDetails'], true);
$updatedatetime = date('Y-m-d H:i:s');
$customer = (int)$_POST['customer']; 
$refname = (int)$_POST['refname']; 

$conn->begin_transaction();

try {
    // Fix: Add quotes around string values
    $insertOrderQuery = "INSERT INTO `tbl_trust_confirmation` 
        (`date`, `remark`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_employee_idtbl_employee`, `tbl_customer_idtbl_customer`) 
        VALUES ('$confirmationdate', '$remark', 1, '$updatedatetime', $userID, $refname, $customer)";
    
    if (!$conn->query($insertOrderQuery)) {
        throw new Exception("Error inserting purchase record: " . $conn->error);
    }

    $confirmationID = $conn->insert_id;

    foreach ($orderDetails as $detail) {
        $productId = (int)$detail['productId'];
        $fullQty = (int)str_replace(',', '', $detail['fullQty']);
        // Fix: Don't convert comment to integer, keep as string
        $comment = $conn->real_escape_string($detail['comment']);

        // Fix: Add quotes around comment value
        $insertDetailQuery = "INSERT INTO `tbl_trust_confirmation_detail`(`qty`, `comment`, `status`, `updatedatetime`, `tbl_trust_confirmation_idtbl_trust_confirmation`, `tbl_product_idtbl_product`) 
             VALUES ($fullQty, '$comment', 1, '$updatedatetime', $confirmationID, $productId)";
        
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