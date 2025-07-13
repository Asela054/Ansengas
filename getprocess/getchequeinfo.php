<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_cheque_info` WHERE `idtbl_cheque_info`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_cheque_info'];
$obj->startno=$row['startno'];
$obj->endno=$row['endno'];
$obj->bank=$row['tbl_bank_idtbl_bank'];
$obj->bankbranch=$row['tbl_bank_branch_idtbl_bank_branch'];
$obj->accountno=$row['tbl_bank_account_idtbl_bank_account'];

echo json_encode($obj);
?>