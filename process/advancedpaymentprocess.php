<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$recordOption = $_POST['recordOption'];
$recordID = isset($_POST['recordID']) ? $_POST['recordID'] : null;
$date = addslashes($_POST['date']);
$invoice = addslashes($_POST['invoice']);
$customer = addslashes($_POST['customer']);
$payment = addslashes($_POST['payment']);
$updatedatetime = date('Y-m-d H:i:s');

if ($recordOption == 1) {
    $insert = "INSERT INTO tbl_advanced_payment (date, invoice_id, payment_amount, status, insertdatetime, tbl_user_idtbl_user, tbl_customer_idtbl_customer) VALUES ('$date', '$invoice', '$payment', '1', '$updatedatetime', '$userID', '$customer')";
    
    $insertexcesspayment = "INSERT INTO tbl_invoice_excess_payment (date, excess_amount, status, updatedatetime, updateuser, tbl_customer_idtbl_customer) VALUES ('$date', '$payment', '1', '$updatedatetime', '$userID', '$customer')";
    
    if ($conn->query($insert) && $conn->query($insertexcesspayment)) {
        header("Location: ../advancedpayment.php?action=4");
    } else {
        header("Location: ../advancedpayment.php?action=5");
    }
} else {
    if ($recordID === null) {
        header("Location: ../advancedpayment.php?action=error");
        exit();
    }
    
    $update = "UPDATE tbl_advanced_payment SET date='$date', invoice_id='$invoice', payment_amount='$payment', updateuser='$userID', updatedatetime='$updatedatetime', tbl_customer_idtbl_customer='$customer' WHERE idtbl_advanced_payment='$recordID'";
    
    if ($conn->query($update)) {
        header("Location: ../advancedpayment.php?action=6");
    } else {
        header("Location: ../advancedpayment.php?action=5");
    }
}

$conn->close();
?>
