<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_bank` WHERE `idtbl_bank`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_bank'];
$obj->name=$row['bankname'];
$obj->code=$row['code'];
echo json_encode($obj);
?>