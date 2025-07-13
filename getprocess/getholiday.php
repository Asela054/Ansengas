<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_month_holiday` WHERE `idtbl_month_holiday`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_month_holiday'];
$obj->date=$row['date'];
$obj->title=$row['title'];

echo json_encode($obj);
?>