<?php
session_start();
if(!isset($_SESSION['userid'])){header ("Location:index.php");}
require_once('connection/db.php');//die('bc');
$userID=$_SESSION['userid'];
// Read the CSV file
$filename = 'freeissueinvoice.csv';
$data = [];

if (($handle = fopen($filename, 'r')) !== FALSE) {
    while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $data[] = $row;
    }
    fclose($handle);
}

print_r($data);

foreach ($data as $index => $row) {
    $invoiceno = $row[3];
    $freeqty = $row[5];

    if($freeqty > 0){
        $updateinvoice = "UPDATE `tbl_invoice` SET `invtype`='1' WHERE";
        if (substr($invoiceno, 0, 3) === 'AGT') {
            $taxno = preg_replace('/\D/', '', $invoiceno);
            $updateinvoice .= " `tax_invoice_num`='$taxno'";
        } elseif (substr($invoiceno, 0, 4) === 'INV-') {
            $invno = preg_replace('/\D/', '', $invoiceno);
            $updateinvoice .= " `idtbl_invoice`='$invno'";
        } else {
            continue; // Skip if format is unrecognized
        }
        $conn->query($updateinvoice);   
    } 
}