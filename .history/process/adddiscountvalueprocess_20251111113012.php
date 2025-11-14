<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit;
}

require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$salesrep = addslashes($_POST['salesrep']);
$updatedatetime = date('Y-m-d h:i:s');

$products = $_POST['product'];
$hiddenID = $_POST['hiddenID'];

                $insert = "INSERT INTO `tbl_customerwise_salesrep`(`status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`, `tbl_employee_idtbl_employee`) VALUES ('1','$updatedatetime','$userID','$hiddenID','$product','$salesrep')";
                if ($conn->query($insert) !== true) {
                    header("Location:../customer.php?action=5");
                    exit;
                }


$action = ($duplicate) ? 7 : (($recordOption == 1) ? 4 : 6);
header("Location:../customer.php?action=$action");
exit;
?>


