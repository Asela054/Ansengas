<?php 
require_once('../connection/db.php');
ini_set('max_execution_time', 6200); //6200 seconds = 120 minutes

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];
$customer=$_POST['customer'];

if(!empty($_POST['customer'])){
    $sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1 AND `idtbl_customer`='$customer'";
    $resultcustomer =$conn-> query($sqlcustomer);
}
else{
    $sqlcustomer="SELECT `idtbl_customer`, `name` FROM `tbl_customer` WHERE `status`=1";
    $resultcustomer =$conn-> query($sqlcustomer);
}

if(!empty($_POST['customer'])){
?>
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Date</th>
            <th>Invoice</th>
            <th class="text-right">Invoice Total</th>
            <th class="text-right">Invoice Payment</th>
            <th class="text-right">Balance</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        while($rowcustomer = $resultcustomer-> fetch_assoc()){ 
            $customerID=$rowcustomer['idtbl_customer'];
            
            $sqlinvcount="SELECT COUNT(*) AS `count` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto'";
            $resultinvcount =$conn-> query($sqlinvcount);
            $rowinvcount = $resultinvcount-> fetch_assoc();

            $sqlinvoicelist="SELECT `idtbl_invoice`, `date`, `total` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto'";
            $resultinvoicelist =$conn-> query($sqlinvoicelist);

            if($rowinvcount['count']>0){
        ?>
        <tr>
            <td colspan="7"><?php echo $rowcustomer['name']; ?></td>
        </tr>
        <?php 
            while($rowinvoicelist = $resultinvoicelist-> fetch_assoc()){  
                $invID=$rowinvoicelist['idtbl_invoice'];

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invID'";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc()
        ?>
        <tr>
            <td>&nbsp;</td>
            <td><?php echo $rowinvoicelist['date'] ?></td>
            <td><?php echo 'INV-'.$rowinvoicelist['idtbl_invoice'] ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicelist['total'], 2); ?></td>
            <td class="text-right"><?php echo number_format($rowpayment['payamount'], 2); ?></td>
            <td class="text-right"><?php echo number_format(($rowinvoicelist['total']-$rowpayment['payamount']), 2) ?></td>
            <td class="text-center"><button class="btn btn-outline-dark btn-sm viewbtninv" id="<?php echo $rowinvoicelist['idtbl_invoice'] ?>"><i class="fas fa-eye"></i></button></td>
        </tr>
        <?php }}} ?>
    </tbody>
</table>
<?php } else{ ?>
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th>Customer</th>
            <th class="text-right">Invoice Total</th>
            <th class="text-right">Invoice Payment</th>
            <th class="text-right">Balance</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        while($rowcustomer = $resultcustomer-> fetch_assoc()){ 
            $customerID=$rowcustomer['idtbl_customer'];

            if($validfrom=='' && $validto==''){
                $sqlinvoicelist="SELECT SUM(`total`) AS `total` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID'";
                $resultinvoicelist =$conn-> query($sqlinvoicelist);
                $rowinvoicelist = $resultinvoicelist-> fetch_assoc();

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$customerID')";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc();
            }
            else{
                $sqlinvoicelist="SELECT SUM(`total`) AS `total` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto'";
                $resultinvoicelist =$conn-> query($sqlinvoicelist);
                $rowinvoicelist = $resultinvoicelist-> fetch_assoc();

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto')";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc();
            }

            if($rowinvoicelist['total']>0){
        ?>
        <tr>
            <td><?php echo $rowcustomer['name']; ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicelist['total'], 2); ?></td>
            <td class="text-right"><?php echo number_format($rowpayment['payamount'], 2); ?></td>
            <td class="text-right"><?php echo number_format(($rowinvoicelist['total']-$rowpayment['payamount']), 2) ?></td>
        </tr>
        <?php }} ?>
    </tbody>
</table>
<?php } ?>