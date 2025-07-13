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
$table = 'tbl_pettycash_voucher';

// Table's primary key
$primaryKey = 'idtbl_pettycash_voucher';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_pettycash_voucher`', 'dt' => 'idtbl_pettycash_voucher', 'field' => 'idtbl_pettycash_voucher' ),
	array( 'db' => '`u`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`u`.`amount`', 'dt' => 'amount', 'field' => 'amount' ),
	array( 'db' => '`u`.`chequeno`', 'dt' => 'chequeno', 'field' => 'chequeno' ),
	array( 'db' => '`u`.`desc`', 'dt' => 'desc', 'field' => 'desc' ),
	array( 'db' => '`u`.`approvestatus`', 'dt' => 'approvestatus', 'field' => 'approvestatus' ),
    array( 'db' => '`ua`.`subaccount`', 'dt' => 'debitaccount', 'field' => 'debitaccount', 'as' => 'debitaccount' ),
	array( 'db' => '`ua`.`subaccountname`', 'dt' => 'debitaccountname', 'field' => 'debitaccountname', 'as' => 'debitaccountname' ),
	array( 'db' => '`ub`.`subaccount`', 'dt' => 'creditaccount', 'field' => 'creditaccount', 'as' => 'creditaccount' ),
	array( 'db' => '`ub`.`subaccountname`', 'dt' => 'creditaccountname', 'field' => 'creditaccountname', 'as' => 'creditaccountname' ),
	array( 'db' => '`uc`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`ud`.`branch`', 'dt' => 'branch', 'field' => 'branch' ),
	array( 'db' => '`u`.`status`',   'dt' => 'status', 'field' => 'status' )
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

$joinQuery = "FROM `tbl_pettycash_voucher` AS `u` LEFT JOIN `tbl_subaccount` AS `ua` ON (`ua`.`idtbl_subaccount` = `u`.`debitaccountid`) LEFT JOIN `tbl_subaccount` AS `ub` ON (`ub`.`idtbl_subaccount` = `u`.`creditaccountid`) LEFT JOIN `tbl_company` AS `uc` ON (`uc`.`idtbl_company` = `u`.`tbl_company_idtbl_company`) LEFT JOIN `tbl_company_branch` AS `ud` ON (`ud`.`idtbl_company_branch` = `u`.`tbl_company_branch_idtbl_company_branch`)";

$extraWhere = "`u`.`status`=1";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);