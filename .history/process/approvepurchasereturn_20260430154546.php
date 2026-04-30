<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$orderID = $_POST['orderID'];
$updatedatetime = date('Y-m-d h:i:s');

// Start transaction
$conn->begin_transaction();

try {
    // Get purchase return details
    $sql = "SELECT prd.tbl_product_idtbl_product, prd.emptyqty, prd.refillqty 
            FROM tbl_purchase_return_detail prd 
            WHERE prd.tbl_purchase_return_idtbl_purchase_return = '$orderID' AND prd.status = 1";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $productId = $row['tbl_product_idtbl_product'];
        $emptyQty = $row['emptyqty'];
        $refillQty = $row['refillqty'];
        
        // Get current stock for this product
        $stockSql = "SELECT idtbl_stock, emptyqty FROM tbl_stock WHERE tbl_product_idtbl_product = '$productId' AND status = 1";
        $stockResult = $conn->query($stockSql);
        
        if ($stockRow = $stockResult->fetch_assoc()) {
            $stockId = $stockRow['idtbl_stock'];
            $currentEmptyQty = $stockRow['emptyqty'];
            
            // Reduce stock: subtract empty qty from existing empty qty
            $newEmptyQty = $currentEmptyQty - $emptyQty;
            
            // Ensure qty doesn't go negative
            if ($newEmptyQty < 0) {
                $newEmptyQty = 0;
            }
            
            $updateStockSql = "UPDATE tbl_stock SET 
                emptyqty = '$newEmptyQty',
                updatedatetime = '$updatedatetime',
                tbl_user_idtbl_user = '$userID'
                WHERE idtbl_stock = '$stockId'";
            
            if (!$conn->query($updateStockSql)) {
                throw new Exception("Error updating stock");
            }
        }
    }
    
    // Update purchase return approvestatus to 1
    $updatePRSql = "UPDATE tbl_purchase_return SET 
        approvestatus = '1',
        updatedatetime = '$updatedatetime',
        tbl_user_idtbl_user = '$userID'
        WHERE idtbl_purchase_return = '$orderID'";
    
    if (!$conn->query($updatePRSql)) {
        throw new Exception("Error updating purchase return");
    }
    
    // Commit transaction
    $conn->commit();
    
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-check-circle';
    $actionObj->title = '';
    $actionObj->message = 'Purchase Return Approved Successfully';
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'success';
    
    echo json_encode($actionObj);
    
} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-exclamation-triangle';
    $actionObj->title = '';
    $actionObj->message = 'Error: ' . $e->getMessage();
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'danger';
    
    echo json_encode($actionObj);
}