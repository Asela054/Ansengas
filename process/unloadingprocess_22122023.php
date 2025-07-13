<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$loadID=$_POST['loadID'];
$tableData=$_POST['tableData'];

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$updateloading="UPDATE `tbl_vehicle_load` SET `unloadstatus`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_vehicle_load`='$loadID'";
if($conn->query($updateloading)==true){
    foreach($tableData as $rowtabledata){
        $product=$rowtabledata['col_2'];
        $refill=$rowtabledata['col_5'];
        $trustreturn=$rowtabledata['col_7'];
        $balqty=$rowtabledata['col_8'];

        $emptyqty=$refill+$trustreturn;

        $updatestock="UPDATE `tbl_stock` SET `fullqty`=(`fullqty`+'$balqty'), `emptyqty`=(`emptyqty`+'$emptyqty') WHERE `tbl_product_idtbl_product`='$product'";
        $conn->query($updatestock);

        $updatetruststock="UPDATE `tbl_stock_trust` SET `returnqty`=(`returnqty`+'$trustreturn') WHERE `tbl_product_idtbl_product`='$product'";
        $conn->query($updatetruststock);
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

?>