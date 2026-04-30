<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$orderID = $_POST['orderID'];
$updatedatetime = date('Y-m-d H:i:s');

// Start transaction
$conn->begin_transaction();

try {

    // 🔹 Get purchase return details
    $stmt = $conn->prepare("
        SELECT tbl_product_idtbl_product, emptyqty, refillqty
        FROM tbl_purchase_return_detail
        WHERE tbl_purchase_return_idtbl_purchase_return = ? AND status = 1
    ");
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $productId = $row['tbl_product_idtbl_product'];

        // ✅ Force numeric values (IMPORTANT FIX)
        $emptyQty  = (int)$row['emptyqty'];
        $refillQty = (int)$row['refillqty'];

        // 🔹 Get stock row
        $stmtStock = $conn->prepare("
            SELECT idtbl_stock, fullqty, emptyqty 
            FROM tbl_stock 
            WHERE tbl_product_idtbl_product = ? AND status = 1
        ");
        $stmtStock->bind_param("i", $productId);
        $stmtStock->execute();
        $stockResult = $stmtStock->get_result();

        if ($stockRow = $stockResult->fetch_assoc()) {

            $stockId = $stockRow['idtbl_stock'];
            $currentFullQty  = (int)$stockRow['fullqty'];
            $currentEmptyQty = (int)$stockRow['emptyqty'];

            // ✅ Safe calculation
            $newFullQty  = $currentFullQty;
            $newEmptyQty = $currentEmptyQty;

            if ($refillQty > 0) {
                $newFullQty -= $refillQty;
            }

            if ($emptyQty > 0) {
                $newEmptyQty -= $emptyQty;
            }

            // ✅ Prevent negative values
            $newFullQty  = max(0, $newFullQty);
            $newEmptyQty = max(0, $newEmptyQty);

            // 🔹 Update stock
            $stmtUpdate = $conn->prepare("
                UPDATE tbl_stock SET 
                    fullqty = ?, 
                    emptyqty = ?, 
                    updatedatetime = ?, 
                    tbl_user_idtbl_user = ?
                WHERE idtbl_stock = ?
            ");
            $stmtUpdate->bind_param("iisii", $newFullQty, $newEmptyQty, $updatedatetime, $userID, $stockId);

            if (!$stmtUpdate->execute()) {
                throw new Exception("Error updating stock");
            }

        } else {

            // ✅ If no stock row exists → create new
            $newFullQty  = max(0, 0 - $refillQty);
            $newEmptyQty = max(0, 0 - $emptyQty);

            $stmtInsert = $conn->prepare("
                INSERT INTO tbl_stock 
                (fullqty, emptyqty, status, updatedatetime, tbl_user_idtbl_user, tbl_product_idtbl_product)
                VALUES (?, ?, 1, ?, ?, ?)
            ");
            $stmtInsert->bind_param("iisii", $newFullQty, $newEmptyQty, $updatedatetime, $userID, $productId);

            if (!$stmtInsert->execute()) {
                throw new Exception("Error inserting stock");
            }
        }
    }

    // 🔹 Update purchase return status
    $stmtPR = $conn->prepare("
        UPDATE tbl_purchase_return SET 
            approvestatus = 1,
            updatedatetime = ?, 
            tbl_user_idtbl_user = ?
        WHERE idtbl_purchase_return = ?
    ");
    $stmtPR->bind_param("sii", $updatedatetime, $userID, $orderID);

    if (!$stmtPR->execute()) {
        throw new Exception("Error updating purchase return");
    }

    // ✅ Commit
    $conn->commit();

    echo json_encode([
        "icon" => "fas fa-check-circle",
        "title" => "",
        "message" => "Purchase Return Approved Successfully",
        "type" => "success"
    ]);

} catch (Exception $e) {

    // ❌ Rollback
    $conn->rollback();

    echo json_encode([
        "icon" => "fas fa-exclamation-triangle",
        "title" => "",
        "message" => "Error: " . $e->getMessage(),
        "type" => "danger"
    ]);
}