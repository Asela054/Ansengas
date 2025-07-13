<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d h:i:s');

$date_warehouse = $_POST['date_warehouse'];
$backqty = $_POST['backqty'];
$type = $_POST['tanktype'];
$hiddenID = $_POST['hiddenID'];

$sql = "UPDATE `tbl_damage_return` SET `backstockstatus` = '1', `backstockdate` = '$date_warehouse', `updatedatetime` = '$updatedatetime' WHERE `idtbl_damage_return` = '$hiddenID'";

if ($conn->query($sql) === true) {

    $productQuery = "SELECT `tbl_product_idtbl_product` FROM `tbl_damage_return` WHERE `idtbl_damage_return` = '$hiddenID'";
    $productResult = $conn->query($productQuery);

    if ($productResult->num_rows > 0) {
        $productRow = $productResult->fetch_assoc();
        $productID = $productRow['tbl_product_idtbl_product'];

        $updateDamageQty = "UPDATE `tbl_stock` SET `damageqty_company` = `damageqty_company` - $backqty WHERE `tbl_product_idtbl_product` = '$productID'";
        $conn->query($updateDamageQty);

        if ($type === '1') {
            $updateStockQty = "UPDATE `tbl_stock` SET `fullqty` = `fullqty` + $backqty WHERE `tbl_product_idtbl_product` = '$productID'";
        } else if ($type === '2') {
            $updateStockQty = "UPDATE `tbl_stock` SET `emptyqty` = `emptyqty` + $backqty WHERE `tbl_product_idtbl_product` = '$productID'";
        }

        $conn->query($updateStockQty);
    }
    
    header("Location:../damagereturn.php?action=6");
} else {
    header("Location:../damagereturn.php?action=5");
}
?>
