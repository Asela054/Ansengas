<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT `tbl_master`.*, `tbl_company`.`idtbl_company` FROM `tbl_master` LEFT JOIN `tbl_company_branch` ON `tbl_company_branch`.`idtbl_company_branch`=`tbl_master`.`tbl_company_branch_idtbl_company_branch` LEFT JOIN `tbl_company` ON `tbl_company`.`idtbl_company`=`tbl_company_branch`.`tbl_company_idtbl_company` WHERE `tbl_master`.`idtbl_master`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_master'];
$obj->company=$row['idtbl_company'];
$obj->branch=$row['tbl_company_branch_idtbl_company_branch'];
$obj->year=$row['tbl_finacial_year_idtbl_finacial_year'];

echo json_encode($obj);
?>