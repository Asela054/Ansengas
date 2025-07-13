<?php
require_once('../connection/db.php');

$orderID=$_POST['orderID'];

$sqlorderdetail="SELECT `tbl_credit_note_detail`.`emptyprice`,`tbl_credit_note_detail`.`emptyqty`,`tbl_credit_note_detail`.`balanceqty`,`tbl_product`.`product_name`,`tbl_product`.`idtbl_product` FROM `tbl_credit_note_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_credit_note_detail`.`tbl_product_idtbl_product` WHERE `tbl_credit_note_detail`.`status`=1 AND `tbl_credit_note_detail`.`tbl_credit_note_idtbl_credit_note`='$orderID'";
$resultorderdetail=$conn->query($sqlorderdetail);

$sqlorder="SELECT `nettotal`, `remark` FROM `tbl_credit_note` WHERE `idtbl_credit_note`='$orderID'";
$resultorder=$conn->query($sqlorder);
$roworder=$resultorder->fetch_assoc();

$detailarray=array();
while($roworderdetail=$resultorderdetail->fetch_assoc()){
    $totempty=$roworderdetail['emptyqty']*$roworderdetail['emptyprice'];
    $total=number_format(($totempty), 2);

    $objdetail=new stdClass();
    $objdetail->productname=$roworderdetail['product_name'];
    $objdetail->productid=$roworderdetail['idtbl_product'];
    $objdetail->emptyqty=$roworderdetail['emptyqty'];
    $objdetail->balanceqty=$roworderdetail['balanceqty'];
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