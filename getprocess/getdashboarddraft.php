<?php 
require_once('../connection/db.php');

$thismonth = date("n"); 
$thisyear = date("Y"); 

$daysarray = array();
$productonearray = array();
$producttwoarray = array();
$productthreearray = array();
$productfourarray = array();
$dayscount = cal_days_in_month(CAL_GREGORIAN, $thismonth, $thisyear);
$total2kg = 0;
$total5kg = 0;
$total12_5kg = 0;
$total35_5kg = 0;

for ($i = 1; $i <= $dayscount; $i++) {
    $daysarray[] = $i;

    if ($i <= date('d')) {
        $arrayproduct = array('1', '2', '4', '6');
        foreach ($arrayproduct as $rowproduct) {
            $newsalecount = 0;
            $refillsalecount = 0;

            $invdate = date('Y-m-') . $i;
            $sqlsaleproduct = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` WHERE `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `date`='$invdate' AND `status`=1) AND `status`=1 AND `tbl_product_idtbl_product`='$rowproduct'";
            $resultsaleproduct = $conn->query($sqlsaleproduct);
            $rowsaleproduct = $resultsaleproduct->fetch_assoc();

            if (!empty($rowsaleproduct['newqty'])) {
                $newsalecount = $rowsaleproduct['newqty'];
            }
            if (!empty($rowsaleproduct['refillqty'])) {
                $refillsalecount = $rowsaleproduct['refillqty'];
            }

            $totsalecount = $newsalecount + $refillsalecount;

            if ($totsalecount > 0) {
                if ($rowproduct == 1) {
                    $productonearray[] = $totsalecount;
                    $total12_5kg += $totsalecount;
                } else if ($rowproduct == 2) {
                    $producttwoarray[] = $totsalecount;
                    $total35_5kg += $totsalecount;
                } else if ($rowproduct == 4) {
                    $productthreearray[] = $totsalecount;
                    $total5kg += $totsalecount;
                } else if ($rowproduct == 6) {
                    $productfourarray[] = $totsalecount;
                    $total2kg += $totsalecount;
                }
            } else {
                if ($rowproduct == 1) {
                    $productonearray[] = null;
                } else if ($rowproduct == 2) {
                    $producttwoarray[] = null;
                } else if ($rowproduct == 4) {
                    $productthreearray[] = null;
                } else if ($rowproduct == 6) {
                    $productfourarray[] = null;
                }
            }
        }
    }
}

$obj = new stdClass();
$obj->daysarray = $daysarray;
$obj->productonearray = $productonearray;
$obj->producttwoarray = $producttwoarray;
$obj->productthreearray = $productthreearray;
$obj->productfourarray = $productfourarray;
$obj->total12_5kg = $total12_5kg;
$obj->total37_5kg = $total35_5kg;
$obj->total5kg = $total5kg;
$obj->total2kg = $total2kg;

echo json_encode($obj);