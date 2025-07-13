<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_mainaccount` WHERE `idtbl_mainaccount`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_mainaccount'];
$obj->mainclasscode=$row['mainclasscode'];
$obj->subclasscode=$row['subclasscode'];
$obj->code=$row['code'];
$obj->accountname=$row['accountname'];
echo json_encode($obj);
?>