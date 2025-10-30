<?php
require_once('../connection/db.php');

$reimbursementid=$_POST['reimbursementid'];
$html='';

$sql="SELECT `tbl_invoice_reimbursement`.`idtbl_invoice_reimbursement`, `tbl_invoice_reimbursement`.`date`, `tbl_invoice_reimbursement`.`reimdocno`, `tbl_invoice_reimbursement`.`netamount`, `tbl_invoice_reimbursement`.`status`, `tbl_invoice_reimbursement_detail`.`invoicedate`, `tbl_invoice_reimbursement_detail`.`amount`, `tbl_invoice_reimbursement_detail`.`tbl_invoice_idtbl_invoice`, `tbl_customer`.`name` AS customername, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num` FROM `tbl_invoice_reimbursement` LEFT JOIN `tbl_invoice_reimbursement_detail` ON `tbl_invoice_reimbursement_detail`.`tbl_invoice_reimbursement_idtbl_invoice_reimbursement`=`tbl_invoice_reimbursement`.`idtbl_invoice_reimbursement` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_reimbursement_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice_reimbursement`.`status`=1 AND `tbl_invoice_reimbursement`.`idtbl_invoice_reimbursement`='$reimbursementid'";
$result=mysqli_query($conn,$sql);

?>
<table class="table table-striped table-bordered table-sm small">
    <thead>
        <tr>
            <th>Document No.</th>
            <th>Customer</th>
            <th>Invoice No.</th>
            <th>Invoice Date</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while($row=mysqli_fetch_array($result)){
            $invoicenum = !empty($row['tax_invoice_num']) ? 'AGT'.$row['tax_invoice_num'] : 'INV-'.$row['tbl_invoice_idtbl_invoice'];
        ?>
            <tr>
            <td><?php echo $row['reimdocno'] ?></td>
            <td><?php echo $row['customername'] ?></td>
            <td><?php echo $invoicenum ?></td>
            <td><?php echo date('d-m-Y', strtotime($row['invoicedate'])) ?></td>
            <td style="text-align:right;"><?php echo number_format($row['amount'], 2) ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>