<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$reimno=$_POST['reimno'];
$invoicelist=json_decode($_POST['invoicelist']);
$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');
$transststus=0;

foreach($invoicelist as $datalist){
    $invoiceID = $datalist->invoiceid;
    $discountamount = $datalist->discountamount;
    $customerID = $datalist->customerid;

    $insertreimbursement="INSERT INTO `tbl_invoice_reimbursement`(`date`, `reimdocno`, `amount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_invoice_idtbl_invoice`, `tbl_customer_idtbl_customer`) VALUES ('$today','$reimno','$discountamount','1','$updatedatetime','$userID','$invoiceID','$customerID')";
    if($conn->query($insertreimbursement)==true){
        $updateinvoice="UPDATE `tbl_invoice` SET `paymentcomplete`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invoiceID'";
        $conn->query($updateinvoice);
    }
    else{
        $transststus=1;
        break;
    }    
}

if($transststus==0){
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