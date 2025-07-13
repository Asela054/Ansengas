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
    $sqlcustomer="SELECT `idtbl_customer`, `name`, `discount_status` FROM `tbl_customer` WHERE `status`=1";
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
            <th>&nbsp;</th>
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

            $sqlcustomerrep="SELECT `tbl_customer_idtbl_customer`, GROUP_CONCAT(DISTINCT `name` SEPARATOR ', ') AS `sales_reps` FROM `tbl_customerwise_salesrep` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee` WHERE `tbl_customer_idtbl_customer` = '$customerID' GROUP BY `tbl_customer_idtbl_customer`";
            $resultcustomerrep =$conn-> query($sqlcustomerrep);
            $rowcustomerrep = $resultcustomerrep-> fetch_assoc();

            $sqlinvoicelist="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`,`tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`discount_price`,tbl_invoice.nettotal, DATEDIFF(CURDATE(), tbl_invoice.date) AS days_since_invoice  FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`
            ";
            $resultinvoicelist =$conn-> query($sqlinvoicelist);

            if($rowinvcount['count']>0){
        ?>
        <tr>
            <td><?php echo $rowcustomer['name']; ?></td>
            <td colspan="8"><?php echo $rowcustomerrep['sales_reps']; ?></td>
        </tr>
        <?php 
            while($rowinvoicelist = $resultinvoicelist-> fetch_assoc()){  
                $invID=$rowinvoicelist['idtbl_invoice'];

                $refillqty=$rowinvoicelist['refillqty'];
                $refill_price=$rowinvoicelist['encustomer_refillprice'];
                $discount_price=$rowinvoicelist['discount_price'];

                $total_refillprice=$refill_price*$refillqty;
                $total_discountprice=$discount_price*$refillqty;


                $discount_amount=$total_refillprice-$total_discountprice;

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` WHERE `tbl_invoice_idtbl_invoice`='$invID'";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc();
        ?>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $rowinvoicelist['date'] ?></td>
            <td>
            <?php 
                if ($rowinvoicelist['tax_invoice_num'] == null) {
                    echo 'INV-'.$rowinvoicelist['idtbl_invoice'];
                } else{
                    echo 'AGT'.$rowinvoicelist['tax_invoice_num'];
                }
            ?>
            </td>
            <td class="text-center"><?php echo $rowinvoicelist['days_since_invoice'] ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicelist['nettotal'], 2); ?></td>         
            <?php if ($discountstatus==1) { ?>
            <td class="text-right"><?php echo number_format($discount_amount, 2); ?></td>
            <?php } else{ ?>  
            <td class="text-right"><?php echo number_format('0', 2); ?></td>
            <?php } ?>   
            <td class="text-right"><?php echo number_format($rowpayment['payamount'], 2); ?></td>
            <td class="text-right"><?php echo number_format(($rowinvoicelist['nettotal']-$rowpayment['payamount']), 2) ?></td>
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
            <th>Executive</th>
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

            $discount_amount=0;

            $sqlcustomerrep="SELECT `tbl_customer_idtbl_customer`, GROUP_CONCAT(DISTINCT `name` SEPARATOR ', ') AS `sales_reps` FROM `tbl_customerwise_salesrep` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee` WHERE `tbl_customer_idtbl_customer` = '$customerID' GROUP BY `tbl_customer_idtbl_customer`";
            $resultcustomerrep =$conn-> query($sqlcustomerrep);
            $rowcustomerrep = $resultcustomerrep-> fetch_assoc();


            if($validfrom=='' && $validto==''){
                $sqlinvoicelist="SELECT * FROM (SELECT SUM(`nettotal`) AS `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID') AS `dmain` LEFT JOIN (SELECT (SUM(`tbl_invoice_detail`.`refillqty`*`tbl_invoice_detail`.`encustomer_refillprice`)-SUM(`tbl_invoice_detail`.`refillqty`*`tbl_invoice_detail`.`discount_price`)) AS `discounttotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice_detail`.`tbl_product_idtbl_product`=1) AS `dsub` ON `dsub`.`tbl_customer_idtbl_customer`=`dmain`.`tbl_customer_idtbl_customer`";
                $resultinvoicelist =$conn-> query($sqlinvoicelist);
                $rowinvoicelist = $resultinvoicelist-> fetch_assoc();

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`paymentcomplete`=0";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc();
            }
            else{
                $sqlinvoicelist="SELECT * FROM (SELECT SUM(`nettotal`) AS `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto') AS `dmain` LEFT JOIN (SELECT (SUM(`tbl_invoice_detail`.`refillqty`*`tbl_invoice_detail`.`encustomer_refillprice`)-SUM(`tbl_invoice_detail`.`refillqty`*`tbl_invoice_detail`.`discount_price`)) AS `discounttotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice_detail`.`tbl_product_idtbl_product`=1) AS `dsub` ON `dsub`.`tbl_customer_idtbl_customer`=`dmain`.`tbl_customer_idtbl_customer`";
                $resultinvoicelist =$conn-> query($sqlinvoicelist);
                $rowinvoicelist = $resultinvoicelist-> fetch_assoc();

                $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_invoice`.`paymentcomplete`=0";
                $resultpayment =$conn-> query($sqlpayment);
                $rowpayment = $resultpayment-> fetch_assoc();
            }

            $discount_amount=$rowinvoicelist['discounttotal'];

            if($rowinvoicelist['nettotal']>0){
        ?>
        <tr>
            <td><?php echo $rowcustomer['name']; ?><button class="btn btn-outline-primary btn-sm float-right viewbtn" id="<?php echo $customerID ?>"><i class="far fa-question-circle"></i></button></td>
            <td class=""><?php echo $rowcustomerrep['sales_reps']; ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicelist['nettotal'], 2); ?></td>
            <?php if ($discountstatus==1) { ?>
            <td class="text-right"><?php echo number_format($discount_amount, 2); ?></td>
            <?php } else{ $discount_amount=0; ?>  
            <td class="text-right"><?php echo number_format($discount_amount, 2); ?></td>
            <?php } ?><td class="text-right"><?php echo number_format($rowpayment['payamount'], 2); ?></td>
            <td class="text-right"><?php echo number_format(($rowinvoicelist['nettotal']-($discount_amount+$rowpayment['payamount'])), 2); ?></td>
        </tr>
        <?php }} ?>
    </tbody>
</table>
<?php } ?>

<script>
        $(document).ready(function() {
        $('#tableoutstanding tbody').on('click', '.viewbtn', function() {
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
