<?php
session_start();
require_once('../connection/db.php');

$recordID=$_POST['recordID'];

function ConvertRupeeToText($amount) {
    $ones = array(
        0 => '',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen'
    );

    $tens = array(
        2 => 'twenty',
        3 => 'thirty',
        4 => 'forty',
        5 => 'fifty',
        6 => 'sixty',
        7 => 'seventy',
        8 => 'eighty',
        9 => 'ninety'
    );

    $amount = str_replace(',', '', $amount);
    $rupees = intval($amount);
    $cents = intval(round(($amount - $rupees) * 100));

    $words = '';

    $numberToWords = function($num) use (&$numberToWords, $ones, $tens) {
        $str = '';

        if ($num >= 1000000000) {
            $str .= $numberToWords(intval($num / 1000000000)) . ' billion ';
            $num %= 1000000000;
        }

        if ($num >= 1000000) {
            $str .= $numberToWords(intval($num / 1000000)) . ' million ';
            $num %= 1000000;
        }

        if ($num >= 1000) {
            $str .= $numberToWords(intval($num / 1000)) . ' thousand ';
            $num %= 1000;
        }

        if ($num >= 100) {
            $str .= $ones[intval($num / 100)] . ' hundred ';
            $num %= 100;
        }

        if ($num > 0) {
            if ($str !== '') {
                $str .= ' ';
            }

            if ($num < 20) {
                $str .= $ones[$num];
            } else {
                $str .= $tens[intval($num / 10)];
                if ($num % 10 > 0) {
                    $str .= '-' . $ones[$num % 10];
                }
            }
        }

        return trim($str);
    };

    if ($rupees > 0) {
        $words .= $numberToWords($rupees);
    }

    if ($cents > 0) {
        if ($rupees > 0) {
            $words .= ' and ';
        }
        $words .= $numberToWords($cents) . ' cents';
    }

    if ($words === '') {
        $words = 'zero';
    }

    return ucfirst(trim($words));
}  

$sqlinvoiceinfo="SELECT `tbl_invoice`.`idtbl_invoice`,`tbl_invoice`.`invtype`,`tbl_invoice`.`status`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, `tbl_invoice`.`taxamount`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`paymentcomplete`, `tbl_customer`.`type`, `tbl_customer`.`vat_status`, `tbl_customer`.`vat_num`, `tbl_customer`.`name`, `tbl_customer`.`tax_cus_name`, `tbl_customer`.`address`, `tbl_employee`.`name` AS `saleref`, `tbl_area`.`area`, `tbl_customer`.`phone` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` LEFT JOIN `tbl_employee` ON `tbl_employee`.`idtbl_employee`=`tbl_invoice`.`ref_id` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_invoice`.`tbl_area_idtbl_area` WHERE `tbl_invoice`.`idtbl_invoice`='$recordID'";
$resultinvoiceinfo =$conn-> query($sqlinvoiceinfo); 
$rowinvoiceinfo = $resultinvoiceinfo-> fetch_assoc();

$vatStatus = $rowinvoiceinfo['vat_status'];
$invtype = $rowinvoiceinfo['invtype'];
$cusType = $rowinvoiceinfo['type'];
$status = $rowinvoiceinfo['status'];

$sqlinvoicedetail="SELECT `tbl_product`.`product_name`, `tbl_invoice_detail`.`newqty`, `tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`emptyqty`, `tbl_invoice_detail`.`trustqty`, `tbl_invoice_detail`.`trustreturnqty`, `tbl_invoice_detail`.`newprice`, `tbl_invoice_detail`.`refillprice`, `tbl_invoice_detail`.`emptyprice`, `tbl_invoice_detail`.`encustomer_newprice`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`encustomer_emptyprice` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$recordID'";
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

$sqlpaymethod = "SELECT 
    GROUP_CONCAT(
        CASE `tbl_invoice_payment_detail`.`method`
            WHEN 1 THEN 'Cash'
            WHEN 2 THEN 'Bank / Cheque'
            WHEN 3 THEN 'Online'
            ELSE 'Unknown'
        END
    ) AS payment_methods
