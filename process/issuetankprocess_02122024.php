<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$issuecustomer=addslashes($_POST['issuecustomer']);
$issueqty=addslashes($_POST['issueqty']);
$issueproduct=addslashes($_POST['hideproductid']);
$returndate=date('Y-m-d');

    $insert = "INSERT INTO `tbl_tank_transaction_return` (`return_date`, `qty`, `tbl_user_idtbl_user`, `tbl_tank_transaction_idtbl_tank_transaction`) VALUES ('$returndate', '$issueqty', '$userID', '$issuecustomer')";

    $updatestock = "UPDATE `tbl_stock` SET `emptyqty` = (`emptyqty` - '$issueqty') WHERE `tbl_product_idtbl_product` = '$issueproduct'";

    $updatetransaction = "UPDATE `tbl_tank_transaction` SET `issuestatus` = 1, `issuedate` = '$returndate' WHERE `idtbl_tank_transaction` = '$issuecustomer'";

    $insertResult = $conn->query($insert);
    $updateResult = $conn->query($updatestock);
    $transactionResult = $conn->query($updatetransaction);


    if ($insertResult === true && $updateResult === true && $transactionResult === true) {
        header("Location:../transaction.php?action=4");
    } else {
        header("Location:../transaction.php?action=5");
    }
?>