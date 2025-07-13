<?php 
require_once('../connection/db.php');

$validfrom = $_POST['validfrom'];
$validto = $_POST['validto'];
$typeSelector = $_POST['typeSelector'];
$dataselector = $_POST['dataselector'];

if($typeSelector==1){
    if($dataselector==''){
        $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address` FROM `tbl_customer` WHERE `status`=1";
        $result = $conn->query($sql);
    }
    else{
        $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address` FROM `tbl_customer` WHERE `status`=1 AND `idtbl_customer`='$dataselector'";
        $result = $conn->query($sql);
    }
}
else if($typeSelector==2){
    $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address` FROM `tbl_customer` LEFT JOIN `tbl_customerwise_salesrep` ON `tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` WHERE `tbl_customerwise_salesrep`.`status`=1 AND `tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee`='$dataselector' GROUP BY `tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer`";
    $result = $conn->query($sql);
}
else if($typeSelector==3){
   $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address` FROM `tbl_customer` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`WHERE `tbl_customer`.`status`=1 AND `tbl_vehicle_load`.`lorryid`='$dataselector' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_invoice`.`tbl_customer_idtbl_customer`";
    $result = $conn->query($sql);
}
else if($typeSelector==4){
    $sql = "SELECT `idtbl_customer`, `name`, `phone`, `address` FROM `tbl_customer` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`tbl_customer_idtbl_customer`=`tbl_customer`.`idtbl_customer` LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`WHERE `tbl_customer`.`status`=1 AND `tbl_vehicle_load`.`driverid`='$dataselector' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto' GROUP BY `tbl_invoice`.`tbl_customer_idtbl_customer`";
     $result = $conn->query($sql);
 }


$sqlproductlist = "SELECT `idtbl_product`, `product_name` FROM `tbl_product` WHERE `status`=1 AND `tbl_product_category_idtbl_product_category`=1";
$resultproductlist = $conn->query($sqlproductlist);
$products = array();
while ($rowproductlist = $resultproductlist->fetch_assoc()) {
    $products[$rowproductlist['idtbl_product']] = $rowproductlist['product_name'];
}
?>
<?php 
?>
<div class="scrollbar pb-3" id="style-2">
<table class="table table-striped table-bordered table-sm" id="tableoutstanding">
    <thead>
        <tr>
            <th style="border: none;">Distributor: </th>
            <th colspan="11" style="border: none;">ANSEN Gas Distributors (Pvt) Ltd</th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th class="text-center" colspan="<?php echo count($products); ?>">Refill Cylinders</th>
            <th class="text-center" colspan="<?php echo count($products); ?>">New Cylinders</th>
            <th class="text-center" colspan="<?php echo count($products); ?>">Empty Cylinders</th>
        </tr>
        <tr>
            <th>Dealer Code</th>
            <th>Dealer Name</th>
            <th>Address</th>
            <th>Telephone Number</th>
            <?php?>
            <?php foreach ($products as $product_id => $product_name) : ?>
                <th><?php echo $product_name; ?></th>
            <?php endforeach; ?>
            <?php?>
            <?php foreach ($products as $product_id => $product_name) : ?>
                <th><?php echo $product_name; ?></th>
            <?php endforeach; ?>
            <?php foreach ($products as $product_id => $product_name) : ?>
                <th><?php echo $product_name; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
