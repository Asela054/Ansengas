<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$invoicelist=json_decode($_POST['invoicelist']);
$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');
$transststus=0;
$flag = true;
$conn->autocommit(FALSE);

foreach($invoicelist as $datalist){
    $invoiceID = $datalist->invoiceid;
    $productID = $datalist->productid;
    $customerID = $datalist->customerid;
    $price = $datalist->price;
    $total = $datalist->total;
    $qty = $datalist->qty;

    $insertreimbursementdetail="INSERT INTO `tbl_invoice_market_info`(`invdate`, `qty`, `unitprice`, `totalamount`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_invoice_idtbl_invoice`, `tbl_product_idtbl_product`) VALUES ('$today','$qty','$price','$total','1','$updatedatetime','$userID','$customerID','$invoiceID','$productID')";
    if(!$conn->query($insertreimbursementdetail)){
        $transststus=1;
        $flag = false;
        break;
    }    
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
?>