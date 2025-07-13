
<?php
require_once('dbConnect.php');

$customerID=$_POST["customerID"];
// $customerID=253;

// $sql="SELECT `tbl_invoice`.`idtbl_invoice`, `tbl_invoice`.`date`, `tbl_invoice`.`total`, SUM(`tbl_invoice_payment_has_tbl_invoice`.`payamount`) AS `payamount` FROM `tbl_invoice` LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`=`tbl_invoice`.`idtbl_invoice` WHERE `tbl_invoice`.`tbl_customer_idtbl_customer`='$customerID' AND `tbl_invoice`.`status`=1 AND `tbl_invoice`.`paymentcomplete`=0 GROUP BY `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice`";
$sql="SELECT * FROM (SELECT `idtbl_invoice`, `tax_invoice_num`, `non_tax_invoice_num`, `date`, `total`, `taxamount`, `nettotal`, `tbl_customer_idtbl_customer`, `vat` FROM `tbl_invoice` WHERE `tbl_customer_idtbl_customer`='$customerID' AND `status`=1 AND `paymentcomplete`=0) AS `dinv` LEFT JOIN (SELECT SUM(`payamount`) AS `payamount`, `tbl_invoice_idtbl_invoice` FROM `tbl_invoice_payment_has_tbl_invoice` GROUP BY `tbl_invoice_idtbl_invoice`) AS `dhasinv` ON `dhasinv`.`tbl_invoice_idtbl_invoice`=`dinv`.`idtbl_invoice` ORDER BY `dinv`.`date` DESC";
$res = mysqli_query($con, $sql);
$result = array();

while ($row = mysqli_fetch_array($res)) {
    $subarray=array();
    $invoiceID=$row['idtbl_invoice'];
    $sqlinvoiceinfo = "SELECT `tbl_invoice_detail`.*, `tbl_product`.`product_name`, `tbl_product`.`tbl_product_category_idtbl_product_category` FROM `tbl_invoice_detail` LEFT JOIN `tbl_product` ON `tbl_product`.`idtbl_product`=`tbl_invoice_detail`.`tbl_product_idtbl_product` WHERE `tbl_invoice_detail`.`status`=1 AND `tbl_invoice_detail`.`tbl_invoice_idtbl_invoice`='$invoiceID'";
    $resinvoiceinfo = mysqli_query($con, $sqlinvoiceinfo);
    while ($rowinvoiceinfo = mysqli_fetch_array($resinvoiceinfo)) {
        array_push($subarray, $rowinvoiceinfo);
    }
    
    array_push($result, array("invoice" => $row['idtbl_invoice'], "tax_invoice_num" => $row['tax_invoice_num'], "date" => $row['date'],"total" => $row['total'],"taxamount" => $row['taxamount'],"nettotal" => $row['nettotal'],"payamount" => $row['payamount'],"vat" => $row['vat'], "invoiceinfo" => $subarray));
}

print(json_encode($result));
mysqli_close($con);
?>