<?php 
while ($row = $result->fetch_assoc()) { 
    $customerID = $row['idtbl_customer'];
    $phone = $row['phone'];
    $address = $row['address'];

    $sqlinvcount = "SELECT COUNT(*) AS `count` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto'";
    $resultinvcount = $conn->query($sqlinvcount);
    $rowinvcount = $resultinvcount->fetch_assoc();

    if ($rowinvcount['count'] > 0) {
?>
        <tr>
            <td nowrap>&nbsp;</td>
            <td nowrap><?php echo $row['name']; ?></td>
            <td nowrap><?php echo $row['address']; ?></td>
            <td nowrap><?php echo $row['phone']; ?></td>
            <?php 
            foreach ($products as $product_id => $product_name) :
                $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
                $resultvat = $conn->query($sqlvat);
                $rowvat = $resultvat->fetch_assoc();

                $vatamount = $rowvat['vat'];

                $sqlsaleinfo="SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `newqty`, SUM(`tbl_invoice_detail`.`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`";
                if($typeSelector==2){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_customerwise_salesrep` ON `tbl_customerwise_salesrep`.`tbl_product_idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product`";
                }
                else if($typeSelector==3){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`";
                }
                else if($typeSelector==4){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`";
                }
                $sqlsaleinfo .= " WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_product_idtbl_product`='$product_id' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto'";
                if($typeSelector==2){
                    $sqlsaleinfo.=" AND `tbl_customerwise_salesrep`.`tbl_product_idtbl_product`='$product_id' AND `tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee`='$dataselector' AND `tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer`='$customerID'";
                }
                else if($typeSelector==3){
                    $sqlsaleinfo.=" AND `tbl_vehicle_load`.`lorryid`='$dataselector'";
                }
                else if($typeSelector==4){
                    $sqlsaleinfo.=" AND `tbl_vehicle_load`.`driverid`='$dataselector'";
                }
                // echo $sqlsaleinfo;
                $resultsaleinfo = $conn->query($sqlsaleinfo);
                $rowsaleinfo = $resultsaleinfo->fetch_assoc();

                // LV1409
                // LE1294
                // thilak traders
            ?>
                <td class="text-center"><?php echo $rowsaleinfo['refillqty'] != 0 ? $rowsaleinfo['refillqty'] : ''; ?></td>
            <?php endforeach; ?>
            <?php 
            foreach ($products as $product_id => $product_name) :
                $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
                $resultvat = $conn->query($sqlvat);
                $rowvat = $resultvat->fetch_assoc();

                $vatamount = $rowvat['vat'];

                $sqlsaleinfo = "SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `newqty`, SUM(`tbl_invoice_detail`.`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`";
                if($typeSelector==2){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_customerwise_salesrep` ON `tbl_customerwise_salesrep`.`tbl_product_idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product`";
                }
                else if($typeSelector==3){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`";
                }
                else if($typeSelector==4){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`";
                }
                $sqlsaleinfo .= " WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_product_idtbl_product`='$product_id' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto'";
                if($typeSelector==2){
                    $sqlsaleinfo.=" AND `tbl_customerwise_salesrep`.`tbl_product_idtbl_product`='$product_id' AND `tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee`='$dataselector' AND `tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer`='$customerID'";
                }
                else if($typeSelector==3){
                    $sqlsaleinfo.=" AND `tbl_vehicle_load`.`lorryid`='$dataselector'";
                }
                else if($typeSelector==4){
                    $sqlsaleinfo.=" AND `tbl_vehicle_load`.`driverid`='$dataselector'";
                }

                $resultsaleinfo = $conn->query($sqlsaleinfo);
                $rowsaleinfo = $resultsaleinfo->fetch_assoc();
                
            ?>
                <td class="text-center"><?php echo $rowsaleinfo['newqty'] != 0 ? $rowsaleinfo['newqty'] : ''; ?></td>
            <?php endforeach; ?>
            <?php 
            foreach ($products as $product_id => $product_name) :
                $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
                $resultvat = $conn->query($sqlvat);
                $rowvat = $resultvat->fetch_assoc();

                $vatamount = $rowvat['vat'];

                $sqlsaleinfo = "SELECT SUM(`tbl_invoice_detail`.`newqty`) AS `newqty`, SUM(`tbl_invoice_detail`.`refillqty`) AS `refillqty`, SUM(`emptyqty`) AS `emptyqty` FROM `tbl_invoice_detail` LEFT JOIN `tbl_invoice` ON `tbl_invoice`.`idtbl_invoice`=`tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`";
                if($typeSelector==2){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_customerwise_salesrep` ON `tbl_customerwise_salesrep`.`tbl_product_idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product`";
                }
                else if($typeSelector==3){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`";
                }
                else if($typeSelector==4){
                    $sqlsaleinfo.=" LEFT JOIN `tbl_vehicle_load` ON `tbl_vehicle_load`.`idtbl_vehicle_load`=`tbl_invoice`.`tbl_vehicle_load_idtbl_vehicle_load`";
                }
                $sqlsaleinfo .= " WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_product_idtbl_product`='$product_id' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`date` BETWEEN '$validfrom' AND '$validto'";
                if($typeSelector==2){
                    $sqlsaleinfo.=" AND `tbl_customerwise_salesrep`.`tbl_product_idtbl_product`='$product_id' AND `tbl_customerwise_salesrep`.`tbl_employee_idtbl_employee`='$dataselector' AND `tbl_customerwise_salesrep`.`tbl_customer_idtbl_customer`='$customerID'";
                }
                else if($typeSelector==3){
                    $sqlsaleinfo.=" AND `tbl_vehicle_load`.`lorryid`='$dataselector'";
                }
                else if($typeSelector==4){
                    $sqlsaleinfo.=" AND `tbl_vehicle_load`.`driverid`='$dataselector'";
                }

                $resultsaleinfo = $conn->query($sqlsaleinfo);
                $rowsaleinfo = $resultsaleinfo->fetch_assoc();
                
            ?>
                <td class="text-center"><?php echo $rowsaleinfo['emptyqty'] != 0 ? $rowsaleinfo['emptyqty'] : ''; ?></td>
            <?php endforeach; ?>
        </tr>
<?php 
    }
}
?>
    </tbody>
</table>
</div>

