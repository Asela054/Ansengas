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
$ref_num = $_POST['ref_num'];
$srl_num = $_POST['srl_num'];
$area = $_POST['area'];
$qty = $_POST['qty'];
$today=date('Y-m-d');

$updatedatetime=date('Y-m-d h:i:s');

if(empty($hidecustomerid)) {
    $insertDamageQuery = "INSERT INTO `tbl_damage_return`(`returntype`, `returndate`, `qty`, `seriel_no`, `reference_no`, `comsendstatus`, `comsenddate`, `backstockstatus`, `backstockdate`, `returncusstatus`, `returncusdate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`, `tbl_vehicle_idtbl_vehicle`, `tbl_employee_idtbl_employee`) VALUES ('$returntype','$today','$qty','$srl_num','$ref_num','0','','0','','0','','1','$updatedatetime','$userID','848','$product','$vehicle','$salesrep')";

    if($conn->query($insertDamageQuery) === true) {
        $lastInsertId = $conn->insert_id;

        $insertDetailQuery = "INSERT INTO `tbl_damage_return_customer_detail`(`cusname`, `phone`, `address`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_damage_return_idtbl_damage_return`) VALUES ('$name','$phone','$address','1','$updatedatetime','$userID','$area','$lastInsertId')";

        if($conn->query($insertDetailQuery) === true) {
            header("Location:../damagereturn.php?action=4");
            exit;
        } else {
            header("Location:../damagereturn.php?action=5");
            exit;
        }
    } else {
        header("Location:../damagereturn.php?action=5");
        exit;
    }
}else {
    if($recordOption == 1) {
        $querydamage = "INSERT INTO `tbl_damage_return`(`returntype`, `returndate`, `qty`, `seriel_no`, `reference_no`, `comsendstatus`, `comsenddate`, `backstockstatus`, `backstockdate`, `returncusstatus`, `returncusdate`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`, `tbl_vehicle_idtbl_vehicle`, `tbl_employee_idtbl_employee`) VALUES ('$returntype','$today','$qty','$srl_num','$ref_num','0','','0','','0','','1','$updatedatetime','$userID','$customer','$product','$vehicle','$salesrep')";
        if($conn->query($querydamage) === true) {
            header("Location:../damagereturn.php?action=4");
        } else {
            header("Location:../damagereturn.php?action=5");
        }
    } else {
        $queryupdate = "UPDATE `tbl_damage_return` SET `returntype`='$returntype',`qty`='$qty',`seriel_no`='$srl_num',`reference_no`='$ref_num',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_customer_idtbl_customer`='$customer',`tbl_product_idtbl_product`='$product' WHERE `idtbl_damage_return`='$recordID'";
        if($conn->query($queryupdate) === true) {
            header("Location:../damagereturn.php?action=6");
        } else {
            header("Location:../damagereturn.php?action=5");
        }
    }
}
?>
