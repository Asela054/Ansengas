<?php 
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('../connection/db.php');//die('bc');
$userID=$_SESSION['userid'];

$vehicleloadID=$_POST['vehicleloadID'];
$invoicedate=$_POST['invoicedate'];

$arrayinvoicelist=array();
$sqlinovicelist="SELECT `idtbl_invoice`, `tax_invoice_num`, `non_tax_invoice_num`, `nettotal`, `paymentcomplete` FROM `tbl_invoice` WHERE `status`=1 AND `date`='$invoicedate' AND `tbl_vehicle_load_idtbl_vehicle_load`='$vehicleloadID'";
$resultinovicelist =$conn-> query($sqlinovicelist);
while($rowinovicelist = $resultinovicelist-> fetch_assoc()){
    $objinvoice=new stdClass();
    $objinvoice->invoiceid=$rowinovicelist['idtbl_invoice'];
    $objinvoice->taxinvoice=$rowinovicelist['tax_invoice_num'];
    $objinvoice->nontaxinvoice=$rowinovicelist['non_tax_invoice_num'];
    $objinvoice->invoicetotal=$rowinovicelist['nettotal'];
    $objinvoice->paystatus=$rowinovicelist['paymentcomplete'];

    // Determine which invoice number to use
    if ($objinvoice->taxinvoice == null) {
        $objinvoice->displayedInvoice = 'INV-' . $objinvoice->invoiceid;
    } else {
        $objinvoice->displayedInvoice = 'AGT' . $objinvoice->taxinvoice;
    }    

    array_push($arrayinvoicelist, $objinvoice);
}

echo json_encode($arrayinvoicelist);
?>