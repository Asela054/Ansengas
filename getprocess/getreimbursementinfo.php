<?php 
require_once('../connection/db.php');

$invoicedate=$_POST['invoicedate'];
$customerID=$_POST['customerID'];
$reimbursementtype=$_POST['reimbursementtype'];
$html='';

if($reimbursementtype==1):
    $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
    $resultvat = $conn->query($sqlvat);
    $rowvat = $resultvat->fetch_assoc();

    $vatamount = $rowvat['vat'];

    $sql="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`,`tbl_invoice_detail`.`refillqty`, `tbl_invoice_detail`.`encustomer_refillprice`, `tbl_invoice_detail`.`discount_price`,tbl_invoice.nettotal, DATEDIFF(CURDATE(), tbl_invoice.date) AS days_since_invoice, `tbl_invoice`.`remarks`, `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_customer`.`creditperiod`, `tbl_customer`.`discount_status`, `tbl_customer`.`type`, `tbl_customer`.`tbl_area_idtbl_area` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_customer`.`discount_status`=1 AND `tbl_invoice`.`invtype` = 0";
    if(!empty($customerID)){$sql.=" AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID'";}
    if(!empty($invoicedate)){$sql.=" AND `tbl_invoice`.`date`='$invoicedate'";}
    $sql.=" GROUP BY `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`";
    $result =$conn-> query($sql);
    while($row = $result-> fetch_assoc()){  
        $invID=$row['idtbl_invoice'];
        $discountstatus=$row['discount_status'];

        $areaID=$row['tbl_area_idtbl_area'];
        $refillqty=$row['refillqty'];
        $customerID=$row['idtbl_customer'];
        $refill_price=(($row['encustomer_refillprice']*($vatamount+100))/100);
        $discount_price=(($row['discount_price']*($vatamount+100))/100);

        $total_refillprice=$refill_price*$refillqty;
        $total_discountprice=$discount_price*$refillqty;

        $sqlinvdetail="SELECT `refillqty`, `trustqty`, `encustomer_refillprice`, `tbl_product_idtbl_product`, `discount_price` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice`='$invID' AND `status`=1 AND `tbl_product_idtbl_product`=1";
        $resultinvdetail = $conn->query($sqlinvdetail);
        $rowinvdetail = $resultinvdetail->fetch_assoc();

        if(!empty($rowinvdetail['tbl_product_idtbl_product']) && $rowinvdetail['discount_price']>0 && $discountstatus==1){
            $refillqty=$rowinvdetail['refillqty']+$rowinvdetail['trustqty'];
            $refill_price=(($rowinvdetail['encustomer_refillprice']*($vatamount+100))/100);
            $discount_price=(($rowinvdetail['discount_price']*($vatamount+100))/100);

            $total_refillprice=$refill_price*$refillqty;
            $total_discountprice=$discount_price*$refillqty;

            $discount_amount=$total_refillprice-$total_discountprice;
        }
        else{
            $discount_amount=0;

            if($row['type']=='2' && $row['discount_price']==0 && $discountstatus==1):
                $sqlmainarea="SELECT `tbl_main_area_idtbl_main_area` FROM `tbl_area` WHERE `status`=1 AND `idtbl_area`='$areaID'";
                $resultmainarea=$conn->query($sqlmainarea);
                $rowmainarea=$resultmainarea->fetch_assoc();

                $mainareaID=$rowmainarea['tbl_main_area_idtbl_main_area'];

                $sqldiscountprise="SELECT `tbl_areawise_product`.`discount_price`, `tbl_invoice_detail`.`tbl_product_idtbl_product`, `tbl_areawise_product`.`encustomer_refillprice` FROM `tbl_areawise_product` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_product_idtbl_product`=`tbl_areawise_product`.`tbl_product_idtbl_product` WHERE `tbl_areawise_product`.`status`=1 AND `tbl_areawise_product`.`discount_price`>0 AND `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$invID' AND `tbl_areawise_product`.`tbl_main_area_idtbl_main_area`='$mainareaID'";
                $resultdiscountprise=$conn->query($sqldiscountprise);
                $rowdiscountprise=$resultdiscountprise->fetch_assoc();

                $refill_price=(($rowdiscountprise['encustomer_refillprice']*($vatamount+100))/100);
                $discount_price=(($rowdiscountprise['discount_price']*($vatamount+100))/100);
                $total_discountprice=$discount_price*$refillqty;
                $total_refillprice=$refill_price*$refillqty;

                $discount_amount=($total_refillprice-$total_discountprice);
            else:
                if($row['date']>='2025-09-18'):
                    $sqlinvdetail="SELECT `refillqty`, `trustqty`, `encustomer_refillprice`, `tbl_product_idtbl_product`, `discount_price` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice`='$invID' AND `status`=1 ";
                    $resultinvdetail = $conn->query($sqlinvdetail);
                    while($rowinvdetail = $resultinvdetail->fetch_assoc()){ 
                        $pID = $rowinvdetail['tbl_product_idtbl_product'];

                        $sqlcheckprice="SELECT ap.encustomer_refillprice, cd.discount_amount FROM tbl_product p 
                        LEFT JOIN tbl_areawise_product ap ON p.idtbl_product = ap.tbl_product_idtbl_product 
                        LEFT JOIN `tbl_customer_discount` cd ON cd.tbl_product_idtbl_product = p.idtbl_product AND cd.tbl_customer_idtbl_customer = '$customerID'
                        JOIN `tbl_main_area` ma ON ap.`tbl_main_area_idtbl_main_area` = ma.`idtbl_main_area` 
                        JOIN `tbl_area` sa ON ap.`tbl_main_area_idtbl_main_area` = sa.`tbl_main_area_idtbl_main_area`
                        WHERE `ap`.`status` = 1 AND p.tbl_product_category_idtbl_product_category IN (1,2) AND sa.`idtbl_area` = '$areaID' AND p.idtbl_product = '$pID'";
                        $resultcheckprice = $conn->query($sqlcheckprice);
                        $rowcheckprice = $resultcheckprice->fetch_assoc();

                        if(!empty($rowcheckprice['discount_amount'])){
                            $refillqty=$rowinvdetail['refillqty']+$rowinvdetail['trustqty'];
                            $refill_price=(($rowcheckprice['encustomer_refillprice']*($vatamount+100))/100);
                            $discount_price=(($rowinvdetail['discount_price']*($vatamount+100))/100);

                            $total_refillprice=$refill_price*$refillqty;
                            $total_discountprice=$discount_price*$refillqty;

                            $discount_amount=$total_refillprice-$total_discountprice;
                        }
                        else{
                            $discount_amount=0;
                        }
                    }
                endif;
                // if(!empty($row['discount_price'])):
                //     $discount_amount=($total_refillprice-$total_discountprice);
                // else:
                //     $discount_amount=0;
                // endif;
                // echo $discount_amount.'-->'.$row['name'].'<br>';
            endif;
        }

        if ($row['tax_invoice_num'] == null) {
            $invoiceno = 'INV-'.$row['idtbl_invoice'];
        } else{
            $invoiceno = 'AGT'.$row['tax_invoice_num'];
        }

        if($discount_amount>0):
            $html.='<tr>
                <td class="text-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="check'.$row['idtbl_invoice'].'" data-invoiceid="'.$row['idtbl_invoice'].'" data-discountamount="'.$discount_amount.'" data-customer="'.$row['idtbl_customer'].'" data-invoicedate="'.$row['date'].'">
                        <label class="custom-control-label m-0" for="check'.$row['idtbl_invoice'].'"></label>
                    </div>
                </td>
                <td>'.$row['name'].'</td>
                <td>'.$invoiceno.'</td>
                <td>'.$row['date'].'</td>
                <td class="text-right">'.number_format($row['nettotal'], 2).'</td>
                <td class="text-right">'.number_format($discount_amount, 2).'</td>
            </tr>';
        endif;
    }
