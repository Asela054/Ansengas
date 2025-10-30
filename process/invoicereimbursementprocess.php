<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$reimno=$_POST['reimno'];
$totalreimbursement=$_POST['totalreimbursement'];
$invoicelist=json_decode($_POST['invoicelist']);
$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');
$transststus=0;
$flag = true;
$conn->autocommit(FALSE);

$insertreimbursement="INSERT INTO `tbl_invoice_reimbursement`(`date`, `reimdocno`, `netamount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`) VALUES ('$today','$reimno','$totalreimbursement','1','$updatedatetime','$userID')";
if($conn->query($insertreimbursement)==true){
    $reimbursementID=$conn->insert_id;

    foreach($invoicelist as $datalist){
        $invoiceID = $datalist->invoiceid;
        $discountamount = $datalist->discountamount;
        $customerID = $datalist->customerid;
        $invoicedate = $datalist->invoicedate;

        $insertreimbursementdetail="INSERT INTO `tbl_invoice_reimbursement_detail`(`invoicedate`, `amount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_invoice_reimbursement_idtbl_invoice_reimbursement`, `tbl_invoice_idtbl_invoice`, `tbl_customer_idtbl_customer`) VALUES ('$invoicedate','$discountamount','1','$updatedatetime','$userID','$reimbursementID','$invoiceID','$customerID')";
        if($conn->query($insertreimbursementdetail)==true){
            $updateinvoice="UPDATE `tbl_invoice` SET `paymentcomplete`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_invoice`='$invoiceID'";
            if (!$conn->query($updateinvoice)) {
                $flag = false;
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