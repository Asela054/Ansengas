<?php 
require_once('../connection/db.php');

$paymentinoiceID=$_POST['paymentinoiceID'];

$sqlpaymentdetail="SELECT `tbl_invoice_payment_has_tbl_invoice`.* , `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num` FROM `tbl_invoice_payment_has_tbl_invoice` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$paymentinoiceID'";
$resultpaymentdetail=$conn->query($sqlpaymentdetail);

$sqlpayment="SELECT * FROM `tbl_invoice_payment` WHERE `idtbl_invoice_payment`='$paymentinoiceID' AND `status`=1";
$resultpayment=$conn->query($sqlpayment);
$rowpayment=$resultpayment->fetch_assoc();

$sqlpaymentbank="SELECT * FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `method` IN (2, 3) AND `tbl_invoice_payment_idtbl_invoice_payment`='$paymentinoiceID'";
$resultpaymentbank=$conn->query($sqlpaymentbank);

$sumpayment="SELECT SUM(payamount) AS payamount FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$paymentinoiceID'";
$resultsumpayment=$conn->query($sumpayment);

$customersql="SELECT * FROM tbl_customer JOIN tbl_invoice ON tbl_customer.idtbl_customer = tbl_invoice.tbl_customer_idtbl_customer JOIN tbl_invoice_payment_has_tbl_invoice ON tbl_invoice.idtbl_invoice = tbl_invoice_payment_has_tbl_invoice.tbl_invoice_idtbl_invoice WHERE tbl_invoice_payment_has_tbl_invoice.tbl_invoice_payment_idtbl_invoice_payment = '$paymentinoiceID'";
$result = mysqli_query($conn, $customersql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $customerName = $row['name'];
    $customerAddress = $row['address'];
    $customerPhone = $row['phone'];

    mysqli_free_result($result);
} 
?>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-right"><img src="images/logoprint.png" width="80" height="80" class="img-fluid"></td>
                    <td colspan="4" class="text-center small align-middle">
                    <h2 class="font-weight-light m-0">ANSEN GAS DISTRIBUTORS (PVT) LTD</h2>
                    65, Arcbishop, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka<br>
                        Tel: 0312 235 050 | Email: info@ansengas.lk<br>
                        <span class="font-weight-bold">Distributor for LAUGFS Gas PLC.</span>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>            
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col-6">
        <div class="row">
            <div class="col-4">Customer</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php echo $customerName; ?></div>
        </div>
        <div class="row">
            <div class="col-4">Address</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php echo $customerAddress; ?></div>
        </div>
        <div class="row">
            <div class="col-4">Contact No</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php echo $customerPhone; ?></div>
        </div>
    </div>
    <div class="col-6 text-right">
        <div class="row">
            <div class="col-4">Receipt No</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6">PR-<?php echo $paymentinoiceID; ?></div>
        </div>
        <div class="row">
            <div class="col-4">Receipt Date</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php echo $rowpayment['date']; ?></div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <h5 class="text-center">Payment Receipt</h5>
        <hr class="border-dark">
    </div>
    
</div>
<div class="row mt-3">
    <div class="col-12">
    <table class="table table-striped table-bordered table-black table-sm small bg-transparent tableprint">
    <thead>
        <tr>
            <th>#</th>
            <th>Invoice No</th>
            <th class="text-right">Invoice Amount</th>
            <th class="text-right">Payment</th>
        </tr>
    </thead>
    <tbody>
    <?php
$i = 1;
$netTotal = 0;
$payamount = 0;

while ($rowpaymentdetail = $resultpaymentdetail->fetch_assoc()) {
    $invoiceID = $rowpaymentdetail['tbl_invoice_idtbl_invoice'];
    $sqlinvoice = "SELECT `idtbl_invoice`,`tax_invoice_num`,`nettotal` FROM `tbl_invoice` WHERE `idtbl_invoice`='$invoiceID' AND `status`=1";
    $resultinvoice = $conn->query($sqlinvoice);
    $rowinvoice = $resultinvoice->fetch_assoc();

    $sqlpaymentsum = "SELECT SUM(`payamount`) AS payamount FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID'";
    $resultpaymentsum = $conn->query($sqlpaymentsum);
    $rowpaymentsum = $resultpaymentsum->fetch_assoc();

    $netTotal += $rowinvoice['nettotal'];
    $payamount += $rowpaymentsum['payamount']; // Use += to accumulate the payamount

    ?>
    <tr>
        <td><?php echo $i ?></td>
        <td>
        <?php 
            if ($rowinvoice['tax_invoice_num'] == null) {
                echo 'INV-'.$rowinvoice['idtbl_invoice'];
            } else {
                echo 'AGT'.$rowinvoice['tax_invoice_num'];
            }
        ?>
        </td>
        <td class="text-right"><?php echo number_format($rowinvoice['nettotal'], 2); ?></td>
        <td class="text-right"><?php echo number_format($rowpaymentdetail['payamount'], 2); ?></td>
    </tr>
    <?php $i++;
}

$balance = $netTotal - $payamount;
?>
    </tbody>
</table>

<div class="row">
    <div class="col-9 text-right"><h2 class="font-weight-bold">Net Total</h2></div>
    <div class="col-3 text-right"><h2 class="font-weight-bold"><?php echo 'Rs.' . number_format($netTotal, 2); ?></h2></div>
</div>
<div class="row">
    <div class="col-9 text-right"><h2 class="font-weight-bold">Paid Amount</h2></div>
    <div class="col-3 text-right"><h2 class="font-weight-bold"><?php echo 'Rs.' . number_format($payamount, 2); ?></h2></div>
</div>
<div class="row">
    <div class="col-9 text-right"><h5 class="font-weight-light">Payment</h5></div>
    <div class="col-3 text-right"><h5 class="font-weight-light"><?php echo 'Rs.'.number_format($rowpayment['payment'], 2); ?></h5></div>
</div>
<div class="row">
    <div class="col-9 text-right"><h6 class="font-weight-light">balance</h6></div>
    <div class="col-3 text-right"><h6 class="font-weight-light"><?php echo 'Rs.' . number_format($balance, 2); ?></h6></div>
</div>
<?php if($resultpaymentbank->num_rows > 0) { ?>
<div class="row">
    <div class="col-6">
        <h6 class="title-style small"><span>Cheque / Online Transfer Information</span></h6>
        <table class="table table-striped table-bordered table-sm small">
            <thead>
                <tr>
                    <th>Cheque Date</th>
                    <th>Cheque | Receipt No</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php while($rowpaymentbank=$resultpaymentbank->fetch_assoc()){ ?>
                <tr>
                    <td><?php if($rowpaymentbank['chequeno']!=''){echo $rowpaymentbank['chequedate'];}if($rowpaymentbank['receiptno']!=''){echo $rowpaymentbank['receiptno'];} ?></td>
                    <td><?php echo $rowpaymentbank['chequeno']; ?></td>
                    <td class="text-right"><?php echo number_format($rowpaymentbank['amount'], 2); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>