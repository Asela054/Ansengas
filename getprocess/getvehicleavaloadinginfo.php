<?php 
require_once('../connection/db.php');

$lorryID=$_POST['lorryID'];

$sql="SELECT COUNT(*) AS `count` FROM `tbl_vehicle_load` WHERE `status`=1 AND `lorryid`='$lorryID' AND `unloadstatus`=0";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

if($row['count']==0){
    echo '0';
}
else{
    echo '1';
}
?>