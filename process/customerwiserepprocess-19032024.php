<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$customer=addslashes($_POST['customer']);
$product=addslashes($_POST['product']);
$salesrep=addslashes($_POST['salesrep']);
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_customerwise_salesrep`(`status`, `insertdatetime`, `updateuser`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`, `tbl_employee_idtbl_employee`) VALUES ('1','$updatedatetime','$userID','$customer','$product','$salesrep')";
    if($conn->query($insert)==true){        
        header("Location:../customerwiserep.php?action=4");
    }
    else{header("Location:../customerwiserep.php?action=5");}
}
else{
    $update="UPDATE `tbl_customerwise_salesrep` SET `updatedatetime`='$updatedatetime',`updateuser`='$userID' ,`tbl_customer_idtbl_customer`='$customer' ,`tbl_product_idtbl_product`='$product',`tbl_employee_idtbl_employee`='$salesrep' WHERE `idtbl_customerwise_salesrep`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../customerwiserep.php?action=6");
    }
    else{header("Location:../customerwiserep.php?action=5");}
}
?>