<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID=$_SESSION['userid'];
$updatedatetime=date('Y-m-d h:i:s');

$date_customer=$_POST['date_customer'];
$returnqty = (int)$_POST['returnqty'];
$returntanktype = $_POST['returntanktype'];
$hiddenID=$_POST['hiddenID'];

$sql="UPDATE `tbl_damage_return` SET `returncusstatus`='1',`returncusdate`='$date_customer',`updatedatetime`='$updatedatetime' WHERE `idtbl_damage_return`='$hiddenID'";

if ($conn->query($sql) === true) {

    $productQuery = "SELECT `tbl_product_idtbl_product` FROM `tbl_damage_return` WHERE `idtbl_damage_return` = '$hiddenID'";
    $productResult = $conn->query($productQuery);

    if ($productResult->num_rows > 0) {
        $productRow = $productResult->fetch_assoc();
        $productID = $productRow['tbl_product_idtbl_product'];

        if ($returntanktype === '1') { 
                $updateStockQuery = "UPDATE `tbl_stock` SET  `fullqty` = `fullqty` - $returnqty WHERE `tbl_product_idtbl_product` = '$productID'";
            } elseif ($returntanktype === '2') {
                $updateStockQuery = "UPDATE `tbl_stock` SET  `emptyqty` = `emptyqty` - $returnqty WHERE `tbl_product_idtbl_product` = '$productID'";
            }

        $conn->query($updateStockQuery);
    }

    header("Location:../damagereturn.php?action=6");
} else {
    header("Location:../damagereturn.php?action=5");
}
?>