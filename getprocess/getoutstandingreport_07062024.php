<?php 
require_once('../connection/db.php');
ini_set('max_execution_time', 6200); //6200 seconds = 120 minutes

$validfrom=$_POST['validfrom'];
$validto=$_POST['validto'];
$customer=$_POST['customer'];

if(!empty($_POST['customer'])){
    $sqlcustomer="SELECT `idtbl_customer`, `name`, `discount_status` FROM `tbl_customer` WHERE `status`=1 AND `idtbl_customer`='$customer'";
    $resultcustomer =$conn-> query($sqlcustomer);
}
else{
    $sqlcustomer="SELECT `idtbl_customer`, `name`, `discount_status` FROM `tbl_customer` WHERE `status`=1 ORDER BY `name` ASC";
    $resultcustomer =$conn-> query($sqlcustomer);
}

if(!empty($_POST['customer'])){
?>
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Executive</th>
            <th>Date</th>
            <th>Invoice</th>
            <th class="text-center">No of Days Since Invoice</th>
            <th class="text-right">Invoice Total</th>
            <th class="text-right">Discount Amount</th>
            <th class="text-right">Invoice Payment</th>
            <th class="text-right">Balance</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        while($rowcustomer = $resultcustomer-> fetch_assoc()){ 
            $customerID=$rowcustomer['idtbl_customer'];
            $discountstatus=$rowcustomer['discount_status'];
            
            $sqlinvcount="SELECT COUNT(*) AS `count` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto'";
            $resultinvcount =$conn-> query($sqlinvcount);
            $rowinvcount = $resultinvcount-> fetch_assoc();

            $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
            $resultvat = $conn->query($sqlvat);
            $rowvat = $resultvat->fetch_assoc();

            $vatamount = $rowvat['vat'];


            $sqlcustomerrep="SELECT `tbl_customer_idtbl_customer`, GROUP_CONCAT(DISTINCT `name` SEPARATOR ', ') AS `sales_reps` FROM `tbl_customerwise_salesrep` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee` WHERE `tbl_customer_idtbl_customer` = '$customerID' GROUP BY `tbl_customer_idtbl_customer`";
            $resultcustomerrep =$conn-> query($sqlcustomerrep);
            $rowcustomerrep = $resultcustomerrep-> fetch_assoc();

            $sqlinvoicelist="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`,`tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`discount_price`,tbl_invoice.nettotal, DATEDIFF(CURDATE(), tbl_invoice.date) AS days_since_invoice FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`";
            $resultinvoicelist =$conn-> query($sqlinvoicelist);

            if($rowinvcount['count']>0){
        ?>
        <tr>
            <td><?php echo $rowcustomer['name']; ?></td>
            <td colspan="8"><?php echo $rowcustomerrep['sales_reps']; ?></td>
        </tr>
        <?php 
            $netoutstanding=0;
            while($rowinvoicelist = $resultinvoicelist-> fetch_assoc()){  
                $invID=$rowinvoicelist['idtbl_invoice'];

                $refillqty=$rowinvoicelist['refillqty'];
                $refill_price=(($rowinvoicelist['encustomer_refillprice']*($vatamount+100))/100);
                $discount_price=(($rowinvoicelist['discount_price']*($vatamount+100))/100);

                $total_refillprice=$refill_price*$refillqty;
                $total_discountprice=$discount_price*$refillqty;

                if ($discountstatus==1) {//echo 'IN';
                    if($rowinvoicelist['date']<'2024-04-01'){
                        $discount_amount=($total_refillprice-$total_discountprice);
                    }
                    else{
                        $discount_amount=0;
                    }
                }
                else{
                    $discount_amount=0;
                }

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invID'";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc();

                // $result = (($rowinvoicelist['nettotal'] - $rowpayment['payamount']) - $discount_amount);
                // $display_value = ($result < 0) ? 0 : $result;
                
                $balanceamount = ($rowinvoicelist['nettotal'] - ($rowpayment['payamount']+$discount_amount));
                $balanceamount = ($balanceamount < 0) ? 0 : $balanceamount;
                // if($balanceamount<0){$display_value=0;}
                // else{$display_value=$balanceamount;}

                if($balanceamount>0){
                    $netoutstanding+=$balanceamount;
        ?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $rowinvoicelist['date'] ?></td>
            <td>
            <button type="button" class="btn btn-link btn-sm viewbtn m-0 p-0" id="<?php echo $rowinvoicelist['idtbl_invoice'] ?>">
            <?php 
                if ($rowinvoicelist['tax_invoice_num'] == null) {
                    echo 'INV-'.$rowinvoicelist['idtbl_invoice'];
                } else{
                    echo 'AGT'.$rowinvoicelist['tax_invoice_num'];
                }
            ?>
            </button>
            </td>
            <td class="text-center"><?php echo $rowinvoicelist['days_since_invoice'] ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicelist['nettotal'], 2); ?></td>
            <td class="text-right"><?php echo number_format($discount_amount, 2); ?></td>
            <td class="text-right"><?php echo number_format($rowpayment['payamount'], 2); ?></td>
            <td class="text-right"><?php echo number_format($balanceamount, 2); ?></td>
        </tr>
        <?php }}}} ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="8">Net Outstanding</th>
            <th class="text-right"><?php echo number_format($netoutstanding, 2) ?></th>
        </tr>
    </tfoot>
