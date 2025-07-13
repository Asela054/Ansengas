<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_area` WHERE `idtbl_area`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_area'];
$obj->area=$row['area'];
$obj->mainarea=$row['tbl_main_area_idtbl_main_area'];

echo json_encode($obj);
?>