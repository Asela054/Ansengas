<?php
require_once('../connection/db.php');

$record=$_POST['orderID'];

$sql="SELECT * FROM `tbl_dispatch` WHERE `porder_id`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

if($result->num_rows>0){
    $obj=new stdClass();
    $obj->id=$row['idtbl_dispatch'];
    $obj->driverid=$row['driver_id'];
    $obj->officerid=$row['officer_id'];
}
else{
    $obj=new stdClass();
    $obj->id='0';
}

echo json_encode($obj);
?>