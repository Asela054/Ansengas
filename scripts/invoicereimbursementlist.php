<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
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
$table = 'tbl_invoice_reimbursement';

// Table's primary key
$primaryKey = 'idtbl_invoice_reimbursement';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`main`.`idtbl_invoice_reimbursement`', 'dt' => 'idtbl_invoice_reimbursement', 'field' => 'idtbl_invoice_reimbursement' ),
	array( 'db' => '`main`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`main`.`reimdocno`', 'dt' => 'reimdocno', 'field' => 'reimdocno' ),
	array( 'db' => '`main`.`customer_name`', 'dt' => 'customer_name', 'field' => 'customer_name' ),
	array( 'db' => '`main`.`invoiceno`', 'dt' => 'invoiceno', 'field' => 'invoiceno' ),
	array( 'db' => '`main`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
	array( 'db' => '`main`.`status`', 'dt' => 'status', 'field' => 'status' )
);


// SQL server connection information
require('config.php');
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_host
);

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

// require( 'ssp.class.php' );
require('ssp.customized.class.php' );

$joinQuery = "FROM (
    SELECT 
        r.idtbl_invoice_reimbursement,
        r.date,
        r.reimdocno,
        r.amount,
        CASE 
            WHEN i.tax_invoice_num IS NULL OR i.tax_invoice_num = '' THEN CONCAT('INV-', i.idtbl_invoice)
            ELSE CONCAT('AGT', i.tax_invoice_num)
        END AS invoiceno,
        c.name AS customer_name,
        r.status
    FROM tbl_invoice_reimbursement r
    LEFT JOIN tbl_invoice i ON i.idtbl_invoice = r.tbl_invoice_idtbl_invoice
    LEFT JOIN tbl_customer c ON c.idtbl_customer = i.tbl_customer_idtbl_customer
    WHERE 
    r.status IN (1, 2)
) AS main";


echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery)
);
