<?php
session_start();
require_once('../connection/db.php');

$recordID=$_POST['recordID'];

$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`taxamount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`vat_status`, `tbl_customer`.`vat_num`, `tbl_customer`.`name`, `tbl_customer`.`address`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`='$recordID'";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
$rowinvoiceinfo = $resultinvoiceinfo-> fetch_assoc();

$vatStatus = $rowinvoiceinfo['vat_status'];

$sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`emptyqty`, `tbl_invoice_detail`.`newprice`, `tbl_invoice_detail`.`refillprice`, `tbl_invoice_detail`.`emptyprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID' AND `tbl_invoice_detail`.`status`=1";
$resultinvoicedetail=$conn->query($sqlinvoicedetail);

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);

$method = null; 
$chequeNo = null;
$bankName = null;

$sqlpayment = "SELECT `tbl_invoice_payment_detail`.`method`,`tbl_invoice_payment_detail`.`chequeno`,`tbl_bank`.`bankname` FROM `tbl_invoice_payment_detail` LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank` = `tbl_invoice_payment_detail`.`tbl_bank_idtbl_bank` LEFT JOIN `tbl_invoice_payment` ON `tbl_invoice_payment`.`idtbl_invoice_payment` = `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment`.`idtbl_invoice_payment` WHERE `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` = $recordID";

$resultpayment = $conn->query($sqlpayment);

if ($resultpayment->num_rows > 0) {
    $rowpaymentinfo = $resultpayment->fetch_assoc();

    $method = $rowpaymentinfo['method'];

    if ($method == 1) {
        $paymentType = "Cash";
    } elseif ($method == 2) {
        $paymentType = "Credit";
    } elseif ($method == 3) {
        $paymentType = "Cheque";
    } else {
        $paymentType = "Unknown";
    }

    $chequeNo = $rowpaymentinfo['chequeno'];
    $bankName = $rowpaymentinfo['bankname'];
}

$vatValue = null;
if ($resultvat) {
    $rowvat = $resultvat->fetch_assoc();

    if ($rowvat) {
        $vatValue = $rowvat['vat'];
    }
}

