<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];

$sql="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`tax_invoice_num`, `tbl_customer`.`vat_num`, `tbl_customer`.`tax_cus_name`, `tbl_customer`.`name`, `tbl_invoice`.`total`, `tbl_invoice`.`taxamount`, `tbl_invoice`.`nettotal` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`status`=1 AND `tbl_customer`.`vat_status`=1 ORDER BY `tbl_invoice`.`date`, `tbl_invoice`.`tax_invoice_num` ASC";
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
        <?php 
        while($row = $result-> fetch_assoc()){ 
            $invoiceID = $row['idtbl_invoice'];
            $sqldesc = "SELECT 
                CASE 
                    WHEN COUNT(DISTINCT c.idtbl_product_category) = 2 THEN 'Refil Gas & Gas Accessories'
                    WHEN MAX(c.idtbl_product_category) = 1 THEN 'Refil Gas'
                    WHEN MAX(c.idtbl_product_category) = 2 THEN 'Gas Accessories'
                    ELSE 'Other'
                END AS category_label
            FROM tbl_product_category c
            INNER JOIN tbl_product p ON p.tbl_product_category_idtbl_product_category = c.idtbl_product_category
            INNER JOIN tbl_invoice_detail d ON d.tbl_product_idtbl_product = p.idtbl_product
            WHERE d.status = 1 
            AND d.tbl_invoice_idtbl_invoice = $invoiceID";
            $resultdesc = $conn-> query($sqldesc);
            $rowdesc = $resultdesc-> fetch_assoc();
        ?>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $row['date']; ?></td>
            <td>
            <?php 
                $companyID = 1;

                $yy  = date('y', strtotime($row["date"]));          // e.g. 26
                $mmm = strtoupper(date('M', strtotime($row["date"]))); // e.g. JUL
                $qqqqMap = [
                    1 => 'AGT1'
                ];
                $qqqq = $qqqqMap[$companyID] ?? 'GEN1';
                $taxDatePrefix = $yy . $mmm . '_' . $qqqq . '_';

                // Strip the first two characters from tax_invoice_num, keep the rest
                $rawTaxInvNum = $row['tax_invoice_num'];
                $strippedTaxInvNum = substr($rawTaxInvNum, 2);

                if($row["date"] < '2026-07-01'){
                    echo 'AGT'.$row['tax_invoice_num'];
                }
                else{
                    echo $taxDatePrefix . sprintf('%05d', $strippedTaxInvNum);
                }
            ?>
            </td>
            <td><?php echo $row['vat_num']; ?></td>
            <td><?php echo $row['tax_cus_name']; ?></td>
            <td><?php echo $rowdesc['category_label']; ?></td>
            <td class="text-right"><?php echo number_format($row['total'], 2); ?></td>
            <td class="text-right"><?php echo number_format($row['taxamount'], 2); ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table> 