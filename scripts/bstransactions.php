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
$table = 'tbl_gl_bank_statement_details';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`log_id`', 'dt' => 'detail_id', 'field' => 'log_id' ),
	array( 'db' => '`transaction_date`', 'dt' => 'col_date', 'field' => 'transaction_date' ),
	array( 'db' => '`cheque_no`', 'dt' => 'cheque_no', 'field' => 'cheque_no' ),
	array( 'db' => '`transaction_particulars`', 'dt' => 'col_narration', 'field' => 'transaction_particulars' ),
	array( 'db' => '`transaction_amount`', 'dt' => 'col_amount', 'field' => 'transaction_amount' ),
	array( 'db' => '`id`', 'dt' => 'header_id', 'field' => 'id' ),
	array( 'db' => '`log_cancel`', 'dt' => 'detail_cancel', 'field' => 'log_cancel' ), 
	array( 'db' => '`ismatched`', 'dt' => 'is_matched', 'field' => 'ismatched' )
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
$optCrDr = isset($_POST['set_crdr'])?$conn->real_escape_string($_POST['set_crdr']):'-1';

$setStmtNum = $conn->real_escape_string(isset($_POST['set_bsnum'])?$_POST['set_bsnum']:'-');

$joinQuery = "FROM (SELECT `ud`.`log_id`, `u`.`transaction_date`, `u`.`cheque_no`, `u`.`transaction_particulars`, abs(`u`.`transaction_amount`) as transaction_amount, `u`.`id`, `ud`.`log_cancel`, `u`.`ismatched` FROM (SELECT * FROM `tbl_gl_bank_statement_details` WHERE `tbl_gl_bank_statement_id`='".$setStmtNum."' AND `crdr`='".$optCrDr."') AS u LEFT OUTER JOIN (select `id` AS log_id, `particulars_id`, `log_cancel`, `log_insert` from `tbl_gl_ac_audit_details` WHERE `tbl_gl_ac_audit_id`='".$optCode."' AND reg_group_code='BANK') AS `ud` ON `u`.`id`=`ud`.`particulars_id` WHERE  IFNULL(`ud`.`log_insert`, 0)=0 AND ((IFNULL(`ud`.`log_cancel`, 1)=0) OR (`u`.`ismatched`=0))) AS drv";

$extraWhere = "";//

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);