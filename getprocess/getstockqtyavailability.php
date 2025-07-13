<?php 
require_once('../connection/db.php');

$productID=$_POST['productID'];
$qty=$_POST['qty'];
$typeID=$_POST['typeID'];

if($typeID==1){
    $sql="SELECT `emptyqty` FROM `tbl_stock` WHERE `tbl_product_idtbl_product`='$productID' AND `status`=1";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();

    if($row['emptyqty']<$qty){
        echo '1';
    }
    else{
        echo '0';
    }
}
else if($typeID==2){
    $sql="SELECT `returnqty` FROM `tbl_stock_trust` WHERE `tbl_product_idtbl_product`='$productID' AND `status`=1";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();

    if($row['returnqty']<$qty){
        echo '1';
    }
    else{
        echo '0';
    }
}
else if($typeID==3){
    $sql="SELECT `saftyreturnqty` FROM `tbl_stock_trust` WHERE `tbl_product_idtbl_product`='$productID' AND `status`=1";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();

    if($row['saftyreturnqty']<$qty){
        echo '1';
    }
    else{
        echo '0';
    }
}
else if($typeID==4){
    $sql="SELECT `fullqty` FROM `tbl_stock` WHERE `tbl_product_idtbl_product`='$productID' AND `status`=1";
    $result=$conn->query($sql);
    $row=$result->fetch_assoc();

    if($row['fullqty']<$qty){
        echo '1';
    }
    else{
        echo '0';
    }
}