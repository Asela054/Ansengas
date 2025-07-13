<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d H:i:s');

$record = $_GET['record'] ?? null;

if ($record === null) {
    header("Location: ../error_page.php?error=missing_record");
    exit();
}

// Update sendstatus in tbl_credit_note
$updatestockstatus = "UPDATE `tbl_credit_note` SET `sendstatus` = 1 WHERE `idtbl_credit_note` = ?";
$stmt3 = $conn->prepare($updatestockstatus);
$stmt3->bind_param('i', $record);
$stmt3->execute();
$stmt3->close();

// Select emptyqty and product ID from tbl_credit_note_detail
$sqlqty = "SELECT `emptyqty`, `tbl_product_idtbl_product`
           FROM `tbl_credit_note_detail`
           WHERE `status` = 1
           AND `tbl_credit_note_idtbl_credit_note` = ?";
$stmt = $conn->prepare($sqlqty);
$stmt->bind_param('i', $record);
$stmt->execute();
$resultqty = $stmt->get_result();

if ($resultqty->num_rows > 0) {
    while ($rowqty = $resultqty->fetch_assoc()) {
        $emptyQty = $rowqty['emptyqty'];
        $productId = $rowqty['tbl_product_idtbl_product'];

        // Update emptyqty in tbl_stock
        $updatestock = "UPDATE `tbl_stock` SET `emptyqty` = `emptyqty` + ? WHERE `tbl_product_idtbl_product` = ?";
        $stmt2 = $conn->prepare($updatestock);
        $stmt2->bind_param('ii', $emptyQty, $productId);
        
        if (!$stmt2->execute()) {
            header("Location: ../creditnote.php?action=action=5");
            exit();
        }
        
        $stmt2->close();
    }
    header("Location: ../creditnote.php?action=4");
} else {
    header("Location: ../creditnote.php?action=5");
}

$stmt->close();
$conn->close();
exit();
?>
