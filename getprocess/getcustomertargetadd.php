<?php 
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT 
        `u`.`idtbl_cutomer_target`,
        `u`.`month`,
        `u`.`targettank`,
        `u`.`tbl_customer_idtbl_customer`,
        `u`.`tbl_product_idtbl_product`,
        `ua`.`name`
    FROM `tbl_cutomer_target` AS `u` 
    LEFT JOIN `tbl_customer` AS `ua` ON (`ua`.`idtbl_customer` = `u`.`tbl_customer_idtbl_customer`)
    WHERE `idtbl_cutomer_target`='$record'";

$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_cutomer_target'];
$obj->month=date("Y-m", strtotime($row['month']));
$obj->targettank=$row['targettank'];
$obj->customer=$row['tbl_customer_idtbl_customer'];
$obj->customerName=$row['name'];
$obj->product=$row['tbl_product_idtbl_product'];

echo json_encode($obj);
?>