<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("");}
require_once('../connection/db.php');

$userID = $_SESSION['userid'];
$updatedatetime = date('Y-m-d h:i:s');

$date_warehouse = $_POST['date_warehouse'];
$backqty = $_POST['backqty'];
$returnType = $_POST['tanktype'];
$hiddenID = $_POST['hiddenID'];

$sql = "UPDATE `tbl_damage_return` SET `backstockstatus` = '1', `backstockdate` = '$date_warehouse', `updatedatetime` = '$updatedatetime' WHERE `idtbl_damage_return` = '$hiddenID'";

if ($conn->query($sql) === true) {
    $productQuery = "SELECT `tbl_product_idtbl_product`, `tank_type` FROM `tbl_damage_return` WHERE `idtbl_damage_return` = '$hiddenID'";
    $productResult = $conn->query($productQuery);

    if ($productResult->num_rows > 0) {
        $productRow = $productResult->fetch_assoc();
        $productID = $productRow['tbl_product_idtbl_product'];
        $originalTankType = $productRow['tank_type'];

        if ($originalTankType === '1' && $returnType === '2') {
            $updateStockQuery = "UPDATE `tbl_stock` SET `damage_fullqty_company` = `damage_fullqty_company` - $backqty, `emptyqty` = `emptyqty` + $backqty WHERE `tbl_product_idtbl_product` = '$productID'";
        } 
        elseif ($originalTankType === '2' && $returnType === '2') {
            $updateStockQuery = "UPDATE `tbl_stock` SET `damage_emptyqty_company` = `damage_emptyqty_company` - $backqty, `emptyqty` = `emptyqty` + $backqty WHERE `tbl_product_idtbl_product` = '$productID'";
        }
        else {
            if ($returnType === '1') { 
                $updateStockQuery = "UPDATE `tbl_stock` SET `damage_fullqty_company` = `damage_fullqty_company` - $backqty, `fullqty` = `fullqty` + $backqty WHERE `tbl_product_idtbl_product` = '$productID'";
            } elseif ($returnType === '2') {
                $updateStockQuery = "UPDATE `tbl_stock` SET `damage_emptyqty_company` = `damage_emptyqty_company` - $backqty, `emptyqty` = `emptyqty` + $backqty WHERE `tbl_product_idtbl_product` = '$productID'";
            }
        }

        if (isset($updateStockQuery)) {
            $conn->query($updateStockQuery);
        }
    }
    
    header("Location:../damagereturn.php?action=6");
} else {
    header("Location:../damagereturn.php?action=5");
}
?>