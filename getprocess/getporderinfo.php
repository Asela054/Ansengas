<?php
require_once('../connection/db.php');

$orderID=$_POST['ponumber'];

$sqlorderdetail="SELECT `tbl_porder_detail`.`unitprice`,`tbl_porder_detail`.`refillprice`,`tbl_porder_detail`.`emptyprice`,`tbl_porder_detail`.`newqty`,`tbl_porder_detail`.`refillqty`,`tbl_porder_detail`.`saftyqty`,`tbl_porder_detail`.`returnqty`,`tbl_porder_detail`.`saftyreturnqty`,`tbl_porder_detail`.`emptyqty`,`tbl_porder_detail`.`trustqty`,`tbl_product`.`product_name`,`tbl_product`.`idtbl_product` FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`tbl_product_idtbl_product` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID'";
$resultorderdetail=$conn->query($sqlorderdetail);
while ($roworderdetail = $resultorderdetail-> fetch_assoc()) {
    $totrefill=$roworderdetail['refillqty']*$roworderdetail['refillprice'];
    $totnew=$roworderdetail['newqty']*$roworderdetail['unitprice'];
    $totempty=$roworderdetail['emptyqty']*$roworderdetail['emptyprice'];
    $tottrust=$roworderdetail['trustqty']*$roworderdetail['refillprice'];
    $totsafty=$roworderdetail['saftyqty']*$roworderdetail['refillprice'];

    $total=$totnew+$totrefill+$tottrust+$totsafty+$totempty;
?>
<tr>
    <td><?php echo $roworderdetail['product_name'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['idtbl_product'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['unitprice'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['refillprice'] ?></td>
    <td class="d-none"><?php echo $roworderdetail['emptyprice'] ?></td>
    <td class="d-none">0</td>
    <td class="d-none">0</td>
    <td class="text-center"><?php echo $roworderdetail['refillqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['newqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['emptyqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['returnqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['trustqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['saftyqty'] ?></td>
    <td class="text-center"><?php echo $roworderdetail['saftyreturnqty'] ?></td>
    <td class="totaldispatch d-none"><?php echo $total ?></td>
    <td class="text-right"><?php echo number_format($total,2) ?></td>
</tr>
<?php } ?>