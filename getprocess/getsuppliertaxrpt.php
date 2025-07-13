<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$sql="SELECT `date`, `total`, `taxamount`, `nettotal`, `invoicenum`, `dispatchnum` FROM `tbl_grn` WHERE `date` BETWEEN '$validfrom' AND '$validto' AND `status`=1";
$result =$conn-> query($sql);
?>
<table class="table table-striped table-bordered table-sm small" id="table_content">
    <thead>
        <tr>
            <th>Serial No</th>
            <th>Invoice Date</th>
            <th>Tax Invoice No</th>
            <th>Supplier's TIN</th>
            <th>Name of the Supplier</th>
            <th>Description</th>
            <th class="text-right">Value of supply</th>
            <th class="text-right">Vat Amount</th>
            <th class="text-right">Disallowed VAT Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php $i=1; while($row = $result-> fetch_assoc()){ ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['invoicenum']; ?></td>
            <td>114372218-7000</td>
            <td>Laugfs Gas PLC</td>
            <td>Gas Refill</td>
            <td class="text-right"><?php echo number_format($row['total'], 2); ?></td>
            <td class="text-right"><?php echo number_format($row['taxamount'], 2); ?></td>
            <td>&nbsp;</td>
        </tr>
        <?php $i++;} ?>
    </tbody>
</table> 