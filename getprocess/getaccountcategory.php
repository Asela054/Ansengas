<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_account_category` WHERE `idtbl_account_category`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_account_category'];
$obj->category=$row['category'];

echo json_encode($obj);
?>