<?php 
require_once('../connection/db.php');

$invID=$_POST['invID'];

$sql="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`emptyqty`, `tbl_invoice_detail`.`newprice`, `tbl_invoice_detail`.`refillprice`, `tbl_invoice_detail`.`emptyprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$invID' AND `tbl_invoice_detail`.`status`=1";
$result=$conn->query($sql);

$sqlinvoice="SELECT `total`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `idtbl_invoice`='$invID' AND `status`=1";
$resultinvoice=$conn->query($sqlinvoice);
$rowinvoice=$resultinvoice->fetch_assoc();

$cusID=$rowinvoice['tbl_customer_idtbl_customer'];

$sqlcustomer="SELECT `type`, `name`, `nic`, `phone`, `email`, `address` FROM `tbl_customer` WHERE `idtbl_customer`='$cusID' AND `status`=1";
$resultcustomer=$conn->query($sqlcustomer);
$rowcustomer=$resultcustomer->fetch_assoc();

?>
<div class="row">
    <div class="col">
        <?php echo $rowcustomer['name'].'<br>'.$rowcustomer['nic'].'<br>'.$rowcustomer['phone'].'<br>'.$rowcustomer['email'].'<br>'.$rowcustomer['address'] ?>
    </div>
</div>
<table class="table table-striped table-bordered table-sm" id="grnlisttable">
    <thead>
        <tr>
            <th>Product</th>
            <th class="text-center">New</th>
            <th class="text-center">Refill</th>
            <th class="text-center">Empty</th>
            <th class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php 
            while($row=$result->fetch_assoc()){
                $totrefill=$row['refillqty']*$row['refillprice'];
                $tottrust=$row['emptyqty']*$row['emptyprice'];
                $totnew=$row['newqty']*$row['newprice'];
                $total=number_format(($totrefill+$totnew+$tottrust), 2);
        ?>
        <tr>
            <td><?php echo $row['product_name']; ?></td>
            <td class="text-center"><?php echo $row['newqty']; ?></td>
            <td class="text-center"><?php echo $row['refillqty']; ?></td>
            <td class="text-center"><?php echo $row['emptyqty']; ?></td>
            <td class="text-right"><?php echo $total; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
<div class="row">
    <div class="col text-right">
        <h4 class="font-weight-normal"><?php echo 'Rs '.number_format($rowinvoice['total'], 2) ?></h4>
    </div>
</div>