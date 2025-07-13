<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit;
}

require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$recordOption = $_POST['recordOption'];
if (!empty($_POST['recordID'])) {
    $recordID = $_POST['recordID'];
}
$salesrep = addslashes($_POST['salesrep']);
$updatedatetime = date('Y-m-d h:i:s');

$customers = $_POST['customer'];
$products = $_POST['product'];

$duplicate = false;

foreach ($customers as $customer) {
    foreach ($products as $product) {
        $check_query = "SELECT * FROM `tbl_customerwise_salesrep` WHERE `tbl_customer_idtbl_customer`='$customer' AND `tbl_product_idtbl_product`='$product' AND `tbl_employee_idtbl_employee`='$salesrep'";
        $result = $conn->query($check_query);

        if ($result->num_rows == 0) {
            if ($recordOption == 1) {
                $insert = "INSERT INTO `tbl_customerwise_salesrep`(`status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`, `tbl_employee_idtbl_employee`) VALUES ('1','$updatedatetime','$userID','$customer','$product','$salesrep')";
                if ($conn->query($insert) !== true) {
                    header("Location:../customerwiserep.php?action=5");
                    exit;
                }
            } else {
                $update = "UPDATE `tbl_customerwise_salesrep` SET `updatedatetime`='$updatedatetime',`updateuser`='$userID' ,`tbl_customer_idtbl_customer`='$customer' ,`tbl_product_idtbl_product`='$product',`tbl_employee_idtbl_employee`='$salesrep' WHERE `idtbl_customerwise_salesrep`='$recordID'";
                if ($conn->query($update) !== true) {
                    header("Location:../customerwiserep.php?action=5");
                    exit;
                }
            }
        } else {
            $duplicate = true;
        }
    }
}

$action = ($duplicate) ? 7 : (($recordOption == 1) ? 4 : 6);
header("Location:../customerwiserep.php?action=$action");
exit;
?>


