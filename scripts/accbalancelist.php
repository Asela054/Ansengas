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
$table = 'tbl_gl_account_balance_details';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => 'drv_acc.subaccount', 'dt' => 'subaccount', 'field' => 'subaccount' ),
	array( 'db' => 'drv_acc.subaccountname', 'dt' => 'subaccountname', 'field' => 'subaccountname' ),
	array( 'db' => 'drv_bal.ac_open_balance', 'dt' => 'openbal', 'field' => 'ac_open_balance' ),
	array( 'db' => 'drv_bal.idtbl_financial_year', 'dt' => 'finyear', 'field' => 'idtbl_financial_year' )
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

$joinQuery = "FROM (SELECT idtbl_subaccount, idtbl_financial_year, ac_open_balance FROM tbl_gl_account_balance_details) AS drv_bal INNER JOIN (SELECT idtbl_subaccount, subaccount, subaccountname FROM tbl_subaccount) AS drv_acc ON drv_bal.idtbl_subaccount=drv_acc.idtbl_subaccount";

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);