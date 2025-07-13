<?php
require_once('../connection/db.php');

$orderID=$_POST['ponumber'];

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

$sqlorderdetail="SELECT `tbl_porder_detail`.`unitprice`,`tbl_porder_detail`.`refillprice`,`tbl_porder_detail`.`emptyprice`,`tbl_porder_detail`.`newqty`,`tbl_porder_detail`.`refillqty`,`tbl_porder_detail`.`saftyqty`,`tbl_porder_detail`.`emptyqty`,`tbl_porder_detail`.`trustqty`,`tbl_product`.`product_name`,`tbl_product`.`idtbl_product`,`tbl_porder_detail`.`unitprice_withoutvat`,`tbl_porder_detail`.`refillprice_withoutvat`,`tbl_porder_detail`.`emptyprice_withoutvat` FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`tbl_product_idtbl_product` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID'";
$resultorderdetail=$conn->query($sqlorderdetail);
while ($roworderdetail = $resultorderdetail-> fetch_assoc()) {
    $totrefill=$roworderdetail['refillqty']*$roworderdetail['refillprice'];
    $totnew=$roworderdetail['newqty']*$roworderdetail['unitprice'];
    $totempty=$roworderdetail['emptyqty']*$roworderdetail['emptyprice'];
    $tottrust=$roworderdetail['trustqty']*$roworderdetail['refillprice'];
    $totsafty=$roworderdetail['saftyqty']*$roworderdetail['refillprice'];

    $totrefillwithoutvat=$roworderdetail['refillqty']*$roworderdetail['refillprice_withoutvat'];
    $totnewwithoutvat=$roworderdetail['newqty']*$roworderdetail['unitprice_withoutvat'];
    $totemptywithoutvat=$roworderdetail['emptyqty']*$roworderdetail['emptyprice_withoutvat'];
    $tottrustwithoutvat=$roworderdetail['trustqty']*$roworderdetail['refillprice_withoutvat'];
    $totsaftywithoutvat=$roworderdetail['saftyqty']*$roworderdetail['refillprice_withoutvat'];


    $total=$totnew+$totrefill+$totempty+$tottrust+$totsafty;
    $totalwithoutvat=$totnewwithoutvat+$totrefillwithoutvat+$totemptywithoutvat+$tottrustwithoutvat+$totsaftywithoutvat;
?>
<tr>
    <td><?php echo $roworderdetail['product_name'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['idtbl_product'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['unitprice_withoutvat'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['refillprice_withoutvat'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['emptyprice_withoutvat'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['unitprice'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['refillprice'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['emptyprice'] ?></td>
    <td class="text-right"><?php echo number_format($roworderdetail['unitprice_withoutvat'],2) ?></td>
    <td class="text-right"><?php echo number_format($roworderdetail['refillprice_withoutvat'],2) ?></td>
    <td class="text-right"><?php echo number_format($roworderdetail['emptyprice_withoutvat'],2) ?></td>
    <td class="text-right"><?php echo number_format($roworderdetail['unitprice'],2) ?></td>
    <td class="text-right"><?php echo number_format($roworderdetail['refillprice'],2) ?></td>
    <td class="text-right"><?php echo number_format($roworderdetail['emptyprice'],2) ?></td>
    <td class="text-center editnewqty"><?php echo $roworderdetail['newqty'] ?></td>
    <td class="text-center editrefillqty"><?php echo $roworderdetail['refillqty'] ?></td>
    <td class="text-center editemptyqty"><?php echo $roworderdetail['emptyqty'] ?></td>
    <td class="text-center edittrustqty"><?php echo $roworderdetail['trustqty'] ?></td>
    <td class="text-center editsaftyqty"><?php echo $roworderdetail['saftyqty'] ?></td>
    <td class="totalwithoutvat d-none"><?php echo $totalwithoutvat ?></td>
    <td class="total d-none"><?php echo $total ?></td>
    <td class="text-right"><?php echo number_format($totalwithoutvat,2) ?></td>
    <td class="text-center"><?php echo $rowvat['vat']. '%' ?></td>
    <td class="text-right"><?php echo number_format($total,2) ?></td>
</tr>
<?php } ?>