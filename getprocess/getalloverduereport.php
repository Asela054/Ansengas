<?php 
require_once('../connection/db.php');

$customer=$_POST['customer'];
?>
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Invoice No.</th>
            <th class="text-center">Date</th>
            <th class="text-center">No of Days Since Invoice</th>
            <th class="text-center">Invoice Amount</th>
        </tr>
    </thead>
    <tbody>
<?php 
$sqlinvcount = "SELECT c.name AS customer_name, i.date, i.idtbl_invoice AS invoice_id, i.tax_invoice_num AS tax_invoice_id, i.nettotal, DATEDIFF(CURDATE(), i.date) AS days_since_invoice 
                FROM tbl_invoice i 
                JOIN tbl_customer c ON i.tbl_customer_idtbl_customer = c.idtbl_customer 
                WHERE c.idtbl_customer='$customer' AND i.paymentcomplete=0 
                ORDER BY i.date ASC";

$resultinvcount = $conn->query($sqlinvcount);

if ($resultinvcount->num_rows > 0) {
    while ($rowinvcount = $resultinvcount->fetch_assoc()) {
        if (!isset($firstRow)) {
            $firstRow = $rowinvcount;
            ?>
            <tr>
                <td colspan="5"><?php echo $firstRow['customer_name']; ?></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>&nbsp;</td>
            <td>
            <?php 
                if ($rowinvcount['tax_invoice_id'] == null) {
                    echo 'INV-'.$rowinvcount['invoice_id'];
                } else {
                    echo 'AGT'.$rowinvcount['tax_invoice_id'];
                }
            ?>
            </td>
            <td class="text-center"><?php echo $rowinvcount['date']; ?></td>
            <td class="text-center"><?php echo $rowinvcount['days_since_invoice']; ?></td>
            <td class="text-center"><?php echo number_format($rowinvcount['nettotal'], 2) ?></td>
        </tr>
        <?php
    }
} else {
    ?>
    <tr>
        <td colspan="5">No invoices found.</td>
    </tr>
    <?php
}
?>
</tbody>

</table>
