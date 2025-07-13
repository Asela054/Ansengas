<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$recordOption=$_POST['recordOption'];
if(!empty($_POST['recordID'])){$recordID=$_POST['recordID'];}
$cusName=addslashes($_POST['cmpName']);
$cusType=$_POST['cusType'];
$pv_num=$_POST['pv_num'];
$owner_name=$_POST['owner_name'];
$owner_dob=$_POST['owner_dob'];
$owner_address=$_POST['ownAddress'];
$cusContact=$_POST['cusContact'];
$cusNic=$_POST['cusNic'];
$address=$_POST['cmpAddress'];
$TaxNum=$_POST['cusTaxNum'];
$alias_name=$_POST['alias_name'];
if(!empty($_POST['feqno'])){$feqno=$_POST['feqno'];}

if(!empty($_POST['vat_status'])){$vatStatus=$_POST['vat_status'];}else{$vatStatus=0;}
if(!empty($_POST['special_customer'])){$specialcustomer=$_POST['special_customer'];}else{$specialcustomer=0;}
if(!empty($_POST['discounted_customer'])){$discountedcustomer=$_POST['discounted_customer'];}else{$discountedcustomer=0;}
if(!empty($_POST['special_area'])){$specialarea=$_POST['special_area'];}else{$specialarea=0;}
$cusEmail=$_POST['cusEmail'];

$cusArea=$_POST['cusArea'];
$cusNoVisit=$_POST['cusNoVisit'];
if(!empty($_POST['cusVisitDays'])){$cusVisitDays=$_POST['cusVisitDays'];}
$cusCreditlimit=$_POST['cusCreditlimit'];
$tax_cus_name=$_POST['tax_cus_name'];
if(!empty($_POST['cusAreaOther'])){$cusAreaOther=$_POST['cusAreaOther'];}

if(!empty($_POST['cuscredittype'])){$cuscredittype=$_POST['cuscredittype'];}else{$cuscredittype=0;}
$cuscreditdays=$_POST['cuscreditdays'];

$updatedatetime=date('Y-m-d h:i:s');

