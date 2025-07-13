<?php 
require_once('../connection/db.php');

$customerId=$_POST['customerId'];

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

$vatamount = $rowvat['vat'];

$sqlcustomer="SELECT `idtbl_customer`, `name`, `discount_status`, `creditperiod` FROM `tbl_customer` WHERE `status`=1 AND `idtbl_customer`='$customerId'";
$resultcustomer =$conn-> query($sqlcustomer);
$rowcustomer = $resultcustomer-> fetch_assoc();

// $sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`,DATEDIFF(CURDATE(), tbl_invoice.date) AS days_since_invoice, `tbl_customer`.`discount_status` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerId' AND `tbl_invoice`.`paymentcomplete`=0";
$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`,`tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`discount_price`,tbl_invoice.nettotal, DATEDIFF(CURDATE(), tbl_invoice.date) AS days_since_invoice, `tbl_invoice`.`remarks`  FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerId' GROUP BY `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
?>
<h6 class="title-style"><span>Customer Invoice Summery</span></h6>
<table class="table table-striped table-bordered table-sm" id="tblinvoice">
    <thead>
        <tr>
            <th>Invoice No</th>
            <th>Book No</th>
            <th>Date</th>
            <th class="text-center">No of Days Since Invoice</th>
            <th class="text-right">Invoice Total</th>
            <th class="text-right">Discount</th>
            <th class="text-right">Invoice Payment</th>
            <th class="text-right">Balance</th>
            <th class="">Remark</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $netoutstanding=0;
        while ($rowinvoicedetail = $resultinvoiceinfo->fetch_assoc()) { 
            $discountstatus=$rowcustomer['discount_status'];
            $invID=$rowinvoicedetail['idtbl_invoice'];
            $refillqty=$rowinvoicedetail['refillqty'];

            $refill_price=(($rowinvoicedetail['encustomer_refillprice']*($vatamount+100))/100);
            $discount_price=(($rowinvoicedetail['discount_price']*($vatamount+100))/100);

            $total_refillprice=$refill_price*$refillqty;
            $total_discountprice=$discount_price*$refillqty;

            if ($discountstatus==1) {
                if($rowinvoicedetail['date']<'2024-04-01'){
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

            // $result = (($rowinvoicedetail['nettotal'] - $rowpayment['payamount']) - $discount_amount);
            // $display_value = ($result < 0) ? 0 : $result;
            
            $balanceamount = ($rowinvoicedetail['nettotal'] - ($rowpayment['payamount']+$discount_amount));
            $balanceamount = ($balanceamount < 0) ? 0 : $balanceamount;
            // if($balanceamount<0){$display_value=0;}
            // else{$display_value=$balanceamount;}

            if($balanceamount>0){
                $netoutstanding+=$balanceamount;
        ?>
        <tr class="<?php if($rowinvoicedetail['days_since_invoice']>=$rowcustomer['creditperiod']){echo 'table-danger';} ?>">
            <td>
            <?php 
                if ($rowinvoicedetail['tax_invoice_num'] == null) {
                    echo 'INV-'.$rowinvoicedetail['idtbl_invoice'];
                } else{
                    echo 'AGT'.$rowinvoicedetail['tax_invoice_num'];
                }
            ?>
            </td>
            <td class=""><?php echo $rowinvoicedetail['non_tax_invoice_num']; ?></td>
            <td class=""><?php echo $rowinvoicedetail['date']; ?></td>
            <td class="text-center"><?php echo $rowinvoicedetail['days_since_invoice'] ?></td>
            <!-- <td class="text-right"><?php echo number_format($rowinvoicedetail['nettotal'], 2); ?></td>
            <td class="text-right"><?php echo number_format($rowinvoicedetail['payamount'], 2); ?></td>
            <td class="text-right"><?php echo number_format(($rowinvoicedetail['nettotal']-$rowinvoicedetail['payamount']), 2); ?></td> -->
            <td class="text-right"><?php echo number_format($rowinvoicedetail['nettotal'], 2); ?></td>
            <td class="text-right"><?php echo number_format($discount_amount, 2); ?></td>
            <td class="text-right"><?php echo number_format($rowpayment['payamount'], 2); ?></td>
            <td class="text-right"><?php echo number_format($balanceamount, 2); ?></td>
            <td class=""><?php echo $rowinvoicedetail['remarks'] ?></td>

        </tr>
        <?php }} ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="7">Net Outstanding</th>
            <th class="text-right"><?php echo number_format($netoutstanding, 2) ?></th>
            <th>&nbsp;</th>
        </tr>
    </tfoot>
</table>