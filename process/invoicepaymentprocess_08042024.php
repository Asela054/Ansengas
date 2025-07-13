<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$tblData=$_POST['tblData'];
$tblPayData=$_POST['tblPayData'];
$totAmount=$_POST['totAmount'];
$payAmount=$_POST['payAmount'];
$balAmount=$_POST['balAmount'];
$date=$_POST['date'];

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$insertpayment="INSERT INTO `tbl_invoice_payment`(`date`, `payment`, `balance`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$date','$payAmount','$balAmount','1','$updatedatetime','$userID')";
if($conn->query($insertpayment)==true){
    $paymentID=$conn->insert_id;

    foreach($tblData as $rowtabledata){
        $invno=$rowtabledata['col_1'];
        $invoiceID=substr($invno, 4);
        $invamount=$rowtabledata['col_6'];
        $invpayamount=$rowtabledata['col_11'];

        if($invamount<=$invpayamount){
            $paymentcompletestatus=1;
            $fullstatus=1;
            $halfstatus=0;
        }
        else{
            $paymentcompletestatus=0;
            $fullstatus=0;
            $halfstatus=1;
        }

        if($invpayamount>0){
            $updateinvoice="UPDATE `tbl_invoice` SET `paymentcomplete`='$paymentcompletestatus',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invoiceID'";
            $conn->query($updateinvoice);

            $updateinvoicehas="INSERT INTO `tbl_invoice_payment_has_tbl_invoice`(`tbl_invoice_payment_idtbl_invoice_payment`, `tbl_invoice_idtbl_invoice`, `payamount`, `fullstatus`, `halfstatus`) VALUES ('$paymentID','$invoiceID','$invpayamount','$fullstatus','$halfstatus')";
            $conn->query($updateinvoicehas);
        }
    }

    foreach($tblPayData as $rowtablepaydata){
        $typename=$rowtablepaydata['col_1'];
        $cashamount=$rowtablepaydata['col_2'];
        $bankamount=$rowtablepaydata['col_3'];
        $chequeno=$rowtablepaydata['col_4'];
        $receiptno=$rowtablepaydata['col_5'];
        $chequedate=$rowtablepaydata['col_6'];
        $bankname=$rowtablepaydata['col_7'];
        $bankID=$rowtablepaydata['col_8'];
        $typeID=$rowtablepaydata['col_9'];

        if($typeID==1){
            $paidamount=$cashamount;
        }
        else{
            $paidamount=$bankamount;
        }

        $insertpaydetail="INSERT INTO `tbl_invoice_payment_detail`(`method`, `amount`, `branch`, `receiptno`, `chequeno`, `chequedate`, `status`, `updatedatetime`, `tbl_bank_idtbl_bank`, `tbl_user_idtbl_user`, `tbl_invoice_payment_idtbl_invoice_payment`) VALUES ('$typeID','$paidamount','-','$receiptno','$chequeno','$chequedate','1','$updatedatetime','$bankID','$userID','$paymentID')";
        $conn->query($insertpaydetail);
    }

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    $action=json_encode($actionObj);

    $obj=new stdClass();
    $obj->paymentinvoice=$paymentID;
    $obj->action=$action;

    echo $actionJSON=json_encode($obj);
}
else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    $action=json_encode($actionObj);

    $obj=new stdClass();
    $obj->paymentinvoice='0';
    $obj->action=$action;

    echo $actionJSON=json_encode($obj);
}

?>