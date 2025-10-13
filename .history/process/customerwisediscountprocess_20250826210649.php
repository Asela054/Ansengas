<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit;
}

require_once('../connection/db.php');
$userID = $_SESSION['userid'];

$recordOption = $_POST['recordOption'];
$updatedatetime = date('Y-m-d h:i:s');

$customers = $_POST['customer'];
$products = $_POST['product'];
$discountType = $_POST['discountType'];
$discountPercentage = isset($_POST['discountPercentage']) ? $_POST['discountPercentage'] : null;
$discountAmount = isset($_POST['discountAmount']) ? $_POST['discountAmount'] : null;

$duplicate = false;

foreach ($customers as $customer) {
    foreach ($products as $product) {
        $check_query = "SELECT * FROM `tbl_customer_discount` WHERE `tbl_customer_idtbl_customer`='$customer' AND `tbl_product_idtbl_product`='$product' AND `tbl_user_idtbl_user`='$userID'";
        $result = $conn->query($check_query);

        if ($result->num_rows == 0) {
            if ($recordOption == 1) {
                $insert = "INSERT INTO `tbl_customer_discount`(`discount_type`, `discount_amount`, `discount_percent`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) 
                           VALUES ('$discountType', '$discountAmount', '$discountPercentage', '1', '$updatedatetime', '$userID', '$customer', '$product')";
                if ($conn->query($insert) !== true) {
                    header("Location:../customerdiscount.php?action=5");
                    exit;
                }
            } else {
                $recordID = $_POST['recordID'];
                $update = "UPDATE `tbl_customer_discount` SET `discount_type`='$discountType', `discount_amount`='$discountAmount', `discount_percent`='$discountPercentage', `updatedatetime`='$updatedatetime', `updateuser`='$userID'
                           WHERE `idtbl_customer_discount`='$recordID'";
                if ($conn->query($update) !== true) {
                    header("Location:../customerdiscount.php?action=5"); 
                    exit;
                }
            }
        } else {
            $duplicate = true;
        }
    }
}

$action = ($duplicate) ? 7 : (($recordOption == 1) ? 4 : 6);
header("Location:../customerdiscount.php?action=$action");
exit;
?>