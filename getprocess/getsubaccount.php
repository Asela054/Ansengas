<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_subaccount` WHERE `idtbl_subaccount`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_subaccount'];
$obj->mainclasscode=$row['mainclasscode'];
$obj->subclasscode=$row['subclasscode'];
$obj->mainaccountcode=$row['mainaccountcode'];
$obj->code=$row['code'];
$obj->subaccountname=$row['subaccountname'];
$obj->accoutcategory=$row['tbl_account_category_idtbl_account_category'];
echo json_encode($obj);
?>