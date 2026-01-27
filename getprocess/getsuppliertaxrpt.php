<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$sql="SELECT `idtbl_grn`, `date`, `total`, `taxamount`, `nettotal`, `invoicenum`, `dispatchnum` FROM `tbl_grn` WHERE `date` BETWEEN '$validfrom' AND '$validto' AND `status`=1";
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
        <?php 
        $i=1; 
        while($row = $result-> fetch_assoc()){ 
            $grnID = $row['idtbl_grn'];
            $sqldesc = "SELECT 
                CASE 
                    WHEN COUNT(DISTINCT c.idtbl_product_category) = 2 THEN 'Refil Gas & Gas Accessories'
                    WHEN MAX(c.idtbl_product_category) = 1 THEN 'Refil Gas'
                    WHEN MAX(c.idtbl_product_category) = 2 THEN 'Gas Accessories'
                    ELSE 'Other'
                END AS category_label
            FROM tbl_product_category c
            INNER JOIN tbl_product p ON p.tbl_product_category_idtbl_product_category = c.idtbl_product_category
            INNER JOIN tbl_grndetail d ON d.tbl_product_idtbl_product = p.idtbl_product
            WHERE d.status = 1 
            AND d.tbl_grn_idtbl_grn = $grnID";
            $resultdesc = $conn-> query($sqldesc);
            $rowdesc = $resultdesc-> fetch_assoc();
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td><?php echo $row['date']; ?></td>
            <td><?php echo $row['invoicenum']; ?></td>
            <td>114372218-7000</td>
            <td>Laugfs Gas PLC</td>
            <td><?php echo $rowdesc['category_label']; ?></td>
            <td class="text-right"><?php echo number_format($row['total'], 2); ?></td>
            <td class="text-right"><?php echo number_format($row['taxamount'], 2); ?></td>
            <td>&nbsp;</td>
        </tr>
        <?php $i++;} ?>
    </tbody>
</table> 