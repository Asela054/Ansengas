<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$netqty=0;

$invoicedate=$_POST['invoicedate'];
$refID=$_POST['refID'];
$vehicleloadID=$_POST['vehicleloadID'];
$areaID=$_POST['areaID'];
$customerID=$_POST['customerID'];
$nettotal=$_POST['nettotal'];
$withouttaxtotal=$_POST['withouttaxtotal'];
$orderDetails = $_POST['orderDetails'];

$invoicemonth = date("n", strtotime($invoicedate));

$taxamount = $nettotal - $withouttaxtotal;

$today=date('Y-m-d');
$updatedatetime=date('Y-m-d h:i:s');

$sqlvat1 = "SELECT `tax_invoice_num` AS last_tax_invoice_num FROM `tbl_invoice` ORDER BY `last_tax_invoice_num` DESC LIMIT 1";
$resultvat1 = $conn->query($sqlvat1);
$rowvat1 = $resultvat1->fetch_assoc();

if ($rowvat1) {
    $lastTaxInvoiceNum = $rowvat1['last_tax_invoice_num'];
    $nextTaxInvoiceNum = $lastTaxInvoiceNum + 1;
} 

$sql = "SELECT `type`, `vat_status` FROM `tbl_customer` WHERE `idtbl_customer`='$customerID'";

if ($result = $conn->query($sql)) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customerType = $row['type'];
        $vat_status = $row['vat_status'];
    } 
} 

if($vat_status==1){
    $insertinvoice="INSERT INTO `tbl_invoice`(`tax_invoice_num`,`date`, `total`, `taxamount`, `nettotal`, `paymentmethod`, `paymentcomplete`, `chequesend`, `companydiffsend`, `ref_id`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`) VALUES ('$nextTaxInvoiceNum','$invoicedate','$withouttaxtotal','$taxamount','$nettotal','0','0','0','0','$refID','1','$updatedatetime','$userID','$areaID','$customerID','$vehicleloadID')";
}else{
    $insertinvoice="INSERT INTO `tbl_invoice`(`date`, `total`, `taxamount`, `nettotal`, `paymentmethod`, `paymentcomplete`, `chequesend`, `companydiffsend`, `ref_id`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`) VALUES ('$invoicedate','$withouttaxtotal','$taxamount','$nettotal','0','0','0','0','$refID','1','$updatedatetime','$userID','$areaID','$customerID','$vehicleloadID')";
}

