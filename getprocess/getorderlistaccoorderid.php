<?php
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sqlorderdetail="SELECT `tbl_porder_detail`.`unitprice`,`tbl_porder_detail`.`refillprice`,`tbl_porder_detail`.`emptyprice`,`tbl_porder_detail`.`newqty`,`tbl_porder_detail`.`refillqty`,`tbl_porder_detail`.`saftyqty`,`tbl_porder_detail`.`saftyreturnqty`,`tbl_porder_detail`.`emptyqty`,`tbl_porder_detail`.`trustqty`,`tbl_porder_detail`.`returnqty`,`tbl_product`.`product_name`,`tbl_product`.`idtbl_product` FROM `tbl_porder_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_porder_detail`.`tbl_product_idtbl_product` WHERE `tbl_porder_detail`.`status`=1 AND `tbl_porder_detail`.`tbl_porder_idtbl_porder`='$orderID'";
$resultorderdetail=$conn->query($sqlorderdetail);

$sqlorder="SELECT `nettotal`, `remark` FROM `tbl_porder` WHERE `idtbl_porder`='$orderID'";
$resultorder=$conn->query($sqlorder);
$roworder=$resultorder->fetch_assoc();

$detailarray=array();
while($roworderdetail=$resultorderdetail->fetch_assoc()){
    $totrefill=$roworderdetail['refillqty']*$roworderdetail['refillprice'];
    $totempty=$roworderdetail['emptyqty']*$roworderdetail['emptyprice'];
    $tottrust=$roworderdetail['trustqty']*$roworderdetail['refillprice'];
    $totnew=$roworderdetail['newqty']*$roworderdetail['unitprice'];
    $totsafty=$roworderdetail['saftyqty']*$roworderdetail['refillprice'];
    $total=number_format(($totrefill+$totempty+$totnew+$tottrust+$totsafty), 2);

    $objdetail=new stdClass();
    $objdetail->productname=$roworderdetail['product_name'];
    $objdetail->productid=$roworderdetail['idtbl_product'];
    $objdetail->unitprice=number_format($roworderdetail['unitprice'], 2);
    $objdetail->refillqty=$roworderdetail['refillqty'];
    $objdetail->emptyqty=$roworderdetail['emptyqty'];
    $objdetail->returnqty=$roworderdetail['returnqty'];
    $objdetail->newqty=$roworderdetail['newqty'];
    $objdetail->trustqty=$roworderdetail['trustqty'];
    $objdetail->safetyqty=$roworderdetail['saftyqty'];
    $objdetail->safetyreturnqty=$roworderdetail['saftyreturnqty'];
    $objdetail->total=$total;

    array_push($detailarray, $objdetail);
}

$obj=new stdClass();
$obj->remark=$roworder['remark'];
$obj->nettotalshow=number_format($roworder['nettotal'], 2);
$obj->nettotal=$roworder['nettotal'];
$obj->tablelist=$detailarray;

echo json_encode($obj);

?>