<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_main_area` WHERE `idtbl_main_area`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_main_area'];
$obj->area=$row['main_area'];

echo json_encode($obj);
?>