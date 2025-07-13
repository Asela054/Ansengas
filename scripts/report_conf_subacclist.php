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
$table = 'tbl_subaccount';

// Table's primary key
$primaryKey = 'idtbl_subaccount';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_subaccount`', 'dt' => 'header_id', 'field' => 'idtbl_subaccount' ),
	array( 'db' => '`u`.`subaccount`', 'dt' => 'subaccount', 'field' => 'subaccount' ),
	array( 'db' => '`u`.`subaccountname`', 'dt' => 'subaccountname', 'field' => 'subaccountname' ),
	array( 'db' => '`drv`.`conf_id`', 'dt' => 'conf_id', 'field' => 'conf_id' ),
	array( 'db' => '`drv`.`report_part_cancel`', 'dt' => 'report_part_cancel', 'field' => 'report_part_cancel' )
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

$optSection = isset($_POST['filter_val'])?((int)$_POST['filter_val']):0;

$joinQuery = "FROM (SELECT `idtbl_subaccount`, `subaccount`, CONCAT(`subaccount`, ' ', `subaccountname`) AS subaccountname FROM `tbl_subaccount` WHERE `status`=1) AS `u` LEFT OUTER JOIN (SELECT `id` AS conf_id, `idtbl_subaccount`, `report_part_cancel` FROM `tbl_gl_report_sub_section_particulars` WHERE `tbl_gl_report_sub_section_id`=".$optSection.") AS drv ON `u`.`idtbl_subaccount`=`drv`.`idtbl_subaccount`";
/*
$setCash = $conn->real_escape_string($optCash);
$setCheque = $conn->real_escape_string(1-$optCash);
*/
$extraWhere = "";//($setCash=='')?'':"`u`.`cash_collection`='".$setCash."' AND `u`.`cheque_collection`='".$setCheque."'";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);