FROM `tbl_invoice_payment_detail` 
LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` 
    ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment` 
WHERE `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` = '$recordID' 
  AND `tbl_invoice_payment_detail`.`status` = 1";
$resultpaymethod = $conn->query($sqlpaymethod);
$rowpaymethod = $resultpaymethod->fetch_assoc();

if ($vatStatus == 0) {
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
                                echo '<div class="">VAT Registration Number: 102575474-7000</div>';
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
    <div class="col-12">
        <div class="row">
            <div class="col-2">Invoice Date</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php echo $rowinvoiceinfo['date']; ?></div>
        </div>
        <div class="row">
            <div class="col-2">Invoice No</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php 
                if ($rowinvoiceinfo['tax_invoice_num'] == null) {
                    echo 'INV-'.$rowinvoiceinfo['idtbl_invoice'];
                } else{
                    echo 'AGT'.$rowinvoiceinfo['tax_invoice_num'];
                }
            ?></div>
        </div>
        <div class="row">
            <div class="col-2">Customer</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php if ($vatStatus == 1) {echo $rowinvoiceinfo['tax_cus_name'];}else{echo $rowinvoiceinfo['name'];} ?></div>
        </div>
        <div class="row">
            <div class="col-2">Address</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php echo $rowinvoiceinfo['address']; ?></div>
        </div>
        <?php if ($vatStatus == 1) { ?>
        <div class="row">
            <div class="col-2">Outlet</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php echo $rowinvoiceinfo['name']; ?></div>
        </div>
        <?php } ?>
        <?php if ($vatStatus == 1) { ?>
        <div class="row">
            <div class="col-2">Tax No</div>
            <div class="col-1 text-center">:</div>
            <div class="col-6"><?php echo $rowinvoiceinfo['vat_num']; ?></div>
        </div>
        <?php } ?>
    </div>
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
        <?php 
        if ($invtype == 1) {
            echo '<h3 class="text-center">Free Issue Note</h3>';
        } else {
            echo ($vatStatus == 1) ? '<h3 class="text-center">TAX INVOICE</h3>' : '';
        }
        ?>
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
                        <td class="text-right">
                            <?php 
                                echo ($invtype == 1) 
                                    ? '0.00' 
                                    : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_newprice'] + $vatNew : ($rowinvoicedetail['newprice'] + $vatNew), 2);
                            ?>
                        </td>
                        <td class="text-right">
                            <?php 
                                echo ($invtype == 1) 
                                    ? '0.00' 
                                    : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_refillprice'] + $vatRefill : ($rowinvoicedetail['refillprice'] + $vatRefill), 2);
                            ?>
                        </td>
                        <td class="text-right">
                            <?php 
                                echo ($invtype == 1) 
                                    ? '0.00' 
                                    : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty : ($rowinvoicedetail['emptyprice'] + $vatEmpty), 2);
                            ?>
                        </td>
                        <td class="text-right">
                            <?php 
                                echo ($invtype == 1) 
                                    ? '0.00' 
                                    : (($cusType == 1) ? $totalWithVATencus : $totalWithVAT);
                            ?>
                        </td>
                    </tr>

                <?php } ?>

            </tbody>
            <tfoot>
            <?php
            if ($invtype == 1) {
                echo '
                    <tr>
                        <th colspan="9" class="text-left">Total</th>
                        <th class="text-right">0.00</th>
                    </tr>
                    <tr>
                        <th colspan="9" class="text-left">VAT</th>
                        <th class="text-right">0.00</th>
                    </tr>
                    <tr>
                        <th colspan="9" class="text-left">Net Total</th>
                        <th class="text-right">0.00</th>
                    </tr>
                ';

            } else {
                if ($vatStatus == 1) {
                    echo '
                        <tr>
                            <th colspan="9" class="text-left">Total</th>
                            <th class="text-right">' . number_format($rowinvoiceinfo['total'], 2) . '</th>
                        </tr>
                        <tr>
                            <th colspan="9" class="text-left">VAT ('.$rowvat['vat'].'%)</th>
                            <th class="text-right">' . number_format($rowinvoiceinfo['taxamount'], 2) . '</th>
                        </tr>';
                }

                echo '
                    <tr>
                        <th colspan="9" class="text-left">Net Total</th>
                        <th class="text-right">' . number_format($rowinvoiceinfo['nettotal'], 2) . '</th>
                    </tr>';
            }
            ?>
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

                                <!-- NEW PRICE -->
                                <td class="text-right">' . 
                                    (($invtype == 1) 
                                        ? '0.00' 
                                        : number_format((($cusType == 1) ? $rowinvoicedetail['encustomer_newprice'] : $rowinvoicedetail['newprice']), 2)
                                    ) . 
                                '</td>

                                <!-- REFILL PRICE -->
                                <td class="text-right">' . 
                                    (($invtype == 1) 
                                        ? '0.00' 
                                        : number_format((($cusType == 1) ? $rowinvoicedetail['encustomer_refillprice'] : $rowinvoicedetail['refillprice']), 2)
                                    ) . 
                                '</td>

                                <!-- EMPTY PRICE -->
                                <td class="text-right">' . 
                                    (($invtype == 1) 
                                        ? '0.00' 
                                        : number_format((($cusType == 1) ? $rowinvoicedetail['encustomer_emptyprice'] : $rowinvoicedetail['emptyprice']), 2)
                                    ) . 
                                '</td>

                                <!-- VAT PERCENTAGE (You can keep original) -->
                                <td class="text-right">' . (($invtype == 1) ? '0%' : $rowvat['vat'] . '%') . '</td>

                                <!-- NEW PRICE + VAT -->
                                <td class="text-right">' . 
                                    (($invtype == 1) 
                                        ? '0.00' 
                                        : number_format((($cusType == 1) ? $rowinvoicedetail['encustomer_newprice'] + $vatNew : ($rowinvoicedetail['newprice'] + $vatNew)), 2)
                                    ) . 
                                '</td>

                                <!-- REFILL PRICE + VAT -->
                                <td class="text-right">' . 
                                    (($invtype == 1) 
                                        ? '0.00' 
                                        : number_format((($cusType == 1) ? $rowinvoicedetail['encustomer_refillprice'] + $vatRefill : ($rowinvoicedetail['refillprice'] + $vatRefill)), 2)
                                    ) . 
                                '</td>

                                <!-- EMPTY PRICE + VAT -->
                                <td class="text-right">' . 
                                    (($invtype == 1) 
                                        ? '0.00' 
                                        : number_format((($cusType == 1) ? $rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty : ($rowinvoicedetail['emptyprice'] + $vatEmpty)), 2)
                                    ) . 
                                '</td>
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
    <div class="col-3 text-center">
        <?php if ($method == 1) : ?>
        <img src="images/seal/paid.png" width="150" height="150" class="img-fluid">
        <?php elseif ($method == 2) : ?>
        <img src="images/seal/received.png" width="150" height="150" class="img-fluid">
        <?php elseif ($method == 3) : ?>
        <img src="images/seal/credit.png" width="150" height="150" class="img-fluid">
        <?php elseif ($status == 3) : ?>
        <img src="images/seal/cancel.png" width="150" height="150" class="img-fluid">
        <?php endif; ?>
    </div>
    <div class="col-3 text-right">Name :</div>
    <div class="col-6">.................................................................................</div>
