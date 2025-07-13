<?php
session_start();
require_once('../connection/db.php');

$recordID=$_POST['recordID'];

$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`taxamount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`type`, `tbl_customer`.`vat_status`, `tbl_customer`.`vat_num`, `tbl_customer`.`name`, `tbl_customer`.`tax_cus_name`, `tbl_customer`.`tax_cus_address`, `tbl_customer`.`tax_num`, `tbl_customer`.`address`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`idtbl_invoice`='$recordID'";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
$rowinvoiceinfo = $resultinvoiceinfo-> fetch_assoc();

$vatStatus = $rowinvoiceinfo['vat_status'];
$cusType = $rowinvoiceinfo['type'];

$sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`emptyqty`, `tbl_invoice_detail`.`trustqty`, `tbl_invoice_detail`.`trustreturnqty`, `tbl_invoice_detail`.`newprice`, `tbl_invoice_detail`.`refillprice`, `tbl_invoice_detail`.`emptyprice`, `tbl_invoice_detail`.`encustomer_newprice`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`encustomer_emptyprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID' AND `tbl_invoice_detail`.`status`=1";
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
                        Tel: 0312 235 050 | Email: info@ansengas.lk<br>
                        <?php
                            if ($vatStatus == 1) {
                                echo '<div class="">VAT Registration Nmber: 102575474-7000</div>';
                            } else {
                            }
                        ?>
                        <span class="font-weight-bold">Distributor for LAUGFS Gas PLC.</span>
                    </td>
                    <td>&nbsp;</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
        <div class="row mt-3">
            <div class="col-12">Invoice: <?php 
                if ($rowinvoiceinfo['tax_invoice_num'] == null) {
                    echo 'INV-'.$rowinvoiceinfo['idtbl_invoice'];
                } else{
                    echo 'AGT'.$rowinvoiceinfo['tax_invoice_num'];
                }
            ?></div>
            <?php
                if ($vatStatus == 1) {
                    echo '<div class="col-12">Customer Name: ' . $rowinvoiceinfo['tax_cus_name'] . '</div>
                    ';
                } else {
                    echo '<div class="col-12">Customer Name: ' . $rowinvoiceinfo['name'] . '</div>';
                }
            ?>
            <?php
                if ($vatStatus == 1) {
                    echo '<div class="col-12">Address: ' . $rowinvoiceinfo['tax_cus_address'] . '</div>
                    ';
                } else {
                    echo '<div class="col-12">Address: ' . $rowinvoiceinfo['address'] . '</div>';
                }
            ?>
            <?php
                if ($vatStatus == 1) {
                    echo '<div class="col-12">Tax No: ' . $rowinvoiceinfo['tax_num'] . '</div>
                    ';
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
        <?php echo ($vatStatus == 1) ? '<h3 class="text-center">TAX INVOICE</h3>' : ''; ?>
        <table
            class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint text-center">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>New</th>
                    <th>Refill</th>
                    <th>Empty</th>
                    <th>Trust</th>
                    <th>Trust Return</th>
                    <th class="text-right">Sale Price</th>
                    <th class="text-right">Refill Price</th>
                    <th class="text-right">Empty Price</th>
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
                        <td class="text-right"><?php echo number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_newprice'] + $vatNew : ($rowinvoicedetail['newprice'] + $vatNew), 2); ?></td>
                        <td class="text-right"><?php echo number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_refillprice'] + $vatRefill : ($rowinvoicedetail['refillprice'] + $vatRefill), 2); ?></td>
                        <td class="text-right"><?php echo number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty : ($rowinvoicedetail['emptyprice'] + $vatEmpty), 2); ?></td>
                        <td class="text-right"><?php echo ($cusType == 1) ? $totalWithVATencus : $totalWithVAT; ?></td>
                    </tr>

                <?php } ?>

            </tbody>
            <tfoot>
            <?php
                if ($vatStatus == 1) {
                    echo '
                        <tr>
                            <th colspan="9" class="text-left">Total</th>
                            <th class="text-right">' . number_format($rowinvoiceinfo['total'], 2) . '</th>
                        </tr>
                        <tr>
                            <th colspan="9" class="text-left">VAT</th>
                            <th class="text-right">' . number_format($rowinvoiceinfo['taxamount'], 2) . '</th>
                        </tr>';
                } else {
                }
                ?>
                <tr>
                    <th colspan="9" class="text-left">Net Total</th>
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
                            // Check if custype is equal to 1
                            if ($cusType == 1) {
                                // If custype is 1, use specific prices for calculation
                                $vatNew = $rowinvoicedetail['encustomer_newprice'] * $rowvat['vat'] / 100;
                                $vatRefill = $rowinvoicedetail['encustomer_refillprice'] * $rowvat['vat'] / 100;
                                $vatEmpty = $rowinvoicedetail['encustomer_emptyprice'] * $rowvat['vat'] / 100;
                            } else {
                                // If custype is not 1, use default prices for calculation
                                $vatNew = $rowvat['vat'] * $rowinvoicedetail['newprice'] / 100;
                                $vatRefill = $rowvat['vat'] * $rowinvoicedetail['refillprice'] / 100;
                                $vatEmpty = $rowvat['vat'] * $rowinvoicedetail['emptyprice'] / 100;
                            }
                        
                            // Calculate total prices including VAT
                            $totalWithVAT = number_format(
                                ($rowinvoicedetail['newqty'] * ($rowinvoicedetail['newprice'] + $vatNew))
                                + ($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['refillprice'] + $vatRefill))
                                + ($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['emptyprice'] + $vatEmpty)),
                                2
                            );
                        
                            echo '
                            <tr>
                            <td>' . $rowinvoicedetail['product_name'] . '</td>
                            <td class="text-right">' . number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_newprice'] : $rowinvoicedetail['newprice']) . '</td>
                            <td class="text-right">' . number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_refillprice'] :$rowinvoicedetail['refillprice']) . '</td>
                            <td class="text-right">' . number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_emptyprice'] :$rowinvoicedetail['emptyprice']) . '</td>
                            <td class="text-right">' . $rowvat['vat'] . '%</td>
                            <td class="text-right">' . number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_newprice'] + $vatNew : ($rowinvoicedetail['newprice'] + $vatNew), 2) . '</td>
                            <td class="text-right">' . number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_refillprice'] + $vatRefill : ($rowinvoicedetail['refillprice'] + $vatRefill), 2) . '</td>
                            <td class="text-right">' . number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty : ($rowinvoicedetail['emptyprice'] + $vatEmpty), 2) . '</td>
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
';

?>
<div class="row mt-4">
    <div class="col-4 text-center">...........................................................<br>Sig. of Driver</div>
    <div class="col-4 text-center">...........................................................<br>Company Seal</div>
    <div class="col-4 text-center">...........................................................<br>Sig. of Customer</div>
</div>
<div class="row mt-5">
    <div class="col-3 text-center"><?php if ($method == 1) : ?>
        <img src="images/seal/paid.png" width="150" height="150" class="img-fluid">
    <?php elseif ($method == 2) : ?>
        <img src="images/seal/received.png" width="150" height="150" class="img-fluid">
    <?php elseif ($method == 3) : ?>
        <img src="images/seal/credit.png" width="150" height="150" class="img-fluid">
    <?php endif; ?></div>
    <div class="col-5 text-right">Name :</div>
    <div class="col-3">.................................................................................</div>
</div>
<div class="row">
    <div class="col-8 text-right">ID No :</div>
    <div class="col-3">.................................................................................</div>
</div>