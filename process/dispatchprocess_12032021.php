<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$netqty=0;

$porderID=$_POST['porderID'];
$lorryID=$_POST['lorryID'];
$trailerID=$_POST['trailerID'];
$driverID=$_POST['driverID'];
$officerID=$_POST['officerID'];
$total=$_POST['total'];
$tableData=$_POST['tableData'];

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$sqlcheckdispatch="SELECT COUNT(*) AS `count` FROM `tbl_dispatch` WHERE `porder_id`='$porderID' AND `status`=1";
$resultcheckdispatch=$conn->query($sqlcheckdispatch);
$rowcheckdispatch=$resultcheckdispatch->fetch_assoc();

if($rowcheckdispatch['count']>0){
    $updatedispatch="UPDATE `tbl_dispatch` SET `driver_id`='$driverID',`officer_id`='$officerID' WHERE `porder_id`='$porderID'";
    if($conn->query($updatedispatch)==true){
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
else{
    $insertdispatch="INSERT INTO `tbl_dispatch`(`distype`, `date`, `netqty`, `nettotal`, `porder_id`, `vehicle_id`, `trailer_id`, `driver_id`, `officer_id`, `ref_id`, `area_id`, `status`, `updatedatetime`, `tbl_user_idtbl_user`) VALUES ('1','$today','0','$total','$porderID','$lorryID','$trailerID','$driverID','$officerID','','','1','$updatedatetime','$userID')";
    if($conn->query($insertdispatch)==true){
        $dispatchID=$conn->insert_id;

        foreach($tableData as $rowtabledata){
            $product=$rowtabledata['col_2'];
            $unitprice=$rowtabledata['col_3'];
            $refillprice=$rowtabledata['col_4'];
            $newsaleprice=$rowtabledata['col_5'];
            $refillsaleprice=$rowtabledata['col_6'];
            $fillqty=$rowtabledata['col_7'];
            $newqty=$rowtabledata['col_8'];
            $reqty=$rowtabledata['col_9'];
            $trustqty=$rowtabledata['col_10'];
            $saftyqty=$rowtabledata['col_11'];
            $saftyreturnqty=$rowtabledata['col_12'];
            $total=$rowtabledata['col_13'];

            $insertdispatchdetail="INSERT INTO `tbl_dispatch_detail`(`type`, `refillqty`, `returnqty`, `newqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice`, `refillprice`, `newsaleprice`, `refillsaleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_dispatch_idtbl_dispatch`, `tbl_product_idtbl_product`) VALUES ('0','$fillqty','$reqty','$newqty','$trustqty','$saftyqty','$saftyreturnqty','$unitprice','$refillprice','$newsaleprice','$refillsaleprice','1','$updatedatetime','$userID','$dispatchID','$product')";
            $conn->query($insertdispatchdetail);

            $netqty=$netqty+($fillqty+$newqty+$reqty+$trustqty+$saftyqty+$saftyreturnqty);

            // Empty update in stock
            if($fillqty>0){
                $updatestock="UPDATE `tbl_stock` SET `emptyqty`=(`emptyqty`-'$fillqty') WHERE `tbl_product_idtbl_product`='$product'";
                $conn->query($updatestock);
            }
            //Trust return update in trsut stock
            if($reqty>0){
                $updatetruststock="UPDATE `tbl_stock_trust` SET `returnqty`=(`returnqty`-'$reqty'), `saftyreturnqty`=(`saftyreturnqty`-'$saftyreturnqty') WHERE `tbl_product_idtbl_product`='$product'";
                $conn->query($updatetruststock);

                $updatestock="UPDATE `tbl_stock` SET `emptyqty`=(`emptyqty`-'$reqty') WHERE `tbl_product_idtbl_product`='$product'";
                $conn->query($updatestock);
            }        
        }

        $updatedispatch="UPDATE `tbl_dispatch` SET `netqty`='$netqty' WHERE `idtbl_dispatch`='$dispatchID'";
        $conn->query($updatedispatch);

        if($porderID!=0){
            $updateorder="UPDATE `tbl_porder` SET `dispatchissue`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$porderID'";
            $conn->query($updateorder);
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
}

?>