</div>
<div class="row">
    <div class="col-6 text-right">ID No :</div>
    <div class="col-6">.................................................................................</div>
</div>
<?php 
} else { 
    $invoiceDetails = [];
    while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
        $invoiceDetails[] = $rowinvoicedetail;
    }
?>
<div class="row justify-content-center">
    <div class="col-2 border border-dark text-center p-2 font-weight-bold">
        <h3 class="mb-1">Tax Invoice</h3>
    </div>
</div>
<div class="row row-cols-1 row-cols-md-2 mt-3">
    <div class="col">
        <div class="card rounded-0 border-dark shadow-none">
            <div class="card-body p-2 small"><label class="font-weight-bold my-0">Date of Invoice:</label> <?php echo $rowinvoiceinfo['date']; ?></div>
        </div>
    </div>
    <div class="col">
        <div class="card rounded-0 border-dark shadow-none">
            <div class="card-body p-2 small"><label class="font-weight-bold my-0">Tax Invoice No:</label> <?php echo 'AGT'.$rowinvoiceinfo['tax_invoice_num']; ?></div>
        </div>        
    </div>
</div>
<div class="row row-cols-1 row-cols-md-2 mt-3">
    <div class="col">
        <div class="card rounded-0 border-dark shadow-none h-100">
            <div class="card-body p-2 small">
                <p class="my-1"><label class="font-weight-bold my-0">Supplier's TIN:</label> 102575474-7000</p>
                <p class="my-1"><label class="font-weight-bold my-0">Supplier's Name:</label> ANSEN GAS DISTRIBUTORS (PVT) LTD</p>
                <p class="my-1"><label class="font-weight-bold my-0">Address:</label> 65, Arcbishop, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka</p>
                <p class="my-1"><label class="font-weight-bold my-0">Telephone No:</label> 0312 235 050</p>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card rounded-0 border-dark shadow-none h-100">
            <div class="card-body p-2 small">
                <p class="my-1"><label class="font-weight-bold my-0">Purchaser's TIN:</label> <?php echo $rowinvoiceinfo['vat_num']; ?></p>
                <p class="my-1"><label class="font-weight-bold my-0">Purchaser's Name:</label> <?php echo $rowinvoiceinfo['tax_cus_name']; ?></p>
                <p class="my-1"><label class="font-weight-bold my-0">Address:</label> <?php echo $rowinvoiceinfo['address']; ?></p>
                <p class="my-1"><label class="font-weight-bold my-0">Telephone No:</label> <?php echo $rowinvoiceinfo['phone']; ?></p>
            </div>
        </div>
    </div>
