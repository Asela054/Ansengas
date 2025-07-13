<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$customer=addslashes($_POST['customer']);
$loadlist=addslashes($_POST['loadlist']);
$product=addslashes($_POST['product']);
$qty=addslashes($_POST['qty']);
$updatedatetime=date('Y-m-d h:i:s');
$collectdate=date('Y-m-d');

if ($recordOption == 1) {
    $insert = "INSERT INTO `tbl_tank_transaction` (`qty`, `collectstatus`, `collectdate`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_vehicle_load_idtbl_vehicle_load`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$qty', '1', '$collectdate', '1', '$updatedatetime', '$userID', '$loadlist', '$customer', '$product')";

    $updatestock = "UPDATE `tbl_stock` SET `emptyqty` = (`emptyqty` + '$qty') WHERE `tbl_product_idtbl_product` = '$product'";

    $insertResult = $conn->query($insert);
    $updateResult = $conn->query($updatestock);

    if ($insertResult === true && $updateResult === true) {
        header("Location:../transaction.php?action=4");
    } else {
        header("Location:../transaction.php?action=5");
    }
}

else{
    $update="UPDATE `tbl_tank_transaction` SET `qty`='$qty',`tbl_user_idtbl_user`='$userID' ,`tbl_vehicle_load_idtbl_vehicle_load`='$loadlist',`tbl_customer_idtbl_customer`='$customer',`tbl_product_idtbl_product`='$product'WHERE `idtbl_tank_transaction`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../transaction.php?action=6");
    }
    else{header("Location:../transaction.php?action=5");}
}
?>