<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$orderdate=$_POST['orderdate'];
$remark=$_POST['remark'];
$total=$_POST['total'];
$tableData=$_POST['tableData'];

$updatedatetime=date('Y-m-d h:i:s');

$insretorder="INSERT INTO `tbl_porder`(`orderdate`, `nettotal`, `remark`, `confirmstatus`, `dispatchissue`, `grnissuestatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('$orderdate','$total','$remark','0','0','0','1','$updatedatetime','$userID')";
if($conn->query($insretorder)==true){
    $orderID=$conn->insert_id;

    foreach($tableData as $rowtabledata){
        $product=$rowtabledata['col_2'];
        $unitprice=$rowtabledata['col_3'];
        $saleprice=$rowtabledata['col_4'];
        $refillprice=$rowtabledata['col_5'];
        $newqty=$rowtabledata['col_6'];
        $fillqty=$rowtabledata['col_7'];
        $trustqty=$rowtabledata['col_8'];
        $returnqty=$rowtabledata['col_9'];
        $saftyqty=$rowtabledata['col_10'];
        $saftyreturnqty=$rowtabledata['col_11'];
        $total=$rowtabledata['col_12'];

        $insertorderdetail="INSERT INTO `tbl_porder_detail`(`type`, `refillqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice`, `refillprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`, `tbl_product_idtbl_product`) VALUES ('0','$fillqty','$returnqty','$newqty','$trustqty','$saftyqty','$saftyreturnqty','$unitprice','$refillprice','1','$updatedatetime','$userID','$orderID','$product')";
        $conn->query($insertorderdetail);
    }

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    echo $actionJSON=json_encode($actionObj);
}
else{
    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    echo $actionJSON=json_encode($actionObj);
}