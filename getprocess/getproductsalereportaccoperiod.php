<?php 
require_once('../connection/db.php');

$validfrom = isset($_POST['validfrom']) ? $_POST['validfrom'] : null;
$validto = isset($_POST['validto']) ? $_POST['validto'] : null;
$typeSelector = isset($_POST['typeSelector']) ? $_POST['typeSelector'] : null;
$dataselector = isset($_POST['dataselector']) ? $_POST['dataselector'] : null;
$cusType = $_POST['cusType'];

$productarray = array();
// $sqlproductlist = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `tbl_product_category_idtbl_product_category`=1 AND `status`=1 ORDER BY `tbl_product`.`orderlevel`";
$sqlproductlist = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 ORDER BY `tbl_product`.`orderlevel`";
$resultproductlist = $conn->query($sqlproductlist);

if ($resultproductlist) {
    while ($rowproductlist = $resultproductlist->fetch_assoc()) { 
        $obj = new stdClass();
        $obj->productID = $rowproductlist['idtbl_product'];
        $obj->product = $rowproductlist['product_name'];

        array_push($productarray, $obj);
    }
}

$html = '';
$html .= '<table class="table table-striped table-bordered table-sm small" id="tableproductsale">
    <thead>
        <tr>
            <th>Product</th>
            <th class="text-center">New Qty</th>
            <th class="text-center">Refill Qty</th>
            <th class="text-center">Empty Qty</th>
            <th class="text-center">Trust Qty</th>
        </tr>
    </thead>
    <tbody>';

