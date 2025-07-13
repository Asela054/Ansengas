<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];
$vehicle=$_POST['vehicle'];

if(!empty($_POST['employee'])){
    $sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 AND `type`=0 AND `idtbl_vehicle`='$vehicle'";
    $resultvehicle =$conn-> query($sqlvehicle);
}
else{
    $sqlvehicle="SELECT `idtbl_vehicle`, `vehicleno` FROM `tbl_vehicle` WHERE `status`=1 AND `type`=0";
    $resultvehicle =$conn-> query($sqlvehicle);
}
?>
<div class="table-container">
    <table class="table table-striped table-bordered table-sm sticky-header" id="table_content">
        <thead class="thead-dark">
            <tr>
                <th>Vehicle no</th>
                <th>Product</th>
                <th>Target Cylinders</th>
                <th>Complete Cylinders</th>
                <th>Target Accessories</th>
                <th>Complete Accessories</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            while($rowvehicle = $resultvehicle-> fetch_assoc()){ 
                $vehicleID=$rowvehicle['idtbl_vehicle'];

                $sqlproductlist="SELECT `tbl_product`.`product_name`, `tbl_product`.`tbl_product_category_idtbl_product_category` AS `category`, SUM(`tbl_vehicle_target`.`targettank`) AS `targettank`, SUM(`tbl_vehicle_target`.`targetcomplete`) AS `targetcomplete` FROM `tbl_vehicle_target` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_vehicle_target`.`tbl_product_idtbl_product` WHERE `tbl_vehicle_target`.`tbl_vehicle_idtbl_vehicle`='$vehicleID' AND `tbl_vehicle_target`.`status`=1 AND `tbl_vehicle_target`.`month` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_product`.`product_name`";
                $resultproductlist =$conn-> query($sqlproductlist);
            ?>
            <tr>
                <td colspan="6"><?php echo $rowvehicle['vehicleno']; ?></td>
            </tr>
            <?php while($rowproductlist = $resultproductlist-> fetch_assoc()){  ?>
                <tr>
                <td>&nbsp;</td>
                <td><?php echo $rowproductlist['product_name'] ?></td>

                <?php if (in_array($rowproductlist['category'], [1, 3])) { ?>
                    <td><?php echo $rowproductlist['targettank'] ?></td>
                    <td><?php echo $rowproductlist['targetcomplete'] ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                <?php } elseif ($rowproductlist['category'] == 2) { ?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><?php echo $rowproductlist['targettank'] ?></td>
                    <td><?php echo $rowproductlist['targetcomplete'] ?></td>
                <?php } ?>
            </tr>
            <?php }} ?>
        </tbody>
    </table>
</div>