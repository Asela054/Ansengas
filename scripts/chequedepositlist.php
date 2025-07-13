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
$table = 'tbl_gl_receipt_details';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`id`', 'dt' => 'header_id', 'field' => 'id' ),
	array( 'db' => '`u`.`id`', 'dt' => 'receipt_no', 'field' => 'id' ),
	array( 'db' => '`rc`.`receipt_account_id`', 'dt' => 'header_acc', 'field' => 'receipt_account_id' ),
	array( 'db' => '`rc`.`receipt_branch_id`', 'dt' => 'header_loc', 'field' => 'receipt_branch_id' ),
	array( 'db' => '`u`.`cheque_no`', 'dt' => 'cheque_no', 'field' => 'cheque_no' ),
	array( 'db' => '`u`.`cheque_bank`', 'dt' => 'col_bank', 'field' => 'cheque_bank' ),
	array( 'db' => '`u`.`receipt_sub_narration`', 'dt' => 'col_narration', 'field' => 'receipt_sub_narration' ),
	array( 'db' => '`rc`.`receipt_customer`', 'dt' => 'col_customer', 'field' => 'receipt_customer' ),
	array( 'db' => '`u`.`received_amount`', 'dt' => 'deposit_amount', 'field' => 'received_amount' ),
	array( 'db' => '`ud`.`deposit_id`', 'dt' => 'detail_id', 'field' => 'deposit_id' ),
	array( 'db' => '`ud`.`deposit_cancel`', 'dt' => 'detail_cancel', 'field' => 'deposit_cancel' )
);

require('../connection/db.php');

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

$optCode = isset($_POST['set_code'])?$conn->real_escape_string($_POST['set_code']):'-1';

$joinQuery = "FROM `tbl_gl_receipt_details` AS u INNER JOIN (select `id`, `receipt_customer`, `receipt_debit_account` AS receipt_account_id, `receipt_debit_branch` AS receipt_branch_id FROM `tbl_gl_receipts` WHERE `receipt_complete`=1) as rc ON `u`.`tbl_gl_receipt_id`=`rc`.`id` LEFT OUTER JOIN (select `id` AS deposit_id, `tbl_gl_receipt_detail_id`, `tbl_gl_bank_deposit_id`, `deposit_cancel` from `tbl_gl_bank_deposit_receipt_details` WHERE `tbl_gl_bank_deposit_id`='".$optCode."') AS `ud` ON `u`.`id`=`ud`.`tbl_gl_receipt_detail_id`";

$optCash = isset($_POST['set_cash'])?((int)$_POST['set_cash']):0;
$setCash = $conn->real_escape_string($optCash);
$setCheque = $conn->real_escape_string(1-$optCash);

$extraWhere = "`u`.`settle_by_cash`='".$setCash."' AND `u`.`settle_by_cheque`='".$setCheque."' AND (`u`.`bank_deposit`=0 OR `ud`.`tbl_gl_bank_deposit_id`='".$optCode."')";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);