if($conn->query($insertinvoice)==true){
    $invoiceID=$conn->insert_id;

    foreach ($orderDetails as $detail) {
        $product = $detail['productId'];
        $unitprice = $detail['unitprice'];
        $refillprice = $detail['refillprice'];
        $emptyprice = $detail['emptyprice'];
        $discountprice = $detail['discountprice'];
        $encustomernewprice = $detail['encustomernewprice'];
        $encustomerrefillprice = $detail['encustomerrefillprice'];
        $encustomeremptyprice = $detail['encustomeremptyprice'];
        $newQty = str_replace(',', '', $detail['newQty']);
        $refillQty = str_replace(',', '', $detail['refillQty']);
        $emptyQty = str_replace(',', '', $detail['emptyQty']);
        $trustQty = str_replace(',', '', $detail['trustQty']);
        $trustreturnQty = str_replace(',', '', $detail['trustreturnqty']);
    
        $newPrice = $refillPrice = $emptyPrice = $encustomernewPrice = $encustomerrefillPrice = $encustomeremptyPrice = $encustomerdiscountPrice = 0;

        if ($customerType == 1) {
            $encustomernewPrice = ($newQty > 0) ? $encustomernewprice : 0;
            $encustomerrefillPrice = ($refillQty > 0 || $trustQty > 0) ? $encustomerrefillprice : 0;
            $encustomeremptyPrice = ($emptyQty > 0) ? $encustomeremptyprice : 0;
            $encustomerdiscountPrice = $discountprice;
        } else {
            $newPrice = ($newQty > 0) ? $unitprice : 0;
            $refillPrice = ($refillQty > 0 || $trustQty > 0) ? $refillprice : 0;
            $emptyPrice = ($emptyQty > 0) ? $emptyprice : 0;
        }

        if ($newQty > 0 || $refillQty > 0 || $emptyQty > 0 || $trustQty > 0 || $trustreturnQty > 0) {
            $insertinvoicedetail = "INSERT INTO `tbl_invoice_detail` (`newqty`, `refillqty`, `emptyqty`, `trustqty`, `trustreturnqty`, `newprice`, `refillprice`, `emptyprice`, `encustomer_newprice`, `encustomer_refillprice`, `encustomer_emptyprice`, `discount_price`, `status`, `updatedatetime`,  `tbl_user_idtbl_user`, `tbl_product_idtbl_product`,  `tbl_invoice_idtbl_invoice`)  VALUES ('$newQty', '$refillQty', '$emptyQty', '$trustQty', '$trustreturnQty', '$newPrice', '$refillPrice', '$emptyPrice', '$encustomernewPrice','$encustomerrefillPrice','$encustomeremptyPrice','$encustomerdiscountPrice', '1', '$updatedatetime', '$userID', '$product', '$invoiceID')";

            $conn->query($insertinvoicedetail);
        }
    
        $totqty=$newQty+$refillQty+$trustQty;

        if ($totqty > 0) {
            $updatestock="UPDATE `tbl_vehicle_load_detail` SET `qty`=(`qty`- '$newQty' - '$refillQty'- '$trustQty'), `emptyqty` = (`emptyqty` + '$refillQty' - '$emptyQty') WHERE `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
            $conn->query($updatestock);
        } elseif ($emptyQty > 0) {
            $updatestock="UPDATE `tbl_vehicle_load_detail` SET `emptyqty` = (`emptyqty` - '$emptyQty') WHERE `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
            $conn->query($updatestock);
        } elseif ($trustreturnQty > 0) {
            $updatestock="UPDATE `tbl_vehicle_load_detail` SET `emptyqty` = (`emptyqty` + '$trustreturnQty') WHERE `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
            $conn->query($updatestock);
        }

        if($trustQty>0 | $trustreturnQty>0){
            $sqlcheckcus="SELECT COUNT(*) AS `count` FROM `tbl_cutomer_trustreturn` WHERE `tbl_customer_idtbl_customer`='' AND `tbl_product_idtbl_product`='' AND `status`=1";
            $resultcheckcus =$conn-> query($sqlcheckcus);
            $rowcheckcus = $resultcheckcus-> fetch_assoc();

            if($rowcheckcus['count']>0){
                $updatecustrust="UPDATE `tbl_cutomer_trustreturn` SET `trustqty`=(`trustqty`+'$trustQty'),`returnqty`=(`returnqty`+'$trustreturnQty'),`updatedatetime`='$updatedatetime',`tbl_user_idtbl_user`='$userID' WHERE `tbl_customer_idtbl_customer`='$customerID' AND `tbl_product_idtbl_product`='$product'";
                $conn->query($updatecustrust);
            }
            else{
                $insertcustrust="INSERT INTO `tbl_cutomer_trustreturn`(`trustqty`, `returnqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$trustQty','$trustreturnQty','1','$updatedatetime','$userID','$customerID','$product')";
                $conn->query($insertcustrust);
            }
        }

        //Target section
        $sqlcheckreftarget="SELECT COUNT(*) AS `count` FROM `tbl_employee_target` WHERE MONTH(`month`)='$invoicemonth' AND `status`=1 AND `tbl_product_idtbl_product`='$product' AND `tbl_employee_idtbl_employee`='$refID'";
        $resultcheckreftarget =$conn-> query($sqlcheckreftarget);
        $rowcheckreftarget = $resultcheckreftarget-> fetch_assoc();
        if($rowcheckreftarget['count']>0){
            $updatereftarget="UPDATE `tbl_employee_target` SET `targetcomplete`=(`targetcomplete`+$totqty) WHERE `tbl_employee_idtbl_employee`='$refID' AND `status`=1 AND MONTH(`month`)='$invoicemonth' AND `tbl_product_idtbl_product`='$product'";
            $conn->query($updatereftarget);
        }

        $sqlcheckvehitarget="SELECT COUNT(*) AS `count` FROM `tbl_vehicle_target` WHERE MONTH(`month`)='$invoicemonth' AND `status`=1 AND `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_idtbl_vehicle` IN (SELECT `lorryid` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadID' AND `status`=1)";
        $resultcheckvehitarget =$conn-> query($sqlcheckvehitarget);
        $rowcheckvehitarget = $resultcheckvehitarget-> fetch_assoc();
        if($rowcheckvehitarget['count']>0){
            $updatevehicletarget="UPDATE `tbl_vehicle_target` SET `targetcomplete`=(`targetcomplete`+$totqty) WHERE `status`=1 AND MONTH(`month`)='$invoicemonth' AND `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_idtbl_vehicle` IN (SELECT `lorryid` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$vehicleloadID' AND `status`=1)";
            $conn->query($updatevehicletarget);
        }
    }

    $arrayinvoicelist=array();
    $sqlinovicelist="SELECT `idtbl_invoice`, `total`, `paymentcomplete` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$invoicedate' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
    $resultinovicelist =$conn-> query($sqlinovicelist);
    while($rowinovicelist = $resultinovicelist-> fetch_assoc()){
        $objinvoice=new stdClass();
        $objinvoice->invoiceid=$rowinovicelist['idtbl_invoice'];
        $objinvoice->invoicetotal=$rowinovicelist['total'];
        $objinvoice->paystatus=$rowinovicelist['paymentcomplete'];

        array_push($arrayinvoicelist, $objinvoice);
    }

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-check-circle';
    $actionObj->title='';
    $actionObj->message='Add Successfully';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='success';

    $obj=new stdClass();
    $obj->action=json_encode($actionObj);
    $obj->invicelist=$arrayinvoicelist;
    $obj->actiontype='1';

    echo json_encode($obj);
}
else{
    $arrayinvoicelist=array();
    $sqlinovicelist="SELECT `idtbl_invoice`, `total` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$invoicedate' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
    $resultinovicelist =$conn-> query($sqlinovicelist);
    while($rowinovicelist = $resultinovicelist-> fetch_assoc()){
        $objinvoice=new stdClass();
        $objinvoice->invoiceid=$rowinovicelist['idtbl_invoice'];
        $objinvoice->invoicetotal=$rowinovicelist['total'];

        array_push($arrayinvoicelist, $objinvoice);
    }

    $actionObj=new stdClass();
    $actionObj->icon='fas fa-exclamation-triangle';
    $actionObj->title='';
    $actionObj->message='Record Error';
    $actionObj->url='';
    $actionObj->target='_blank';
    $actionObj->type='danger';

    $obj=new stdClass();
    $obj->action=json_encode($actionObj);
    $obj->invicelist=$arrayinvoicelist;
    $obj->actiontype='0';

    echo json_encode($obj);
}