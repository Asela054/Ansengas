<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$cusName=addslashes($_POST['cusName']);
$cusType=$_POST['cusType'];
$cusMobile=$_POST['cusMobile'];
$cusNic=$_POST['cusNic'];
$address=$_POST['address'];
$cusVatNum=$_POST['cusVatNum'];
$cusSVat=$_POST['cusSVat'];

if(!empty($_POST['vat_status'])){$vatStatus=$_POST['vat_status'];}else{$vatStatus=0;}
if(!empty($_POST['special_customer'])){$specialcustomer=$_POST['special_customer'];}else{$specialcustomer=0;}
if(!empty($_POST['discounted_customer'])){$discountedcustomer=$_POST['discounted_customer'];}else{$discountedcustomer=0;}
if(!empty($_POST['special_area'])){$specialarea=$_POST['special_area'];}else{$specialarea=0;}
$cusEmail=$_POST['cusEmail'];

$cusArea=$_POST['cusArea'];
$cusNoVisit=$_POST['cusNoVisit'];
$cusVisitDays=$_POST['cusVisitDays'];
$cusCreditlimit=$_POST['cusCreditlimit'];

if(!empty($_POST['cuscredittype'])){$cuscredittype=$_POST['cuscredittype'];}else{$cuscredittype=0;}
$cuscreditdays=$_POST['cuscreditdays'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $query = "INSERT INTO `tbl_customer`(`type`, `name`, `nic`, `phone`, `email`, `address`, `vat_status`, `discount_status`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit`, `credittype`, `creditperiod`, `emergencydate`, `specialcus_status`, `main_area`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('$cusType', '$cusName', '$cusNic', '$cusMobile', '$cusEmail', '$address', '$vatStatus', '$discountedcustomer','$cusVatNum', '$cusSVat', '$cusNoVisit', '$cusCreditlimit','$cuscredittype','$cuscreditdays','', '$specialcustomer', '$specialarea', '1','$updatedatetime', '$userID', '$cusArea')";
    if($conn->query($query)==true){
        $customerID=$conn->insert_id;

        foreach($cusVisitDays AS $rowdays){
            $insertdayslist="INSERT INTO `tbl_customer_visitdays`(`dayname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$rowdays','1','$updatedatetime','$userID','$customerID')";
            $conn->query($insertdayslist);
        }

        if($cusType==2){
            $insertproductlist="INSERT INTO `tbl_customer_product`(`newsaleprice`, `refillsaleprice`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_customer_idtbl_customer`) SELECT `newsaleprice`, `refillsaleprice`, 1, '$updatedatetime', $userID, `idtbl_product`, $customerID FROM `tbl_product` WHERE `status`=1";
            $conn->query($insertproductlist);
        }

        header("Location:../customer.php?action=4");
    }
    else{
        header("Location:../customer.php?action=5");
    }
}
else{
    $update="UPDATE `tbl_customer` SET `type`='$cusType',`name`='$cusName',`nic`='$cusNic',`phone`='$cusMobile',`email`='$cusEmail',`address`='$address',`vat_status`='$vatStatus',`discount_status`='$discountedcustomer',`vat_num`='$cusVatNum',`s_vat`='$cusSVat',`numofvisitdays`='$cusNoVisit',`creditlimit`='$cusCreditlimit',`credittype`='$cuscredittype',`creditperiod`='$cuscreditdays',`specialcus_status`='$specialcustomer',`main_area`='$specialarea',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_area_idtbl_area`='$cusArea' WHERE `idtbl_customer`='$recordID'";
    if($conn->query($update)==true){
        $deletedayslist="DELETE FROM `tbl_customer_visitdays` WHERE `tbl_customer_idtbl_customer`='$recordID'";
        $conn->query($deletedayslist);

        foreach($cusVisitDays AS $rowdays){
            $insertdayslist="INSERT INTO `tbl_customer_visitdays`(`dayname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$rowdays','1','$updatedatetime','$userID','$recordID')";
            $conn->query($insertdayslist);
        }

        header("Location:../customer.php?action=6");
    }
    else{
        header("Location:../customer.php?action=5");
    }
}

?>