<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];
$sql="SELECT * FROM `tbl_subclass` WHERE `idtbl_subclass`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();


$obj=new stdClass();
$obj->id=$row['idtbl_subclass'];
$obj->subclass=$row['subclass'];
$obj->code=$row['code'];
$obj->mainclasscode=$row['mainclasscode'];
echo json_encode($obj);
?>