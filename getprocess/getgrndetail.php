<?php
require_once('../connection/db.php');

$grnid=$_POST['grnid'];

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

$sql="SELECT `tbl_grndetail`.`newqty`, `tbl_grndetail`.`fillqty`, `tbl_grndetail`.`trustqty`, `tbl_grndetail`.`saftyqty`, `tbl_grndetail`.`unitprice_withoutvat`, `tbl_grndetail`.`refillprice_withoutvat`, `tbl_grndetail`.`emptyprice_withoutvat`, `tbl_grndetail`.`unitprice`, `tbl_grndetail`.`refillprice`, `tbl_grndetail`.`emptyprice`, `tbl_grndetail`.`totalwithoutvat`, `tbl_grndetail`.`total`, `tbl_product`.`product_name` FROM `tbl_grndetail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_grndetail`.`tbl_product_idtbl_product` WHERE `tbl_grndetail`.`tbl_grn_idtbl_grn`='$grnid' AND `tbl_grndetail`.`status`=1 ";
$result=$conn->query($sql);
?>
<table class="table table-striped table-bordered table-dark table-sm" id="">
    <thead>
        <tr>
            <th>Product</th>
            <th class="text-center">New price</th>
            <th class="text-center">Refill price</th>
            <th class="text-center">Refill price</th>
            <th class="text-center">New price+(VAT)</th>
            <th class="text-center">Refill price+(VAT)</th>
            <th class="text-center">Refill price+(VAT)</th>
            <th class="text-center">New</th>
            <th class="text-center">Refill</th>
            <th class="text-center">Trust</th>
            <th class="text-center">Safety</th>
            <th class="text-right">Total</th>
            <th class="text-center">VAT(%)</th>
            <th class="text-right">Total+(VAT)</th>

        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td><?php echo $row['product_name']; ?></td>
            <td><?php echo number_format($row['unitprice_withoutvat'],2); ?></td>
            <td><?php echo number_format($row['refillprice_withoutvat'],2); ?></td>
            <td><?php echo number_format($row['emptyprice_withoutvat'],2); ?></td>
            <td><?php echo number_format($row['unitprice'],2); ?></td>
            <td><?php echo number_format($row['refillprice'],2); ?></td>
            <td><?php echo number_format($row['emptyprice'],2); ?></td>
            <td><?php echo $row['newqty']; ?></td>
            <td><?php echo $row['fillqty']; ?></td>
            <td><?php echo $row['trustqty']; ?></td>
            <td><?php echo $row['saftyqty']; ?></td>
            <td class="text-right"><?php echo number_format($row['totalwithoutvat'], 2); ?></td>
            <td><?php echo $rowvat['vat']; ?></td>
            <td class="text-right"><?php echo number_format($row['total'], 2); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>