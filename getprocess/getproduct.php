<?php
require_once('../connection/db.php');

$record=$_POST['recordID'];

$sql="SELECT * FROM `tbl_product` WHERE `idtbl_product`='$record'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$obj=new stdClass();
$obj->id=$row['idtbl_product'];
$obj->product_name=$row['product_name'];
$obj->productcode=$row['product_code'];
$obj->unitprice=$row['newprice'];
$obj->refillprice=$row['refillprice'];
$obj->emptyprice=$row['emptyprice'];
$obj->newsaleprice=$row['newsaleprice'];
$obj->refillsaleprice=$row['refillsaleprice'];
$obj->emptysaleprice=$row['emptysaleprice'];
$obj->category=$row['tbl_product_category_idtbl_product_category'];

echo json_encode($obj);
?>