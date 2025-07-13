<?php
session_start();
require_once('../connection/db.php');

$pettyvoucherID=$_POST['pettyvoucherID'];

$sql="SELECT * FROM `tbl_pettycash_voucher` WHERE `status`=1 AND `idtbl_pettycash_voucher`='$pettyvoucherID'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$accountID=$row['creditaccountid'];
$debitaccountID=$row['debitaccountid'];

$sqlaccount="SELECT `subaccountname` FROM `tbl_subaccount` WHERE `idtbl_subaccount`='$accountID' AND `status`=1";
$resultaccount=$conn->query($sqlaccount);
$rowaccount=$resultaccount->fetch_assoc();

$sqlaccountdebit="SELECT `subaccountname` FROM `tbl_subaccount` WHERE `idtbl_subaccount`='$debitaccountID' AND `status`=1";
$resultaccountdebit=$conn->query($sqlaccountdebit);
$rowaccountdebit=$resultaccountdebit->fetch_assoc();

$sqlpettyinfo="SELECT * FROM `tbl_pettycash` WHERE `idtbl_pettycash` IN (SELECT `tbl_pettycash_idtbl_pettycash` FROM `tbl_pettycash_voucher_has_tbl_pettycash` WHERE `tbl_pettycash_voucher_idtbl_pettycash_voucher`='$pettyvoucherID')";
$resultpettyinfo=$conn->query($sqlpettyinfo);

function convertToIndianCurrency($number) {
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;    
    $digits_length = strlen($no);    
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety');
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;            
            $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str [] = null;
        }  
    }
    
    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And " . ($words[$decimal - $decimal%10]) ." cents" .($words[$decimal%10])  : '';
    return ($Rupees ? '' . $Rupees : '') . $paise . " Only";
}

?>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint">
            <tbody>
                <tr>
                    <td class="text-center"><img src="images/logoprint.png" width="80" height="80" class="img-fluid"></td>
                    <td colspan="4" class="text-left small align-middle">
                        <h4 class="font-weight-light m-0">Ansen Gas Distributors (Pvt) Ltd</h4>
                        65, Archbishop Nicholas Marcus Fernando Mawatha, Negombo<br>
                        Tel: 0094-31-4549149 | Fax: 0094-31-2225050 info@ansengas.lk
                    </td>
                </tr>
            </tbody>            
        </table> 
    </div>
</div>
<div class="row">
    <div class="col-12 text-center">
        <hr>
        <h3><u>Cheque Payment Voucher</u></h3>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint small">
            <tr>
                <td width="10%">Please Pay</td>
                <td width="2%">:</td>
                <td><?php echo $row['debitaccount'].' - '.$rowaccountdebit['subaccountname'] ?></td>
                <td width="10%">Date</td>
                <td width="2%">:</td>
                <td><?php echo $row['date'] ?></td>
            </tr>
            <tr>
                <td width="10%">Bank Account</td>
                <td width="2%">:</td>
                <td><?php echo $row['creditaccount'].' - '.$rowaccount['subaccountname'] ?></td>
                <td width="10%">PV No</td>
                <td width="2%">:</td>
                <td><?php echo 'PT000'.$row['idtbl_pettycash_voucher'] ?></td>
            </tr>
            <tr>
                <td width="10%">&nbsp;</td>
                <td width="2%">&nbsp;</td>
                <td>&nbsp;</td>
                <td width="10%">Cheque No</td>
                <td width="2%">:</td>
                <td><?php echo $row['chequeno'] ?></td>
            </tr>
        </table>
    </div>
</div>
<div class="row mt-3">
    <div class="col-12">
        <table class="table table-striped table-sm tableprint w-100 small">
            <thead>
                <tr>
                    <th colspan="2">Date</th>
                    <th colspan="2">Voucher</th>
                    <th colspan="2">Description</th>
                    <th colspan="2" class="text-right">Amount (Rs.)</th>
                </tr>
            </thead>
            <tbody>
                <?php while($rowpettyinfo=$resultpettyinfo->fetch_assoc()){ ?>
                <tr>
                    <td colspan="2"><?php echo $rowpettyinfo['date']; ?></td>
                    <td colspan="2"><?php echo 'PTC000'.$rowpettyinfo['idtbl_pettycash']; ?></td>
                    <td colspan="2"><?php echo $rowpettyinfo['desc']; ?></td>
                    <td colspan="2" class="text-right"><?php echo number_format($rowpettyinfo['amount'],2) ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>Total</td>
                    <td colspan="7" class="text-right"><?php echo number_format($row['amount'],2) ?></td>
                </tr>
                <tr>
                    <td>Rupee</td>
                    <td colspan="7"><?php echo convertToIndianCurrency($row['amount']) ?></td>
                </tr>
            </tfoot>
        </table>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <table class="w-100 tableprint small">
            <tr>
                <td width="15%">Prepared By</td>
                <td width="2%">:</td>
                <td style="border-bottom: dotted thin;">&nbsp;</td>
                <td width="15%">Received By</td>
                <td width="2%">:</td>
                <td style="border-bottom: dotted thin;">&nbsp;</td>
            </tr>
            <tr>
                <td width="15%">Accountant</td>
                <td width="2%">:</td>
                <td style="border-bottom: dotted thin;">&nbsp;</td>
                <td width="15%">NIC No</td>
                <td width="2%">:</td>
                <td style="border-bottom: dotted thin;">&nbsp;</td>
            </tr>
            <tr>
                <td width="15%">General Manager</td>
                <td width="2%">:</td>
                <td style="border-bottom: dotted thin;">&nbsp;</td>
                <td width="15%">Signature</td>
                <td width="2%">:</td>
                <td style="border-bottom: dotted thin;"></td>
            </tr>
        </table>
    </div>
</div>