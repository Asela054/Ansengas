<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$sql="SELECT `tbl_invoice`.`date`, `tbl_invoice`.`tax_invoice_num`, `tbl_customer`.`vat_num`, `tbl_customer`.`tax_cus_name`, `tbl_customer`.`name`, `tbl_invoice`.`total`, `tbl_invoice`.`taxamount`, `tbl_invoice`.`nettotal` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_customer`.`vat_status`=1 ORDER BY `tbl_invoice`.`date`, `tbl_invoice`.`tax_invoice_num` ASC";
$result =$conn-> query($sql);
?>
<table class="table table-striped table-bordered table-sm small" id="table_content">
    <thead>
        <tr>
            <th>Serial No</th>
            <th>Invoice Date</th>
            <th>Tax Invoice No</th>
            <th>Purchaser's TIN</th>
            <th>Name of the Purchaser</th>
            <th>Description</th>
            <th class="text-right">Value of supply</th>
            <th class="text-right">Vat Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = $result-> fetch_assoc()){ ?>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['tax_invoice_num']; ?></td>
            <td><?php echo $row['vat_num']; ?></td>
            <td><?php echo $row['tax_cus_name']; ?></td>
            <td>&nbsp;</td>
            <td class="text-right"><?php echo number_format($row['total'], 2); ?></td>
            <td class="text-right"><?php echo number_format($row['taxamount'], 2); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table> 