if($recordOption==1){
    $sqlnextfeq="SELECT COALESCE(MAX(feqno) + 1, 1) AS next_feqno FROM tbl_customer WHERE tbl_area_idtbl_area = '$cusArea' AND `status`=1";
    $resultnextfeq = $conn->query($sqlnextfeq);
    $rownextfeq = $resultnextfeq->fetch_assoc();

    $nextfeqno=$rownextfeq['next_feqno'];

    $query = "INSERT INTO `tbl_customer`(`type`, `name`, `alias_name`, `pv_num`,`owner_name`,`owner_dob`,`nic`, `phone`, `email`, `address`, `owner_address`,`tax_cus_name`,`vat_status`, `discount_status`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit`, `credittype`, `creditperiod`, `emergencydate`, `specialcus_status`, `main_area`, `feqno`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`) VALUES ('$cusType', '$cusName', '$alias_name','$pv_num','$owner_name','$owner_dob', '$cusNic', '$cusContact', '$cusEmail', '$address','$owner_address','$tax_cus_name', '$vatStatus', '$discountedcustomer','$TaxNum', '0', '$cusNoVisit', '$cusCreditlimit','$cuscredittype','$cuscreditdays','', '$specialcustomer', '$specialarea', '$nextfeqno', '1','$updatedatetime', '$userID', '$cusArea')";
    if($conn->query($query)==true){
        $customerID=$conn->insert_id;

        if(!empty($_POST['cusVisitDays'])){
            foreach($cusVisitDays AS $rowdays){
                $insertdayslist="INSERT INTO `tbl_customer_visitdays`(`dayname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$rowdays','1','$updatedatetime','$userID','$customerID')";
                $conn->query($insertdayslist);
            }
        }

        if(!empty($_POST['cusAreaOther'])){
            foreach($cusAreaOther as $rowcusAreaOther){
                $insertotherarea="INSERT INTO `tbl_customer_other_route`(`status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_area_idtbl_area`) VALUES ('1','$updatedatetime','$userID','$customerID','$rowcusAreaOther')";
                $conn->query($insertotherarea);
            }
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
    $update="UPDATE `tbl_customer` SET `type`='$cusType',`name`='$cusName',`alias_name`='$alias_name',`pv_num`='$pv_num',`owner_name`='$owner_name',`owner_dob`='$owner_dob',`nic`='$cusNic',`phone`='$cusContact',`email`='$cusEmail',`address`='$address',`owner_address`='$owner_address',`tax_cus_name`='$tax_cus_name',`vat_status`='$vatStatus',`discount_status`='$discountedcustomer',`vat_num`='$TaxNum',`s_vat`='0',`numofvisitdays`='$cusNoVisit',`creditlimit`='$cusCreditlimit',`credittype`='$cuscredittype',`creditperiod`='$cuscreditdays',`specialcus_status`='$specialcustomer',`main_area`='$specialarea',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID',`tbl_area_idtbl_area`='$cusArea' WHERE `idtbl_customer`='$recordID'";
    if($conn->query($update)==true){
        //Feq number part start 10-02-2025
        $arraycustomer=array();
        $sqlcheckfeqno="SELECT `feqno` FROM `tbl_customer` WHERE `idtbl_customer`='$recordID'";
        $resultcheckfeqno = $conn->query($sqlcheckfeqno);
        $rowcheckfeqno = $resultcheckfeqno->fetch_assoc();
        if($rowcheckfeqno['feqno']!=$feqno){
            $sqlgetall="SELECT `idtbl_customer`, `feqno` FROM `tbl_customer` WHERE `tbl_area_idtbl_area`='$cusArea'";
            $resultgetall = $conn->query($sqlgetall);
            while($rowgetall = $resultgetall->fetch_assoc()){
                $arraycustomer[$rowgetall['idtbl_customer']]=$rowgetall['feqno'];
            }

            // print_r($arraycustomer);
            // echo '<br>';
            updateFeqNo($arraycustomer, $recordID, $feqno);
            // print_r($arraycustomer);
            
            foreach($arraycustomer as $key => $value) {
                $updatefeqno="UPDATE `tbl_customer` SET `feqno`='$value',`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `idtbl_customer`='$key'";
                $conn->query($updatefeqno);
            }
        }
        //Feq number part end 10-02-2025

        $deletedayslist="DELETE FROM `tbl_customer_visitdays` WHERE `tbl_customer_idtbl_customer`='$recordID'";
        $conn->query($deletedayslist);

        if(!empty($_POST['cusVisitDays'])){
            foreach($cusVisitDays AS $rowdays){
                $insertdayslist="INSERT INTO `tbl_customer_visitdays`(`dayname`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`) VALUES ('$rowdays','1','$updatedatetime','$userID','$recordID')";
                $conn->query($insertdayslist);
            }
        }

        $deleteotherarea="DELETE FROM `tbl_customer_other_route` WHERE `tbl_customer_idtbl_customer`='$recordID'";
        $conn->query($deleteotherarea);

        if(!empty($_POST['cusAreaOther'])){
            foreach($cusAreaOther as $rowcusAreaOther){
                $insertotherarea="INSERT INTO `tbl_customer_other_route`(`status`, `insertdatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_area_idtbl_area`) VALUES ('1','$updatedatetime','$userID','$recordID','$rowcusAreaOther')";
                $conn->query($insertotherarea);
            }
        }

        header("Location:../customer.php?action=6");
    }
    else{
        header("Location:../customer.php?action=5");
    }
}


function updateFeqNo(&$customers, $customerID, $newFeqNo) {
    if (!isset($customers[$customerID])) {
        throw new Exception("Customer $customerID not found.");
    }

    $oldFeqNo = $customers[$customerID];

    if ($oldFeqNo == $newFeqNo) {
        return;
    }

    $customers[$customerID] = $newFeqNo;

    foreach ($customers as $key => &$value) {
        if ($key == $customerID) {
            continue;
        }

        if ($newFeqNo > $oldFeqNo) {
            if ($value > $oldFeqNo && $value <= $newFeqNo) {
                $value--;
            }
        } else {
            if ($value >= $newFeqNo && $value < $oldFeqNo) {
                $value++;
            }
        }
    }

    asort($customers);
}
?>