</table>
<?php } else{ ?>
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th>Customer</th>
            <th>Executive</th>
            <th class="text-right">Invoice Total</th>
            <th class="text-right">Discount Amount</th>
            <th class="text-right">Invoice Payment</th>
            <th class="text-right">Balance</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $netoutstandingall=0;
        while($rowcustomer = $resultcustomer-> fetch_assoc()){ 
            $customerID=$rowcustomer['idtbl_customer'];
            $discountstatus=$rowcustomer['discount_status'];

            $discount_amount=0;

            $sqlcustomerrep="SELECT `tbl_customer_idtbl_customer`, GROUP_CONCAT(DISTINCT `name` SEPARATOR ', ') AS `sales_reps` FROM `tbl_customerwise_salesrep` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee` WHERE `tbl_customer_idtbl_customer` = '$customerID' GROUP BY `tbl_customer_idtbl_customer`";
            $resultcustomerrep =$conn-> query($sqlcustomerrep);
            $rowcustomerrep = $resultcustomerrep-> fetch_assoc();

            $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
            $resultvat = $conn->query($sqlvat);
            $rowvat = $resultvat->fetch_assoc();

            $vatamount = $rowvat['vat'];


            if($validfrom=='' && $validto==''){
                $sqlinvoicelist="SELECT * FROM (SELECT SUM(`nettotal`) AS `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID') AS `dmain` LEFT JOIN (SELECT IFNULL((SUM(`tbl_invoice_detail`.`refillqty`*(`tbl_invoice_detail`.`encustomer_refillprice`*($vatamount+100))/100)-SUM(`tbl_invoice_detail`.`refillqty`*(`tbl_invoice_detail`.`discount_price`*($vatamount+100))/100)), 0) AS `discounttotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice_detail`.`tbl_product_idtbl_product`=1 AND `tbl_invoice`.`date` < '2024-04-01') AS `dsub` ON `dsub`.`tbl_customer_idtbl_customer`=`dmain`.`tbl_customer_idtbl_customer`";
                // $sqlinvoicelist="SELECT * FROM (SELECT SUM(`nettotal`) AS `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID') AS `dmain` LEFT JOIN (SELECT SUM(`tbl_invoice_detail`.`discount_price`) AS `discounttotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice_detail`.`tbl_product_idtbl_product`=1  AND `tbl_invoice_detail`.`status`=1) AS `dsub` ON `dsub`.`tbl_customer_idtbl_customer`=`dmain`.`tbl_customer_idtbl_customer`";
                $resultinvoicelist =$conn-> query($sqlinvoicelist);
                $rowinvoicelist = $resultinvoicelist-> fetch_assoc();

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`paymentcomplete`=0";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc();
            }
            else{
                $sqlinvoicelist="SELECT * FROM (SELECT SUM(`nettotal`) AS `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto') AS `dmain` LEFT JOIN (SELECT IFNULL((SUM(`tbl_invoice_detail`.`refillqty`*(`tbl_invoice_detail`.`encustomer_refillprice`*($vatamount+100))/100)-SUM(`tbl_invoice_detail`.`refillqty`*(`tbl_invoice_detail`.`discount_price`*($vatamount+100))/100)), 0) AS `discounttotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice_detail`.`tbl_product_idtbl_product`=1 AND `tbl_invoice`.`date` < '2024-04-01') AS `dsub` ON `dsub`.`tbl_customer_idtbl_customer`=`dmain`.`tbl_customer_idtbl_customer`";
                // $sqlinvoicelist="SELECT * FROM (SELECT SUM(`nettotal`) AS `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto') AS `dmain` LEFT JOIN (SELECT SUM(`tbl_invoice_detail`.`discount_price`) AS `discounttotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice_detail`.`tbl_product_idtbl_product`=1  AND `tbl_invoice_detail`.`status`=1) AS `dsub` ON `dsub`.`tbl_customer_idtbl_customer`=`dmain`.`tbl_customer_idtbl_customer`";
                $resultinvoicelist =$conn-> query($sqlinvoicelist);
                $rowinvoicelist = $resultinvoicelist-> fetch_assoc();

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`paymentcomplete`=0";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc();
            }

            $discount_amount=$rowinvoicelist['discounttotal'];

            if($rowinvoicelist['nettotal']>0){
        ?>
        <?php
            $nettotal = $rowinvoicelist['nettotal'];
            $payamount = $rowpayment['payamount'];
            $discount_amount = ($discountstatus == 1) ? $discount_amount : 0;
            $balanceamount = $nettotal - ($payamount+$discount_amount);

            if ($balanceamount>0) {
                $netoutstandingall+=$balanceamount;
            ?>
            <tr>
                <td>
                    <button type="button" class="btn btn-link btn-sm customerviewbtn m- p-0" id="<?php echo $customerID ?>">
                    <?php echo $rowcustomer['name']; ?>
                    </button>
                </td>
                <td class=""><?php echo $rowcustomerrep['sales_reps']; ?></td>
                <td class="text-right"><?php echo number_format($nettotal, 2); ?></td>
                <td class="text-right"><?php echo number_format($discount_amount, 2); ?></td>
                <td class="text-right"><?php echo number_format($payamount, 2); ?></td>
                <td class="text-right"><?php echo number_format($balanceamount, 2); ?></td>
            </tr>
            <?php }}} ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5">Net Outstanding</th>
            <th class="text-right"><?php echo number_format($netoutstandingall, 2) ?></th>
        </tr>
    </tfoot>
</table>
<?php } ?>

<script>
$(document).ready(function() {
    $('#tableoutstanding tbody').on('click', '.viewbtn', function() {
        var invoiceID = $(this).attr('id');

        $('#viewinvoicedetails').html('<div class="text-center"><img src="images/spinner.gif"></div>');
        $('#modalinvoice').modal('show');

        $.ajax({
            type: "POST",
            data: {
                recordID : invoiceID
            },
            url: 'getprocess/getinvoiceprint.php',
            success: function(result) { //alert(result);
                $('#viewinvoicedetails').html(result);
            }
        });
    });
    $('#tableoutstanding tbody').on('click', '.customerviewbtn', function() {
        var customerId = $(this).attr('id');

        $('#viewinvoicedetails').html('<div class="text-center"><img src="images/spinner.gif"></div>');
        $('#modalinvoice').modal('show');

        $.ajax({
            type: "POST",
            data: {
                customerId : customerId
            },
            url: 'getprocess/getissueinvoiceinfotocustomer.php',
            success: function(result) { //alert(result);
                $('#viewinvoicedetails').html(result);
            }
        });
    });
});
</script>
