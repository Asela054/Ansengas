<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$table = 'tbl_invoice';

// Table's primary key
$primaryKey = 'idtbl_invoice';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => '`main`.`idtbl_invoice`', 'dt' => 'idtbl_invoice', 'field' => 'idtbl_invoice' ),
    array( 'db' => '`main`.`tax_invoice_num`', 'dt' => 'tax_invoice_num', 'field' => 'tax_invoice_num' ),
    array( 'db' => '`main`.`non_tax_invoice_num`', 'dt' => 'non_tax_invoice_num', 'field' => 'non_tax_invoice_num' ),
    array( 'db' => '`main`.`date`', 'dt' => 'date', 'field' => 'date' ),
    array( 'db' => '`main`.`nettotal`', 'dt' => 'nettotal', 'field' => 'nettotal' ),
    // array( 'db' => '`main`.`excess_payment`', 'dt' => 'excess_payment', 'field' => 'excess_payment' ),
	array( 'db' => '`main`.`total_paid`', 'dt' => 'total_paid', 'field' => 'total_paid' ),
	array( 'db' => '`main`.`balance_amount`', 'dt' => 'balance_amount', 'field' => 'balance_amount' ),
    array( 'db' => '`main`.`paymentcomplete`', 'dt' => 'paymentcomplete', 'field' => 'paymentcomplete' ),
    array( 'db' => '`main`.`cusname`',   'dt' => 'cusname', 'field' => 'cusname' ),
    array( 'db' => '`main`.`repname`',   'dt' => 'repname', 'field' => 'repname' ),
    array( 'db' => '`main`.`area`',   'dt' => 'area', 'field' => 'area' ),
    array( 'db' => '`main`.`status`',   'dt' => 'status', 'field' => 'status' )
);

// SQL server connection information
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

// require SSP class
require('ssp.customized.class.php');

// Define the join query and extra conditions
$joinQuery = "FROM (SELECT
    u.idtbl_invoice,
    u.tax_invoice_num,
    u.non_tax_invoice_num,
    u.date,
    u.nettotal,
    u.paymentcomplete,
    u.status,
    COALESCE(SUM(pay_inv.payamount), 0) AS total_paid,
    ua.name AS cusname,
    ub.name AS repname,
    uc.area,
    (
        u.nettotal - COALESCE(SUM(pay_inv.payamount), 0)
    ) AS balance_amount
FROM
    tbl_invoice AS u
LEFT JOIN tbl_customer AS ua
    ON ua.idtbl_customer = u.tbl_customer_idtbl_customer
LEFT JOIN tbl_employee AS ub
    ON ub.idtbl_employee = u.ref_id
LEFT JOIN tbl_area AS uc
    ON uc.idtbl_area = u.tbl_area_idtbl_area
LEFT JOIN tbl_invoice_payment_has_tbl_invoice AS pay_inv
    ON u.idtbl_invoice = pay_inv.tbl_invoice_idtbl_invoice
LEFT JOIN tbl_invoice_payment AS pay
    ON pay_inv.tbl_invoice_payment_idtbl_invoice_payment = pay.idtbl_invoice_payment
WHERE
    u.date = CURDATE()
    AND ua.discount_status = 1
GROUP BY
    u.idtbl_invoice
) AS main";

$extraWhere = "`main`.`status` IN (1, 2, 3)";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery)
);

?>
