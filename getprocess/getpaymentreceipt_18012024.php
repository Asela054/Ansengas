<?php 
require_once('../connection/db.php');

$paymentinoiceID=$_POST['paymentinoiceID'];

$sqlpaymentdetail="SELECT * FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$paymentinoiceID'";
$resultpaymentdetail=$conn->query($sqlpaymentdetail);

$sqlpayment="SELECT * FROM `tbl_invoice_payment` WHERE `idtbl_invoice_payment`='$paymentinoiceID' AND `status`=1";
$resultpayment=$conn->query($sqlpayment);
$rowpayment=$resultpayment->fetch_assoc();

$sqlpaymentbank="SELECT * FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `method`=2 AND `tbl_invoice_payment_idtbl_invoice_payment`=''";
$resultpaymentbank=$conn->query($sqlpaymentbank);
?>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-right"><img src="images/logoprint.png" width="80" height="80" class="img-fluid"></td>
                    <td colspan="4" class="text-center small align-middle">
                        <h2 class="font-weight-light m-0">Ansen Gas Distributors (Pvt) Ltd</h2>
                        65, Archbishop Nicholas Marcus Fernando Mawatha, Negombo<br>
                        Tel: 0094-31-4549149 | Fax: 0094-31-2225050 info@ansengas.lk
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>            
        </table>
    </div>
</div>
<div class="row">
    <div class="col-12 text-right">Receipt No: PR-<?php echo $paymentinoiceID; ?></div>
</div>
<div class="row">
    <div class="col-12">
        <h5 class="mt-3">Payment Receipt</h5>
        <hr class="border-dark">
    </div>
</div>
<div class="row">
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
                <?php $i=1;while($rowpaymentdetail=$resultpaymentdetail->fetch_assoc()){ ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td ><?php echo 'INV-'.$rowpaymentdetail['tbl_invoice_idtbl_invoice']; ?></td>
                    <td class="text-right"><?php $invoiceID=$rowpaymentdetail['tbl_invoice_idtbl_invoice']; $sqlinvoice="SELECT `total` FROM `tbl_invoice` WHERE `idtbl_invoice`='$invoiceID' AND `status`=1"; $resultinvoice=$conn->query($sqlinvoice); $rowinvoice=$resultinvoice->fetch_assoc(); echo number_format($rowinvoice['total'], 2); ?></td>
                    <td class="text-right"><?php echo number_format($rowpaymentdetail['payamount'],2); ?></td>
                </tr>
                <?php $i++;} ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-9 text-right"><h2 class="font-weight-bold">Net Total</h2></div>
    <div class="col-3 text-right"><h2 class="font-weight-bold"><?php echo 'Rs.'.number_format($rowpayment['payment'], 2); ?></h2></div>
</div>
<div class="row">
    <div class="col-9 text-right"><h5 class="font-weight-light">Payment</h5></div>
    <div class="col-3 text-right"><h5 class="font-weight-light"><?php echo 'Rs.'.number_format($rowpayment['payment'], 2); ?></h5></div>
</div>
<div class="row">
    <div class="col-9 text-right"><h6 class="font-weight-light">balance</h6></div>
    <div class="col-3 text-right"><h6 class="font-weight-light"><?php echo 'Rs.'.number_format($rowpayment['balance'], 2); ?></h6></div>
</div>
<div class="row">
    <div class="col">
        <?php 
        while($rowpaymentbank=$resultpaymentbank->fetch_assoc()){
            if($rowpaymentbank['chequeno']!=''){echo $rowpaymentbank['chequeno'].' - '.$rowpaymentbank['amount'].'<br>';}
            else if($rowpaymentbank['receiptno']!=''){echo $rowpaymentbank['receiptno'].' - '.$rowpaymentbank['amount'].'<br>';}
        } 
        ?>
    </div>
</div>