<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$product_name = $_POST['productName'];
$productcode = $_POST['productcode'];
$unitprice = $_POST['unitprice'];
$refillprice = $_POST['refillprice'];
$newsaleprice = $_POST['newsaleprice'];
$refillsaleprice = $_POST['refillsaleprice'];
$emptyprice = $_POST['emptyprice'];
$category = $_POST['category'];

$updatedatetime=date('Y-m-d h:i:s');
$today=date('Y-m-d');

if($recordOption==1){
    $query = "INSERT INTO `tbl_product`(`product_code`, `product_name`, `size`, `unitprice`, `refillprice`, `newsaleprice`, `refillsaleprice`, `emptyprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_category_idtbl_product_category`) Values ('$productcode','$product_name','','$unitprice','$refillprice','$newsaleprice','$refillsaleprice','$emptyprice','1','$updatedatetime','$userID','$category')";
    if($conn->query($query)==true){
        $productID=$conn->insert_id;

        $insertstock="INSERT INTO `tbl_stock`(`fullqty`, `emptyqty`, `damageqty`, `update`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('0','0','0','$today','1','$updatedatetime','$userID','$productID')";
        $conn->query($insertstock);

        $inserttruststock="INSERT INTO `tbl_stock_trust`(`trustqty`, `returnqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`) VALUES ('0','0','1','$updatedatetime','$userID','$productID')";
        $conn->query($inserttruststock);

        $sqlcuslist="SELECT `idtbl_customer` FROM `tbl_customer` WHERE `type` IN (2) AND `status` IN (1, 2)";
        $resultcuslist=$conn->query($sqlcuslist);
        while ($rowcuslist = $resultcuslist-> fetch_assoc()) {
            $customerID=$rowcuslist['idtbl_customer'];

            $insertproductsale="INSERT INTO `tbl_customer_product`(`newsaleprice`, `refillsaleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_idtbl_customer`) VALUES ('$newsaleprice','$refillsaleprice','1','$updatedatetime','$userID','$productID','$customerID')";
            $conn->query($insertproductsale);
        }

        header("Location:../product.php?action=4");
    }
    else{header("Location:../product.php?action=5");}
}
else{
    $query = "UPDATE `tbl_product` SET `product_code`='$productcode',`product_name`='$product_name',`unitprice`='$unitprice',`refillprice`='$refillprice',`newsaleprice`='$newsaleprice',`refillsaleprice`='$refillsaleprice',`emptyprice`='$emptyprice',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID', `tbl_product_category_idtbl_product_category`='$category' WHERE `idtbl_product`='$recordID'";
    if($conn->query($query)==true){
        $sqlcuslist="SELECT `idtbl_customer` FROM `tbl_customer` WHERE `type` IN (2) AND `status` IN (1, 2)";
        $resultcuslist=$conn->query($sqlcuslist);
        while ($rowcuslist = $resultcuslist-> fetch_assoc()) {
            $customerID=$rowcuslist['idtbl_customer'];

            $updateproductsale="UPDATE `tbl_customer_product` SET `newsaleprice`='$newsaleprice',`refillsaleprice`='$refillsaleprice',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_product_idtbl_product`='$recordID' AND `tbl_customer_idtbl_customer`='$customerID'";
            $conn->query($updateproductsale);
        }

        header("Location:../product.php?action=6");
    }
    else{header("Location:../product.php?action=5");}
}
?>