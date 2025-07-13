<?php
require_once('dbConnect.php');

$lorryID = $_POST['lorryID'];

$arrayinvoice = array();

$sqlvehcleload = "SELECT * FROM `tbl_vehicle_load` WHERE `lorryid`='$lorryID' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
$resultvehcleload = mysqli_query($con, $sqlvehcleload);
$rowvehcleload = mysqli_fetch_row($resultvehcleload);

$loadid = $rowvehcleload[0];
$idrootId = $rowvehcleload[12];
$allCusStatus = $rowvehcleload[8];

$sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
$resultvat = $con->query($sqlvat);
$rowvat = $resultvat->fetch_assoc();

$vatamount = $rowvat['vat'];

if (mysqli_num_rows($resultvehcleload) > 0){

if ($allCusStatus == '0') {

    $sql = "SELECT * FROM (SELECT `idtbl_customer`, `type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `vat_status`, `discount_status`, `specialcus_status`, `numofvisitdays`, `creditlimit`, `tbl_area_idtbl_area`, `moreinvissue`,`tax_cus_name` FROM `tbl_customer` WHERE `status`='1' AND `tbl_area_idtbl_area`='$idrootId' UNION ALL SELECT `idtbl_customer`, `type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `vat_status`, `discount_status`, `specialcus_status`, `numofvisitdays`, `creditlimit`, `tbl_area_idtbl_area`, `moreinvissue`,`tax_cus_name` FROM `tbl_customer` LEFT JOIN `tbl_customer_special_route` ON `tbl_customer_special_route`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` WHERE `tbl_customer`.`status`='1' AND `tbl_customer_special_route`.`tbl_vehicle_load_idtbl_vehicle_load`='$loadid' UNION ALL SELECT `idtbl_customer`, `type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `vat_status`, `discount_status`, `specialcus_status`, `numofvisitdays`, `creditlimit`, `tbl_customer`.`tbl_area_idtbl_area`, `moreinvissue`,`tax_cus_name` FROM `tbl_customer` LEFT JOIN `tbl_customer_other_route` ON `tbl_customer_other_route`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` WHERE `tbl_customer`.`status`=1 AND `tbl_customer_other_route`.`tbl_area_idtbl_area`='$idrootId') AS combined_results ORDER BY name";
    $res = mysqli_query($con, $sql);
    $result = array();

    while ($row = mysqli_fetch_array($res)) {
        $issueinvoicestatus=0;
        $rejectcustomerstatus=0;
        $cusId = $row['idtbl_customer'];
        $visitStatus = "0";
        $discountstatus=$row['discount_status'];

        $sqlCustomerDayVisit = "SELECT COUNT(*) AS `count` FROM `tbl_customer_ava_qty` WHERE `date`=DATE(NOW()) AND `tbl_customer_idtbl_customer`='$cusId'";
        $resultCustomerDayVisit = mysqli_query($con, $sqlCustomerDayVisit);
        $rowCheckUser = mysqli_fetch_row($resultCustomerDayVisit);
        if ($rowCheckUser[0] > 0) {
            $visitStatus = "1";
        }

        $sqlinvoicelist="SELECT * FROM (SELECT SUM(`nettotal`) AS `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$cusId') AS `dmain` LEFT JOIN (SELECT IFNULL((SUM(`tbl_invoice_detail`.`refillqty`*(`tbl_invoice_detail`.`encustomer_refillprice`*($vatamount+100))/100)-SUM(`tbl_invoice_detail`.`refillqty`*(`tbl_invoice_detail`.`discount_price`*($vatamount+100))/100)), 0) AS `discounttotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$cusId' AND `tbl_invoice_detail`.`tbl_product_idtbl_product`=1 AND `tbl_invoice`.`date` < '2024-04-01') AS `dsub` ON `dsub`.`tbl_customer_idtbl_customer`=`dmain`.`tbl_customer_idtbl_customer`";
        $resultinvoicelist =$con-> query($sqlinvoicelist);
        $rowinvoicelist = $resultinvoicelist-> fetch_assoc();

        $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$cusId' AND `tbl_invoice`.`paymentcomplete`=0";
        $resultpayment =$con-> query($sqlpayment);
        $rowpayment = $resultpayment-> fetch_assoc();

        $nettotal = $rowinvoicelist['nettotal'];
        $payamount = $rowpayment['payamount'];
        $discount_amount = ($discountstatus == 1) ? $discount_amount : 0;
        $balanceamount = $nettotal - ($payamount+$discount_amount);

        if ($balanceamount<0) {$balanceamount=0;}

        $sqlissuecount = "SELECT COUNT(*) AS `issuecount` FROM `tbl_invoice` WHERE `date`=DATE(NOW()) AND `status`=1 AND `tbl_customer_idtbl_customer`='$cusId'";
        $resultissuecount = mysqli_query($con, $sqlissuecount);
        $rowissuecount = mysqli_fetch_row($resultissuecount);

        if($rowissuecount[0]>0){$issueinvoicestatus=1;}

        $sqlrejectcus = "SELECT COUNT(*) AS `rejectcount` FROM `tbl_customer_shop_close` WHERE `status`=1 AND `date`=DATE(NOW()) AND `tbl_customer_idtbl_customer`='$cusId'";
        $resultrejectcus = mysqli_query($con, $sqlrejectcus);
        $rowrejectcus = mysqli_fetch_row($resultrejectcus);

        if($rowrejectcus[0]>0){$rejectcustomerstatus=1;}

        array_push($result, array("id" => $row['idtbl_customer'], "customer_type" => $row['type'], "vat_status" => $row['vat_status'], "discount_status" => $row['discount_status'], "specialcus_status" => $row['specialcus_status'],"shop_name" => $row['name'], "mobile" => $row['phone'],  "address" => $row['address'], "creditlimit" => $row['creditlimit'], "areaid" => $row['tbl_area_idtbl_area'], "moreinvissue" => $row['moreinvissue'],"vatnum" => $row['vat_num'],"taxcusname" => $row['tax_cus_name'], "outStanding" => $balanceamount, "visitStatus" => $visitStatus, "issueinvoicestatus" => $issueinvoicestatus, "rejectcustomerstatus" => $rejectcustomerstatus));
    }
} else {

    $sql = "SELECT `idtbl_customer`, `type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `vat_status`, `discount_status`, `specialcus_status`, `numofvisitdays`, `creditlimit`, `tbl_area_idtbl_area`, `moreinvissue`, `tax_cus_name` FROM `tbl_customer` WHERE `status`='1';";
    $res = mysqli_query($con, $sql);
    $result = array();
    while ($row = mysqli_fetch_array($res)) {
        $issueinvoicestatus=0;
        $rejectcustomerstatus=0;
        $cusId = $row['idtbl_customer'];
        $visitStatus = "0";
        $discountstatus=$row['discount_status'];

        $sqlCustomerDayVisit = "SELECT COUNT(*) AS `count` FROM `tbl_customer_ava_qty` WHERE `date`=DATE(NOW()) AND `tbl_customer_idtbl_customer`='$cusId'";
        $resultCustomerDayVisit = mysqli_query($con, $sqlCustomerDayVisit);
        $rowCheckUser = mysqli_fetch_row($resultCustomerDayVisit);

        if ($rowCheckUser[0] > 0) {
            $visitStatus = "1";
        }

        $sqlinvoicelist="SELECT * FROM (SELECT SUM(`nettotal`) AS `nettotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` WHERE `status`=1 AND `paymentcomplete`=0 AND `tbl_customer_idtbl_customer`='$cusId') AS `dmain` LEFT JOIN (SELECT IFNULL((SUM(`tbl_invoice_detail`.`refillqty`*(`tbl_invoice_detail`.`encustomer_refillprice`*($vatamount+100))/100)-SUM(`tbl_invoice_detail`.`refillqty`*(`tbl_invoice_detail`.`discount_price`*($vatamount+100))/100)), 0) AS `discounttotal`, `tbl_customer_idtbl_customer` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_detail` ON `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$cusId' AND `tbl_invoice_detail`.`tbl_product_idtbl_product`=1 AND `tbl_invoice`.`date` < '2024-04-01') AS `dsub` ON `dsub`.`tbl_customer_idtbl_customer`=`dmain`.`tbl_customer_idtbl_customer`";
        $resultinvoicelist =$con-> query($sqlinvoicelist);
        $rowinvoicelist = $resultinvoicelist-> fetch_assoc();

        $sqlpayment="SELECT SUM(`payamount`) AS `payamount` FROM `tbl_invoice_payment_has_tbl_invoice` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$cusId' AND `tbl_invoice`.`paymentcomplete`=0";
        $resultpayment =$con-> query($sqlpayment);
        $rowpayment = $resultpayment-> fetch_assoc();

        $nettotal = $rowinvoicelist['nettotal'];
        $payamount = $rowpayment['payamount'];
        $discount_amount = ($discountstatus == 1) ? $discount_amount : 0;
        $balanceamount = $nettotal - ($payamount+$discount_amount);

        if ($balanceamount<0) {$balanceamount=0;}

        $sqlissuecount = "SELECT COUNT(*) AS `issuecount` FROM `tbl_invoice` WHERE `date`=DATE(NOW()) AND `status`=1 AND `tbl_customer_idtbl_customer`='$cusId'";
        $resultissuecount = mysqli_query($con, $sqlissuecount);
        $rowissuecount = mysqli_fetch_row($resultissuecount);

        if($rowissuecount[0]>0){$issueinvoicestatus=1;}

        $sqlrejectcus = "SELECT COUNT(*) AS `rejectcount` FROM `tbl_customer_shop_close` WHERE `status`=1 AND `date`=DATE(NOW()) AND `tbl_customer_idtbl_customer`='$cusId'";
        $resultrejectcus = mysqli_query($con, $sqlrejectcus);
        $rowrejectcus = mysqli_fetch_row($resultrejectcus);

        if($rowrejectcus[0]>0){$rejectcustomerstatus=1;}

        array_push($result, array("id" => $row['idtbl_customer'], "customer_type" => $row['type'], "vat_status" => $row['vat_status'], "discount_status" => $row['discount_status'], "specialcus_status" => $row['specialcus_status'], "shop_name" => $row['name'], "mobile" => $row['phone'],  "address" => $row['address'], "creditlimit" => $row['creditlimit'], "areaid" => $row['tbl_area_idtbl_area'], "moreinvissue" => $row['moreinvissue'],"vatnum" => $row['vat_num'],"taxcusname" => $row['tax_cus_name'], "outStanding" => $balanceamount, "visitStatus" => $visitStatus, "issueinvoicestatus" => $issueinvoicestatus, "rejectcustomerstatus" => $rejectcustomerstatus));
    }
}
}

print(json_encode($result));
mysqli_close($con);

?>
