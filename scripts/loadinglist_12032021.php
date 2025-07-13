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
$table = 'tbl_vehicle_load';

// Table's primary key
$primaryKey = 'idtbl_vehicle_load';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_vehicle_load`', 'dt' => 'idtbl_vehicle_load', 'field' => 'idtbl_vehicle_load' ),
	array( 'db' => '`u`.`date`', 'dt' => 'date', 'field' => 'date' ),
	array( 'db' => '`ud`.`vehicleno`', 'dt' => 'vehicleno', 'field' => 'vehicleno' ),
	array( 'db' => '`uc`.`name`', 'dt' => 'name', 'field' => 'name' ),
	array( 'db' => '`u`.`approvestatus`',   'dt' => 'approvestatus', 'field' => 'approvestatus' ),
	array( 'db' => '`u`.`unloadstatus`',   'dt' => 'unloadstatus', 'field' => 'unloadstatus' ),
	array( 'db' => '`ue`.`area`',   'dt' => 'area', 'field' => 'area' ),
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

$joinQuery = "FROM `tbl_vehicle_load` AS `u` LEFT JOIN `tbl_vehicle` AS `ud` ON (`ud`.`idtbl_vehicle` = `u`.`lorryid`) LEFT JOIN `tbl_employee` AS `uc` ON (`uc`.`idtbl_employee` = `u`.`refid`) LEFT JOIN `tbl_area` AS `ue` ON (`ue`.`idtbl_area` = `u`.`tbl_area_idtbl_area`)";

$extraWhere = "`u`.`status` = 1";

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);