?>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td>&nbsp;</td>
                    <td class="text-right"><img src="images/logoprint.png" width="80" height="80" class="img-fluid">
                    </td>
                    <td colspan="4" class="text-center small align-middle">
                    <h2 class="font-weight-light m-0">ANSEN GAS DISTRIBUTORS (PVT) LTD</h2>
                    65, Arcbishop, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka<br>
                        Tel: 0312 235 050 | Fax: 00**-**-******* info@ansengas.lk<br>
                        <span class="font-weight-bold">Distributor for LAUGFS Gas PLC.</span>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
        <div class="row mt-3">
            <div class="col-12">Invoice: INV-<?php echo $rowinvoiceinfo['idtbl_invoice'] ?></div>
            <div class="col-12">Customer Name: <?php echo $rowinvoiceinfo['name'] ?></div>
            <div class="col-12">Address: <?php echo $rowinvoiceinfo['address'] ?></div>
            <?php
                if ($vatStatus == 1) {
                    echo '<div class="col-12">Tax No: ' . $rowinvoiceinfo['vat_num'] . '</div>';
                } else {
                }
            ?>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <?php
        // Fetch all rows into an array
        $invoiceDetails = [];
        while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
            $invoiceDetails[] = $rowinvoicedetail;
        }

        // Display the first table
        ?>
        <table
            class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint text-center">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>New</th>
                    <th>Refill</th>
                    <th>Empty</th>
                    <th class="text-right">Sale Price</th>
                    <th class="text-right">Refill Price</th>
                    <th class="text-right">Empty Price</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
        // Iterate over the array to display the first table
        foreach ($invoiceDetails as $rowinvoicedetail) {
            // Calculate VAT amounts for each product
            $vatNew = $rowvat['vat'] * $rowinvoicedetail['newprice'] / 100;
            $vatRefill = $rowvat['vat'] * $rowinvoicedetail['refillprice'] / 100;
            $vatEmpty = $rowvat['vat'] * $rowinvoicedetail['emptyprice'] / 100;

            // Calculate total prices including VAT
            $totalWithVAT = number_format(
                ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['newprice'] + $vatNew))
                + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['emptyprice'] + $vatEmpty)),
                2
            );
        ?>
                <tr>
                    <td><?php echo $rowinvoicedetail['product_name']; ?></td>
                    <td class="text-center"><?php echo $rowinvoicedetail['newqty']; ?></td>
                    <td class="text-center"><?php echo $rowinvoicedetail['refillqty']; ?></td>
                    <td class="text-center"><?php echo $rowinvoicedetail['emptyqty']; ?></td>
                    <td class="text-right"><?php echo number_format($rowinvoicedetail['newprice'] + $vatNew, 2); ?></td>
                    <td class="text-right">
                        <?php echo number_format($rowinvoicedetail['refillprice'] + $vatRefill, 2); ?></td>
                    <td class="text-right"><?php echo number_format($rowinvoicedetail['emptyprice'] + $vatEmpty, 2); ?>
                    </td>
                    <td class="text-right"><?php echo $totalWithVAT; ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
            <?php
                if ($vatStatus == 1) {
                    echo '
                        <tr>
                            <th colspan="7" class="text-left">Total</th>
                            <th class="text-right">' . number_format($rowinvoiceinfo['total'], 2) . '</th>
                        </tr>
                        <tr>
                            <th colspan="7" class="text-left">VAT</th>
                            <th class="text-right">' . number_format($rowinvoiceinfo['taxamount'], 2) . '</th>
                        </tr>';
                } else {
                }
                ?>
                <tr>
                    <th colspan="7" class="text-left">Net Total</th>
                    <th class="text-right"><?php echo number_format($rowinvoiceinfo['nettotal'], 2) ?></th>
                </tr>
            </tfoot>
        </table>

        <?php
            if ($vatStatus == 1) {
                echo '
                    <table class="table table-striped table-bordered table-black bg-transparent table-sm w-100 text-center">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>New Price</th>
                                <th>Refill Price</th>
                                <th>Empty Price</th>
                                <th>VAT</th>
                                <th>Total(New)</th>
                                <th>Total(Refill)</th>
                                <th>Total(Empty)</th>
                            </tr>
                        </thead>
                        <tbody>';

                foreach ($invoiceDetails as $rowinvoicedetail) {
                    $vatNew = $rowvat['vat'] * $rowinvoicedetail['newprice'] / 100;
                    $vatRefill = $rowvat['vat'] * $rowinvoicedetail['refillprice'] / 100;
                    $vatEmpty = $rowvat['vat'] * $rowinvoicedetail['emptyprice'] / 100;

                    $totalWithVAT = number_format(
                        ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['newprice'] + $vatNew))
                        + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                        + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['emptyprice'] + $vatEmpty)),
                        2
                    );

                    echo '
                            <tr>
                                <td>' . $rowinvoicedetail['product_name'] . '</td>
                                <td class="text-right">' . number_format($rowinvoicedetail['newprice']) . '</td>
                                <td class="text-right">' . number_format($rowinvoicedetail['refillprice']) . '</td>
                                <td class="text-right">' . number_format($rowinvoicedetail['emptyprice']) . '</td>
                                <td class="text-right">' . $rowvat['vat'] . '%</td>
                                <td class="text-right">' . number_format($rowinvoicedetail['newprice'] + $vatNew, 2) . '</td>
                                <td class="text-right">' . number_format($rowinvoicedetail['refillprice'] + $vatRefill, 2) . '</td>
                                <td class="text-right">' . number_format($rowinvoicedetail['emptyprice'] + $vatEmpty, 2) . '</td>
                            </tr>';
                }

                echo '
                        </tbody>
                    </table>';
            } else {
            }
        ?>

    </div>
</div>
<?php

echo '
<div class="row mt-3">
    <div class="col-12">
        <h6>Payment Mode</h6>
        <table class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint border-0">
            <thead>
                <tr>
                    <th>Cash</th>
                    <th>'.($method == 1 ? '✔' : '').'</th>
                    <th>Credit</th>
                    <th>'.($method == 3 ? '✔' : '').'</th>
                    <th>Cheque</th>
                    <th>'.($method == 2 ? '✔' : '').'</th>
                </tr>
                <tr>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th>No</th>
                    <th>'.$chequeNo.'</th>
                </tr>
                <tr>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th class="border-0">&nbsp;</th>
                    <th>Bank</th>
                    <th>'.$bankName.'</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="row mt-4">
    <div class="col-4 text-center">...........................................................<br>Sig. of Driver</div>
    <div class="col-4 text-center">...........................................................<br>Company Seal</div>
    <div class="col-4 text-center">...........................................................<br>Sig. of Customer</div>
</div>
<div class="row mt-4">
    <div class="col-8 text-right">Name :</div>
    <div class="col-4">..................................................................</div>
</div>
<div class="row">
    <div class="col-8 text-right">ID No :</div>
    <div class="col-4">..................................................................</div>
</div>';

?>