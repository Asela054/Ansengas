<?php 
require_once('../connection/db.php');

$today=date('Y-m-d');

$sql="SELECT `tbl_vehicle`.`vehicleno`, `tbl_vehicle_load`.`idtbl_vehicle_load` FROM `tbl_vehicle_load` LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle`=`tbl_vehicle_load`.`lorryid` WHERE `tbl_vehicle_load`.`date`='$today' AND `tbl_vehicle_load`.`status`=1 AND `tbl_vehicle_load`.`unloadstatus`=0";
$result=$conn->query($sql);

$productarray=array();
$sqlprodcut="SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=1";
$resultprodcut=$conn->query($sqlprodcut);
while($rowprodcut=$resultprodcut->fetch_assoc()){
    $obj=new stdClass();
    $obj->productID=$rowprodcut['idtbl_product'];
    $obj->product=$rowprodcut['product_name'];

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
        <?php while($row=$result->fetch_assoc()){ $loadID=$row['idtbl_vehicle_load']; ?>
        <td><?php echo $row['vehicleno']; ?></td>
        <?php 
        foreach($productarray as $rowproductarray){
            $productID=$rowproductarray->productID;
            $sqlavaqty="SELECT SUM(`qty`) AS `qty` FROM `tbl_vehicle_load_detail` WHERE `status`=1 AND `tbl_vehicle_load_idtbl_vehicle_load`='$loadID' AND `tbl_product_idtbl_product`='$productID'";
            $resultavaqty=$conn->query($sqlavaqty);
            $rowavaqty=$resultavaqty->fetch_assoc();
        ?>
        <td class="text-center"><?php echo $rowavaqty['qty']; ?></td>
        <?php }} ?>
    </tbody>
</table>