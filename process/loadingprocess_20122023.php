<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$lorryID=$_POST['lorryID'];
$areaID=$_POST['areaID'];
$driverID=$_POST['driverID'];
$officerID=$_POST['officerID'];
$refID=$_POST['refID'];
$helperID=$_POST['helpername'];
$tableData=$_POST['tableData'];

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$insertloading="INSERT INTO `tbl_vehicle_load`(`date`, `lorryid`, `driverid`, `officerid`, `refid`, `approvestatus`, `unloadstatus`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('$today','$lorryID','$driverID','$officerID','$refID','0','0','1','$updatedatetime','$userID','$areaID')";
if($conn->query($insertloading)==true){
    $loadingID=$conn->insert_id;

    foreach($tableData as $rowtabledata){
        $product=$rowtabledata['col_2'];
        $qty=$rowtabledata['col_3'];

        $insertloaddetail="INSERT INTO `tbl_vehicle_load_detail`(`qty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_vehicle_load_idtbl_vehicle_load`, `tbl_product_idtbl_product`) VALUES ('$qty','1','$updatedatetime','$userID','$loadingID','$product')";
        $conn->query($insertloaddetail);

        $updatestock="UPDATE `tbl_stock` SET `fullqty`=(`fullqty`-'$qty') WHERE `tbl_product_idtbl_product`='$product'";
        $conn->query($updatestock);
    }

    foreach($helperID as $helperlist){
        $inserthelperdetail="INSERT INTO `tbl_employee_has_tbl_vehicle_load`(`tbl_employee_idtbl_employee`, `tbl_vehicle_load_idtbl_vehicle_load`) VALUES ('$helperlist','$loadingID')";
        $conn->query($inserthelperdetail);
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