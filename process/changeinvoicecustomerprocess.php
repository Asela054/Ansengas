<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d H:i:s');

$customer = $_POST['customer'];      
$prev_customer = $_POST['prev_customer'];
$hiddenID = $_POST['hiddenID'];

$sql = "UPDATE `tbl_invoice` SET `tbl_customer_idtbl_customer`='$customer', `updatedatetime`='$updatedatetime'  WHERE `idtbl_invoice`='$hiddenID'";

if ($conn->query($sql) === true) {

    $sql2 = "INSERT INTO `tbl_invoice_customer_change` (`date`, `status`, `updateuser`, `updatedatetime`, `prev_customer_id`, `new_customer_id`, `tbl_invoice_idtbl_invoice`)
             VALUES (NOW(), '1', '$userID', '$updatedatetime', '$prev_customer', '$customer', '$hiddenID')";
    
    $conn->query($sql2);

    header("Location:../invoiceview.php?action=1");
} else {
    header("Location:../invoiceview.php?action=5");
}
?>
