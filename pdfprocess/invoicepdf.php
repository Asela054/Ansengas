<?php
session_start();
ini_set('memory_limit', '999M');
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../vendor/autoload.php';
require_once('../connection/db.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$recordID=$_GET['record'];

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

// Fetch invoice HTML content
ob_start();

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

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$html='';
if ($vatStatus == 0) {
    $invoiceno = 'INT-' . $rowinvoiceinfo['idtbl_invoice'];
    $invoiceDetails = [];
    while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
        $invoiceDetails[] = $rowinvoicedetail;
    }
    $html.='
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>LAUGFS Gas PLC - TAX Invoice INT'.$rowinvoiceinfo['idtbl_invoice'].'</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 14px;
                margin: 0px;
            }
        </style>
    </head>

    <body>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="text-align: right;padding-right: 20px;" width="40%">
                    <img src="https://aws.erav.lk/ansengascrm/images/logoprint.png" alt="LAUGFS Gas PLC" width="100">
                </td>
                <td>
                    <h2 style="font-size: 16px; margin-top: 5px; margin-bottom: 5px;">ANSEN GAS DISTRIBUTORS (PVT) LTD</h2>
                    65, Arcbishop, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka<br>
                    Tel: 0312 235 050 | Email: info@ansengas.lk<br>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="width: 50%;border-collapse: collapse;margin-top: 20px;">
                        <tr>
                            <td width="40%">Invoice Date</td>
                            <td width="10%">:</td>
                            <td>'.$rowinvoiceinfo['date'].'</td>
                        </tr>
                        <tr>
                            <td width="40%">Invoice No</td>
                            <td width="10%">:</td>
                            <td>INV-'.$rowinvoiceinfo['idtbl_invoice'].'</td>
                        </tr>
                        <tr>
                            <td width="40%">Customer Name</td>
                            <td width="10%">:</td>
                            <td>'.$rowinvoiceinfo['name'].'</td>
                        </tr>
                        <tr>
                            <td width="40%">Customer Address</td>
                            <td width="10%">:</td>
                            <td>'.$rowinvoiceinfo['address'].'</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">';
                    if ($invtype == 1) {$html.='<h3 class="text-center">Free Issue Note</h3>';}
                    $html.='<table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                        <tr>
                            <th style="border: 1px solid black; padding: 5px;">Product</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">New</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">Refill</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">Empty</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">Trust</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: center;">Trust Return</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: right;">Sale Price</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: right;">Refill Price</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: right;">Empty Price</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: right;">Total</th>
                        </tr>';
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
                            
                            $html.='
                            <tr>
                                <td style="border: 1px solid black; padding: 5px;">'.$rowinvoicedetail['product_name'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['newqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['refillqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['emptyqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['trustqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: center;">'.$rowinvoicedetail['trustreturnqty'].'</td>
                                <td style="border: 1px solid black; padding: 5px;">' . (($invtype == 1) ? '0.00' : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_newprice'] + $vatNew : ($rowinvoicedetail['newprice'] + $vatNew), 2)) . '</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: right;">' . (($invtype == 1) ? '0.00' : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_refillprice'] + $vatRefill : ($rowinvoicedetail['refillprice'] + $vatRefill), 2)) . '</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: right;">' . (($invtype == 1) ? '0.00' : number_format(($cusType == 1) ? $rowinvoicedetail['encustomer_emptyprice'] + $vatEmpty : ($rowinvoicedetail['emptyprice'] + $vatEmpty), 2)) . '</td>
                                <td style="border: 1px solid black; padding: 5px; text-align: right;">' . (($invtype == 1) ? '0.00' : (($cusType == 1) ? $totalWithVATencus : $totalWithVAT)).'</td>
                            </tr>';
                        }
                        if ($invtype == 1) {
                            $html.='<tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">Total</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">VAT</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">0.00</th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">Net Total</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">0.00</th>
                                </tr>
                            ';
                        }
                        else{
                            if ($vatStatus == 1) {
                                $html.='<tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">Total</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">' . number_format($rowinvoiceinfo['total'], 2) . '</th>
                                </tr>
                                <tr>
                                    <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">VAT ('.$rowvat['vat'].'%)</th>
                                    <th style="border: 1px solid black; padding: 5px; text-align: right;">' . number_format($rowinvoiceinfo['taxamount'], 2) . '</th>
                                </tr>';
                            }
                            $html.='<tr>
                                <th colspan="9" style="border: 1px solid black; padding: 5px; text-align: right;">Net Total</th>
                                <th style="border: 1px solid black; padding: 5px; text-align: right;">' . number_format($rowinvoiceinfo['nettotal'], 2) . '</th>
                            </tr>';
                        }
                    $html.='</table>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 10px;">
                    <h4>Payment Mode</h4>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">Cash</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;font-family: \'DejaVu Sans\', sans-serif;">'.($method == 1 ? '&#10004;' : '').'</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">Credit</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;font-family: \'DejaVu Sans\', sans-serif;">'.($method == 3 ? '&#10004;' : '').'</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">Cheque</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;font-family: \'DejaVu Sans\', sans-serif;" width="20%">'.($method == 2 ? '&#10004;' : '').'</th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">No</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">'.$chequeNo.'</th>
                        </tr>
                        <tr>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th>&nbsp;</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">Bank</th>
                            <th style="border: 1px solid black; padding: 5px;text-align: left;">'.$bankName.'</th>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table style="width: 100%; margin-top: 20px; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 5px; text-align: center;">
                                <br><br><br>
                                _______________________<br>
                                Sig. of Driver
                            </td>
                            <td style="padding: 5px; text-align: center;">
                                <br><br><br>
                                _______________________<br>
                               Company Seal
                            </td>
                            <td style="padding: 5px; text-align: center;">
                                <br><br><br>
                                _______________________<br>
                                Sig. of Customer
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%; padding: 5px; text-align: center;">';
                if ($method == 1) :
                $html.='<img src="https://aws.erav.lk/ansengascrm/images/seal/paid.png" width="150" height="150">';
                elseif ($method == 2) :
                $html.='<img src="https://aws.erav.lk/ansengascrm/images/seal/received.png" width="150" height="150">';
                elseif ($method == 3) :
                $html.='<img src="https://aws.erav.lk/ansengascrm/images/seal/credit.png" width="150" height="150">';
                elseif ($status == 3) :
                $html.='<img src="https://aws.erav.lk/ansengascrm/images/seal/cancel.png" width="150" height="150">';
                endif;
                $html.='</td>
                <td style="width: 50%; padding: 5px; text-align: center;">
                    <br><br><br>
                    _______________________<br>
                    Name & Sig. of Authorized Person
                    <br><br><br>
                    _______________________<br>
                    NIC No
                </td>
            </tr>
        </table>
    </body>
    </html>
    ';
}
else{
    $companyID = 1;

    $yy  = date('y', strtotime($rowinvoiceinfo["date"]));          // e.g. 26
    $mmm = strtoupper(date('M', strtotime($rowinvoiceinfo["date"]))); // e.g. JUL
    $qqqqMap = [
        1 => 'AGT1'
    ];
    $qqqq = $qqqqMap[$companyID] ?? 'GEN1';
    $taxDatePrefix = $yy . $mmm . '_' . $qqqq . '_';

    // Strip the first two characters from tax_invoice_num, keep the rest
    $rawTaxInvNum = $rowinvoiceinfo['tax_invoice_num'];
    $strippedTaxInvNum = substr($rawTaxInvNum, 2);

    $showTaxInvNum = $taxDatePrefix . sprintf('%05d', $strippedTaxInvNum);

    $invoiceno = $showTaxInvNum;
    $invoiceDetails = [];
    while ($rowinvoicedetail = $resultinvoicedetail->fetch_assoc()) {
        $invoiceDetails[] = $rowinvoicedetail;
    }
    
    // Assume vat_customer is 1 for tax invoices
    $vat_customer = 1;

    $html = '
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
				<style>
					* {
						margin: 0;
						padding: 0;
						box-sizing: border-box;
					}

					body {
						font-family: DejaVu Sans, sans-serif;
						font-size: 12px;
						color: #000;
						padding: 20px;
						margin-top: 80px;
					}

					 /** Define the header rules **/
					header {
						position: fixed;
						top: 15px;
						left: 0px;
						right: 0px;
						height: 255px;
					}

					/* ── Header ── */
					.header {
						text-align: center;
						margin-bottom: 15px;
					}

					.header img {
						width: 80px;
						height: auto;
						margin-bottom: 5px;
					}

					.company-name {
						font-size: 16px;
						font-weight: bold;
						text-transform: uppercase;
						margin-bottom: 2px;
					}

					.company-sub {
						font-size: 12px;
						margin-bottom: 2px;
					}

					/* ── Info Table ── */
					.info-table {
						width: 100%;
						// border-collapse: collapse;
						border-spacing: 15px 10px;
						// margin-bottom: 10px;
					}

					.info-table td {
						border: 1px solid #000;
						padding: 5px 8px;
						vertical-align: top;
						width: 50%;
					}

					.label {
						font-weight: bold;
					}

					/* Style for the inner layout tables */
					.inner-details-table {
						width: 100%;
						table-layout: fixed;
						border-collapse: collapse;
					}

					.inner-details-table th {
						text-align: left; /* PDF engines default <th> to center alignment */
						font-weight: bold;
					}

					.inner-details-table td {
						text-align: left; /* PDF engines default <th> to center alignment */
					}

					/* ── Additional Info ── */
					.additional-info {
						border: 1px solid #000;
						padding: 6px 8px;
						margin-bottom: 10px;
						margin-left: 15px;
						margin-right: 15px;
						min-height: 30px;
					}

					/* ── Items Table ── */
                    .items-table {
                        width: calc(100% - 30px);
                        border-collapse: collapse;
                        margin-left: 15px;
                        margin-right: 15px;
                    }

					.items-table th {
						border: 1px solid #000;
						padding: 6px 5px;
						text-align: center;
						font-weight: bold;
						background-color: #f0f0f0;
						font-size: 12px;
					}

					.items-table td {
						border: 1px solid #000;
						padding: 5px;
						vertical-align: top;
						font-size: 12px;
					}

					.items-table .col-ref       { width: 10%; text-align: center; }
					.items-table .col-desc      { width: 42%; text-align: left;   }
					.items-table .col-qty       { width: 10%; text-align: center; }
					.items-table .col-unitprice { width: 18%; text-align: right;  }
					.items-table .col-amount    { width: 20%; text-align: right;  }

					.items-table .empty-row td  { height: 25px; }

					/* ── Summary ── */
					.summary-label { text-align: left;  font-size: 12px; }
					.summary-value { text-align: right; font-size: 12px; font-weight: bold; }

					/* ── Total Words & Mode ── */
					.total-words-box {
						border: 1px solid #000;
						padding: 6px 8px;
						min-height: 28px;
						margin-top: 15px;
						margin-left: 15px;
						margin-right: 15px;
					}

					.mode-payment-box {
						border: 1px solid #000;
						border-top: 0;
						padding: 6px 8px;
						min-height: 28px;
						margin-left: 15px;
						margin-right: 15px;
					}

					/* ── Footer ── */
					.footer-ref {
						text-align: left;
						font-size: 9px;
						margin-top: 15px;
					}

					.footer {
						margin-top: 5px;
						font-size: 9px;
						text-align: center;
						color: #555;
						border-top: 1px solid #000;
						padding-top: 5px;
					}

					/* ── Utilities ── */
					.text-right  { text-align: right;  }
					.text-center { text-align: center; }
					.text-left   { text-align: left;   }
					.font-bold   { font-weight: bold;  }
					.asterisk    { color: #888; 	  }

					/** Define the footer rules **/
					footer {
						position: fixed; 
						bottom: 0px; 
						left: 0px; 
						right: 0px;
						height: 120px; /* Slightly increased to fit signatures comfortably */
					}

					.footertable {
						width: 100%;
						text-align: center;
						border-collapse: collapse;
						margin-top: 10px;
					}

					.footertable td {
						width: 33.33%;
						padding-top: 60px; /* Creates the space for physical signatures */
						vertical-align: bottom;
					}

					.sig-line {
						border-top: 1px dotted #000;
						width: 80%;
						margin: 0 auto 5px auto;
					}

				</style>
		</head>
		<body>

			<!-- ══ HEADER ══════════════════════════════════════════════════════ -->
			<header>
				<div class="header">
					<div class="company-name">ANSEN GAS DISTRIBUTORS (PVT) LTD</div>
					<div class="company-sub">65, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka</div>
					<div class="company-sub">Tel: 0312 235 050 | Email: info@ansengas.lk</div>
				</div>
			</header>

			<!-- ══ FOOTER ══════════════════════════════════════════════════════ -->
            <footer>
                <!--
                <table class="footertable">
                    <tbody>
                        <tr>
                            <td>
                                <div class="sig-line"></div>
                                Prepared By
                            </td>
                            <td>
                                <div class="sig-line"></div>
                                Checked By
                            </td>
                            <td>
                                <div class="sig-line"></div>
                                For ANSEN GAS
                            </td>
                        </tr>
                    </tbody>
                </table>
                -->
                <div style="text-align: center; font-size: 10px; padding-top: 20px;">
                    This is a computer-generated invoice. No signature is required.
                </div>
            </footer>

			<!-- ══ TITLE ════════════════════════════════════════════════════════ -->
			<div style="text-align: center; margin-bottom: 15px;">
				<table style="margin: 0 auto; border-collapse: collapse;">
					<tr>
						<td style="
							border: 2px solid #000;
							padding: 8px 30px;
							font-size: 16px;
							font-weight: bold;
							letter-spacing: 2px;
							text-align: center;
						">TAX INVOICE</td>
					</tr>
				</table>
			</div>

			<!-- ══ SUPPLIER & PURCHASER INFO ════════════════════════════════════ -->

			<table class="info-table">

				<!-- Row 1: Date of Invoice | Tax Invoice No -->
				<tr>
					<td>
						<table class="inner-details-table" style="width: 100%; border: none; border-collapse: collapse;">
							<tr>
								<th style="border: none;width:38%;vertical-align: top;">Date of Invoice</th>
								<th style="border: none;width:2%;vertical-align: top;">:</th>
								<td style="border: none;width:60%;vertical-align: top;padding-top:0;">'. date('m/d/Y', strtotime($rowinvoiceinfo['date'])) .'</td>
							</tr>
						</table>
					</td>
					<td>
						<table class="inner-details-table" style="width: 100%; border: none; border-collapse: collapse;">
							<tr>
								<th style="border: none;width:38%;vertical-align: top;">Tax Invoice No.</th>
								<th style="border: none;width:2%;vertical-align: top;">:</th>
                                <td style="border: none;width:60%;vertical-align: top;padding-top:0;">'. $showTaxInvNum .'</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- Row 2: Supplier TIN | Purchaser TIN -->
				<tr>
					<td>	
						<table class="inner-details-table" style="width: 100%; border: none; border-collapse: collapse;">
							<tr>
								<th style="border: none;width:38%;vertical-align: top;">Supplier`s TIN</th>
								<th style="border: none;width:2%; width: 2px;vertical-align: top;">:</th>
								<td style="border: none;width:60%;vertical-align: top;padding-top:0;">102575474</td>
							</tr>
							<tr>
								<th style="border: none;vertical-align: top;">Supplier`s Name</th>
								<th style="border: none;vertical-align: top;">:</th>
								<td style="border: none;vertical-align: top;padding-top:0;">ANSEN GAS DISTRIBUTORS (PVT) LTD</td>
							</tr>
							<tr>
								<th style="border: none;vertical-align: top;">Address</th>
								<th style="border: none;vertical-align: top;">:</th>
								<td style="border: none;vertical-align: top;padding-top:0;">65, Archbishop Nicholas Marcus Fernando Mawatha, Negombo, Sri Lanka</td>
							</tr>
							<tr>
								<th style="border: none;vertical-align: top;">Telephone No. </th>
								<th style="border: none;vertical-align: top;">:</th>
								<td style="border: none;vertical-align: top;padding-top:0;">0312 235 050</td>
							</tr>
						</table>
					</td>
					<td>
						<table class="inner-details-table" style="width: 100%; border: none; border-collapse: collapse;">
							<tr>
								<th style="border: none;width:38%;vertical-align: top;">Purchaser`s TIN</th>
								<th style="border: none;width:2%;vertical-align: top;">:</th>
                                <td style="border: none;width:60%;vertical-align: top;padding-top:0;">'. explode('-', $rowinvoiceinfo['vat_num'])[0] .'</td>
							</tr>
							<tr>
								<th style="border: none;vertical-align: top;">Purchaser`s Name</th>
								<th style="border: none;vertical-align: top;">:</th>
								<td style="border: none;vertical-align: top;padding-top:0;">' . $rowinvoiceinfo['tax_cus_name'] . '</td>
							</tr>
							<tr>
								<th style="border: none;vertical-align: top;">Address</th>
								<th style="border: none;vertical-align: top;">:</th>
								<td style="border: none;vertical-align: top;padding-top:0;">' . $rowinvoiceinfo['address'] . '</td>
							</tr>
							<tr>
								<th style="border: none;vertical-align: top;">Telephone No.</th>
								<th style="border: none;vertical-align: top;">:</th>
								<td style="border: none;vertical-align: top;padding-top:0;">' . $rowinvoiceinfo['phone'] . '</td>
							</tr>
						</table>
					</td>
				</tr>

				<!-- Row 6: Date of Supply | Place of Supply -->
				<tr>
					<td>
						<table class="inner-details-table" style="width: 100%; border: none; border-collapse: collapse;">
							<tr>
								<th style="border: none;width:38%;vertical-align: top;">Date of Supply</th>
								<th style="border: none;width:2%;vertical-align: top;">:</th>
								<td style="border: none;width:60%;vertical-align: top;padding-top:0;">'. date('m/d/Y', strtotime($rowinvoiceinfo['date'])) .'</td>
							</tr>
						</table>						
					</td>
					<td>
						<table class="inner-details-table" style="width: 100%; border: none; border-collapse: collapse;">
							<tr>
								<th style="border: none;width:38%;vertical-align: top;">Place of Supply </th>
								<th style="border: none;width:2%;vertical-align: top;">:</th>
								<td style="border: none;width:60%;vertical-align: top;padding-top:0;">' . $rowinvoiceinfo['name'] . '</td>
							</tr>
						</table>						
					</td>
				</tr>

			</table>

            <!-- ══ ADDITIONAL INFORMATION ════════════════════════════════════════ -->
            <div class="additional-info">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 5px; vertical-align: top; height: 25px;">
                            <strong>Additional Information if any: </strong> 
                        </td>                        
                    </tr>  
                </table>					
            </div>

			<!-- ══ ITEMS TABLE ════════════════════════════════════════════════════ -->
			<table class="items-table">

				<!-- ── Head ── -->
                <thead>
                    <tr>
                        <th class="col-ref" style="vertical-align: top;">
                            Reference
                        </th>
                        <th class="col-desc" style="vertical-align: top;">Description of Goods or Services</th>
                        <th class="col-qty" style="vertical-align: top;">Quantity</th>
                        <th class="col-unitprice" style="vertical-align: top;">Unit Price</th>
                        <th class="col-amount" style="vertical-align: top;">
                            Amount Excluding VAT (Rs.)
                        </th>
                    </tr>
                </thead>

				<!-- ── Body ── -->
				<tbody>';
					$ref_counter = 1;
					foreach ($invoiceDetails as $rowinvoicedetail) {
						if ($cusType == 1) {
							$vatNew = $rowinvoicedetail['encustomer_newprice'] * $rowvat['vat'] / 100;
							$vatRefill = $rowinvoicedetail['encustomer_refillprice'] * $rowvat['vat'] / 100;
							$vatEmpty = $rowinvoicedetail['encustomer_emptyprice'] * $rowvat['vat'] / 100;
						} else {
							$vatNew = $rowvat['vat'] * $rowinvoicedetail['newprice'] / 100;
							$vatRefill = $rowvat['vat'] * $rowinvoicedetail['refillprice'] / 100;
							$vatEmpty = $rowvat['vat'] * $rowinvoicedetail['emptyprice'] / 100;
						}

						if($rowinvoicedetail['newqty']>0) {
							$unitprice = $cusType == 1 ? $rowinvoicedetail['encustomer_newprice'] : $rowinvoicedetail['newprice'];
							$total = $cusType == 1 ? $rowinvoicedetail['newqty'] * $rowinvoicedetail['encustomer_newprice'] : $rowinvoicedetail['newqty'] * $rowinvoicedetail['newprice'];
							$html.='<tr>
								<td class="col-ref text-center">'. $ref_counter .'</td>
								<td class="col-desc">' . $rowinvoicedetail['product_name'] . ' - New</td>
								<td class="col-qty text-center">' . $rowinvoicedetail['newqty'] . '</td>
								<td class="col-unitprice text-right">' . number_format($unitprice, 2, '.', ',') . '</td>
								<td class="col-amount text-right">' . number_format($total, 2, '.', ',') . '</td>
							</tr>';
							$ref_counter++;
						}
						
						if($rowinvoicedetail['refillqty']>0) {
							$unitprice = $cusType == 1 ? $rowinvoicedetail['encustomer_refillprice'] : $rowinvoicedetail['refillprice'];
							$total = $cusType == 1 ? $rowinvoicedetail['refillqty'] * $rowinvoicedetail['encustomer_refillprice'] : $rowinvoicedetail['refillqty'] * $rowinvoicedetail['refillprice'];
							$html.='<tr>
								<td class="col-ref text-center">'. $ref_counter .'</td>
								<td class="col-desc">' . $rowinvoicedetail['product_name'] . ' - Refill</td>
								<td class="col-qty text-center">' . $rowinvoicedetail['refillqty'] . '</td>
								<td class="col-unitprice text-right">' . number_format($unitprice, 2, '.', ',') . '</td>
								<td class="col-amount text-right">' . number_format($total, 2, '.', ',') . '</td>
							</tr>';
							$ref_counter++;
						}
						
						if($rowinvoicedetail['emptyqty']>0) {
							$unitprice = $cusType == 1 ? $rowinvoicedetail['encustomer_emptyprice'] : $rowinvoicedetail['emptyprice'];
							$total = $cusType == 1 ? $rowinvoicedetail['emptyqty'] * $rowinvoicedetail['encustomer_emptyprice'] : $rowinvoicedetail['emptyqty'] * $rowinvoicedetail['emptyprice'];
							$html.='<tr>
								<td class="col-ref text-center">'. $ref_counter .'</td>
								<td class="col-desc">' . $rowinvoicedetail['product_name'] . ' - Empty</td>
								<td class="col-qty text-center">' . $rowinvoicedetail['emptyqty'] . '</td>
								<td class="col-unitprice text-right">' . number_format($unitprice, 2, '.', ',') . '</td>
								<td class="col-amount text-right">' . number_format($total, 2, '.', ',') . '</td>
							</tr>';
							$ref_counter++;
						}
					}
				$html.='</tbody>';
				
				$vat_amount = $rowinvoiceinfo['taxamount'];
				$subtotal = $rowinvoiceinfo['total'];
				$nettotal = $rowinvoiceinfo['nettotal'];
				$rupeetext = ConvertRupeeToText(round($nettotal, 2));

				$html.='
				<!-- ── Summary ── -->
				<tfoot>
				<tr>
					<td colspan="4" class="summary-label">
						Total Value of Supply :
					</td>
					<td class="summary-value text-right">
						' . number_format($subtotal, 2, '.', ',') . '
					</td>
				</tr>
				<tr>
					<td colspan="4" class="summary-label">
						VAT Amount (Total Value of Supply @ ' . $rowvat['vat'] . '%)
					</td>
					<td class="summary-value text-right">
						' . number_format($vat_amount, 2, '.', ',') . '
					</td>
				</tr>
				<tr>
					<td colspan="4" class="summary-label font-bold">
						Total Amount / consideration including VAT :
					</td>
					<td class="summary-value text-right font-bold">
						' . number_format($nettotal, 2, '.', ',') . '
					</td>
				</tr>
				</tfoot>

			</table>

			<!-- ══ TOTAL IN WORDS ════════════════════════════════════════════════ -->
			<div class="total-words-box">
				<table class="inner-details-table" style="width: 100%; border: none; border-collapse: collapse;">
					<tr>
						<th style="border: none;width:24%;vertical-align: top;">Total Amount in words</th>
						<th style="border: none;width:2%;vertical-align: top;">:</th>
						<td style="border: none;width:74%;vertical-align: top;padding-top:0;">'. $rupeetext .'</td>
					</tr>
				</table>	
			</div>

			<!-- ══ MODE OF PAYMENT ═══════════════════════════════════════════════ -->
			<div class="mode-payment-box">
				<table class="inner-details-table" style="width: 100%; border: none; border-collapse: collapse;">
					<tr>
						<th style="border: none;width:24%;vertical-align: top;">Mode of Payment</th>
						<th style="border: none;width:2%;vertical-align: top;">:</th>
						<td style="border: none;width:74%;vertical-align: top;padding-top:0;"></td>
					</tr>
				</table>	
			</div>
		</body>
		</html>
	';
}

// echo $html;
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("ansen_gas_invoice_" . $invoiceno . ".pdf", ["Attachment" => false]);

// Close database connection
mysqli_close($conn);
?>