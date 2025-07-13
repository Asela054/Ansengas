<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$companylorry=$_POST['companylorry'];
$distributorlorry=$_POST['distributorlorry'];
$lorrynum=$_POST['lorrynum'];
$trailernum=$_POST['trailernum'];
$scheduletime=date("h:i:s", strtotime($_POST['scheduletime']));
$orderID=$_POST['orderID'];
$driverid=0;

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insretorderdelivery="INSERT INTO `tbl_porder_delivery`(`driverid`, `vehicleid`, `trailerid`, `scheduletime`, `comlorrystatus`, `dislorrystatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_porder_idtbl_porder`) VALUES ('$driverid','$lorrynum','$trailernum','$scheduletime','$companylorry','$distributorlorry','1','$updatedatetime','$userID','$orderID')";
    if($conn->query($insretorderdelivery)==true){
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
}
else{
    $updateorderdelivery="UPDATE `tbl_porder_delivery` SET `driverid`='$driverid',`vehicleid`='$lorrynum',`trailerid`='$trailernum', `scheduletime`='$scheduletime',`comlorrystatus`='$companylorry',`dislorrystatus`='$distributorlorry',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder_delivery`='$recordID'";
    if($conn->query($updateorderdelivery)==true){
        $actionObj=new stdClass();
        $actionObj->icon='fas fa-check-circle';
        $actionObj->title='';
        $actionObj->message='Update Successfully';
        $actionObj->url='';
        $actionObj->target='_blank';
        $actionObj->type='primary';

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
}