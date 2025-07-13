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
$table = 'tbl_account_transaction';

// Table's primary key
$primaryKey = 'idtbl_account_transaction';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.idtbl_account_transaction', 'dt' => 'header_id', 'field' => 'idtbl_account_transaction' ),
	array( 'db' => '`u`.trano', 'dt' => 'trn_num', 'field' => 'trano' ),
	array( 'db' => '`u`.tratype', 'dt' => 'trn_type', 'field' => 'tratype' ),
	array( 'db' => '`u`.crdr', 'dt' => 'dr_cr', 'field' => 'crdr' ),
	array( 'db' => '`u`.narration', 'dt' => 'trn_narration', 'field' => 'narration' ),
	array( 'db' => '`u`.tradate', 'dt' => 'trn_date', 'field' => 'tradate' ),
	array( 'db' => '`u`.acccode', 'dt' => 'trn_accnum', 'field' => 'acccode' ),
	array( 'db' => '`u`.refno', 'dt' => 'trn_refnum', 'field' => 'refno' ),
	array( 'db' => '`u`.branchcode', 'dt' => 'trn_branch', 'field' => 'branchcode' ),
	array( 'db' => '`u`.accamount', 'dt' => 'trn_amount', 'field' => 'accamount' ),
	array( 'db' => '`ud`.`log_id`', 'dt' => 'detail_id', 'field' => 'log_id' ),
	array( 'db' => '`ud`.`log_cancel`', 'dt' => 'detail_cancel', 'field' => 'log_cancel' )
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

$joinQuery = "FROM (SELECT * FROM `tbl_account_transaction` WHERE `accamount`>0 AND `trano` NOT IN (SELECT CONCAT('X', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano FROM tbl_gl_transaction_revoke_regs)) AS u LEFT OUTER JOIN (select `id` AS log_id, `trano`, `log_cancel` from `tbl_gl_rev_audit_details` WHERE `tbl_gl_rev_audit_id`='".$optCode."') AS `ud` ON `u`.`trano`=`ud`.`trano`";
/*
$joinQuery .= " AND `u`.trano NOT IN (SELECT trano FROM tbl_gl_transaction_revoke_regs UNION ALL SELECT CONCAT('X', SUBSTRING(CONCAT('00000000', id), -9, 9)) AS trano FROM tbl_gl_transaction_revoke_regs)";
*/

$extraWhere = "";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);