foreach ($productarray as $rowproductarray) { 
    $productID = $rowproductarray->productID;
    $productname = $rowproductarray->product;
    $sqlsalecount = "";
    $totalsumarray=array();

    if ($typeSelector == 1) {
        $sqlsalecount = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`emptyqty`) AS `emptyqty`, SUM(`trustqty`) AS `trustqty` FROM `tbl_invoice_detail` 
        WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `date` BETWEEN '$validfrom' AND '$validto'";
        if (!empty($dataselector)) {
            $sqlsalecount .= " AND `tbl_customer_idtbl_customer`=$dataselector";
        }
        $sqlsalecount .= ")";
        $resultsalecount = $conn->query($sqlsalecount);
    }
    else if ($typeSelector == 2) {
        $totnewqty=0;
        $totrefilqty=0;
        $totemptyqty=0;
        $tottrustqty=0;

        $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address`, `tbl_area`.`area` FROM `tbl_customer` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_customer`.`tbl_area_idtbl_area` LEFT JOIN `tbl_customerwise_salesrep` ON `tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` WHERE `tbl_customerwise_salesrep`.`status`=1 AND `tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee`='$dataselector' "; if(!empty($cusType)){$sql.="AND `tbl_customer`.`type`='$cusType' ";} $sql.="GROUP BY `tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer` ORDER BY `tbl_customer`.`tbl_area_idtbl_area` ASC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) { 
            $customerID = $row['idtbl_customer'];

            $sqlsaleinfo="SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `newqty`, SUM(`tbl_invoice_detail`.`refillqty`) AS `refillqty`, SUM(`tbl_invoice_detail`.`emptyqty`) AS `emptyqty`, SUM(`tbl_invoice_detail`.`trustqty`) AS `trustqty` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_customerwise_salesrep` ON `tbl_customerwise_salesrep`.`tbl_product_idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_product_idtbl_product`='$productID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_customerwise_salesrep`.`tbl_product_idtbl_product`='$productID' AND `tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee`='$dataselector' AND `tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer`='$customerID'";
            $resultsaleinfo = $conn->query($sqlsaleinfo);
            while($rowsaleinfo = $resultsaleinfo->fetch_assoc()){
                $totnewqty+=$rowsaleinfo['newqty'];
                $totrefilqty+=$rowsaleinfo['refillqty'];
                $totemptyqty+=$rowsaleinfo['emptyqty'];
                $tottrustqty+=$rowsaleinfo['trustqty'];
            }
        }

        $obj=new stdClass();
        $obj->productID=$productID;
        $obj->productname=$productname;
        $obj->totnewqty=$totnewqty;
        $obj->totrefilqty=$totrefilqty;
        $obj->totemptyqty=$totemptyqty;
        $obj->tottrustqty=$tottrustqty;

        array_push($totalsumarray, $obj);
    } else if ($typeSelector == 3) {
        $totnewqty=0;
        $totrefilqty=0;
        $totemptyqty=0;
        $tottrustqty=0;

        $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address`, `tbl_area`.`area` FROM `tbl_customer` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_customer`.`tbl_area_idtbl_area` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`WHERE `tbl_customer`.`status`=1 AND `tbl_vehicle_load`.`lorryid`='$dataselector' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_invoice`.`tbl_customer_idtbl_customer` ORDER BY `tbl_customer`.`tbl_area_idtbl_area` ASC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) { 
            $customerID = $row['idtbl_customer'];

            // $sqlsalecount = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`emptyqty`) AS `emptyqty`, SUM(`trustqty`) AS `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_vehicle_load`.`lorryid`=$dataselector AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID')";
            $sqlsalecount = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`emptyqty`) AS `emptyqty`, SUM(`trustqty`) AS `trustqty` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_product_idtbl_product`='$productID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_vehicle_load`.`lorryid`='$dataselector'";
            $resultsalecount = $conn->query($sqlsalecount);
            while($rowsalecount = $resultsalecount->fetch_assoc()){
                $totnewqty+=$rowsalecount['newqty'];
                $totrefilqty+=$rowsalecount['refillqty'];
                $totemptyqty+=$rowsalecount['emptyqty'];
                $tottrustqty+=$rowsalecount['trustqty'];
            }
        }

        $obj=new stdClass();
        $obj->productID=$productID;
        $obj->productname=$productname;
        $obj->totnewqty=$totnewqty;
        $obj->totrefilqty=$totrefilqty;
        $obj->totemptyqty=$totemptyqty;
        $obj->tottrustqty=$tottrustqty;

        array_push($totalsumarray, $obj);
    } else if ($typeSelector == 4) {
        $totnewqty=0;
        $totrefilqty=0;
        $totemptyqty=0;
        $tottrustqty=0;

        $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address`, `tbl_area`.`area` FROM `tbl_customer` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_customer`.`tbl_area_idtbl_area` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`WHERE `tbl_customer`.`status`=1 AND `tbl_vehicle_load`.`driverid`='$dataselector' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_invoice`.`tbl_customer_idtbl_customer` ORDER BY `tbl_customer`.`tbl_area_idtbl_area` ASC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) { 
            $customerID = $row['idtbl_customer'];

            // $sqlsalecount = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`emptyqty`) AS `emptyqty`, SUM(`trustqty`) AS `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_vehicle_load`.`driverid`=$dataselector AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID')";
            $sqlsalecount = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`emptyqty`) AS `emptyqty`, SUM(`trustqty`) AS `trustqty` FROM  `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_product_idtbl_product`='$productID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_vehicle_load`.`driverid`='$dataselector'";
            $resultsalecount = $conn->query($sqlsalecount);
            while($rowsalecount = $resultsalecount->fetch_assoc()){
                $totnewqty+=$rowsalecount['newqty'];
                $totrefilqty+=$rowsalecount['refillqty'];
                $totemptyqty+=$rowsalecount['emptyqty'];
                $tottrustqty+=$rowsalecount['trustqty'];
            }
        }

        $obj=new stdClass();
        $obj->productID=$productID;
        $obj->productname=$productname;
        $obj->totnewqty=$totnewqty;
        $obj->totrefilqty=$totrefilqty;
        $obj->totemptyqty=$totemptyqty;
        $obj->tottrustqty=$tottrustqty;

        array_push($totalsumarray, $obj);
    } else if ($typeSelector == 5) {
        $totnewqty=0;
        $totrefilqty=0;
        $totemptyqty=0;
        $tottrustqty=0;
        
        $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address`, `tbl_area`.`area` FROM `tbl_customer` LEFT JOIN `tbl_area` ON `tbl_area`.`idtbl_area`=`tbl_customer`.`tbl_area_idtbl_area` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`WHERE `tbl_customer`.`status`=1 AND `tbl_customer`.`tbl_area_idtbl_area`='$dataselector' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_invoice`.`tbl_customer_idtbl_customer` ORDER BY `tbl_customer`.`tbl_area_idtbl_area` ASC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) { 
            $customerID = $row['idtbl_customer'];

            // $sqlsalecount = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`emptyqty`) AS `emptyqty`, SUM(`trustqty`) AS `trustqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$productID' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load` WHERE `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_vehicle_load`.`tbl_area_idtbl_area`=$dataselector AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID')";
            $sqlsalecount = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty`, SUM(`emptyqty`) AS `emptyqty`, SUM(`trustqty`) AS `trustqty` FROM  `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice` LEFT JOIN `tbl_customer` ON `tbl_customer`.`idtbl_customer`=`tbl_invoice`.`tbl_customer_idtbl_customer` WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_product_idtbl_product`='$productID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' AND `tbl_customer`.`tbl_area_idtbl_area`='$dataselector'";
            $resultsalecount = $conn->query($sqlsalecount);
            while($rowsalecount = $resultsalecount->fetch_assoc()){
                $totnewqty+=$rowsalecount['newqty'];
                $totrefilqty+=$rowsalecount['refillqty'];
                $totemptyqty+=$rowsalecount['emptyqty'];
                $tottrustqty+=$rowsalecount['trustqty'];
            }
        }

        $obj=new stdClass();
        $obj->productID=$productID;
        $obj->productname=$productname;
        $obj->totnewqty=$totnewqty;
        $obj->totrefilqty=$totrefilqty;
        $obj->totemptyqty=$totemptyqty;
        $obj->tottrustqty=$tottrustqty;

        array_push($totalsumarray, $obj);
    }

    if ($typeSelector>1) {
        foreach($totalsumarray as $rowsummerylist){
            $html .= '<tr>
                <td>' . $rowsummerylist->productname . '</td>
                <td class="text-center">' . (isset($rowsummerylist->totnewqty) ? $rowsummerylist->totnewqty : '-') . '</td>
                <td class="text-center">' . (isset($rowsummerylist->totrefilqty) ? $rowsummerylist->totrefilqty : '-') . '</td>
                <td class="text-center">' . (isset($rowsummerylist->totemptyqty) ? $rowsummerylist->totemptyqty : '-') . '</td>
                <td class="text-center">' . (isset($rowsummerylist->tottrustqty) ? $rowsummerylist->tottrustqty : '-') . '</td>
            </tr>';
        }
    }
    else{
        $resultsalecount = $conn->query($sqlsalecount);
        if ($resultsalecount) {
            $rowsalecount = $resultsalecount->fetch_assoc();
            $html .= '<tr>
                <td>' . $rowproductarray->product . '</td>
                <td class="text-center">' . (isset($rowsalecount['newqty']) ? $rowsalecount['newqty'] : '-') . '</td>
                <td class="text-center">' . (isset($rowsalecount['refillqty']) ? $rowsalecount['refillqty'] : '-') . '</td>
                <td class="text-center">' . (isset($rowsalecount['emptyqty']) ? $rowsalecount['emptyqty'] : '-') . '</td>
                <td class="text-center">' . (isset($rowsalecount['trustqty']) ? $rowsalecount['trustqty'] : '-') . '</td>
            </tr>';
        }
    }
}

$html .= '</tbody>
</table>';

$objdata = new stdClass();
$objdata->html = $html;

echo json_encode($objdata);
?>
