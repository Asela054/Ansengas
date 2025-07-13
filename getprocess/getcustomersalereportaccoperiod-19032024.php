<?php 
require_once('../connection/db.php');

$validfrom = $_POST['validfrom'];
$validto = $_POST['validto'];
$customer = $_POST['customer'];

if (!empty($_POST['customer'])) {
    $sqlcustomer = "SELECT `idtbl_customer`, `name`, `phone`, `address` FROM `tbl_customer` WHERE `status`=1 AND `idtbl_customer`='$customer'";
    $resultcustomer = $conn->query($sqlcustomer);
} else {
    $sqlcustomer = "SELECT `idtbl_customer`, `name`, `phone`, `address` FROM `tbl_customer` WHERE `status`=1";
    $resultcustomer = $conn->query($sqlcustomer);
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
            <th style="border: none;">Month: </th>
            <th style="border: none;"><?php echo date("F Y"); ?></th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th class="text-center" colspan="<?php echo count($products); ?>">Refill Cylinders</th>
            <th class="text-center" colspan="<?php echo count($products); ?>">New Cylinders</th>
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
        </tr>
    </thead>
    <tbody>
<?php 
while ($rowcustomer = $resultcustomer->fetch_assoc()) { 
    $customerID = $rowcustomer['idtbl_customer'];
    $phone = $rowcustomer['phone'];
    $address = $rowcustomer['address'];

    $sqlinvcount = "SELECT COUNT(*) AS `count` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto'";
    $resultinvcount = $conn->query($sqlinvcount);
    $rowinvcount = $resultinvcount->fetch_assoc();

    if ($rowinvcount['count'] > 0) {
?>
        <tr>
            <td>&nbsp;</td>
            <td style="border: none;"><?php echo $rowcustomer['name']; ?></td>
            <td style="border: none;"><?php echo $rowcustomer['address']; ?></td>
            <td style="border: none;"><?php echo $rowcustomer['phone']; ?></td>
            <?php 
            foreach ($products as $product_id => $product_name) :
                $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
                $resultvat = $conn->query($sqlvat);
                $rowvat = $resultvat->fetch_assoc();

                $vatamount = $rowvat['vat'];

               $sqlsaleinfo = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$product_id' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto')";
                $resultsaleinfo = $conn->query($sqlsaleinfo);
                $rowsaleinfo = $resultsaleinfo->fetch_assoc();
            ?>
                <td class="text-center"><?php echo $rowsaleinfo['refillqty'] != 0 ? $rowsaleinfo['refillqty'] : ''; ?></td>
            <?php endforeach; ?>
            <?php 
            foreach ($products as $product_id => $product_name) :
                $sqlvat = "SELECT `idtbl_vat_info`, `vat` FROM `tbl_vat_info` ORDER BY `idtbl_vat_info` DESC LIMIT 1";
                $resultvat = $conn->query($sqlvat);
                $rowvat = $resultvat->fetch_assoc();

                $vatamount = $rowvat['vat'];

                $sqlsaleinfo = "SELECT SUM(`newqty`) AS `newqty`, SUM(`refillqty`) AS `refillqty` FROM `tbl_invoice_detail` WHERE `status`=1 AND `tbl_product_idtbl_product`='$product_id' AND `tbl_invoice_idtbl_invoice` IN (SELECT `idtbl_invoice` FROM `tbl_invoice` WHERE `status`=1 AND `tbl_customer_idtbl_customer`='$customerID' AND `date` BETWEEN '$validfrom' AND '$validto')";
                $resultsaleinfo = $conn->query($sqlsaleinfo);
                $rowsaleinfo = $resultsaleinfo->fetch_assoc();
            ?>
                <td class="text-center"><?php echo $rowsaleinfo['newqty'] != 0 ? $rowsaleinfo['newqty'] : ''; ?></td>
            <?php endforeach; ?>
        </tr>
<?php 
    }
}
?>
    </tbody>
</table>
</div>

