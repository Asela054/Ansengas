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
$table = 'tbl_employee';

// Table's primary key
$primaryKey = 'idtbl_employee';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
	array( 'db' => '`u`.`idtbl_employee`', 'dt' => 'idtbl_employee', 'field' => 'idtbl_employee' ),
	array( 'db' => '`u`.`name`', 'dt' => 'empname', 'field' => 'empname', 'as' => 'empname' ),
	array( 'db' => '`u`.`epfno`', 'dt' => 'epfno', 'field' => 'epfno' ),
	array( 'db' => '`ud`.`name`', 'dt' => 'factoryname', 'field' => 'factoryname', 'as' => 'factoryname' ),
	array( 'db' => '`uc`.`name`', 'dt' => 'deptname', 'field' => 'deptname', 'as' => 'deptname' ),
	array( 'db' => '`ue`.`sectionname`', 'dt' => 'sectionname', 'field' => 'sectionname' ),
	array( 'db' => '`uf`.`grade`', 'dt' => 'grade', 'field' => 'grade' ),
	array( 'db' => '`ug`.`machine`', 'dt' => 'machine', 'field' => 'machine' ),
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

$joinQuery = "FROM `tbl_employee` AS `u` JOIN `tbl_factory` AS `ud` ON (`ud`.`idtbl_factory` = `u`.`tbl_factory_idtbl_factory`) JOIN `tbl_department` AS `uc` ON (`uc`.`idtbl_department` = `u`.`tbl_department_idtbl_department`) JOIN `tbl_section` AS `ue` ON (`ue`.`idtbl_section` = `u`.`tbl_section_idtbl_section`) JOIN `tbl_grade` AS `uf` ON (`uf`.`idtbl_grade` = `u`.`tbl_grade_idtbl_grade`) JOIN `tbl_machine` AS `ug` ON (`ug`.`idtbl_machine` = `u`.`tbl_machine_idtbl_machine`)";

if($_POST['factoryID']==1){
    $extraWhere = "`u`.`status` IN (1, 2)";
}
else{
    $factoryID=$_POST['factoryID'];
    $extraWhere = "`u`.`status` IN (1, 2) AND `u`.`tbl_factory_idtbl_factory`='$factoryID'";
}

echo json_encode(
	SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);