</div>
<div class="row row-cols-1 row-cols-md-2 mt-3">
    <div class="col">
        <div class="card rounded-0 border-dark shadow-none">
            <div class="card-body p-2 small"><label class="font-weight-bold my-0">Date of Delivery:</label> <?php echo $rowinvoiceinfo['date']; ?></div>
        </div>
    </div>
    <div class="col">
        <div class="card rounded-0 border-dark shadow-none">
            <div class="card-body p-2 small"><label class="font-weight-bold my-0">Place of Supply:</label> </div>
        </div>        
    </div>
</div>
<div class="row row-cols-1 row-cols-md-1 mt-3">
    <div class="col">
        <div class="card rounded-0 border-dark shadow-none">
            <div class="card-body p-2 small" style="height: 100px;"><label class="font-weight-bold my-0">Additional Information if any: </label> </div>
        </div>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <table
            class="table table-striped table-bordered table-black bg-transparent table-sm w-100 tableprint small">
            <thead>
                <tr>
                    <th nowrap>Reference</th>
                    <th nowrap>Description of Goods or Services</th>
                    <th nowrap class="text-center">Quantity</th>
                    <th nowrap class="text-right">Unit Price</th>
                    <th nowrap class="text-right">Amount Excluding VAT (Rs.)</th>
                </tr>
            </thead>
            <?php
                $invoiceproducts = array();
                $i=1;
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

                    if($rowinvoicedetail['newqty']>0) {
                        $obj = new stdClass();
                        $obj->reference = $i;
                        $obj->description = $rowinvoicedetail['product_name'].' - New';
                        $obj->quantity = $rowinvoicedetail['newqty'];
                        $obj->unitprice = $cusType == 1 ? number_format(($rowinvoicedetail['encustomer_newprice']+$vatNew), 2) : number_format(($rowinvoicedetail['newprice']+$vatNew), 2);
                        $obj->amount = $cusType == 1 ? number_format($rowinvoicedetail['newqty'] * ($rowinvoicedetail['encustomer_newprice']+$vatNew), 2) : number_format($rowinvoicedetail['newqty'] * ($rowinvoicedetail['newprice']+$vatNew), 2);
                        array_push($invoiceproducts, $obj);
                    }
                    if($rowinvoicedetail['refillqty']>0) {
                        $obj = new stdClass();
                        $obj->reference = $i;
                        $obj->description = $rowinvoicedetail['product_name'].' - Refill';
                        $obj->quantity = $rowinvoicedetail['refillqty'];
                        $obj->unitprice = $cusType == 1 ? number_format(($rowinvoicedetail['encustomer_refillprice']+$vatRefill), 2) : number_format(($rowinvoicedetail['refillprice']+$vatRefill), 2);
                        $obj->amount = $cusType == 1 ? number_format($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['encustomer_refillprice']+$vatRefill), 2) : number_format($rowinvoicedetail['refillqty'] * ($rowinvoicedetail['refillprice']+$vatRefill), 2);
                        array_push($invoiceproducts, $obj);
                    }
                    if($rowinvoicedetail['emptyqty']>0) {
                        $obj = new stdClass();
                        $obj->reference = $i;
                        $obj->description = $rowinvoicedetail['product_name'].' - Empty';
                        $obj->quantity = $rowinvoicedetail['emptyqty'];
                        $obj->unitprice = $cusType == 1 ? number_format(($rowinvoicedetail['encustomer_emptyprice']+$vatEmpty), 2) : number_format(($rowinvoicedetail['emptyprice']+$vatEmpty), 2);
                        $obj->amount = $cusType == 1 ? number_format($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['encustomer_emptyprice']+$vatEmpty), 2) : number_format($rowinvoicedetail['emptyqty'] * ($rowinvoicedetail['emptyprice']+$vatEmpty), 2);
                        array_push($invoiceproducts, $obj);
                    }
                    $i++;
                }
            ?>
            <tbody>
                <?php 
                    foreach ($invoiceproducts as $product) {
                        echo '
                            <tr>
                                <td nowrap>'.$product->reference.'</td>
                                <td nowrap>'.$product->description.'</td>
                                <td nowrap class="text-center">'.$product->quantity.'</td>
                                <td nowrap class="text-right">'.$product->unitprice.'</td>
                                <td nowrap class="text-right">'.$product->amount.'</td>
                            </tr>
                        ';
                    }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Total Value of Supply:</th>
                    <th class="text-right"><?php echo number_format($rowinvoiceinfo['total'], 2); ?></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">VAT Amount (Total Value of Supply @ <?php echo $rowvat['vat']; ?>%)</th>
                    <th class="text-right"><?php echo number_format($rowinvoiceinfo['taxamount'], 2); ?></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">Total Amount including VAT:</th>
                    <th class="text-right"><?php echo number_format($rowinvoiceinfo['nettotal'], 2); ?></th>
                </tr>
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <div class="card rounded-0 border-dark shadow-none">
            <div class="card-body p-2 small"><label class="font-weight-bold my-0">Total Amount in words: </label> <?php echo ConvertRupeeToText($rowinvoiceinfo['nettotal']); ?></div>
        </div>
    </div>
    <div class="col-12">
        <div class="card rounded-0 border-dark border-top-0 shadow-none">
            <div class="card-body p-2 small"><label class="font-weight-bold my-0">Mode of Payment: </label> <?php echo $rowpaymethod['payment_methods']; ?></div>
        </div>
    </div>
</div>
<?php } ?>