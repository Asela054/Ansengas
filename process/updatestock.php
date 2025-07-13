<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php");
    exit();
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$product = $_POST['product'];
$qty = $_POST['qty'];
$balanceqty = $_POST['balanceqty'];
$record = $_POST['hiddencreditnoteId'];
$updatedatetime = date('Y-m-d H:i:s');

$updatestockstatus = "UPDATE `tbl_credit_note_detail` SET `balanceqty` = ? WHERE `tbl_credit_note_idtbl_credit_note` = ? AND `tbl_product_idtbl_product` = ?";
$stmt3 = $conn->prepare($updatestockstatus);
if ($stmt3) {
    $stmt3->bind_param('iii', $balanceqty, $record, $product);
    $stmt3->execute();
    $stmt3->close();
}

$updatestock = "UPDATE `tbl_stock` SET `emptyqty` = `emptyqty` + ? WHERE `tbl_product_idtbl_product` = ?";
$stmt2 = $conn->prepare($updatestock);
if ($stmt2) {
    $stmt2->bind_param('ii', $qty, $product);
    if (!$stmt2->execute()) {
        header("Location: ../creditnote.php?action=action=6");
        exit();
    }
    $stmt2->close();
}

if ($balanceqty == 0) {
    $updateCreditNote = "UPDATE `tbl_credit_note` SET `sendstatus` = 1, `updatedatetime` = ? WHERE `idtbl_credit_note` = ?";
    $stmt4 = $conn->prepare($updateCreditNote);
    if ($stmt4) {
        $stmt4->bind_param('si', $updatedatetime, $record);
        if (!$stmt4->execute()) {
            header("Location: ../creditnote.php?action=action=6");
            exit();
        }
        $stmt4->close();
    }
}

header("Location: ../creditnote.php?action=4");
$conn->close();
exit();
?>
