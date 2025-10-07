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
$table = 'tbl_trust_confirmation';

// Table's primary key
$primaryKey = 'idtbl_trust_confirmation';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    array( 'db' => '`main`.`idtbl_trust_confirmation`', 'dt' => 'idtbl_trust_confirmation', 'field' => 'idtbl_trust_confirmation' ),
    array( 'db' => '`main`.`date`', 'dt' => 'date', 'field' => 'date' ),
    array( 'db' => '`main`.`remark`', 'dt' => 'remark', 'field' => 'remark' ),
    array( 'db' => '`main`.`customer_name`', 'dt' => 'customer_name', 'field' => 'customer_name' ),
    array( 'db' => '`main`.`employee_name`', 'dt' => 'employee_name', 'field' => 'employee_name' ),
    array( 'db' => '`main`.`status`',   'dt' => 'status', 'field' => 'status' )
);

// SQL server connection information
require('config.php');
$sql_details = array(
    'user' => $db_username,
    'pass' => $db_password,
    'db'   => $db_name,
    'host' => $db_host
);

// require SSP class
require('ssp.customized.class.php');

$joinQuery = "FROM (SELECT 
    u.idtbl_trust_confirmation,
    u.date,
    u.remark,
    u.status,
    ua.name AS customer_name,
    ub.name AS employee_name
FROM 
    tbl_trust_confirmation AS u
LEFT JOIN 
    tbl_customer AS ua ON ua.idtbl_customer = u.tbl_customer_idtbl_customer
LEFT JOIN 
    tbl_employee AS ub ON ub.idtbl_employee = u.tbl_employee_idtbl_employee
) AS main";

$extraWhere = "`main`.`status` = 1";

echo json_encode(
    SSP::simple( $_POST, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere)
);

?>