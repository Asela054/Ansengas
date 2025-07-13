<?php 
require_once('../connection/db.php');

$pettyaccount=$_POST['pettyaccount'];

$sqlopenbal="SELECT `closebal` FROM `tbl_pettycash_reimburse` WHERE `status`=1 AND `tbl_subaccount_idtbl_subaccount` IN (SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$pettyaccount' AND `status`=1) ORDER BY `idtbl_pettycash_reimburse` DESC LIMIT 1";
$resultopenbal=$conn->query($sqlopenbal);
$rowopenbal = $resultopenbal-> fetch_assoc();

$sqlnonpostbal="SELECT SUM(`amount`) AS `amount` FROM `tbl_pettycash` WHERE `status`=1 AND `poststatus`=0 AND `tbl_subaccount_idtbl_subaccount` IN (SELECT `idtbl_subaccount` FROM `tbl_subaccount` WHERE `subaccount`='$pettyaccount' AND `status`=1)";
$resultnonpostbal=$conn->query($sqlnonpostbal);
$rownonpostbal = $resultnonpostbal-> fetch_assoc();

if(!empty($rownonpostbal['amount'])){
    $notpostbal=$rownonpostbal['amount'];
}
else{
    $notpostbal=0;
}

$obj=new stdClass();
$obj->openbal=$rowopenbal['closebal'];
$obj->openbalshow=number_format($rowopenbal['closebal'], 2);
$obj->nonpost=$notpostbal;
$obj->nonpostshow=number_format($notpostbal, 2);
$obj->closebal=($rowopenbal['closebal']-$rownonpostbal['amount']);
$obj->closebalshow=number_format(($rowopenbal['closebal']-$rownonpostbal['amount']), 2);

echo json_encode($obj);

