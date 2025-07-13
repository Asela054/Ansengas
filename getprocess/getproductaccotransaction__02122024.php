<?php
require_once('../connection/db.php');

$transID=$_POST['transID'];

$sqlproduct="SELECT `idtbl_product`,`product_name`,`qty` FROM `tbl_product` LEFT JOIN `tbl_tank_transaction` ON `tbl_product`.`idtbl_product`=`tbl_tank_transaction`.`tbl_product_idtbl_product` WHERE `idtbl_tank_transaction`='$transID' AND `issuestatus`=0";
$resultproduct=$conn->query($sqlproduct);
$rowproduct=$resultproduct->fetch_assoc();

if($resultproduct-> num_rows > 0) {
    $obj=new stdClass();
    $obj->id=$rowproduct['idtbl_product'];
    $obj->product=$rowproduct['product_name'];
    $obj->qty=$rowproduct['qty'];

}
echo json_encode($obj);
?>