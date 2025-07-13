<?php 
require_once('../connection/db.php');

$invoicedate=$_POST['invoicedate'];
$customerID=$_POST['customerID'];
$html='';

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $conn->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

$vatamount = $rowvat['vat'];

$sql="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`,`tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`discount_price`,tbl_invoice.nettotal, DATEDIFF(CURDATE(), tbl_invoice.date) AS days_since_invoice, `tbl_invoice`.`remarks`, `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_customer`.`creditperiod`, `tbl_customer`.`discount_status` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_customer`.`discount_status`=1";
if(!empty($customerID)){$sql.=" AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID'";}
if(!empty($invoicedate)){$sql.=" AND `tbl_invoice`.`date`='$invoicedate'";}
$sql.=" GROUP BY `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`";
$result =$conn-> query($sql);
while($row = $result-> fetch_assoc()){  
    $invID=$row['idtbl_invoice'];
    $discountstatus=$row['discount_status'];

    $refillqty=$row['refillqty'];
    $refill_price=(($row['encustomer_refillprice']*($vatamount+100))/100);
    $discount_price=(($row['discount_price']*($vatamount+100))/100);

    $total_refillprice=$refill_price*$refillqty;
    $total_discountprice=$discount_price*$refillqty;

    if ($discountstatus==1) {
        if($row['date']<'2024-04-01' || $row['date']>'2025-04-01'){
            $discount_amount=($total_refillprice-$total_discountprice);
        }
        else{
            $discount_amount=0;
        }
    }
    else{
        $discount_amount=0;
    }

    if ($row['tax_invoice_num'] == null) {
        $invoiceno = 'INV-'.$row['idtbl_invoice'];
    } else{
        $invoiceno = 'AGT'.$row['tax_invoice_num'];
    }

    $html.='<tr>
        <td class="text-center">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="check'.$row['idtbl_invoice'].'" data-invoiceid="'.$row['idtbl_invoice'].'" data-discountamount="'.$discount_amount.'" data-customer="'.$row['idtbl_customer'].'">
                <label class="custom-control-label m-0" for="check'.$row['idtbl_invoice'].'"></label>
            </div>
        </td>
        <td>'.$row['name'].'</td>
        <td>'.$invoiceno.'</td>
        <td>'.$row['date'].'</td>
        <td class="text-right">'.number_format($row['nettotal'], 2).'</td>
        <td class="text-right">'.number_format($discount_amount, 2).'</td>
    </tr>';
}

echo $html;