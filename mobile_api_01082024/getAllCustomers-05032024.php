<?php

require_once('dbConnect.php');

$lorryID = $_POST['lorryID'];

$arrayinvoice = array();

$sqlvehcleload = "SELECT * FROM `tbl_vehicle_load` WHERE `lorryid`='$lorryID' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
$resultvehcleload = mysqli_query($con, $sqlvehcleload);
$rowvehcleload = mysqli_fetch_row($resultvehcleload);

$idrootId = $rowvehcleload[12];
$allCusStatus = $rowvehcleload[8];

if (mysqli_num_rows($resultvehcleload) > 0){

if ($allCusStatus == '0') {

    $sql = "SELECT `idtbl_customer`, `type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit` FROM `tbl_customer` WHERE `status`='1' AND `tbl_area_idtbl_area`='$idrootId';";
    $res = mysqli_query($con, $sql);
    $result = array();

    while ($row = mysqli_fetch_array($res)) {

        $cusId = $row['idtbl_customer'];
        $visitStatus = "0";

        $sqlCustomerDayVisit = "SELECT COUNT(*) AS `count` FROM `tbl_customer_ava_qty` WHERE `date`=DATE(NOW()) AND `tbl_customer_idtbl_customer`='$cusId'";
        $resultCustomerDayVisit = mysqli_query($con, $sqlCustomerDayVisit);
        $rowCheckUser = mysqli_fetch_row($resultCustomerDayVisit);
        if ($rowCheckUser[0] > 0) {
            $visitStatus = "1";
        }

        array_push($result, array("id" => $row['idtbl_customer'], "shop_name" => $row['name'], "mobile" => $row['phone'],  "address" => $row['address'], "creditlimit" => $row['creditlimit'], "outStanding" => "2000", "visitStatus" => $visitStatus));
    }
} else {

    $sql = "SELECT `idtbl_customer`, `type`, `name`, `nic`, `phone`, `email`, `address`, `vat_num`, `s_vat`, `numofvisitdays`, `creditlimit` FROM `tbl_customer` WHERE `status`='1';";
    $res = mysqli_query($con, $sql);
    $result = array();
    while ($row = mysqli_fetch_array($res)) {

        $cusId = $row['idtbl_customer'];
        $visitStatus = "0";

        $sqlCustomerDayVisit = "SELECT COUNT(*) AS `count` FROM `tbl_customer_ava_qty` WHERE `date`=DATE(NOW()) AND `tbl_customer_idtbl_customer`='$cusId'";
        $resultCustomerDayVisit = mysqli_query($con, $sqlCustomerDayVisit);
        $rowCheckUser = mysqli_fetch_row($resultCustomerDayVisit);

        if ($rowCheckUser[0] > 0) {
            $visitStatus = "1";
        }

        array_push($result, array("id" => $row['idtbl_customer'], "shop_name" => $row['name'], "mobile" => $row['phone'],  "address" => $row['address'], "creditlimit" => $row['creditlimit'], "outStanding" => "2000", "visitStatus" => $visitStatus));
    }
}
}

print(json_encode($result));
mysqli_close($con);

?>
