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

$duplicate = false;

    foreach ($products as $product) {
        $check_query = "SELECT * FROM `tbl_customerwise_salesrep` WHERE `tbl_customer_idtbl_customer`='$hiddenID' AND `tbl_product_idtbl_product`='$product' AND `tbl_employee_idtbl_employee`='$salesrep'";
        $result = $conn->query($check_query);

        if ($result->num_rows == 0) {
                $insert = "INSERT INTO `tbl_customerwise_salesrep`(`status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`, `tbl_employee_idtbl_employee`) VALUES ('1','$updatedatetime','$userID','$hiddenID','$product','$salesrep')";
                if ($conn->query($insert) !== true) {
                    header("Location:../customer.php?action=5");
                    exit;
                }
        } else {
            $duplicate = true;
        }
    }


$action = ($duplicate) ? 7 : (($recordOption == 1) ? 4 : 6);
header("Location:../customer.php?action=$action");
exit;
?>


