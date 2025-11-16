<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('connection/db.php');//die('bc');
$userID=$_SESSION['userid'];
// Read the CSV file
$filename = 'rimbursement_list_06112025.csv';
$data = [];

if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $data[] = $row;
    }
    fclose($handle);
}
// print_r($data);
// Initialize arrays for each month
$monthlyData = [];

// Process each row
foreach ($data as $row) { 
    $dateinvoice = date("Y-m-d", strtotime($row[2].'-2025'));
    $day=date("d", strtotime($row[2].'-2025'));
    $month = date("F", strtotime($row[2].'-2025'));
    $discountamount = str_replace(',', '', $row[9]); 
    $invoiceno = $row[5]; 
    
    $period = ($day <= 15) ? '1-15' : '16-end';
    $key = $month . '_' . $period;

    if (!isset($monthlyData[$key])) {
        $monthlyData[$key] = [];
    }

    $sqlinvinfo="SELECT `tbl_customer_idtbl_customer`, `idtbl_invoice`, `nettotal` FROM `tbl_invoice` WHERE";
    if (substr($invoiceno, 0, 3) === 'AGT') {
        $taxno = preg_replace('/\D/', '', $invoiceno);
        $sqlinvinfo .= " `tax_invoice_num`='$taxno'";
    } elseif (substr($invoiceno, 0, 4) === 'INV-') {
        $invno = preg_replace('/\D/', '', $invoiceno);
        $sqlinvinfo .= " `idtbl_invoice`='$invno'";
    } else {
        continue; // Skip if format is unrecognized
    }
    
    $resultinvinfo = $conn->query($sqlinvinfo);
    $rowinvinfo = $resultinvinfo->fetch_assoc();

    $invoiceID = $rowinvinfo['idtbl_invoice'];

    $sql12kg="SELECT SUM(`refillqty`) AS `refilltot` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice`='$invoiceID' AND `tbl_product_idtbl_product`=1";
    $result12kg = $conn->query($sql12kg);
    $row12kg = $result12kg->fetch_assoc();

    $sqlpayinfo="SELECT SUM(`tbl_invoice_payment_has_tbl_invoice`.`payamount`) AS `totalpay` FROM `tbl_invoice_payment_has_tbl_invoice` LEFT JOIN `tbl_invoice_payment` ON `tbl_invoice_payment`.`idtbl_invoice_payment` = `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` WHERE `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`='$invoiceID' AND `tbl_invoice_payment`.`status`=1";
    $resultpayinfo = $conn->query($sqlpayinfo);
    $rowpayinfo = $resultpayinfo->fetch_assoc();

    if($rowpayinfo['totalpay']>0){
        if($rowinvinfo['tbl_customer_idtbl_customer']==542){
            $specaildiscount = $row12kg['refilltot'] * 35.40;
            $nettotal       = (float) ($rowinvinfo['nettotal'] ?? 0);
            $nettotal       = round($nettotal);
            $totalpay       = (float) ($rowpayinfo['totalpay'] ?? 0);
            $discountamount = (float) ($discountamount ?? 0); // Assuming $discountamount is defined elsewhere
            $ansenexpenceamount = $specaildiscount;
            $refilqty = $row12kg['refilltot'];
            $unitexpence = 35.40;
            // Perform the calculation with guaranteed numbers
            // $expenseamount = $nettotal - ($totalpay + $discountamount);
            // // echo "Expense Amount for Invoice $invoiceno: $specaildiscount --> $expenseamount\n"."<br>";
            // if($expenseamount >= $specaildiscount){
            //     $ansenexpenceamount = $specaildiscount;
            // }
            $oldnettotal = round($totalpay + $discountamount + $ansenexpenceamount);
            $difference = $nettotal - $oldnettotal;
            if($oldnettotal >= $nettotal){$paymentcomplete = 1;} 
            else{
                if($difference<=20){
                    $paymentcomplete = 1;
                }
                else{
                    $paymentcomplete = 0;
                }
            }

            // if('AGT2405457'==$invoiceno){
            //     echo "Invoice No: $invoiceno, Net Total: $nettotal, Total Pay: $totalpay, Discount Amount: $discountamount, Ansen Expense Amount: $ansenexpenceamount, Old Net Total: $oldnettotal, Payment Complete: $paymentcomplete<br>";
            // }
        }
        else if($rowinvinfo['tbl_customer_idtbl_customer']==1264){
            $specaildiscount = $row12kg['refilltot'] * 59;
            $nettotal       = (float) ($rowinvinfo['nettotal'] ?? 0);
            $nettotal       = round($nettotal);
            $totalpay       = (float) ($rowpayinfo['totalpay'] ?? 0);
            $discountamount = (float) ($discountamount ?? 0); // Assuming $discountamount is defined elsewhere
            $ansenexpenceamount = $specaildiscount;
            $refilqty = $row12kg['refilltot'];
            $unitexpence = 59;
            // Perform the calculation with guaranteed numbers
            // $expenseamount = $nettotal - ($totalpay + $discountamount);
            // // echo "Expense Amount for Invoice $invoiceno: $specaildiscount --> $expenseamount\n"."<br>";
            // if($expenseamount >= $specaildiscount){
            //     $ansenexpenceamount = $specaildiscount;
            // }
            $oldnettotal = round($totalpay + $discountamount + $ansenexpenceamount);
            $difference = $nettotal - $oldnettotal;
            if($oldnettotal >= $nettotal){$paymentcomplete = 1;} 
            else{
                if($difference<=20){
                    $paymentcomplete = 1;
                }
                else{
                    $paymentcomplete = 0;
                }
            }
        }
        else{
            $nettotal       = (float) ($rowinvinfo['nettotal'] ?? 0);
            $nettotal       = round($nettotal);
            $totalpay       = (float) ($rowpayinfo['totalpay'] ?? 0);
            $discountamount = (float) ($discountamount ?? 0);
            $ansenexpenceamount = 0;
            $oldnettotal = round($totalpay + $discountamount + $ansenexpenceamount);
            $difference = $nettotal - $oldnettotal;
            if($oldnettotal >= $nettotal){$paymentcomplete = 1;} 
            else{
                if($difference<=20){
                    $paymentcomplete = 1;
                }
                else{
                    $paymentcomplete = 0;
                }
            }
            $refilqty = 0;
            $unitexpence = 0;
            // if('AGT2403406'==$invoiceno){
            //     echo "Invoice No: $invoiceno, Net Total: $nettotal, Total Pay: $totalpay, Discount Amount: $discountamount, Ansen Expense Amount: $ansenexpenceamount, Old Net Total: $oldnettotal, Payment Complete: $paymentcomplete<br>";
            // }
        }
    }

    // if($rowinvinfo['tbl_customer_idtbl_customer']==542){
        $monthlyData[$key][] = [
            'invoicedate' => $dateinvoice,  
            'invoiceno' => $invoiceno,  
            'discountamount' => $discountamount,
            'customer' => $rowinvinfo['tbl_customer_idtbl_customer'],
            'invoice' => $rowinvinfo['idtbl_invoice'],
            'ansenexpence' => $ansenexpenceamount,
            'paymentcomplete' => $paymentcomplete,
            'refilqty' => $refilqty,
            'unitexpence' => $unitexpence,
            'nettotal' => $nettotal,
            'oldnettotal' => $oldnettotal
        ];
    // }
}
// print_r($monthlyData);
foreach ($monthlyData as $monthPeriod => $entries) {
    // echo "=== $monthPeriod ===\n";
    // echo "Total entries: " . count($entries) . "\n";
    
    // Calculate total for column 10
    $totalCol10 = 0;
    foreach ($entries as $entry) {
        $totalCol10 += floatval($entry['discountamount']);
    }
    // echo "Total Amount: " . number_format($totalCol10, 2) . "\n\n";
    // print_r($entries);

    // Extract the Month and Period to determine the date
    list($monthName, $period) = explode('_', $monthPeriod);
    
    // Determine the day (15th or end of month)
    if ($period === '1-15') {
        // For '1-15' period, the date is the 15th of the month
        $dayOfMonth = '15';
    } else { // '16-end'
        // For '16-end' period, the date is the last day of the month
        $dayOfMonth = date('t', strtotime('2025-' . $monthName . '-01'));
    }
    
    // Construct the final date for the 'date' column in the table (YYYY-MM-DD)
    $today = date('Y-m-d', strtotime('2025-' . $monthName . '-' . $dayOfMonth));

    $monthPeriod = '2025_'.$monthPeriod;

    $reimno=$monthPeriod;
    $totalreimbursement=$totalCol10;
    $invoicelist=$entries;
    // $today=date('Y-m-d');
    $updatedatetime=date('Y-m-d h:i:s');
    $transststus=0;
    $flag = true;
    $conn->autocommit(FALSE);

    $html = '';
    $html .= '<table border="1">
        <thead>
            <tr>
                <th>Invoice No</th>
                <th>Customer</th>
                <th>Invoice Total</th>
                <th>Payment Total</th>
                <th>Credit Amount</th>
            </tr>
        </thead>
        <tbody>';
        foreach($invoicelist as $datalist){
            if($datalist['paymentcomplete']==1): continue;
            else:
                $difference = $datalist['nettotal'] - $datalist['oldnettotal'];
                $sqlcus = "SELECT `name` FROM `tbl_customer` WHERE `idtbl_customer`='".$datalist['customer']."'";
                $resultcus = $conn->query($sqlcus);
                $rowcus = $resultcus->fetch_assoc();

                // echo $datalist['nettotal'].' --> '.$datalist['oldnettotal'].'<br>';
                // echo 'Invoice No '.$datalist['invoiceno'].' --> Customer ID '.$datalist['customer']." --> Difference ".$difference."<br>";
                $html .= '<tr>
                    <td>'.$datalist['invoiceno'].'</td>
                    <td>'.$rowcus['name'].'</td>
                    <td class="text-right">'.number_format($datalist['nettotal'], 2).'</td>
                    <td class="text-right">'.number_format($datalist['oldnettotal'], 2).'</td>
                    <td class="text-right">'.number_format($difference, 2).'</td>
                </tr>';
            endif;
        }
    $html .= '</tbody>
    </table>';
    echo $html;
    $insertreimbursement="INSERT INTO `tbl_invoice_reimbursement`(`date`, `reimdocno`, `netamount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) VALUES ('$today','$reimno','$totalreimbursement','1','$updatedatetime','$userID')";
    if($conn->query($insertreimbursement)==true){
        $reimbursementID=$conn->insert_id;

        foreach($invoicelist as $datalist){
            $invoiceID = $datalist['invoice'];
            $discountamount = $datalist['discountamount'];
            $customerID = $datalist['customer'];
            $invoicedate = $datalist['invoicedate'];
            $paymentcomplete = $datalist['paymentcomplete'];
            $ansenexpence = $datalist['ansenexpence'];
            $refilqty = $datalist['refilqty'];
            $unitexpence = $datalist['unitexpence'];

            $insertreimbursementdetail="INSERT INTO `tbl_invoice_reimbursement_detail`(`invoicedate`, `amount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_invoice_reimbursement_idtbl_invoice_reimbursement`, `tbl_invoice_idtbl_invoice`, `tbl_customer_idtbl_customer`) VALUES ('$invoicedate','$discountamount','1','$updatedatetime','$userID','$reimbursementID','$invoiceID','$customerID')";
            if($conn->query($insertreimbursementdetail)==true){
                $updateinvoice="UPDATE `tbl_invoice` SET `paymentcomplete`='$paymentcomplete',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invoiceID'";
                if (!$conn->query($updateinvoice)) {
                    $flag = false;
                }   
                
                if($ansenexpence>0){
                    $insertspecialexpence="INSERT INTO `tbl_invoice_special_discount`(`invdate`, `qty`, `unitprice`, `totalamount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_invoice_idtbl_invoice`, `tbl_product_idtbl_product`) VALUES ('$invoicedate','$refilqty','$unitexpence','$ansenexpence','1','$updatedatetime','$userID','$customerID','$invoiceID','1')";
                    if (!$conn->query($insertspecialexpence)) {
                        $flag = false;
                    } 
                }
            }
            else{
                $transststus=1;
                $flag = false;
                break;
            }    
        }
    }
    else{
        $transststus=1;
        $flag = false;
    }

    if($transststus==0 && $flag){
        $conn->commit();

        $actionObj=new stdClass();
        $actionObj->icon='fas fa-save';
        $actionObj->title='';
        $actionObj->message='Record Added Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='success';

        $actionJSON=json_encode($actionObj);
        
        $obj=new stdClass();
        $obj->status=1;
        $obj->action=$actionJSON;

        echo json_encode($obj);
    }
    else{
        $conn->rollback();

        $actionObj=new stdClass();
        $actionObj->icon='fas fa-warning';
        $actionObj->title='';
        $actionObj->message='Record Error';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='danger';

        $actionJSON=json_encode($actionObj);
        
        $obj=new stdClass();
        $obj->status=0;
        $obj->action=$actionJSON;

        echo json_encode($obj);
    }
}
?>