<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

// $invdate=$_POST['invdate'];
$specialcustomer=$_POST['specialcustomer'];
$vehicleloadid=$_POST['vehicleloadid'];
$recordstatus=1;
$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

// print_r($specialcustomer);
$sqlvehicleload="SELECT `date` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadid'";
$resultvehicleload=$conn->query($sqlvehicleload);
$rowvehicleload=$resultvehicleload->fetch_assoc();

$load=$rowvehicleload['date'];

$delete="DELETE FROM `tbl_customer_special_route` WHERE `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadid'";
$conn->query($delete);

foreach($specialcustomer as $rowcustomerlist){
    $insert="INSERT INTO `tbl_customer_special_route`(`date`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_vehicle_load_idtbl_vehicle_load`, `tbl_customer_idtbl_customer`) VALUES ('$load','1','$updatedatetime','$userID','$vehicleloadid','$rowcustomerlist')";
    if($conn->query($insert)==true){
        $recordstatus=1;
    }
    else{
        $recordstatus=0;
        break;
    }
}

if($recordstatus==1){
    header("Location:../loading.php?action=4");
}
else{header("Location:../loading.php?action=5");}
?>