<?php 
require_once('../connection/db.php');

$invID=$_POST['invID'];

$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`taxamount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`type`, `tbl_customer`.`vat_status`, `tbl_customer`.`vat_num`, `tbl_customer`.`name`, `tbl_customer`.`address`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`='$invID'";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
$rowinvoiceinfo = $resultinvoiceinfo-> fetch_assoc();

$vatStatus = $rowinvoiceinfo['vat_status'];
$cusType = $rowinvoiceinfo['type'];

$sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`emptyqty`, `tbl_invoice_detail`.`trustqty`, `tbl_invoice_detail`.`trustreturnqty`, `tbl_invoice_detail`.`newprice`, `tbl_invoice_detail`.`refillprice`, `tbl_invoice_detail`.`emptyprice`, `tbl_invoice_detail`.`encustomer_newprice`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`encustomer_emptyprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$invID' AND `tbl_invoice_detail`.`status`=1";
$resultinvoicedetail=$conn->query($sqlinvoicedetail);

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);

$sqlinvoice="SELECT `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `idtbl_invoice`='$invID' AND `status`=1";
$resultinvoice=$conn->query($sqlinvoice);
$rowinvoice=$resultinvoice->fetch_assoc();

$cusID=$rowinvoice['tbl_customer_idtbl_customer'];

$sqlcustomer="SELECT `type`, `name`, `nic`, `phone`, `email`, `address` FROM `tbl_customer` WHERE `idtbl_customer`='$cusID' AND `status`=1";
$resultcustomer=$conn->query($sqlcustomer);
$rowcustomer=$resultcustomer->fetch_assoc();

$vatValue = null;
if ($resultvat) {
    $rowvat = $resultvat->fetch_assoc();

    if ($rowvat) {
        $vatValue = $rowvat['vat'];
    }
}

?>
<div class="row">
    <div class="col">
        <?php echo $rowcustomer['name'].'<br>'.$rowcustomer['nic'].'<br>'.$rowcustomer['phone'].'<br>'.$rowcustomer['email'].'<br>'.$rowcustomer['address'] ?>
    </div>
</div>
<?php
        $invoiceDetails = [];
        while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
            $invoiceDetails[] = $rowinvoicedetail;
        }

        // Display the first table
        ?>
        <?php echo ($vatStatus == 1) ? '<h3 class="text-center">VAT INVOICE</h3>' : ''; ?>
        <table class="table table-striped table-bordered table-sm" id="grnlisttable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>New</th>
                    <th>Refill</th>
                    <th>Empty</th>
                    <th>Trust</th>
                    <th>Trust Return</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ($invoiceDetails as $rowinvoicedetail) {
                    // Check if cusType is equal to 1
                    if ($cusType == 1) {
                        $vatNew = $rowinvoicedetail['encustomer_newprice'] * $rowvat['vat'] / 100;
                        $vatRefill = $rowinvoicedetail['encustomer_refillprice'] * $rowvat['vat'] / 100;
                        $vatEmpty = $rowinvoicedetail['encustomer_emptyprice'] * $rowvat['vat'] / 100;
                    } else {
                        $vatNew = $rowvat['vat'] * $rowinvoicedetail['newprice'] / 100;
                        $vatRefill = $rowvat['vat'] * $rowinvoicedetail['refillprice'] / 100;
                        $vatEmpty = $rowvat['vat'] * $rowinvoicedetail['emptyprice'] / 100;
                    }

                    // Calculate total prices including VAT
                    $totalWithVAT = number_format(
                        ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['newprice'] + $vatNew))
                        + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                        + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['emptyprice'] + $vatEmpty))
                        + ($rowinvoicedetail['trustqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                        + ($rowinvoicedetail['trustreturnqty'] * 0),
                        2
                    );

                    $totalWithVATencus = number_format(
                        ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['encustomer_newprice'] + $vatNew))
                        + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['encustomer_refillprice'] + $vatRefill))
                        + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty))
                        + ($rowinvoicedetail['trustqty'] * ($rowinvoicedetail['encustomer_refillprice'] + $vatRefill))
                        + ($rowinvoicedetail['trustreturnqty'] * 0),
                        2
                    );
                    ?>

                    <tr>
                        <td><?php echo $rowinvoicedetail['product_name']; ?></td>
                        <td class="text-center"><?php echo $rowinvoicedetail['newqty']; ?></td>
                        <td class="text-center"><?php echo $rowinvoicedetail['refillqty']; ?></td>
                        <td class="text-center"><?php echo $rowinvoicedetail['emptyqty']; ?></td>
                        <td class="text-center"><?php echo $rowinvoicedetail['trustqty']; ?></td>
                        <td class="text-center"><?php echo $rowinvoicedetail['trustreturnqty']; ?></td>
                        <td class="text-right"><?php echo ($cusType == 1) ? $totalWithVATencus : $totalWithVAT; ?></td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>
<div class="row">
    <div class="col text-right">
        <h4 class="font-weight-normal"><?php echo 'Rs '.number_format($rowinvoice['nettotal'], 2) ?></h4>
    </div>
</div>