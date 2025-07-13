<?php 
require_once('../connection/db.php');

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];
$receipttype=$_POST['receipttype'];

if($receipttype==1){
    $sql="SELECT * FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `addaccountstatus`=0 AND `method`=1 AND `tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `idtbl_invoice_payment` FROM `tbl_invoice_payment` WHERE `status`=1 AND `date` BETWEEN '$validfrom' AND '$validto')";
    $result=$conn->query($sql);
}
else if($receipttype==2){
    $sql="SELECT * FROM `tbl_invoice_payment_detail` WHERE `status`=1 AND `addaccountstatus`=0 AND `method`=2 AND `tbl_invoice_payment_idtbl_invoice_payment` IN (SELECT `idtbl_invoice_payment` FROM `tbl_invoice_payment` WHERE `status`=1 AND `date` BETWEEN '$validfrom' AND '$validto')";
    $result=$conn->query($sql);
}
else if($receipttype==3){
    $sql="SELECT `idtbl_invoice`, `total` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `date` BETWEEN '$validfrom' AND '$validto'";
    $result=$conn->query($sql);
}
?>
<table class="table table-striped table-bordered table-sm" id="paymentlisttable">
    <thead>
        <tr>
            <th>#</th>
            <th class="d-none">PaymentinfoID</th>
            <th>Pay Type</th>
            <th>Customer</th>
            <th class="d-none">Amounthide</th>
            <th class="text-right">Amount</th>
            <th>Cheque No</th>
            <th>Cheque Date</th>
            <th>Bank</th>
            <th>Branch</th>
            <th class="text-center">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checkallocate" id="selectAll">
                    <label class="custom-control-label" for="selectAll"></label>
                </div>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if($receipttype==1){ $i=1; while($row=$result->fetch_assoc()){ 
            $paymentID=$row['tbl_invoice_payment_idtbl_invoice_payment'];
            $sqlcus="SELECT `name` FROM `tbl_customer` WHERE `idtbl_customer` IN (SELECT `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `idtbl_invoice` IN (SELECT `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$paymentID') AND `status`=1) AND `status`=1";
            $resultcus=$conn->query($sqlcus);
            $rowcus=$resultcus->fetch_assoc();
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td class="d-none"><?php echo $row['idtbl_invoice_payment_detail']; ?></td>
            <td><?php echo 'Cash Payment' ?></td>
            <td><?php echo $rowcus['name'] ?></td>
            <td class="d-none"><?php echo $row['amount'] ?></td>
            <td class="text-right"><?php echo number_format($row['amount'],2) ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="text-center">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checkallocate" id="customCheck<?php echo $row['idtbl_invoice_payment_detail'] ?>">
                    <label class="custom-control-label" for="customCheck<?php echo $row['idtbl_invoice_payment_detail'] ?>"></label>
                </div>
            </td>
        </tr>
        <?php 
        $i++;}}
        if($receipttype==2){ $i=1; while($row=$result->fetch_assoc()){ 
            $bankID=$row['tbl_bank_idtbl_bank'];
            $sqlbank="SELECT `bankname` FROM `tbl_bank` WHERE `status`=1 AND `idtbl_bank`='$bankID'";
            $resultbank=$conn->query($sqlbank);
            $rowbank=$resultbank->fetch_assoc();

            $paymentID=$row['tbl_invoice_payment_idtbl_invoice_payment'];
            $sqlcus="SELECT `name` FROM `tbl_customer` WHERE `idtbl_customer` IN (SELECT `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `idtbl_invoice` IN (SELECT `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_payment_idtbl_invoice_payment`='$paymentID') AND `status`=1) AND `status`=1";
            $resultcus=$conn->query($sqlcus);
            $rowcus=$resultcus->fetch_assoc();
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td class="d-none"><?php echo $row['idtbl_invoice_payment_detail']; ?></td>
            <td><?php echo 'Cheque Payment' ?></td>
            <td><?php echo $rowcus['name'] ?></td>
            <td class="d-none"><?php echo $row['amount'] ?></td>
            <td class="text-right"><?php echo number_format($row['amount'],2) ?></td>
            <td><?php echo $row['chequeno'] ?></td>
            <td><?php echo $row['chequedate'] ?></td>
            <td><?php echo $rowbank['bankname'] ?></td>
            <td><?php echo $row['branch'] ?></td>
            <td class="text-center">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checkallocate" id="customCheck<?php echo $row['idtbl_invoice_payment_detail'] ?>">
                    <label class="custom-control-label" for="customCheck<?php echo $row['idtbl_invoice_payment_detail'] ?>"></label>
                </div>
            </td>
        </tr>
        <?php 
        $i++;}} 
        if($receipttype==3){ $i=1; while($row=$result->fetch_assoc()){ 
            $invoiceID=$row['idtbl_invoice'];

            $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID'";
            $resultpayment =$conn-> query($sqlpayment);
            $rowpayment = $resultpayment-> fetch_assoc();

            $sqlcus="SELECT `name` FROM `tbl_customer` WHERE `idtbl_customer` IN (SELECT `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `idtbl_invoice`='$invoiceID' AND `status`=1) AND `status`=1";
            $resultcus=$conn->query($sqlcus);
            $rowcus=$resultcus->fetch_assoc();
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td class="d-none"><?php echo $row['idtbl_invoice']; ?></td>
            <td><?php echo 'Credit Payment' ?></td>
            <td><?php echo $rowcus['name'] ?></td>
            <td class="d-none"><?php echo $row['amount'] ?></td>
            <td class="text-right"><?php echo number_format(($row['total']-$rowpayment['payamount']),2) ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td class="text-center">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input checkallocate" id="customCheck<?php echo $row['idtbl_invoice'] ?>">
                    <label class="custom-control-label" for="customCheck<?php echo $row['idtbl_invoice'] ?>"></label>
                </div>
            </td>
        </tr>
        <?php $i++;}} ?>
    </tbody>
</table>
<hr>
<div class="row">
    <div class="col-12 text-right">
        <button type="button" class="btn btn-outline-primary btn-sm px-4" id="btnaddtolist"><i class="fas fa-plus"></i>&nbsp;Add Receipt To List</button>
    </div>
</div>