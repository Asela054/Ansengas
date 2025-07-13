<?php

require_once('dbConnect.php');

$loadid = $_POST['loadid'];

$arrayinvoice=array();

$sqlvehcleload="SELECT * FROM `tbl_vehicle_load` WHERE `idtbl_vehicle_load`='$loadid' AND `approvestatus`='1' AND `unloadstatus`='0' AND `status`='1' AND `date`=DATE(Now())";
$resultvehcleload = mysqli_query($con, $sqlvehcleload);
$rowvehcleload = mysqli_fetch_row($resultvehcleload);

$idrootId = $rowvehcleload[12];

$sql = "SELECT * FROM (SELECT `idtbl_invoice`, `tax_invoice_num`, `non_tax_invoice_num`, `date`, `total`, `taxamount`, `nettotal`, `tbl_customer_idtbl_customer`, `vat` FROM `tbl_invoice` WHERE `tbl_vehicle_load_idtbl_vehicle_load` IN (SELECT `idtbl_vehicle_load` FROM `tbl_vehicle_load` WHERE `tbl_area_idtbl_area`='$idrootId') AND `date`=DATE(Now())) AS `dinv` LEFT JOIN (SELECT SUM(`payamount`) AS `payamount`, `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_payment_has_tbl_invoice` GROUP BY `tbl_invoice_idtbl_invoice`) AS `dhasinv` ON `dhasinv`.`tbl_invoice_idtbl_invoice`=`dinv`.`idtbl_invoice` ORDER BY `dinv`.`idtbl_invoice` DESC";
$res = mysqli_query($con, $sql);
$result = array();

while ($rowIn = mysqli_fetch_array($res)) {
    $invoId = $rowIn['idtbl_invoice'];
    $qslItem = "SELECT tblInv.*,tbl_product.product_name FROM((SELECT `newqty`, `refillqty`, `emptyqty`, `trustqty`, `trustreturnqty`, `newprice`, `refillprice`, `emptyprice`, `encustomer_newprice`, `encustomer_refillprice`, `encustomer_emptyprice`, `discount_price`,`tbl_product_idtbl_product` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice`='$invoId') tblInv)INNER JOIN tbl_product ON tblInv.tbl_product_idtbl_product=tbl_product.idtbl_product";
    $resItem = mysqli_query($con, $qslItem);
    $invoicedetail = array();
    while ($row = mysqli_fetch_array($resItem)) {
        array_push($invoicedetail, array("newqty" => $row['newqty'], "refillqty" => $row['refillqty'], "emptyqty" => $row['emptyqty'],"trustqty" => $row['trustqty'], "trustreturnqty" => $row['trustreturnqty'],"newprice" => $row['newprice'], "refillprice" => $row['refillprice'],"emptyprice" => $row['emptyprice'], "encustomer_newprice" => $row['encustomer_newprice'],"encustomer_refillprice" => $row['encustomer_refillprice'],"encustomer_emptyprice" => $row['encustomer_emptyprice'],"discount_price" => $row['discount_price'],"proId" => $row['tbl_product_idtbl_product'],"proName"=>$row['product_name']));
    }

    $obj=new stdClass();
    $obj->invoiceID=$rowIn['idtbl_invoice'];
    $obj->date=$rowIn['date'];
    $obj->total=$rowIn['total'];
    $obj->taxamount=$rowIn['taxamount'];
    $obj->nettotal=$rowIn['nettotal'];
    $obj->taxinvoicenum=$rowIn['tax_invoice_num'];
    $obj->nontaxinvoicenum=$rowIn['non_tax_invoice_num'];
    $obj->payamount=$rowIn['payamount'];
    $obj->customerID=$rowIn['tbl_customer_idtbl_customer'];
    $obj->invoiceinfo=$invoicedetail;

    array_push($arrayinvoice, $obj);
}

echo json_encode($arrayinvoice);
