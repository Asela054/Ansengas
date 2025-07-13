<?php 
require_once('../connection/db.php');

$today = date('m');

$sql = "SELECT `idtbl_employee`,`name` FROM  `tbl_employee` WHERE `status` = 1 AND tbl_user_type_idtbl_user_type=7";
$result = $conn->query($sql);

$productarray = array();
$sqlprodcut = "SELECT `idtbl_product`, `product_name`, `orderlevel` FROM `tbl_product` WHERE `status` = 1 AND `tbl_product_category_idtbl_product_category` = 1";
$resultprodcut = $conn->query($sqlprodcut);
while($rowprodcut = $resultprodcut->fetch_assoc()){
    $obj = new stdClass();
    $obj->productID = $rowprodcut['idtbl_product'];
    $obj->product = $rowprodcut['product_name'];
    $obj->orderlevel = $rowprodcut['orderlevel'];

    array_push($productarray, $obj);
}
usort($productarray, function($a, $b) {
    return strcmp($a->orderlevel, $b->orderlevel);
});
?>
<table class="table table-striped table-bordered table-sm small">
    <thead  class="bg-secondary-soft">
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
            $employeeID = $row['idtbl_employee'];
            $sqlavaqty = "SELECT SUM(`targettank`) AS `targettank` FROM `tbl_employee_target` WHERE `status` = 1 AND `tbl_employee_idtbl_employee` = '$employeeID' AND `targettank` != 0";
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
            <td><?php echo $row['name']; ?></td>
            <?php 
            foreach($productarray as $rowproductarray){
                $employeeID = $row['idtbl_employee'];
                $productID = $rowproductarray->productID;
                $sqlavaqty = "SELECT SUM(`targettank`) AS `targettank`, SUM(`targetcomplete`) AS `targetcomplete` FROM `tbl_employee_target` WHERE `status` = 1 AND `tbl_employee_idtbl_employee` = '$employeeID' AND `tbl_product_idtbl_product` = '$productID' AND MONTH(`month`) = MONTH(CURRENT_DATE()) AND YEAR(`month`) = YEAR(CURRENT_DATE())";
                $resultavaqty = $conn->query($sqlavaqty);
                $rowavaqty = $resultavaqty->fetch_assoc();
            ?>
                <td class="text-center"><?php echo isset($rowavaqty['targetcomplete']) && isset($rowavaqty['targettank']) ? $rowavaqty['targetcomplete'] . '&nbsp;of&nbsp;' . $rowavaqty['targettank'] . ' Completed' : '-'; ?></td>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
