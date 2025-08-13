<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d h:i:s');

$date_company = $_POST['date_company'];
$sendqty = $_POST['sendqty'];
$hiddenID = $_POST['hiddenID'];

$sql = "UPDATE `tbl_damage_return`  SET `comsendstatus`='1', `comsenddate`='$date_company', `updatedatetime`='$updatedatetime' 
        WHERE `idtbl_damage_return`='$hiddenID'";

if ($conn->query($sql) === true) {

    $productQuery = "SELECT `tbl_product_idtbl_product` FROM `tbl_damage_return` WHERE `idtbl_damage_return` = '$hiddenID'";
    $productResult = $conn->query($productQuery);

    if ($productResult->num_rows > 0) {
        $productRow = $productResult->fetch_assoc();
        $productID = $productRow['tbl_product_idtbl_product'];

        $updateStockQuery = "UPDATE `tbl_stock` SET `damageqty_yard` = `damageqty_yard` - $sendqty, `damageqty_company` = `damageqty_company` + $sendqty WHERE `tbl_product_idtbl_product` = '$productID'";
        
        $conn->query($updateStockQuery);
    }

    header("Location:../damagereturn.php?action=6");
} else {
    header("Location:../damagereturn.php?action=5");
}
?>
