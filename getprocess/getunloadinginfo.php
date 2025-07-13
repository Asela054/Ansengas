<?php 
require_once('../connection/db.php');

$loadID=$_POST['loadID'];

$sql="SELECT * FROM `tbl_vehicle_load_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_vehicle_load_detail`.`tbl_product_idtbl_product` WHERE `tbl_vehicle_load_detail`.`tbl_vehicle_load_idtbl_vehicle_load`='$loadID' AND `tbl_vehicle_load_detail`.`status`=1";
$result=$conn->query($sql);
?>
<div class="row">
    <div class="col">
        <table class="table table-striped table-bordered table-sm nowrap w-100" id="tableunloading">
            <thead>
                <tr>
                    <th nowrap>Product</th>
                    <th nowrap class="d-none">ProductID</th>
                    <th nowrap class="text-center">Load Qty</th>
                    <th nowrap class="text-center">New</th>
                    <th nowrap class="text-center">Refill</th>
                    <th nowrap class="text-center">Empty</th>
                    <th nowrap class="text-center">Trust</th>
                    <th nowrap class="text-center">Trust Return</th>
                    <th nowrap class="text-center">Balance Full Qty</th>
                    <th nowrap class="text-center">Balance Empty Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while($row=$result->fetch_assoc()){ 
                    $productID=$row['idtbl_product'];

                    $sqlinvoiceqty="SELECT SUM(`newqty`+`refillqty`+`trustqty`) AS `qty`, SUM(`refillqty`) AS `refillqty`, SUM(`newqty`) AS `newqty`, SUM(`emptyqty`) AS `emptyqty`, SUM(`trustqty`) AS `trustqty`, SUM(`trustreturnqty`) AS `trustreturnqty` FROM `tbl_invoice_detail` WHERE `tbl_product_idtbl_product`='$productID' AND `status`=1 AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_vehicle_load_idtbl_vehicle_load`='$loadID')";
                    $resultinvoiceqty=$conn->query($sqlinvoiceqty);
                    $rowinvoiceqty=$resultinvoiceqty->fetch_assoc()
                ?>
                <tr>
                    <td><?php echo $row['product_name'] ?></td>
                    <td class="d-none"><?php echo $row['idtbl_product'] ?></td>
                    <td class="text-center"><?php echo $row['qty']+$rowinvoiceqty['qty'] ?></td>
                    <td class="text-center"><?php if($rowinvoiceqty['newqty']){echo $rowinvoiceqty['newqty'];}else{echo '0';} ?></td>
                    <td class="text-center"><?php if($rowinvoiceqty['refillqty']){echo $rowinvoiceqty['refillqty'];}else{echo '0';} ?></td>
                    <td class="text-center"><?php if($rowinvoiceqty['emptyqty']){echo $rowinvoiceqty['emptyqty'];}else{echo '0';} ?></td>
                    <td class="text-center"><?php if($rowinvoiceqty['trustqty']){echo $rowinvoiceqty['trustqty'];}else{echo '0';} ?></td>
                    <td class="text-center"><?php if($rowinvoiceqty['trustreturnqty']){echo $rowinvoiceqty['trustreturnqty'];}else{echo '0';} ?></td>

                    <td class="text-center"><?php echo $row['qty'] ?></td>
                    <td class="text-center"><?php echo $row['emptyqty'] ?></td>

                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>