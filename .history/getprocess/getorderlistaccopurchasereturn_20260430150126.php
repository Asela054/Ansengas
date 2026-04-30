<?php
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sqlorderdetail="SELECT 
    `tbl_purchase_return_detail`.`emptyqty`,
    `tbl_purchase_return_detail`.`refillqty`,
    `tbl_purchase_return_detail`.`emptyprice_withoutvat`,
    `tbl_purchase_return_detail`.`refillprice_withoutvat`,
    `tbl_purchase_return_detail`.`emptyprice`,
    `tbl_purchase_return_detail`.`refillprice`,
    `tbl_product`.`product_name`,
    `tbl_product`.`idtbl_product` 
FROM `tbl_purchase_return_detail` 
LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_purchase_return_detail`.`tbl_product_idtbl_product` 
WHERE `tbl_purchase_return_detail`.`status`=1 
AND `tbl_purchase_return_detail`.`tbl_purchase_return_idtbl_purchase_return`='$orderID'";

$resultorderdetail=$conn->query($sqlorderdetail);

$sqlorder="SELECT `nettotal`, `remark`, `invoicenum`, `date` FROM `tbl_purchase_return` WHERE `idtbl_purchase_return`='$orderID'";
$resultorder=$conn->query($sqlorder);
$roworder=$resultorder->fetch_assoc();

$detailarray=array();
while($roworderdetail=$resultorderdetail->fetch_assoc()){
    $totempty=$roworderdetail['emptyqty']*$roworderdetail['emptyprice'];
    $totrefill=$roworderdetail['refillqty']*$roworderdetail['refillprice'];
    $total=number_format(($totempty+$totrefill), 2);

    $objdetail=new stdClass();
    $objdetail->productname=$roworderdetail['product_name'];
    $objdetail->productid=$roworderdetail['idtbl_product'];
    $objdetail->emptyqty=$roworderdetail['emptyqty'];
    $objdetail->refillqty=$roworderdetail['refillqty'];
    $objdetail->emptyprice=$roworderdetail['emptyprice'];
    $objdetail->refillprice=$roworderdetail['refillprice'];
    $objdetail->total=$total;

    array_push($detailarray, $objdetail);
}

$obj=new stdClass();
$obj->remark=$roworder['remark'];
$obj->invoicenum=$roworder['invoicenum'];
$obj->date=$roworder['date'];
$obj->nettotalshow=number_format($roworder['nettotal'], 2);
$obj->nettotal=$roworder['nettotal'];
$obj->tablelist=$detailarray;

echo json_encode($obj);

?>