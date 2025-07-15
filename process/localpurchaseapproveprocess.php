<?php
session_start();
if(!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit();
}

require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$recordID = $_POST['porderid'];
$confirmnot = $_POST['confirmnot'];
$updatedatetime = date('Y-m-d H:i:s');

$conn->autocommit(FALSE);

try {
    $sql = "UPDATE tbl_local_purchase SET approvestatus = '$confirmnot', updatedatetime = '$updatedatetime' WHERE idtbl_local_purchase = '$recordID'";
    
    if(!$conn->query($sql)) {
        throw new Exception("Failed to update purchase status");
    }
    
    if($confirmnot == 1) {
        $detailSql = "SELECT tbl_product_idtbl_product, fullqty, emptyqty FROM tbl_local_purchasedetail WHERE tbl_local_purchase_idtbl_local_purchase = '$recordID' AND status = 1";
        $detailResult = $conn->query($detailSql);
        
        if($detailResult->num_rows > 0) {
            while($row = $detailResult->fetch_assoc()) {
                $productId = $row['tbl_product_idtbl_product'];
                $fullQty = $row['fullqty'];
                $emptyQty = $row['emptyqty'];
                
                $stockCheckQuery = "SELECT idtbl_stock FROM tbl_stock WHERE tbl_product_idtbl_product = '$productId' LIMIT 1";
                $stockResult = $conn->query($stockCheckQuery);
                
                if($stockResult->num_rows > 0) {
                    $updateStockQuery = "UPDATE tbl_stock SET fullqty = fullqty + '$fullQty', emptyqty = emptyqty + '$emptyQty', updatedatetime = '$updatedatetime', tbl_user_idtbl_user = '$userID' WHERE tbl_product_idtbl_product = '$productId'";
                } else {
                    $updateStockQuery = "INSERT INTO tbl_stock (fullqty, emptyqty, damage_fullqty_yard, damage_emptyqty_yard, damage_fullqty_company, damage_emptyqty_company, `update`, status, updatedatetime, tbl_user_idtbl_user, tbl_product_idtbl_product) VALUES ('$fullQty', '$emptyQty', '0', '0', '0', '0', '1', '1', '$updatedatetime', '$userID', '$productId')";
                }
                
                if(!$conn->query($updateStockQuery)) {
                    throw new Exception("Error updating stock for product $productId");
                }
            }
        }
    }
    
    $conn->commit();
    
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-check-circle';
    $actionObj->title = '';
    
    if($confirmnot == 1) {
        $actionObj->message = 'Purchase Order Approved and Stock Updated Successfully';
    } else {
        $actionObj->message = 'Purchase Order Rejected Successfully';
    }
    
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'success';
    
    $response = new stdClass();
    $response->status = 1;
    $response->action = $actionObj;
    
    echo json_encode($response);
    
} catch (Exception $e) {
    $conn->rollback();
    
    $actionObj = new stdClass();
    $actionObj->icon = 'fas fa-exclamation-triangle';
    $actionObj->title = '';
    $actionObj->message = 'Error: ' . $e->getMessage();
    $actionObj->url = '';
    $actionObj->target = '_blank';
    $actionObj->type = 'danger';
    
    $response = new stdClass();
    $response->status = 0;
    $response->action = $actionObj;
    
    echo json_encode($response);
    
} finally {
    $conn->autocommit(TRUE);
    $conn->close();
}
?>