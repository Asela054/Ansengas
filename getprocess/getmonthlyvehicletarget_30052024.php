<?php 
require_once('../connection/db.php');

$today = date('m');

$sql = "SELECT `idtbl_vehicle`,`vehicleno` FROM  `tbl_vehicle` WHERE `status` = 1";
$result = $conn->query($sql);

$productarray = array();
$sqlprodcut = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status` = 1 AND `tbl_product_category_idtbl_product_category` = 1";
$resultprodcut = $conn->query($sqlprodcut);
while($rowprodcut = $resultprodcut->fetch_assoc()){
    $obj = new stdClass();
    $obj->productID = $rowprodcut['idtbl_product'];
    $obj->product = $rowprodcut['product_name'];

    array_push($productarray, $obj);
}
?>
<table class="table table-striped table-bordered table-sm small">
    <thead>
        <tr>
            <th>Vehicle</th>
            <?php foreach($productarray as $rowproductarray){ ?>
            <th class="text-center"><?php echo $rowproductarray->product; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        while($row = $result->fetch_assoc()){ 
            $vehicleID = $row['idtbl_vehicle'];
            $sqlavaqty = "SELECT SUM(`targettank`) AS `targettank` FROM `tbl_vehicle_target` WHERE `status` = 1 AND `tbl_vehicle_idtbl_vehicle` = '$vehicleID' AND `targettank` != 0";
            $resultavaqty = $conn->query($sqlavaqty);
            $rowavaqty = $resultavaqty->fetch_assoc();

            // Check if there are any non-empty target tanks for this vehicle
            $hasNonEmptyTargetTank = false; 
            foreach($productarray as $rowproductarray){
                $productID = $rowproductarray->productID;
                if ($rowavaqty['targettank'] !== null && $rowavaqty['targettank'] !== 0) {
                    $hasNonEmptyTargetTank = true;
                    break; // No need to continue checking
                }
            }

            // Skip displaying the row if all target tanks for this vehicle are empty
            if (!$hasNonEmptyTargetTank) {
                continue;
            }
        ?>
        <tr>
            <td><?php echo $row['vehicleno']; ?></td>
            <?php 
            foreach($productarray as $rowproductarray){
                $vehicleID = $row['idtbl_vehicle'];
                $productID = $rowproductarray->productID;
                $sqlavaqty = "SELECT SUM(`targettank`) AS `targettank`,SUM(`targetcomplete`) AS `targetcomplete` FROM `tbl_vehicle_target` WHERE `status` = 1 AND `tbl_vehicle_idtbl_vehicle` = '$vehicleID' AND `tbl_product_idtbl_product` = '$productID'";
                $resultavaqty = $conn->query($sqlavaqty);
                $rowavaqty = $resultavaqty->fetch_assoc();
            ?>
                <td class="text-center"><?php echo isset($rowavaqty['targetcomplete']) && isset($rowavaqty['targettank']) ? $rowavaqty['targetcomplete'] . '&nbsp;of&nbsp;' . $rowavaqty['targettank'] . ' Completed' : '-'; ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
