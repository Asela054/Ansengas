<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$products=$_POST['product'];
$disvalue=addslashes($_POST['disvalue']);
$hiddenID=addslashes($_POST['hiddenID']);
$updatedatetime=date('Y-m-d h:i:s');

$success=true;
foreach($products as $productID){
    $productID=addslashes($productID);
    $insert="INSERT INTO `tbl_customer_product_special`(`discountprice`, `status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$disvalue','1','$updatedatetime','$userID','$hiddenID','$productID')";
    if($conn->query($insert)==true){        
        // Continue with next product
    }
    else{
        $success=false;
        break;
    }
}
if($success==true){        
    header("Location:../area.php?action=4");
}
else{header("Location:../area.php?action=5");}
?>