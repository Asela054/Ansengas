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
	//array( 'db' => '`u`.`deposit_complete`', 'dt' => 'header_prg', 'field' => 'deposit_complete' ),
	array( 'db' => '`u`.`id`', 'dt' => 'receipt_no', 'field' => 'id' ),
	array( 'db' => '`u`.`cheque_no`', 'dt' => 'cheque_no', 'field' => 'cheque_no' ),
	array( 'db' => '`u`.`cheque_bank`', 'dt' => 'col_bank', 'field' => 'cheque_bank' ),
	array( 'db' => '`u`.`branch_info`', 'dt' => 'col_branch', 'field' => 'branch_info' ),
	array( 'db' => '`u`.`receipt_sub_narration`', 'dt' => 'col_narration', 'field' => 'receipt_sub_narration' ),
	array( 'db' => '`u`.`receipt_customer`', 'dt' => 'col_customer', 'field' => 'receipt_customer' ),
	array( 'db' => '`u`.`cheque_date`', 'dt' => 'col_date', 'field' => 'cheque_date' ),
	array( 'db' => '`u`.`deposit_amount`', 'dt' => 'deposit_amount', 'field' => 'deposit_amount' )
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

$joinQuery = "FROM (select `drv_doc`.`id`, `tbl_gl_receipt_details`.`cheque_no`, `tbl_gl_receipt_details`.`cheque_bank`, '' AS `branch_info`, `tbl_gl_receipt_details`.`receipt_sub_narration`, `tbl_gl_receipts`.`receipt_customer`, `tbl_gl_receipt_details`.`cheque_date`, `tbl_gl_receipt_details`.`received_amount` AS `deposit_amount`, `settle_by_cash` AS `cash_collection`, `settle_by_cheque` AS `cheque_collection`, IFNULL(drv_prg.deposit_complete, 0) AS deposit_complete from `tbl_gl_receipt_details` INNER JOIN `tbl_gl_receipts` ON `tbl_gl_receipt_details`.`tbl_gl_receipt_id`=`tbl_gl_receipts`.`id` INNER JOIN (SELECT `tbl_gl_bank_deposit_id` AS `id`, `tbl_gl_receipt_detail_id` FROM `tbl_gl_bank_deposit_receipt_details` WHERE `deposit_cancel`=0) AS drv_doc ON `tbl_gl_receipt_details`.`id`=`drv_doc`.`tbl_gl_receipt_detail_id` LEFT OUTER JOIN (SELECT id, deposit_complete FROM `tbl_gl_bank_deposits`) AS drv_prg ON drv_doc.id=drv_prg.id WHERE `tbl_gl_receipt_details`.`settle_by_cheque`=1 AND `tbl_gl_receipt_details`.`bank_deposit`=1 UNION ALL select `id` AS `id`, '-' AS `cheque_no`, '-' AS `cheque_bank`, '-' AS `branch_info`, `deposit_narration` AS `receipt_sub_narration`, '-' AS `receipt_customer`, '-' AS `cheque_date`, `deposit_amount`, 1 AS cash_collection, 0 AS cheque_collection, deposit_complete FROM `tbl_gl_bank_deposits` WHERE `cash_collection`=1) AS `u`";

$setCash = '';
$setCheque = '';

if(isset($_POST['filter_val'])&&($_POST['filter_val']!='')){
	$optCash = ((int)$_POST['filter_val']);
	$setCash = $conn->real_escape_string($optCash);
	$setCheque = $conn->real_escape_string(1-$optCash);
}

$extraWhere = ($setCash=='')?'':"`u`.`cash_collection`='".$setCash."' AND `u`.`cheque_collection`='".$setCheque."'";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);