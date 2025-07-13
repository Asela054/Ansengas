<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_areawise_product` WHERE `idtbl_areawise_product`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_areawise_product'];
$obj->newprice=$row['newsaleprice'];
$obj->refillprice=$row['refillsaleprice'];
$obj->emptyprice=$row['emptysaleprice'];
$obj->encustomer_newprice=$row['encustomer_newprice'];
$obj->encustomer_refillprice=$row['encustomer_refillprice'];
$obj->encustomer_emptyprice=$row['encustomer_emptyprice'];
$obj->discount_price=$row['discount_price'];
$obj->product=$row['tbl_product_idtbl_product'];
$obj->mainarea=$row['tbl_main_area_idtbl_main_area'];

echo json_encode($obj);
?>