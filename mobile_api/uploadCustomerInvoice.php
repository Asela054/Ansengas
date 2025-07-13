<?php

require_once('dbConnect.php');

$orderData = $_POST['data'];
$refId = $_POST['refId'];

$sqlvehcleload = "SELECT * FROM `tbl_vehicle_load` WHERE `refid`='$refId' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
$resultvehcleload = mysqli_query($con, $sqlvehcleload);
$rowvehcleload = mysqli_fetch_row($resultvehcleload);

$idload = $rowvehcleload[0];
$areaId = $rowvehcleload[12];

$flag = true;
$con->autocommit(FALSE);

$headerInvoice = json_decode($orderData);

foreach ($headerInvoice as $hino) {

    $cusId = $hino->customerId;
    $netPrice = $hino->netPrice;
    $date = $hino->date;
    $rejectReason = $hino->rejectReason;
    $itemArray = $hino->arrayList;

    $totqty = 0;
    $invoicemonth = date("n", strtotime($date));

    $sql = "INSERT INTO `tbl_invoice`(`date`, `total`, `paymentmethod`, `paymentcomplete`, `ref_id`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_area_idtbl_area`, `tbl_customer_idtbl_customer`, `tbl_vehicle_load_idtbl_vehicle_load`) VALUES ('$date','$netPrice','0','0','$refId','1',NOW(),'$refId','$areaId','$cusId','$idload')";
    $result = mysqli_query($con, $sql);
    $last_id = $con->insert_id;

    if (!$result) {
        $flag = false;
    }

    foreach ($itemArray as $indet) {
        $invoiceId = $indet->invoiceId;
        $prductId = $indet->prductId;
        $fullQty = $indet->fullQty;
        $emptyQty = $indet->emptyQty;
        $trustQty = $indet->trustQty;
        $trustReQty = $indet->trustReQty;
        $refillQty = $indet->refillQty;
        $returnQty = $indet->returnQty;
        $newQty = $indet->newQty;
        $refillPrice = $indet->refillPrice;
        $newrefillprice = $indet->newrefillprice;
        $newunitprice = $indet->newunitprice;
        $newPrice = $indet->newPrice;
        $totalAmount = $indet->totalAmount;
        $product_name = $indet->product_name;


        $sqlItem = "INSERT INTO `tbl_invoice_detail`(`newqty`, `refillqty`, `returnqty`, `trustqty`, `unitprice`, `refillprice`, `newsaleprice`, `newrefillprice`,`status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_product_idtbl_product`, `tbl_invoice_idtbl_invoice`) VALUES 
        ('$newQty','$refillQty','$trustReQty','$trustQty','$newunitprice','$newrefillprice','$newPrice','$refillPrice','1',NOW(),'$refId','$prductId','$last_id')";
        $resultItem = mysqli_query($con, $sqlItem);

        if (!$resultItem) {
            $flag = false;
        }

        $totqty = $newQty + $refillQty + $trustQty;
        $updatestock = "UPDATE `tbl_vehicle_load_detail` SET `qty`=(`qty`-'$totqty') WHERE `tbl_product_idtbl_product`='$prductId' AND `tbl_vehicle_load_idtbl_vehicle_load`='$idload'";
        $resultUpdateQty = mysqli_query($con, $updatestock);

        if ($trustQty > 0 | $returnQty > 0) {
            $sqlcheckcus = "SELECT COUNT(*) AS `count` FROM `tbl_cutomer_trustreturn` WHERE `tbl_customer_idtbl_customer`='$cusId' AND `tbl_product_idtbl_product`='$prductId' AND `status`=1";
            $resultcheckcus = $con->query($sqlcheckcus);
            $rowcheckcus = $resultcheckcus->fetch_assoc();

            if ($rowcheckcus['count'] > 0) {
                $updatecustrust = "UPDATE `tbl_cutomer_trustreturn` SET `trustqty`=(`trustqty`+'$trustQty'),`returnqty`=(`returnqty`+'$returnQty'),`updatedatetime`=NOW(),`tbl_user_idtbl_user`='$refId' WHERE `tbl_customer_idtbl_customer`='$cusId',`tbl_product_idtbl_product`='$prductId'";
                $con->query($updatecustrust);
            } else {
                $insertcustrust = "INSERT INTO `tbl_cutomer_trustreturn`(`trustqty`, `returnqty`, `status`, `updatedatetime`, `tbl_user_idtbl_user`, `tbl_customer_idtbl_customer`, `tbl_product_idtbl_product`) VALUES ('$trustQty','$returnQty','1',NOW(),'$refId','$cusId','$prductId')";
                $con->query($insertcustrust);
            }
        }

        //Target section
        $sqlcheckreftarget = "SELECT COUNT(*) AS `count` FROM `tbl_employee_target` WHERE MONTH(`month`)='$invoicemonth' AND `status`=1 AND `tbl_product_idtbl_product`='$prductId' AND `tbl_employee_idtbl_employee`='$refId'";
        $resultcheckreftarget = $con->query($sqlcheckreftarget);
        $rowcheckreftarget = $resultcheckreftarget->fetch_assoc();
        if ($rowcheckreftarget['count'] > 0) {
            $updatereftarget = "UPDATE `tbl_employee_target` SET `targetcomplete`=(`targetcomplete`+$totqty) WHERE `tbl_employee_idtbl_employee`='$refID' AND `status`=1 AND MONTH(`month`)='$invoicemonth' AND `tbl_product_idtbl_product`='$prductId'";
            $con->query($updatereftarget);
        }

        $sqlcheckvehitarget = "SELECT COUNT(*) AS `count` FROM `tbl_vehicle_target` WHERE MONTH(`month`)='$invoicemonth' AND `status`=1 AND `tbl_product_idtbl_product`='$prductId' AND `tbl_vehicle_idtbl_vehicle` IN (SELECT `lorryid` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$idload' AND `status`=1)";
        $resultcheckvehitarget = $con->query($sqlcheckvehitarget);
        $rowcheckvehitarget = $resultcheckvehitarget->fetch_assoc();
        if ($rowcheckvehitarget['count'] > 0) {
            $updatevehicletarget = "UPDATE `tbl_vehicle_target` SET `targetcomplete`=(`targetcomplete`+$totqty) WHERE `status`=1 AND MONTH(`month`)='$invoicemonth' AND `tbl_product_idtbl_product`='$prductId' AND `tbl_vehicle_idtbl_vehicle` IN (SELECT `lorryid` FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$idload' AND `status`=1)";
            $con->query($updatevehicletarget);
        }
    }
}


if ($flag) {
    $con->commit();
    $response = array("code" => '200', "message" => 'Update Complete');
    print_r(json_encode($response));
} else {
    $con->rollback();
    $response = array("code" => '500', "message" => 'Update Not Complete');
    print_r(json_encode($response));
}

?>
