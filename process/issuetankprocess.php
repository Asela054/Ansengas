<?php 
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location:index.php");
    exit;
}

require_once('../connection/db.php');

$userID = $_SESSION['userid'];

if (!empty($_POST['recordID'])) {
    $recordID = $_POST['recordID'];
}

$issuecustomer = addslashes($_POST['issuecustomer']);
$issueqty = addslashes($_POST['issueqty']);
$returnqty = addslashes($_POST['returnqty']);
$issueproduct = addslashes($_POST['hideproductid']);
$returndate = date('Y-m-d');

$insert = "INSERT INTO `tbl_tank_transaction_return` (`return_date`, `qty`, `tbl_user_idtbl_user`, `tbl_tank_transaction_idtbl_tank_transaction`) VALUES ('$returndate', '$issueqty', '$userID', '$issuecustomer')";
$insertResult = $conn->query($insert);

if (!$insertResult) {
    header("Location:../transaction.php?action=5");
    exit;
}

$updatestock = "UPDATE `tbl_stock` SET `emptyqty` = (`emptyqty` - '$issueqty') WHERE `tbl_product_idtbl_product` = '$issueproduct'";
$updateResult = $conn->query($updatestock);

if (!$updateResult) {
    header("Location:../transaction.php?action=5");
    exit;
}

// Return table Sum 
$returnsql = "SELECT SUM(`qty`) as returnqty FROM `tbl_tank_transaction_return` WHERE `tbl_tank_transaction_idtbl_tank_transaction` = '$issuecustomer'";
$returnresult = $conn->query($returnsql);
$returnRow = $returnresult->fetch_assoc();
$returnqtyTotal = $returnRow['returnqty'];

// Tank transaction qty
$collectsql = "SELECT `qty` FROM `tbl_tank_transaction` WHERE `idtbl_tank_transaction` = '$issuecustomer'";
$collectresult = $conn->query($collectsql);
$collectRow = $collectresult->fetch_assoc();
$collectqty = $collectRow['qty'];

if ($returnqtyTotal == $collectqty) {
    $updatetransaction = "UPDATE `tbl_tank_transaction` SET `issuestatus` = 1, `issuedate` = '$returndate' WHERE `idtbl_tank_transaction` = '$issuecustomer'";
    $transactionResult = $conn->query($updatetransaction);

    if (!$transactionResult) {
        header("Location:../transaction.php?action=5");
        exit;
    }
}

header("Location:../transaction.php?action=4");
exit;
?>