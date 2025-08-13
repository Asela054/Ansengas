<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$netqty=0;

$tableData=$_POST['tableData'];
if(!empty($_POST['tableDataPay'])){$tableDataPay=$_POST['tableDataPay'];}
$total=$_POST['total'];
$distotal=$_POST['distotal'];
$nettotal=$_POST['nettotal'];
$paytotal=$_POST['paytotal'];
$billtype=$_POST['billtype'];
$cusname=$_POST['cusname'];
$cusnic=$_POST['cusnic'];
$cusmobile=$_POST['cusmobile'];
$priceeditstatus=$_POST['priceeditstatus'];
$billapproveuser=$_POST['billapproveuser'];

$balance=$nettotal-$paytotal;
if($balance<0){$balance=0;}
if($balance>0){$halfstatus=1;$fullstatus=0;$paycomplete=0;}
else{$halfstatus=0;$fullstatus=1;$paycomplete=1;}

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');


$insertinvoice="INSERT INTO `tbl_invoice`(`date`, `total`, `taxamount`, `nettotal`, `paymentmethod`, `paymentcomplete`, `chequesend`, `companydiffsend`, `ref_id`, `addtoaccountstatus`, `protoaccountstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`) VALUES ('$today','$total','0','$nettotal','0','1','0','0','0','0','0','1','$updatedatetime','$userID','26','848','1')";
if($conn->query($insertinvoice)==true){
    $invoiceID=$conn->insert_id;

    $newQty = $refillQty = $emptyQty = $newPrice = $refillPrice = $emptyPrice = 0;

foreach ($tableData as $rowtabledata) {
    $productID = $rowtabledata['col_6'];
    $qty = $rowtabledata['col_2'];
    $saleprice = $rowtabledata['col_8'];
    $total = $rowtabledata['col_10'];
    $deiscountpresntage = $rowtabledata['col_11'];
    $discountamount = $rowtabledata['col_12'];
    $totalwithdiscount = $rowtabledata['col_13'];
    $editstatus = $rowtabledata['col_14'];
    $groupID = $rowtabledata['col_15'];

    // Reset variables for each iteration
    $newQty = $refillQty = $emptyQty = $newPrice = $refillPrice = $emptyPrice = 0;

    if ($groupID == 1) {
        $newQty = $qty;
        $newPrice = $saleprice;
    } elseif ($groupID == 2) {
        $refillQty = $qty;
        $refillPrice = $saleprice;
    } elseif ($groupID == 3) {
        $emptyQty = $qty;
        $emptyPrice = $saleprice;
    }

    $insertinvoicedetail = "INSERT INTO `tbl_invoice_detail`(`newqty`, `refillqty`, `emptyqty`, `encustomer_newprice`, `encustomer_refillprice`, `encustomer_emptyprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_invoice_idtbl_invoice`) VALUES ('$newQty','$refillQty','$emptyQty','$newPrice','$refillPrice','$emptyPrice','1','$updatedatetime','$userID','$productID','$invoiceID')";
    $conn->query($insertinvoicedetail);

    $updatestock = "UPDATE `tbl_stock` SET `fullqty` = (`fullqty` - '$newQty' - '$refillQty'), `emptyqty` = (`emptyqty` + '$refillQty' - '$emptyQty') WHERE `tbl_product_idtbl_product` = '$productID'";
    $conn->query($updatestock);

}

    if($billtype==1){
        $insertpayment="INSERT INTO `tbl_invoice_payment`(`date`, `payment`, `balance`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$today','$paytotal','$balance','1','$updatedatetime','$userID')";
        if($conn->query($insertpayment)==true){
            $invoicepayID=$conn->insert_id;

            if(!empty($tableDataPay)){
                foreach($tableDataPay as $rowtableDataPay){
                    $paymethod=$rowtableDataPay['col_1'];
                    $bank=$rowtableDataPay['col_3'];
                    $chequeno=$rowtableDataPay['col_4'];
                    $chequedate=$rowtableDataPay['col_5'];
                    $totalamount=$rowtableDataPay['col_6'];

                    $insertpaymentdetail="INSERT INTO `tbl_invoice_payment_detail`(`method`, `amount`, `branch`, `receiptno`, `chequeno`, `chequedate`, `addaccountstatus`, `protoaccountstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`,`tbl_bank_idtbl_bank`, `tbl_invoice_payment_idtbl_invoice_payment`) VALUES ('$paymethod','$totalamount','','','$chequeno','$chequedate','1','1','1','$updatedatetime','$userID','$bank','$invoicepayID')";
                    $conn->query($insertpaymentdetail);
                }
            }

            $inserthastable="INSERT INTO `tbl_invoice_payment_has_tbl_invoice`(`tbl_invoice_payment_idtbl_invoice_payment`, `tbl_invoice_idtbl_invoice`, `payamount`, `fullstatus`, `halfstatus`) VALUES ('$invoicepayID','$invoiceID','$paytotal','$fullstatus','$halfstatus')";
            $conn->query($inserthastable);
        }
    }

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    $obj=new stdClass();
    $obj->action=json_encode($actionObj);
    $obj->actiontype='1';
    $obj->invoiceid=$invoiceID;
    $obj->billtype=$billtype;

    echo json_encode($obj);

}else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    $obj=new stdClass();
    $obj->action=json_encode($actionObj);
    $obj->actiontype='0';
    $obj->invoiceid='0';
    $obj->billtype=$billtype;

    echo json_encode($obj);
}


?>