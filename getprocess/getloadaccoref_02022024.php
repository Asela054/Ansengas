<?php 
require_once('../connection/db.php');

$invdate=$_POST['invdate'];
$refID=$_POST['refID'];

$sql="SELECT `tbl_vehicle_load`.`idtbl_vehicle_load`, `tbl_vehicle`.`vehicleno` FROM `tbl_vehicle_load` LEFT JOIN `tbl_vehicle` ON `tbl_vehicle`.`idtbl_vehicle`=`tbl_vehicle_load`.`lorryid` WHERE `refid`='$refID' AND `tbl_vehicle_load`.`status`=1 AND `date`='$invdate' AND `approvestatus`=1 AND `unloadstatus`=0";
$result=$conn->query($sql);

$arraylist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->id=$row['idtbl_vehicle_load'];
    $obj->vehicle=$row['vehicleno'];
    
    array_push($arraylist, $obj);
}

echo json_encode($arraylist);
?>