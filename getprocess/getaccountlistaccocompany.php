<?php 
require_once('../connection/db.php');

$company=$_POST['company'];
$companybranch=$_POST['companybranch'];

$sql="SELECT `subaccount`, `subaccountname` FROM `tbl_subaccount` WHERE `subaccount` IN (SELECT `subaccountno` FROM `tbl_account_allocation` WHERE `tbl_company_idtbl_company`='$company' AND `tbl_company_branch_idtbl_company_branch`='$companybranch' AND `status`=1) AND `status`=1 AND `tbl_account_category_idtbl_account_category`=2";
$result=$conn->query($sql);

$arrayaccountlist=array();
while($row=$result->fetch_assoc()){
    $obj=new stdClass();
    $obj->subaccount=$row['subaccount'];
    $obj->subaccountname=$row['subaccountname'];
    
    array_push($arrayaccountlist, $obj);
}

echo json_encode($arrayaccountlist);