elseif($reimbursementtype==2):
    $sql="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`tax_invoice_num`, `tbl_invoice`.`non_tax_invoice_num`, `tbl_invoice`.`date`, `tbl_invoice`.`nettotal`, `tbl_invoice`.`remarks`, `tbl_customer`.`idtbl_customer`, `tbl_customer`.`name`, `tbl_customer`.`creditperiod`, `tbl_customer`.`discount_status`, `tbl_customer`.`type`, `tbl_customer`.`tbl_area_idtbl_area` FROM `tbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer` = `tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice`.`status` = 1 AND `tbl_invoice`.`date`='$invoicedate' AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`invtype` = 1";
    if (!empty($customerID)) {
        $sql .= " AND `tbl_invoice`.`tbl_customer_idtbl_customer` = '$customerID'";
    }
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $invID = $row['idtbl_invoice'];

        if ($row['tax_invoice_num'] == null) {
            $invoiceno = 'INV-' . $row['idtbl_invoice'];
        } else {
            $invoiceno = 'AGT' . $row['tax_invoice_num'];
        }

        $html .= '<tr>
                <td class="text-center">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="check' . $row['idtbl_invoice'] . '" data-invoiceid="' . $row['idtbl_invoice'] . '" data-discountamount="' . $row['nettotal'] . '" data-customer="' . $row['idtbl_customer'] . '" data-invoicedate="' . $row['date'] . '">
                        <label class="custom-control-label m-0" for="check' . $row['idtbl_invoice'] . '"></label>
                    </div>
                </td>
                <td>' . $row['name'] . '</td>
                <td>' . $invoiceno . '</td>
                <td>' . $row['date'] . '</td>
                <td class="text-right">' . number_format($row['nettotal'], 2) . '</td>
                <td class="text-right">' . number_format($row['nettotal'], 2) . '</td>
            </tr>';
    }
endif;

echo $html;