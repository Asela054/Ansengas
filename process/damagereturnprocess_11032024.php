<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$customer = $_POST['customer'];
$returntype = $_POST['returntype'];
$product = $_POST['product'];
$hidecustomerid = $_POST['hiddencustomerid'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$salesrep = $_POST['salesrep'];
$vehicle = $_POST['vehicle'];
$area = $_POST['area'];
$qty = $_POST['qty'];
$today=date('Y-m-d');

$updatedatetime=date('Y-m-d h:i:s');

if(empty($hidecustomerid)){
    $querycustomer = "INSERT INTO `tbl_customer`(`type`,`name`, `phone`,`address`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('1','$name','$phone','$address','1','$updatedatetime','$userID','$area')";
    if($conn->query($querycustomer) === true) {
        $customer_id = $conn->insert_id;

        $querydamage = "INSERT INTO `tbl_damage_return`(`returntype`, `returndate`, `qty`, `comsendstatus`, `comsenddate`, `backstockstatus`, `backstockdate`, `returncusstatus`, `returncusdate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`, `tbl_vehicle_idtbl_vehicle`, `tbl_employee_idtbl_employee`) VALUES ('$returntype','$today','$qty','0','','0','','0','','1','$updatedatetime','$userID','$customer_id','$product','$vehicle','$salesrep')";
        if($conn->query($querydamage) === true) {
            header("Location:../damagereturn.php?action=4");
        } else {
            header("Location:../damagereturn.php?action=5");
        }
    } else {
        header("Location:../damagereturn.php?action=5");
    }
} else {
    if($recordOption == 1) {
        $querydamage = "INSERT INTO `tbl_damage_return`(`returntype`, `returndate`, `qty`, `comsendstatus`, `comsenddate`, `backstockstatus`, `backstockdate`, `returncusstatus`, `returncusdate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`, `tbl_vehicle_idtbl_vehicle`, `tbl_employee_idtbl_employee`) VALUES ('$returntype','$today','$qty','0','','0','','0','','1','$updatedatetime','$userID','$customer','$product','$vehicle','$salesrep')";
        if($conn->query($querydamage) === true) {
            header("Location:../damagereturn.php?action=4");
        } else {
            header("Location:../damagereturn.php?action=5");
        }
    } else {
        $queryupdate = "UPDATE `tbl_damage_return` SET `returntype`='$returntype',`qty`='$qty',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_customer_idtbl_customer`='$customer',`tbl_product_idtbl_product`='$product' WHERE `idtbl_damage_return`='$recordID'";
        if($conn->query($queryupdate) === true) {
            header("Location:../damagereturn.php?action=6");
        } else {
            header("Location:../damagereturn.php?action=5");
        }
    }
}
?>
