<?php
require_once('dbConnect.php');
$invoiceID = $_POST['invoiceid'];

$sql = "SELECT
    `tbl_invoice_payment_detail`.`idtbl_invoice_payment_detail`,
    CASE WHEN `tbl_invoice_payment_detail`.`method` = 1 THEN 'Cash' WHEN `tbl_invoice_payment_detail`.`method` = 2 THEN 'Cheque' WHEN `tbl_invoice_payment_detail`.`method` = 3 THEN 'Bank Deposit' ELSE 'Unknown'
END AS `method`,
`tbl_invoice_payment_detail`.`method` AS `methodtype`,
`tbl_invoice_payment_detail`.`amount`,
`tbl_invoice_payment_detail`.`branch`,
`tbl_invoice_payment_detail`.`receiptno`,
`tbl_invoice_payment_detail`.`chequeno`,
`tbl_invoice_payment_detail`.`chequedate`,
`tbl_bank`.`bankname`
FROM
    `tbl_invoice_payment_detail`
LEFT JOIN `tbl_invoice_payment_has_tbl_invoice` ON `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_payment_idtbl_invoice_payment` = `tbl_invoice_payment_detail`.`tbl_invoice_payment_idtbl_invoice_payment`
LEFT JOIN `tbl_bank` ON `tbl_bank`.`idtbl_bank` = `tbl_invoice_payment_detail`.`tbl_bank_idtbl_bank`
WHERE
    `tbl_invoice_payment_has_tbl_invoice`.`tbl_invoice_idtbl_invoice` = '$invoiceID'";
$result = mysqli_query($con, $sql);

$dataarray=array();

while ($row = mysqli_fetch_array($result)) {
    array_push($dataarray, array("methodtype" => $row['methodtype'], "method" => $row['method'], "amount" => $row['amount'], "branch" => $row['branch'], "receiptno" => $row['receiptno'], "chequeno" => $row['chequeno'], "chequedate" => $row['chequedate'], "bankname" => $row['bankname']));
}

print(json_encode($dataarray));
mysqli_close($con);