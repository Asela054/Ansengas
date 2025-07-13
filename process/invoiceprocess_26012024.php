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

$insertinvoice="INSERT INTO `tbl_invoice`(`date`, `total`, `taxamount`, `nettotal`, `paymentmethod`, `paymentcomplete`, `chequesend`, `companydiffsend`, `ref_id`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`) VALUES ('$invoicedate','$withouttaxtotal','$taxamount','$nettotal','0','0','0','0','$refID','1','$updatedatetime','$userID','$areaID','$customerID','$vehicleloadID')";
if($conn->query($insertinvoice)==true){
    $invoiceID=$conn->insert_id;

    foreach ($orderDetails as $detail) {
        $product = $detail['productId'];
        $unitprice = $detail['unitprice'];
        $refillprice = $detail['refillprice'];
        $emptyprice = $detail['emptyprice'];
        $discountedprice = $detail['discountedprice'];
        $newQty = str_replace(',', '', $detail['newQty']);
        $refillQty = str_replace(',', '', $detail['refillQty']);
        $emptyQty = str_replace(',', '', $detail['emptyQty']);
        $trustQty = str_replace(',', '', $detail['trustQty']);
        $trustreturnQty = str_replace(',', '', $detail['trustreturnqty']);



        if (!empty($newQty) || !empty($refillQty) || !empty($emptyQty) || !empty($trustQty) || !empty($trustreturnQty)) { $newPrice = !empty($newQty) ? $unitprice : null; $refillPrice = !empty($refillQty) ? $refillprice : null; $emptyPrice = !empty($emptyQty) ? $emptyprice : null; $refillPrice = !empty($trustQty) ? $refillprice : null; $refillPrice = !empty($trustreturnQty) ? $refillprice : null; $discountedPrice = !empty($refillQty) ? $discountedprice : null;
        
            $insertinvoicedetail = "INSERT INTO `tbl_invoice_detail` (`newqty`, `refillqty`, `emptyqty`, `trustqty`, `trustreturnqty`, `newprice`, `refillprice`, `emptyprice`, `discountedprice`, `status`, `updatedatetime`,  `tbl_user_idtbl_user`, `tbl_product_idtbl_product`,  `tbl_invoice_idtbl_invoice`)  VALUES ('$newQty', '$refillQty', '$emptyQty', '$trustQty', '$trustreturnQty', '$newPrice', '$refillPrice', '$emptyPrice', '$discountedPrice', '1', '$updatedatetime', '$userID', '$product', '$invoiceID')";
        
            $conn->query($insertinvoicedetail);
        }

        $totqty=$newQty+$refillQty+$trustQty;

        if ($totqty > 0) {
            $updatestock="UPDATE `tbl_vehicle_load_detail` SET `qty`=(`qty`- '$newQty' - '$refillQty'- '$trustQty'), `emptyqty` = (`emptyqty` + '$trustreturnQty' + '$refillQty' - '$emptyQty') WHERE `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
            $conn->query($updatestock);
        } elseif ($emptyQty > 0) {
            $updatestock="UPDATE `tbl_vehicle_load_detail` SET `emptyqty` = (`emptyqty` - '$emptyQty') WHERE `tbl_product_idtbl_product`='$product' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
            $conn->query($updatestock);
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