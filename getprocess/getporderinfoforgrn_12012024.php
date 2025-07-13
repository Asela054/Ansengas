<?php
require_once('../connection/db.php');

$orderID=$_POST['ponumber'];

$sqlorderdetail="SELECT * FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`tbl_product_idtbl_product` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID'";
$resultorderdetail=$conn->query($sqlorderdetail);
while ($roworderdetail = $resultorderdetail-> fetch_assoc()) {
    $totrefill=$roworderdetail['refillqty']*$roworderdetail['refillprice'];
    $totnew=$roworderdetail['newqty']*$roworderdetail['unitprice'];
    $totempty=$roworderdetail['emptyqty']*$roworderdetail['refillprice'];
    $tottrust=$roworderdetail['trustqty']*$roworderdetail['refillprice'];
    $totsafty=$roworderdetail['saftyqty']*$roworderdetail['refillprice'];

    $total=$totnew+$totrefill+$totempty+$tottrust+$totsafty;
?>
<tr>
    <td><?php echo $roworderdetail['product_name'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['idtbl_product'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['unitprice'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['refillprice'] ?></td>
    <td class="text-right"><?php echo number_format($roworderdetail['unitprice'],2) ?></td>
    <td class="text-right"><?php echo number_format($roworderdetail['refillprice'],2) ?></td>
    <td class="text-center editnewqty"><?php echo $roworderdetail['newqty'] ?></td>
    <td class="text-center editrefillqty"><?php echo $roworderdetail['refillqty'] ?></td>
    <td class="text-center editemptyqty"><?php echo $roworderdetail['emptyqty'] ?></td>
    <td class="text-center edittrustqty"><?php echo $roworderdetail['trustqty'] ?></td>
    <td class="text-center editsaftyqty"><?php echo $roworderdetail['saftyqty'] ?></td>
    <td class="total d-none"><?php echo $total ?></td>
    <td class="text-right"><?php echo number_format($total,2) ?></td>
</tr>
<?php } ?>