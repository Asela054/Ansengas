<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_finacial_year` WHERE `idtbl_finacial_year`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_finacial_year'];
$obj->year=$row['year'];
$obj->startdate=$row['startdate'];
$obj->enddate=$row['enddate'];
$obj->description=$row['desc'];

echo json_encode($obj);
?>