<?php
require_once('dbConnect.php');

$refID=$_POST['refID'];
$customerid=$_POST['customerid'];
$vehicleloadid=$_POST['vehicleloadid'];
$rejectreasonid=$_POST['rejectreasonid'];

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$flag = true;

$insertreject="INSERT INTO `tbl_customer_shop_close`(`date`, `status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`, `tbl_reject_reason_idtbl_reject_reason`) VALUES ('$today','1','$updatedatetime','$refID','$customerid','$vehicleloadid','$rejectreasonid')";
if($con->query($insertreject)==true){
    $con->commit();
    $response = array("code" => '200', "message" => 'Record Insert Successfully.');
    print_r(json_encode($response));
}
else{
    $con->rollback();
    $response = array("code" => '500', "message" => 'Record Unsuccessfully.');
    print_r(json_encode($response));
}