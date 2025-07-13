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
$table = 'tbl_gl_receipts';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 'rhead.receipt_customer', 'dt' => 'receipt_customer', 'field' => 'receipt_customer' ),
	array( 'db' => 'rhead.receipt_category', 'dt' => 'receipt_category', 'field' => 'receipt_category' ),
	array( 'db' => 'rhead.receipt_debit_branch_code', 'dt' => 'receipt_debit_branch', 'field' => 'receipt_debit_branch_code' ),
	array( 'db' => 'racc.subaccountname AS receipt_debit_subaccount', 'dt' => 'receipt_debit_account', 'field' => 'receipt_debit_subaccount' ),
	array( 'db' => 'rhead.receipt_head_narration', 'dt' => 'receipt_head_narration', 'field' => 'receipt_head_narration' ),
	array( 'db' => 'rinfo.receipt_head_amount', 'dt' => 'receipt_head_amount', 'field' => 'receipt_head_amount' ),
	array( 'db' => 'rhead.id', 'dt' => 'header_id', 'field' => 'id' )
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

$joinQuery = "FROM tbl_gl_receipts AS rhead INNER JOIN (SELECT tbl_gl_receipt_id, SUM(received_amount) AS receipt_head_amount FROM tbl_gl_receipt_details WHERE receipt_cancel=0 GROUP BY tbl_gl_receipt_id) AS rinfo ON rhead.id=rinfo.tbl_gl_receipt_id INNER JOIN (SELECT subaccount, subaccountname FROM `tbl_subaccount`) AS `racc` ON rhead.receipt_debit_subaccount=`racc`.`subaccount`";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);