<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$employee=$_POST['employee'];
$targetmonth=$_POST['targetmonth'];
$targetmonth = date("Y-m-d", strtotime($targetmonth));
$target=$_POST['target'];
$product=$_POST['product'];
$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $insert="INSERT INTO `tbl_employee_target`(`month`, `targettank`, `targetcomplete`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_employee_idtbl_employee`, `tbl_product_idtbl_product`) VALUES ('$targetmonth','$target','0','1','$updatedatetime','$userID','$employee','$product')";
    if($conn->query($insert)==true){        
        header("Location:../employeetargetadd.php?action=4");
    }
    else{header("Location:../employeetargetadd.php?action=5");}
}
else{
    $update="UPDATE `tbl_employee_target` SET `month`='$targetmonth',`targettank`='$target',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_employee_idtbl_employee`='$employee',`tbl_product_idtbl_product`='$product' WHERE `idtbl_employee_target`='$recordID'";
    if($conn->query($update)==true){     
        header("Location:../employeetargetadd.php?action=6");
    }
    else{header("Location:../employeetargetadd.php?action=5");}
}
?>