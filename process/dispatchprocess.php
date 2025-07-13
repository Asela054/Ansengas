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
$helperID=$_POST['helperID'];
$total=$_POST['total'];
$tableData=$_POST['tableData'];

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$sqlcheckdispatch="SELECT COUNT(*) AS `count`, `idtbl_dispatch` FROM `tbl_dispatch` WHERE `porder_id`='$porderID' AND `status`=1";
$resultcheckdispatch=$conn->query($sqlcheckdispatch);
$rowcheckdispatch=$resultcheckdispatch->fetch_assoc();

if($rowcheckdispatch['count']>0){
    $updatedispatch="UPDATE `tbl_dispatch` SET `driver_id`='$driverID',`officer_id`='$officerID' WHERE `porder_id`='$porderID'";
    if($conn->query($updatedispatch)==true){
        $dispatchID=$rowcheckdispatch['idtbl_dispatch'];

        $deletehelper="DELETE FROM `tbl_employee_has_tbl_dispatch` WHERE `tbl_dispatch_idtbl_dispatch`='$dispatchID'";
        $conn->query($deletehelper);

        foreach($helperID as $rowhelperlist){
            $inserthelper="INSERT INTO `tbl_employee_has_tbl_dispatch`(`tbl_employee_idtbl_employee`, `tbl_dispatch_idtbl_dispatch`) VALUES ('$rowhelperlist','$dispatchID')";
            $conn->query($inserthelper);
        }

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
            $emptyprice=$rowtabledata['col_5'];
            $newsaleprice=$rowtabledata['col_6'];
            $refillsaleprice=$rowtabledata['col_7'];
            $fillqty=$rowtabledata['col_8'];
            $newqty=$rowtabledata['col_9'];
            $emptyqty=$rowtabledata['col_10'];
            $reqty=$rowtabledata['col_11'];
            $trustqty=$rowtabledata['col_12'];
            $saftyqty=$rowtabledata['col_13'];
            $saftyreturnqty=$rowtabledata['col_14'];
            $total=$rowtabledata['col_15'];

            $insertdispatchdetail="INSERT INTO `tbl_dispatch_detail`(`type`, `refillqty`, `returnqty`, `newqty`, `emptyqty`, `trustqty`, `saftyqty`, `saftyreturnqty`, `unitprice`, `refillprice`,`emptyprice`, `newsaleprice`, `refillsaleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_dispatch_idtbl_dispatch`, `tbl_product_idtbl_product`) VALUES ('0','$fillqty','$reqty','$newqty','$emptyqty','$trustqty','$saftyqty','$saftyreturnqty','$unitprice','$refillprice','$emptyprice','$newsaleprice','$refillsaleprice','1','$updatedatetime','$userID','$dispatchID','$product')";
            $conn->query($insertdispatchdetail);

            $netqty=$netqty+($fillqty+$newqty+$reqty+$trustqty+$saftyqty+$saftyreturnqty);

            // Empty update in stock
            if($fillqty>0){
                $updatestock="UPDATE `tbl_stock` SET `emptyqty`=(`emptyqty`-'$fillqty') WHERE `tbl_product_idtbl_product`='$product'";
                $conn->query($updatestock);
            }
            //Trust return update in trsut stock
            if($reqty>0){
                $updatetruststock="UPDATE `tbl_stock_trust` SET `trustqty`=(`trustqty`-'$reqty'), `saftyqty`=(`saftyqty`-'$saftyreturnqty') WHERE `tbl_product_idtbl_product`='$product'";
                $conn->query($updatetruststock);

                $updatestock="UPDATE `tbl_stock` SET `emptyqty`=(`emptyqty`-'$reqty') WHERE `tbl_product_idtbl_product`='$product'";
                $conn->query($updatestock);
            }    
            if($saftyreturnqty>0){
                $updatestock="UPDATE `tbl_stock` SET `emptyqty`=(`emptyqty`-'$saftyreturnqty') WHERE `tbl_product_idtbl_product`='$product'";
                $conn->query($updatestock);
            }       
        }

        $updatedispatch="UPDATE `tbl_dispatch` SET `netqty`='$netqty' WHERE `idtbl_dispatch`='$dispatchID'";
        $conn->query($updatedispatch);

        if($porderID!=0){
            $updateorder="UPDATE `tbl_porder` SET `dispatchissue`='1',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_porder`='$porderID'";
            $conn->query($updateorder);
        }

        $deletehelper="DELETE FROM `tbl_employee_has_tbl_dispatch` WHERE `tbl_dispatch_idtbl_dispatch`='$dispatchID'";
        $conn->query($deletehelper);

        foreach($helperID as $rowhelperlist){
            $inserthelper="INSERT INTO `tbl_employee_has_tbl_dispatch`(`tbl_employee_idtbl_employee`, `tbl_dispatch_idtbl_dispatch`) VALUES ('$rowhelperlist','$dispatchID')";
            $conn->query($inserthelper);
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