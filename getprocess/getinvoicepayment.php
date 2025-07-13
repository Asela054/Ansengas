<?php 
require_once('../connection/db.php');

if (!empty($_POST['invoiceno'])) {
    $invoiceno = $_POST['invoiceno'];

    if ($invoiceno !== false) {
        $sql = "SELECT * FROM (SELECT `idtbl_invoice`, `tax_invoice_num`, `non_tax_invoice_num`, `date`, `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `idtbl_invoice`='$invoiceno' AND `status`=1 AND `paymentcomplete`=0) AS `dinv` LEFT JOIN (SELECT SUM(`payamount`) AS `payamount`, `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_payment_has_tbl_invoice` GROUP BY `tbl_invoice_idtbl_invoice`) AS `dhasinv` ON `dhasinv`.`tbl_invoice_idtbl_invoice`=`dinv`.`idtbl_invoice` ORDER BY `dinv`.`date` DESC";
    } else {
        $sql = "SELECT * FROM (SELECT `idtbl_invoice`, `tax_invoice_num`, `non_tax_invoice_num`, `date`, `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `tax_invoice_num`='$invoiceno' AND `status`=1 AND `paymentcomplete`=0) AS `dinv` LEFT JOIN (SELECT SUM(`payamount`) AS `payamount`, `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_payment_has_tbl_invoice` GROUP BY `tbl_invoice_idtbl_invoice`) AS `dhasinv` ON `dhasinv`.`tbl_invoice_idtbl_invoice`=`dinv`.`idtbl_invoice` ORDER BY `dinv`.`date` DESC";
    }

    $result = $conn->query($sql);

} elseif (!empty($_POST['customerID'])) {
    $customerID = $_POST['customerID'];

    $sql = "SELECT * FROM (SELECT `idtbl_invoice`, `tax_invoice_num`, `non_tax_invoice_num`, `date`, `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `tbl_customer_idtbl_customer`='$customerID' AND `status`=1 AND `paymentcomplete`=0) AS `dinv` LEFT JOIN (SELECT SUM(`payamount`) AS `payamount`, `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_payment_has_tbl_invoice` GROUP BY `tbl_invoice_idtbl_invoice`) AS `dhasinv` ON `dhasinv`.`tbl_invoice_idtbl_invoice`=`dinv`.`idtbl_invoice` ORDER BY `dinv`.`date` DESC";
    $result = $conn->query($sql);
}
?>
<table class="table table-striped table-bordered table-sm" id="paymentDetailTable">
    <thead>
        <tr>
            <th class="d-none">InvoiceID</th>
            <th>Invoice No</th>
            <!-- <th>Book No</th> -->
            <th class="d-none">Sale Type</th>
            <th>Date</th>
            <th class="text-right">Amount</th>
            <th class="d-none">Hide Amount</th>
            <th class="text-right">Paid Amount</th>
            <th class="text-right">Balance</th>
            <th>Full Payment</th>
            <th>Half Payment</th>
            <th class="text-right">Payment</th>
            <th class="d-none">CustomerID</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row=$result->fetch_assoc()){ ?>
        <tr>
            <td class="d-none"><?php echo 'INV-'.$row['idtbl_invoice']; ?></td>
            <td>
                <?php 
                if ($row['tax_invoice_num'] == null) {
                    echo 'INV-'.$row['idtbl_invoice'];
                } else {
                    echo 'AGT'.$row['tax_invoice_num'];
                }
                ?>
            </td>
            <!-- <td><?php // echo $row['non_tax_invoice_num']; ?></td> -->
            <td class="d-none">&nbsp;</td>
            <td><?php echo $row['date']; ?></td>
            <td class="text-right"><?php echo number_format($row['nettotal'],2); ?></td>
            <td class="d-none"><?php echo sprintf('%.2f', $row['nettotal']); ?></td>
            <td class="text-right"><?php echo number_format($row['payamount'],2); ?></td>
            <td class="text-right"><?php echo number_format(max(($row['nettotal'] - $row['payamount']), 0), 2); ?></td>
            <td>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input fullAmount" name="payCheck1" id="payCheck1<?php echo $row['idtbl_invoice']; ?>" value="1" <?php if($row['payamount']>0){echo 'disabled';} ?>>
                    <label class="custom-control-label small" for="payCheck1<?php echo $row['idtbl_invoice']; ?>">Full Payment</label>
                </div>
            </td>
            <td>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input halfAmount" name="payCheck2" id="payCheck2<?php echo $row['idtbl_invoice']; ?>">
                    <label class="custom-control-label small" for="payCheck2<?php echo $row['idtbl_invoice']; ?>">Half Payment</label>
                </div>
            </td>
            <td class='paidAmount text-right'>0.00</td>
            <td class='d-none'><?php echo $row['tbl_customer_idtbl_customer']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>