<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_employee` WHERE `idtbl_employee`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_employee'];
$obj->name=$row['name'];
$obj->dob=$row['dob'];
$obj->epfno=$row['epfno'];
$obj->nic=$row['nic'];
$obj->phone=$row['phone'];
$obj->address=$row['address'];
$obj->emptype=$row['tbl_user_type_idtbl_user_type'];
$obj->useraccount=$row['useraccount_id'];


echo json_encode($obj);
?>