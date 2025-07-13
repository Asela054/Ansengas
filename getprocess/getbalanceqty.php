<?php
require_once('../connection/db.php');

$productID=$_POST['productID'];
$hiddenID=$_POST['hiddenID'];

$sqlproduct="SELECT `balanceqty` FROM `tbl_credit_note_detail` WHERE `tbl_credit_note_idtbl_credit_note`='$hiddenID' AND `tbl_product_idtbl_product`='$productID'";
$resultproduct=$conn->query($sqlproduct);
$rowproduct=$resultproduct->fetch_assoc();

if($resultproduct-> num_rows > 0) {
    $obj=new stdClass();
    $obj->balanceqty=$rowproduct['balanceqty'];
}
else{
    $obj=new stdClass();
    $obj->balanceqty='0';
}
echo json_encode($obj);
?>