<?php
require_once('dbConnect.php');

$lorryId = $_POST['lorryId'];

$arrayinvoice = array();

$sqlvehcleload = "SELECT * FROM `tbl_vehicle_load` WHERE `lorryid`='$lorryId' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
$resultvehcleload = mysqli_query($con, $sqlvehcleload);
$rowvehcleload = mysqli_fetch_row($resultvehcleload);

$idrootId = $rowvehcleload[12];
$allCusStatus = $rowvehcleload[8];
if ($allCusStatus == '0') {

    $sql = "select cusProDet.*,tbl_customer_stock.fullqty FROM ((SELECT tbl_customer_product.idtbl_customer_product,tbl_customer_product.newsaleprice,tbl_customer_product.refillsaleprice,tbl_customer_product.tbl_product_idtbl_product,tbl_customer_product.tbl_customer_idtbl_customer FROM tbl_customer_product INNER JOIN tbl_customer ON tbl_customer_product.tbl_customer_idtbl_customer=tbl_customer.idtbl_customer WHERE tbl_customer.tbl_area_idtbl_area='$idrootId' AND tbl_customer_product.status='1') AS cusProDet) LEFT JOIN tbl_customer_stock ON cusProDet.tbl_customer_idtbl_customer=tbl_customer_stock.tbl_customer_idtbl_customer AND cusProDet.tbl_product_idtbl_product=tbl_customer_stock.tbl_product_idtbl_product";
    $res = mysqli_query($con, $sql);
    $result = array();

    while ($row = mysqli_fetch_array($res)) {
        $bufferStk = $row['fullqty'];
        if ($bufferStk == null) {
            $bufferStk = "0";
        }
        array_push($result, array("id" => $row['idtbl_customer_product'], "newsaleprice" => $row['newsaleprice'], "refillsaleprice" => $row['refillsaleprice'], "bufferQty" => $row['fullqty'], "tbl_product_idtbl_product" => $row['tbl_product_idtbl_product'], "tbl_customer_idtbl_customer" => $row['tbl_customer_idtbl_customer']));
    }
}else{

    $sql = "select cusProDet.*,tbl_customer_stock.fullqty FROM ((SELECT tbl_customer_product.idtbl_customer_product,tbl_customer_product.newsaleprice,tbl_customer_product.refillsaleprice,tbl_customer_product.tbl_product_idtbl_product,tbl_customer_product.tbl_customer_idtbl_customer FROM tbl_customer_product INNER JOIN tbl_customer ON tbl_customer_product.tbl_customer_idtbl_customer=tbl_customer.idtbl_customer WHERE tbl_customer_product.status='1') AS cusProDet) LEFT JOIN tbl_customer_stock ON cusProDet.tbl_customer_idtbl_customer=tbl_customer_stock.tbl_customer_idtbl_customer AND cusProDet.tbl_product_idtbl_product=tbl_customer_stock.tbl_product_idtbl_product";
    $res = mysqli_query($con, $sql);
    $result = array();

    while ($row = mysqli_fetch_array($res)) {
        $bufferStk = $row['fullqty'];
        if ($bufferStk == null) {
            $bufferStk = "0";
        }
        array_push($result, array("id" => $row['idtbl_customer_product'], "newsaleprice" => $row['newsaleprice'], "refillsaleprice" => $row['refillsaleprice'], "bufferQty" => $row['fullqty'], "tbl_product_idtbl_product" => $row['tbl_product_idtbl_product'], "tbl_customer_idtbl_customer" => $row['tbl_customer_idtbl_customer']));
    }

}

print(json_encode($result));
mysqli_close($con);
